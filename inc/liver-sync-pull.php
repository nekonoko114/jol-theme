<?php
/**
 * スプレッドシートからライバー情報をPullして同期する処理
 */

// 管理画面に同期用メニューを追加
add_action('admin_menu', 'jol_liver_sync_add_menu');
function jol_liver_sync_add_menu() {
    add_management_page(
        'ライバー情報同期',
        'ライバー情報同期',
        'manage_options',
        'jol-liver-sync',
        'jol_liver_sync_page_html'
    );
}

function jol_liver_sync_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['run_sync']) && check_admin_referer('jol_liver_sync_action', 'jol_liver_sync_nonce')) {
        $result = jol_run_liver_sync();
        if (is_wp_error($result)) {
            $message = '<div class="notice notice-error is-dismissible"><p>エラーが発生しました: ' . esc_html($result->get_error_message()) . '</p></div>';
        } else {
            $message = '<div class="notice notice-success is-dismissible"><p>' . esc_html($result) . '</p></div>';
        }
    }

    ?>
    <div class="wrap">
        <h1>ライバー情報同期</h1>
        <?php echo $message; ?>
        <p>Googleスプレッドシートから最新のライバー情報を取得し、WordPressへ登録・更新します。</p>
        <p>※ 画像のダウンロードなどにより、処理に数分かかる場合があります。ボタンは1度だけ押してそのままお待ちください。</p>
        <form method="post" action="">
            <?php wp_nonce_field('jol_liver_sync_action', 'jol_liver_sync_nonce'); ?>
            <input type="submit" name="run_sync" id="run_sync" class="button button-primary" value="今すぐ同期を実行する">
        </form>
    </div>
    <?php
}

