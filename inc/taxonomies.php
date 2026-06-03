<?php
/**
 * カスタムタクソノミーのラベル配列を生成するヘルパー関数
 *
 * @param string $singular 単数形の名前（例：カテゴリー、タグ）
 * @param string $plural 複数形の名前（省略時は単数形と同じ）
 * @return array ラベル設定の配列
 */
function jol_get_taxonomy_labels($singular, $plural = null)
{
  $plural = $plural ?: $singular;
  return array(
    'name'                       => $plural,
    'singular_name'              => $singular,
    'menu_name'                  => $plural,
    'all_items'                  => 'すべての' . $plural,
    'parent_item'                => '親' . $singular,
    'parent_item_colon'          => '親' . $singular . ':',
    'new_item_name'              => '新しい' . $singular . '名',
    'add_new_item'               => '新しい' . $singular . 'を追加',
    'edit_item'                  => $singular . 'を編集',
    'update_item'                => $singular . 'を更新',
    'view_item'                  => $singular . 'を表示',
    'separate_items_with_commas' => $singular . 'をカンマで区切る',
    'add_or_remove_items'        => $singular . 'を追加または削除',
    'choose_from_most_used'      => 'よく使われる' . $singular . 'から選択',
    'popular_items'              => '人気の' . $singular,
    'search_items'               => $singular . 'を検索',
    'not_found'                  => $singular . 'が見つかりません',
    'no_terms'                   => $singular . 'がありません',
    'items_list'                 => $singular . 'リスト',
    'items_list_navigation'      => $singular . 'リストナビゲーション',
  );
}

// タクソノミーの一括登録
function jol_register_custom_taxonomies()
{
  // ライバータグ
  register_taxonomy('liver_tag', 'liver', array(
    'labels'            => jol_get_taxonomy_labels('ライバータグ'),
    'hierarchical'      => false,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'liver-tag'),
  ));

  // ライバーカテゴリー
  register_taxonomy('liver_category', 'liver', array(
    'labels'            => jol_get_taxonomy_labels('ライバーカテゴリー'),
    'hierarchical'      => true,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'liver-category'),
  ));

  // インタビューカテゴリー
  register_taxonomy('interview_category', 'interview', array(
    'labels'            => jol_get_taxonomy_labels('インタビューカテゴリー'),
    'hierarchical'      => true,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'interview-category'),
  ));

  // インタビュータグ
  register_taxonomy('interview_tag', 'interview', array(
    'labels'            => jol_get_taxonomy_labels('インタビュータグ'),
    'hierarchical'      => false,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'interview-tag'),
  ));

  // イベントカテゴリー
  register_taxonomy('event_category', 'event', array(
    'labels'            => jol_get_taxonomy_labels('イベントカテゴリー'),
    'hierarchical'      => true,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'event-category'),
  ));

  // イベントタグ
  register_taxonomy('event_tag', 'event', array(
    'labels'            => jol_get_taxonomy_labels('イベントタグ'),
    'hierarchical'      => false,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'event-tag'),
  ));
}
add_action('init', 'jol_register_custom_taxonomies', 0);
