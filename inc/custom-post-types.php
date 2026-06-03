<?php
// カスタム投稿タイプ「ライバー」
function jol_create_post_type_liver()
{
  register_post_type(
    'liver',
    array(
      'labels' => array(
        'name'                  => '所属ライバー',
        'singular_name'         => 'ライバー',
        'menu_name'             => 'ライバー',
        'add_new'               => '新規追加',
        'add_new_item'          => '新しいライバーを追加',
        'edit_item'             => 'ライバーを編集',
        'new_item'              => '新しいライバー',
        'view_item'             => 'ライバーを表示',
        'search_items'          => 'ライバーを検索',
        'not_found'             => 'ライバーが見つかりません',
        'not_found_in_trash'    => 'ゴミ箱にライバーはありません',
      ),
      'public'              => true,
      'has_archive'         => true,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'menu_icon'           => 'dashicons-microphone',
      'capability_type'     => 'post',
      'hierarchical'        => false,
      'rewrite'             => array('slug' => 'livers'),
      'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
      'taxonomies'          => array('liver_tag', 'liver_category'),
      'show_in_rest'        => true,
      'rest_base'           => 'livers',
    )
  );
}
add_action('init', 'jol_create_post_type_liver', 0);

// カスタム投稿タイプ「インタビュー」
function jol_create_post_type_interview()
{
  register_post_type(
    'interview',
    array(
      'labels' => array(
        'name'                  => 'インタビュー',
        'singular_name'         => 'インタビュー',
        'menu_name'             => 'インタビュー',
        'add_new'               => '新規追加',
        'add_new_item'          => '新しいインタビューを追加',
        'edit_item'             => 'インタビューを編集',
        'new_item'              => '新しいインタビュー',
        'view_item'             => 'インタビューを表示',
        'search_items'          => 'インタビューを検索',
        'not_found'             => 'インタビューが見つかりません',
        'not_found_in_trash'    => 'ゴミ箱にインタビューはありません',
      ),
      'public'              => true,
      'has_archive'         => true,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 6,
      'menu_icon'           => 'dashicons-format-chat',
      'capability_type'     => 'post',
      'hierarchical'        => false,
      'rewrite'             => array('slug' => 'interviews'),
      'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
      'taxonomies'          => array('interview_category', 'interview_tag'),
      'show_in_rest'        => true,
      'rest_base'           => 'interviews',
    )
  );
}
add_action('init', 'jol_create_post_type_interview', 0);

// カスタム投稿タイプ「イベント」の作成
function jol_create_post_type_event()
{
  register_post_type(
    'event',
    array(
      'labels' => array(
        'name'                  => 'イベント',
        'singular_name'         => 'イベント',
        'menu_name'             => 'イベント',
        'add_new'               => '新規追加',
        'add_new_item'          => '新しいイベントを追加',
        'edit_item'             => 'イベントを編集',
        'new_item'              => '新しいイベント',
        'view_item'             => 'イベントを表示',
        'search_items'          => 'イベントを検索',
        'not_found'             => 'イベントが見つかりません',
        'not_found_in_trash'    => 'ゴミ箱にイベントはありません',
      ),
      'public'              => true,
      'has_archive'         => true,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 7,
      'menu_icon'           => 'dashicons-calendar-alt',
      'capability_type'     => 'post',
      'hierarchical'        => false,
      'rewrite'             => array('slug' => 'events'),
      'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
      'taxonomies'          => array('event_category', 'event_tag'),
      'show_in_rest'        => true,
      'rest_base'           => 'events',
    )
  );
}
add_action('init', 'jol_create_post_type_event', 0);

// カスタム投稿タイプの管理画面がクラシックエディターになっているので、Gutenbergエディターに変更
function jol_enable_gutenberg_for_custom_post_types($can_edit, $post_type)
{
  if (in_array($post_type, array('liver', 'interview', 'event'))) {
    return true;
  }
  return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'jol_enable_gutenberg_for_custom_post_types', 10, 2);

// Gutenbergエディターを確実に有効化（追加の設定）
function jol_force_gutenberg_for_custom_post_types()
{
  add_filter('use_block_editor_for_post', function ($use_block_editor, $post) {
    if (in_array($post->post_type, array('liver', 'interview', 'event'))) {
      return true;
    }
    return $use_block_editor;
  }, 10, 2);
}
add_action('init', 'jol_force_gutenberg_for_custom_post_types');

// カスタム投稿タイプでREST APIサポートを確実にする（Gutenbergに必要）
function jol_ensure_rest_support_for_custom_post_types()
{
  $cpts = [
    'liver' => 'livers',
    'interview' => 'interviews',
    'event' => 'events'
  ];

  foreach ($cpts as $type => $base) {
    $post_type = get_post_type_object($type);
    if ($post_type) {
      $post_type->show_in_rest = true;
      $post_type->rest_base = $base;
    }
  }
}
add_action('init', 'jol_ensure_rest_support_for_custom_post_types', 20);
