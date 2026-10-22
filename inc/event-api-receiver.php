<?php
/**
 * Discord Bot等の外部ツールからイベント投稿を受け取るREST APIエンドポイント
 *
 * エンドポイント: POST /wp-json/jol/v1/event
 */

if (!defined('ABSPATH')) {
    exit;
}

// 認証用APIキー（wp-config.phpで JOL_EVENT_API_KEY が定義されていればそれを優先、なければ規定キー）
if (!defined('JOL_EVENT_API_KEY')) {
    define('JOL_EVENT_API_KEY', 'jol_event_secret_token_2026');
}

add_action('rest_api_init', function () {
    register_rest_route('jol/v1', '/event', array(
        'methods'             => 'POST',
        'callback'            => 'jol_handle_create_event_api',
        'permission_callback' => 'jol_verify_event_api_permission',
    ));
});

/**
 * APIキーの認証
 */
function jol_verify_event_api_permission($request) {
    $api_key = $request->get_header('x-jol-api-key');
    if (empty($api_key)) {
        $api_key = $request->get_param('api_key');
    }

    if (empty($api_key) || !hash_equals(JOL_EVENT_API_KEY, trim((string) $api_key))) {
        return new WP_Error(
            'rest_forbidden',
            '認証に失敗しました。正しいAPIキーを指定してください。',
            array('status' => 401)
        );
    }

    return true;
}

/**
 * Discord CDN等のクエリパラメータ付きURLから安全に画像をダウンロードしメディアライブラリに登録する
 *
 * @param string $img_url 画像URL
 * @param int    $post_id 紐付ける投稿ID
 * @param string $desc    画像の説明・タイトル
 * @return int|WP_Error   アタッチメントID または WP_Error
 */
function jol_sideload_event_image($img_url, $post_id, $desc = '') {
    $tmp_file = download_url($img_url, 60);
    if (is_wp_error($tmp_file)) {
        return $tmp_file;
    }

    // URLパスからクエリパラメータ(?ex=...等)を除去したファイル名を取得
    $url_path = parse_url($img_url, PHP_URL_PATH);
    $filename = $url_path ? wp_basename($url_path) : '';
    $filename = sanitize_file_name($filename);

    // 拡張子が含まれていない場合は画像タイプから補完
    $filetype = wp_check_filetype($filename);
    if (empty($filetype['ext'])) {
        $mime = function_exists('mime_content_type') ? @mime_content_type($tmp_file) : '';
        $ext_map = array(
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
        );
        $ext = isset($ext_map[$mime]) ? $ext_map[$mime] : 'jpg';
        $filename = (!empty($filename) ? $filename : 'event-image-' . time()) . '.' . $ext;
    }

    $file_array = array(
        'name'     => $filename,
        'tmp_name' => $tmp_file,
    );

    $attachment_id = media_handle_sideload($file_array, $post_id, $desc);
    if (is_wp_error($attachment_id)) {
        @unlink($file_array['tmp_name']);
        return $attachment_id;
    }

    return $attachment_id;
}

/**
 * イベント投稿作成処理
 */
function jol_handle_create_event_api($request) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $params = $request->get_json_params();
    if (empty($params) || !is_array($params)) {
        $params = $request->get_params();
    }

    $title    = !empty($params['title']) ? sanitize_text_field($params['title']) : '';
    $content  = !empty($params['content']) ? wp_kses_post($params['content']) : '';
    $excerpt  = !empty($params['excerpt']) ? sanitize_textarea_field($params['excerpt']) : '';
    $category = !empty($params['category']) ? sanitize_text_field($params['category']) : 'event-news';
    $status   = !empty($params['status']) && in_array($params['status'], array('publish', 'draft'), true)
        ? $params['status']
        : 'publish';

    // 画像URL群の取得（配列または単一URLに対応）
    $image_urls = array();
    if (!empty($params['image_urls']) && is_array($params['image_urls'])) {
        foreach ($params['image_urls'] as $url) {
            $cleaned = esc_url_raw(trim((string) $url));
            if (!empty($cleaned)) {
                $image_urls[] = $cleaned;
            }
        }
    } elseif (!empty($params['image_url'])) {
        $cleaned = esc_url_raw(trim((string) $params['image_url']));
        if (!empty($cleaned)) {
            $image_urls[] = $cleaned;
        }
    }

    if (empty($title)) {
        return new WP_Error('missing_title', 'タイトルは必須です。', array('status' => 400));
    }

    // 1. 投稿を作成
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_excerpt' => $excerpt,
        'post_status'  => $status,
        'post_type'    => 'event',
    );

    $post_id = wp_insert_post($post_data, true);
    if (is_wp_error($post_id) || !$post_id) {
        $error_msg = is_wp_error($post_id) ? $post_id->get_error_message() : '投稿の作成に失敗しました。';
        return new WP_Error('post_creation_failed', $error_msg, array('status' => 500));
    }

    // 2. タクソノミー（event_category）の設定（ターム未作成時は日本語名で自動生成）
    $category_map = array(
        'event-news' => 'イベント',
        'battle'     => 'ガチバトル',
        'liver-news' => 'ライバーニュース',
    );
    if (!isset($category_map[$category])) {
        $category = 'event-news';
    }

    if (taxonomy_exists('event_category')) {
        $term = get_term_by('slug', $category, 'event_category');
        if (!$term || is_wp_error($term)) {
            $inserted = wp_insert_term(
                $category_map[$category],
                'event_category',
                array('slug' => $category)
            );
            if (!is_wp_error($inserted) && !empty($inserted['term_id'])) {
                wp_set_object_terms($post_id, array((int) $inserted['term_id']), 'event_category');
            }
        } else {
            wp_set_object_terms($post_id, array((int) $term->term_id), 'event_category');
        }
    }

    // 3. 画像群のダウンロード＆ギャラリー登録
    $gallery_attachment_ids = array();
    $image_errors = array();

    if (!empty($image_urls)) {
        foreach ($image_urls as $index => $img_url) {
            $desc = $title . ' - 画像 ' . ($index + 1);
            $attachment_id = jol_sideload_event_image($img_url, $post_id, $desc);
            if (!is_wp_error($attachment_id)) {
                $gallery_attachment_ids[] = (int) $attachment_id;
            } else {
                $err_msg = $attachment_id->get_error_message();
                $image_errors[] = $err_msg;
                error_log('JOL Event API Image Download Error (' . $img_url . '): ' . $err_msg);
            }
        }

        // 1枚目を一覧用のアイキャッチ画像に設定し、全画像IDをメタデータに保存
        if (!empty($gallery_attachment_ids)) {
            set_post_thumbnail($post_id, $gallery_attachment_ids[0]);
            update_post_meta($post_id, '_event_gallery_image_ids', $gallery_attachment_ids);
        }
    }

    $permalink = get_permalink($post_id);

    return rest_ensure_response(array(
        'success'        => true,
        'post_id'        => (int) $post_id,
        'title'          => $title,
        'status'         => $status,
        'category'       => $category,
        'url'            => $permalink,
        'image_count'    => count($gallery_attachment_ids),
        'image_attached' => !empty($gallery_attachment_ids),
        'image_errors'   => $image_errors,
    ));
}