function jol_run_liver_sync() {
    // 画像ダウンロード等でタイムアウトしないように時間を延長
    set_time_limit(300);

    // スプレッドシートのCSVエクスポートURL (※新しいスプレッドシート用のURLに変更)
    $csv_url = 'https://docs.google.com/spreadsheets/d/1XuVwub4c2LNxtK9PFFt36uohbu3d3y2aDWap7oNK4WU/export?format=csv';
    
    // HTTPリクエストでCSVを取得
    $response = wp_remote_get($csv_url, array('timeout' => 30));

    if (is_wp_error($response)) {
        return new WP_Error('csv_fetch_error', 'スプレッドシートの取得に失敗しました: ' . $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    if (empty($body)) {
        return new WP_Error('empty_csv', 'CSVデータが空です。共有設定が「リンクを知っている全員」になっているか確認してください。');
    }

    // CSVをパース
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $body);
    rewind($stream);

    $header = fgetcsv($stream); // 1行目をスキップ
    $processed_count = 0;

    // wp_insert_post, media_sideload_image 用のユーティリティ関数
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    while (($row = fgetcsv($stream)) !== false) {
        // 新しいスプレッドシートの列構造に対応
        $creator_account = isset($row[0]) ? trim($row[0]) : ''; // A列: クリエイターアカウント
        $liver_name      = isset($row[1]) ? trim($row[1]) : ''; // B列: クリエイター名
        $tiktok_url_val  = isset($row[2]) ? trim($row[2]) : ''; // C列: アカウントURL
        $display_flag    = isset($row[3]) ? trim($row[3]) : ''; // D列: 表示フラグ
        $drive_url       = ''; // 新しいスプレッドシートにGoogle Drive画像URL列は無いため空

        if (empty($liver_name) || empty($creator_account)) {
            continue;
        }

        // TikTok URLの判定と補完（C列のデータがあれば優先、無ければアカウント名から自動生成）
        if (!empty($tiktok_url_val)) {
            if (!preg_match('/^https?:\/\//', $tiktok_url_val)) {
                $tiktok_url_val = 'https://www.tiktok.com/@' . ltrim($tiktok_url_val, '/@');
            }
            $tiktok_url = $tiktok_url_val;
        } else {
            $formatted_account = strpos($creator_account, '@') === 0 ? $creator_account : '@' . $creator_account;
            $tiktok_url = 'https://www.tiktok.com/' . $formatted_account;
        }

        // 表示フラグの判定（TRUE、1、yesなどに柔軟に対応）
        $df_lower = strtolower(trim($display_flag));
        $is_displayed = ($df_lower === 'true' || $df_lower === '1' || $df_lower === 'yes') ? 1 : 0;

        // タイトルで既存の投稿を探す
        $existing_post = get_page_by_title($liver_name, OBJECT, 'liver');
        $post_id = $existing_post ? $existing_post->ID : 0;

        // ==========================================
        // FALSEのメンバー：既存なら下書きにして終了、新規なら作らない
        // ==========================================
        if (!$is_displayed) {
            if ($post_id) {
                $post_data = array(
                    'ID'           => $post_id,
                    'post_status'  => 'draft',
                );
                wp_update_post($post_data);
            }
            continue; // ここでスキップし、画像取得も行わない
        }

        // ==========================================
        // TRUEのメンバー：情報を登録・更新し、画像を取得する
        // ==========================================
        
        // 毎回TikTokをスクレイピングして最新データ（画像＆プロフィール文）を取得する
        $tiktok_data = jol_scrape_tiktok_data($tiktok_url);
        $image_url = $tiktok_data['image_url'];
        $bio = $tiktok_data['bio'];
        $image_source = 'TikTok';

        if ($post_id) {
            $post_data = array(
                'ID'           => $post_id,
                'post_status'  => 'publish',
                'post_content' => $bio, // TikTokから取得したプロフィール文を本文にセット
            );
            wp_update_post($post_data);
        } else {
            $post_data = array(
                'post_title'   => $liver_name,
                'post_type'    => 'liver',
                'post_status'  => 'publish',
                'post_content' => $bio, // TikTokから取得したプロフィール文を本文にセット
            );
            $post_id = wp_insert_post($post_data);
        }

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        // ACFの情報を更新
        if (function_exists('update_field')) {
            update_field('creator_account', $creator_account, $post_id);
            update_field('account_url', $tiktok_url, $post_id);
            // ※ 特徴(feature)や配信開始時期(delivery)は、スプレッドシート側に存在しないため、
            // 手動での入力を保護するために同期による上書き（クリア）は行いません。
        }

        // アイキャッチ画像の設定
        if (!has_post_thumbnail($post_id) || get_post_meta($post_id, '_image_sync_error', true)) {
            if (empty($image_url) && strpos($drive_url, 'drive.google.com') !== false) {
                if (preg_match('/id=([^&]+)/', $drive_url, $matches) || preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $drive_url, $matches)) {
                    $image_url = 'https://drive.google.com/uc?export=download&id=' . $matches[1];
                    $image_source = 'Google Drive';
                }
            }

            if (!empty($image_url)) {
                $attachment_id = media_sideload_image($image_url, $post_id, $liver_name . 'のプロフィール画像', 'id');
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                    delete_post_meta($post_id, '_image_sync_error'); // 成功したらエラー履歴を消す
                } else {
                    update_post_meta($post_id, '_image_sync_error', $image_source . 'からの画像取得エラー: ' . $attachment_id->get_error_message());
                }
            } else {
                update_post_meta($post_id, '_image_sync_error', 'TikTokとGoogle Driveの両方で画像のURLが見つかりませんでした。');
            }
        }

        $processed_count++;
    }

    fclose($stream);
    return sprintf('%d 件のライバー情報を同期・更新しました。', $processed_count);
}

/**
 * TikTokのプロフィールURLからアバター画像URLと自己紹介文を抽出する関数
 */
function jol_scrape_tiktok_data($url) {
    $result = array(
        'image_url' => '',
        'bio'       => '',
    );

    $response = wp_remote_get($url, array(
        'timeout' => 10,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
    ));

    if (is_wp_error($response)) {
        return $result;
    }

    $html = wp_remote_retrieve_body($response);

    // 1. 画像の抽出
    if (preg_match('/<meta[^>]*property="og:image"[^>]*content="([^"]+)"/i', $html, $matches) || 
        preg_match('/<meta[^>]*content="([^"]+)"[^>]*property="og:image"/i', $html, $matches)) {
        $result['image_url'] = html_entity_decode($matches[1]);
    } elseif (preg_match('/"avatar(?:Large|Medium|Thumb)":"([^"]+)"/', $html, $matches)) {
        $result['image_url'] = str_replace(array('\u002F', '\\'), array('/', ''), $matches[1]);
    }

    // 2. プロフィール文(signature)の抽出
    if (preg_match('/"signature":"([^"]*)"/', $html, $matches)) {
        $bio = json_decode('"' . $matches[1] . '"'); // \u3042 等のユニコードエスケープをデコード
        if ($bio !== null) {
            $result['bio'] = $bio;
        }
    }

    return $result;
}
