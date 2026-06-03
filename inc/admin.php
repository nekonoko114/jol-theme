<?php
// ウィジェットサイドバーの登録
function jol_widgets_init()
{
  register_sidebar(array(
    'name'          => 'サイドバー',
    'id'            => 'sidebar-1',
    'description'   => 'サイドバーウィジェットエリア',
    'before_widget' => '<div class="widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ));
}
add_action('widgets_init', 'jol_widgets_init');

// ACFプラグイン状況の確認とデバッグ用
function jol_check_acf_status()
{
  if (current_user_can('administrator')) {
    if (function_exists('get_field')) {
      error_log('ACF Plugin: Active');
    } else {
      error_log('ACF Plugin: Not found - Using WordPress meta fields');
    }
  }
}
add_action('init', 'jol_check_acf_status');

// デバッグ用：カスタム投稿タイプの登録状況を確認
function jol_debug_cpt_registration()
{
  if (current_user_can('manage_options') && isset($_GET['debug_cpt'])) {
    echo '<pre>';
    echo "Registered Post Types:\n";
    print_r(get_post_types(array(), 'objects'));
    echo "\n\nLiver Post Type:\n";
    print_r(get_post_type_object('liver'));
    echo "\n\nInterview Post Type:\n";
    print_r(get_post_type_object('interview'));
    echo "\n\nEvent Post Type:\n";
    print_r(get_post_type_object('event'));
    echo '</pre>';
    exit;
  }
}
add_action('init', 'jol_debug_cpt_registration', 99);

// 管理画面の投稿一覧にサムネイルカラムを追加

// ライバー投稿タイプ用
function jol_add_liver_thumbnail_column($columns) {
  $new_columns = array();
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = 'サムネイル';
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}
add_filter('manage_liver_posts_columns', 'jol_add_liver_thumbnail_column');

function jol_display_liver_thumbnail_column($column_name, $post_id) {
  if ($column_name === 'thumbnail') {
    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
    echo $thumbnail ? $thumbnail : '—';
  }
}
add_action('manage_liver_posts_custom_column', 'jol_display_liver_thumbnail_column', 10, 2);

// インタビュー投稿タイプ用
function jol_add_interview_thumbnail_column($columns) {
  $new_columns = array();
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = 'サムネイル';
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}
add_filter('manage_interview_posts_columns', 'jol_add_interview_thumbnail_column');

function jol_display_interview_thumbnail_column($column_name, $post_id) {
  if ($column_name === 'thumbnail') {
    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
    echo $thumbnail ? $thumbnail : '—';
  }
}
add_action('manage_interview_posts_custom_column', 'jol_display_interview_thumbnail_column', 10, 2);

// イベント投稿タイプ用
function jol_add_event_thumbnail_column($columns) {
  $new_columns = array();
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = 'サムネイル';
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}
add_filter('manage_event_posts_columns', 'jol_add_event_thumbnail_column');

function jol_display_event_thumbnail_column($column_name, $post_id) {
  if ($column_name === 'thumbnail') {
    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
    echo $thumbnail ? $thumbnail : '—';
  }
}
add_action('manage_event_posts_custom_column', 'jol_display_event_thumbnail_column', 10, 2);

// サムネイルカラムの幅を調整するCSS
function jol_add_admin_thumbnail_column_css() {
  echo '<style>
    .column-thumbnail {
      width: 100px;
      text-align: center;
    }
    .column-thumbnail img {
      max-width: 80px;
      height: auto;
      border-radius: 4px;
    }
  </style>';
}
add_action('admin_head', 'jol_add_admin_thumbnail_column_css');
