<?php
// テーマサポート
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support(
  'html5',
  array(
    'comment-list', // comment-list (commnt-list is a typo in original, fixing it here)
    'comment-form', // comment-form (commnt-form is a typo in original)
    'search-form',  // search-form (serch-form is a typo in original)
    'gallery',
    'caption'
  )
);

// 不要なWordPressのデフォルトスクリプトを削除
function jol_remove_unnecessary_scripts()
{
  if (!is_admin()) {
    // 絵文字スクリプトを削除（不要な場合）
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // WordPressの埋め込みスクリプトを削除（必要に応じて）
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
  }
}
add_action('init', 'jol_remove_unnecessary_scripts');

// 抜粋（Excerpt）のカスタマイズ
function jol_custom_excerpt_more($more)
{
  return ' <a class="c-btn c-btn-more-right" href="' . get_permalink() . '">もっと見る</a>'; // 「続きを読む」ボタンを追加
}
add_filter('excerpt_more', 'jol_custom_excerpt_more');

function jol_custom_excerpt_length($length)
{
  return 40; // 40文字に設定
}
add_filter('excerpt_length', 'jol_custom_excerpt_length', 999);

// カスタムヘッダー
function jol_custom_header_setup()
{
  $args = array(
    'default-image'          => '',  // デフォルトの画像（なし）
    'random-default'         => false,
    'width'                  => 1200,  // 画像のデフォルト幅
    'height'                 => 600,   // 画像のデフォルト高さ
    'flex-width'             => true,  // 幅の調整を許可
    'flex-height'            => true,  // 高さの調整を許可
    'uploads'                => true,  // 画像のアップロードを許可
    'wp-head-callback'       => 'jol_header_style', // ヘッダーのスタイルを適用
  );
  add_theme_support('custom-header', $args);
}
add_action('after_setup_theme', 'jol_custom_header_setup');

// 管理画面でアップロードしたヘッダー画像を CSS に反映 するための関数
function jol_header_style()
{
  if (get_header_image()) { // ヘッダー画像がある場合のみ適用
?>
    <style type="text/css">
      .custom-header img {
        /* background-image: url('<?php echo esc_url(get_header_image()); ?>'); */
        background-size: cover;
        background-position: center;
        object-fit: cover;
        height: <?php echo get_custom_header()->height; ?>px;
        width: 100%;
      }
    </style>
  <?php
  }
}

// 共通ライバーサムネイル画像のURLを定義
define('COMMON_LIVER_THUMBNAIL_URL', get_template_directory_uri() . '/assets/images/liver-thumbnail/liver-thumbnail-01.webp');
