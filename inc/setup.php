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

/**
 * パンくずリストの出力（Breadcrumb NavXT 優先・未導入時は標準フォールバック）
 */
function jol_breadcrumbs()
{
  if (function_exists('bcn_display')) {
    bcn_display();
    return;
  }

  if (is_front_page()) {
    return;
  }

  $items = array();
  $items[] = '<a href="' . esc_url(home_url('/')) . '">HOME</a>';

  if (is_post_type_archive()) {
    $post_type_obj = get_queried_object();
    if ($post_type_obj && !empty($post_type_obj->labels->name)) {
      $items[] = '<span class="current-item">' . esc_html($post_type_obj->labels->name) . '</span>';
    }
  } elseif (is_singular()) {
    $post_type = get_post_type();
    if ($post_type && $post_type !== 'post' && $post_type !== 'page') {
      $post_type_obj = get_post_type_object($post_type);
      $archive_link = get_post_type_archive_link($post_type);
      if ($post_type_obj && $archive_link) {
        $items[] = '<a href="' . esc_url($archive_link) . '">' . esc_html($post_type_obj->labels->name) . '</a>';
      }
    } elseif ($post_type === 'post') {
      $categories = get_the_category();
      if (!empty($categories)) {
        $items[] = '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
      }
    }
    $items[] = '<span class="current-item">' . esc_html(get_the_title()) . '</span>';
  } elseif (is_tax() || is_category() || is_tag()) {
    $term = get_queried_object();
    if ($term && !empty($term->name)) {
      $items[] = '<span class="current-item">' . esc_html($term->name) . '</span>';
    }
  } elseif (is_archive()) {
    $items[] = '<span class="current-item">' . esc_html(get_the_archive_title()) . '</span>';
  } elseif (is_search()) {
    $items[] = '<span class="current-item">検索結果: ' . esc_html(get_search_query()) . '</span>';
  } elseif (is_404()) {
    $items[] = '<span class="current-item">404 Not Found</span>';
  }

  echo implode('<span class="breadcrumb-sep" aria-hidden="true">/</span>', $items);
}
