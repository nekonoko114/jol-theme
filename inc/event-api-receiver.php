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

    if (empty($api_key) || !hash_equals(JOL_EVENT_API_KEY, $api_key)) {
        return new WP_Error('rest_forbidden', '認証に失敗しました。正しいAPIキーを指定してください。', array('status' => 401));
    }

    return true;
}

/**
 * イベント投稿作成処理
 */
function jol_handle_create_event_api($request) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $params = $request->get_json_params();
    if (empty($params)) {
        $params = $request->get_params();
    }

    $title      = !empty($params['title']) ? sanitize_text_field($params['title']) : '';
    $content    = !empty($params['content']) ? wp_kses_post($params['content']) : '';
    $category   = !empty($params['category']) ? sanitize_text_field($params['category']) : 'event-news';
    $status     = !empty($params['status']) && in_array($params['status'], array('publish', 'draft'), true) ? $params['status'] : 'publish';
    
    // 画像URL群の取得（配列または単一URLに対応）
    $image_urls = array();
    if (!empty($params['image_urls']) && is_array($params['image_urls'])) {
        foreach ($params['image_urls'] as $url) {
            $cleaned = esc_url_raw($url);
            if (!empty($cleaned)) {
                $image_urls[] = $cleaned;
            }
        }
    } elseif (!empty($params['image_url'])) {
        $cleaned = esc_url_raw($params['image_url']);
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
        'post_status'  => $status,
        'post_type'    => 'event',
    );

    $post_id = wp_insert_post($post_data);
    if (is_wp_error($post_id) || !$post_id) {
        $error_msg = is_wp_error($post_id) ? $post_id->get_error_message() : '投稿の作成に失敗しました。';
        return new WP_Error('post_creation_failed', $error_msg, array('status' => 500));
    }

    // 2. タクソノミー（event_category）の設定
    $valid_categories = array('event-news', 'battle', 'liver-news');
    if (!in_array($category, $valid_categories, true)) {
        $category = 'event-news';
    }
    wp_set_object_terms($post_id, $category, 'event_category');

    // 3. 画像群のダウンロード＆ギャラリー登録
    $gallery_attachment_ids = array();
    if (!empty($image_urls)) {
        foreach ($image_urls as $index => $img_url) {
            $desc = $title . ' - 画像 ' . ($index + 1);
            $attachment_id = media_sideload_image($img_url, $post_id, $desc, 'id');
            if (!is_wp_error($attachment_id)) {
                $gallery_attachment_ids[] = $attachment_id;
            } else {
                error_log('JOL Event API Image Download Error (' . $img_url . '): ' . $attachment_id->get_error_message());
            }
        }

        // 1枚目を一覧用のアイキャッチ画像に設定
        if (!empty($gallery_attachment_ids)) {
            set_post_thumbnail($post_id, $gallery_attachment_ids[0]);
            // 全画像のアタッチメントIDをカスタムフィールドに保存
            update_post_meta($post_id, '_event_gallery_image_ids', $gallery_attachment_ids);
        }
    }

    $permalink = get_permalink($post_id);

    return rest_ensure_response(array(
        'success'        => true,
        'post_id'        => $post_id,
        'title'          => $title,
        'status'         => $status,
        'category'       => $category,
        'url'            => $permalink,
        'image_count'    => count($gallery_attachment_ids),
        'image_attached' => !empty($gallery_attachment_ids),
    ));
}
