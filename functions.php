<?php
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support(
  'html5',
  array(
    'commnt-list',
    'commnt-form',
    'serch-form',
    'gallery',
    'caption'
  )
);

//カスタムメニューの追加
function my_menu_init()
{
  register_nav_menus(
    array(
      'header' => 'ヘッダーメニュー',
      'drawer' => 'ドロワーメニュー',
      'footer' => 'フッターメニュー'
    )
  );
}
add_action('init', 'my_menu_init');

//cssとjsの読み込み
function enqueue_theme_assets()
{
  // CSS Reset
  wp_enqueue_style('modern-css-reset', 'https://unpkg.com/modern-css-reset/dist/reset.min.css', array(), null);

  // Font Awesome
  wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v6.2.0/css/all.css', array(), '6.2.0');

  // Swiper CSS
  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');

  // メインスタイルシート
  wp_enqueue_style('theme-style', get_template_directory_uri() . '/assets/css/style.css', array('modern-css-reset'), filemtime(get_template_directory() . '/assets/css/style.css'));

  // jQuery設定（headセクションで読み込み）
  if (!is_admin()) {
    // WordPress標準のjQueryを削除してCDNのjQueryを使用
    wp_deregister_script('jquery');
    // jQueryをheadで読み込む（第5引数をfalseにする）
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', false);

    // jQueryの$エイリアスを確保
    wp_add_inline_script('jquery', 'window.jQuery = window.$ = jQuery;');
  }

  // particles.js (FVパーティクル用 - トップページのみ)
  if (is_front_page() || is_home()) {
    wp_enqueue_script('particles-js', 'https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js', array(), '2.0.0', true);

    // コンパイル済みファイルのパス確認（修正済み）
    $particles_file = get_template_directory() . '/assets/js/particles-config.js';
    if (file_exists($particles_file)) {
      wp_enqueue_script('particles-config', get_template_directory_uri() . '/assets/js/particles-config.js', array('particles-js'), filemtime($particles_file), true);
    } else {
      // フォールバック用のバージョン管理
      wp_enqueue_script('particles-config', get_template_directory_uri() . '/assets/js/particles-config.js', array('particles-js'), '1.0.0', true);
    }
  }

  // Swiper JavaScript
  wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), '11.0.0', true);

  // モジュール化されたJavaScript
  wp_enqueue_script('loading-animation', get_template_directory_uri() . '/assets/js/modules/loadingAnimation.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/modules/loadingAnimation.js'), true);
  wp_enqueue_script('main-swiper', get_template_directory_uri() . '/assets/js/modules/mainSwiper.js', array('swiper-js'), time(), true);
  wp_enqueue_script('liver-slider', get_template_directory_uri() . '/assets/js/modules/liverSlider.js', array('swiper-js'), time(), true);
  //hamburger.jsの読み込み
  wp_enqueue_script('hamburger', get_template_directory_uri() . '/assets/js/modules/hamburger.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/modules/hamburger.js'), true);

  // ランキングページ用スクリプト
  if (is_page_template('page-ranking.php')) {
    wp_enqueue_script('ranking-manager', get_template_directory_uri() . '/assets/js/ranking.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/ranking.js'), true);
  }

  // メインアプリケーション（最後に読み込む）
  wp_enqueue_script('theme-app', get_template_directory_uri() . '/assets/js/app.js', array('loading-animation', 'main-swiper', 'liver-slider'), time(), true);

  // WordPress標準のcomment-replyスクリプト（コメント機能を使用する場合）
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'enqueue_theme_assets');

//投稿のデフォルトスタイルを変更する
add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {
  if (isset($args->add_li_class)) {
    $classes[] = $args->add_li_class; // 任意のクラスを追加
  }
  return $classes;
}, 10, 4);


//ウィジェットサイドバーの登録
function my_theme_widgets_init()
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
add_action('widgets_init', 'my_theme_widgets_init');


// Twitter埋め込みスクリプトの読み込み
function add_twitter_embed_script()
{
  if (is_active_sidebar('sidebar-1')) { // サイドバーがアクティブなときのみ
    wp_enqueue_script('twitter-widgets', 'https://platform.twitter.com/widgets.js', array(), null, true);
  }
}
add_action('wp_enqueue_scripts', 'add_twitter_embed_script');

// 管理画面用のスクリプトとスタイル
function enqueue_admin_assets($hook)
{
  // 管理画面でも必要に応じてアセットを読み込み
  if ($hook === 'post.php' || $hook === 'post-new.php') {
    // admin.cssファイルが存在する場合のみ読み込み
    $admin_css_path = get_template_directory() . '/assets/css/admin.css';
    if (file_exists($admin_css_path)) {
      wp_enqueue_style('admin-custom', get_template_directory_uri() . '/assets/css/admin.css', array(), filemtime($admin_css_path));
    }
  }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_assets');

// DNSプリフェッチとプリロードの追加
function add_resource_hints($urls, $relation_type)
{
  if (wp_installing() || is_admin()) {
    return $urls;
  }

  switch ($relation_type) {
    case 'dns-prefetch':
      $urls[] = '//code.jquery.com';
      $urls[] = '//cdn.jsdelivr.net';
      $urls[] = '//use.fontawesome.com';
      $urls[] = '//unpkg.com';
      break;

    case 'preconnect':
      $urls[] = 'https://fonts.googleapis.com';
      $urls[] = 'https://fonts.gstatic.com';
      break;
  }

  return $urls;
}
add_filter('wp_resource_hints', 'add_resource_hints', 10, 2);

// 不要なWordPressのデフォルトスクリプトを削除
function remove_unnecessary_scripts()
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
add_action('init', 'remove_unnecessary_scripts');

// jQueryの読み込み順序を確保
function ensure_jquery_loaded()
{
  if (!is_admin() && !wp_script_is('jquery', 'done') && !wp_script_is('jquery', 'enqueued')) {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', false);
  }
}
add_action('wp_enqueue_scripts', 'ensure_jquery_loaded', 5);

// デバッグ用：スクリプトの読み込み状況を出力
function debug_script_loading()
{
  if (WP_DEBUG && !is_admin()) {
    wp_add_inline_script('jquery', '
      console.log("jQuery loaded:", typeof jQuery !== "undefined");
      console.log("$ loaded:", typeof $ !== "undefined");
      if (typeof jQuery !== "undefined") {
        console.log("jQuery version:", jQuery.fn.jquery);
      }
    ');
  }
}
add_action('wp_enqueue_scripts', 'debug_script_loading', 999);

// アセットのバージョン管理（開発環境用）
function get_asset_version($file_path)
{
  if (WP_DEBUG) {
    return filemtime(get_template_directory() . $file_path);
  }
  return wp_get_theme()->get('Version');
}

// キャッシュバスティング（開発環境用）
function add_cache_busting($src, $handle)
{
  if (WP_DEBUG && !is_admin()) {
    // 開発環境では現在時刻をパラメータとして追加
    $src = add_query_arg('cb', time(), $src);
  }
  return $src;
}
add_filter('script_loader_src', 'add_cache_busting', 10, 2);
add_filter('style_loader_src', 'add_cache_busting', 10, 2);

function custom_excerpt_more($more)
{
  return ' <a class="c-btn c-btn-more-right" href="' . get_permalink() . '">もっと見る</a>'; // 「続きを読む」ボタンを追加
}
add_filter('excerpt_more', 'custom_excerpt_more');

//カスタムヘッダー
function theme_custom_header_setup()
{
  $args = array(
    'default-image'          => '',  // デフォルトの画像（なし）
    'random-default'         => false,
    'width'                  => 1200,  // 画像のデフォルト幅
    'height'                 => 600,   // 画像のデフォルト高さ
    'flex-width'             => true,  // 幅の調整を許可
    'flex-height'            => true,  // 高さの調整を許可
    'uploads'                => true,  // 画像のアップロードを許可
    'wp-head-callback'       => 'theme_header_style', // ヘッダーのスタイルを適用
  );
  add_theme_support('custom-header', $args);
}
add_action('after_setup_theme', 'theme_custom_header_setup');

//管理画面でアップロードしたヘッダー画像を CSS に反映 するための関数
function theme_header_style()
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

//カスタム投稿タイプ「ライバー」
function create_post_type_liver()
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
add_action('init', 'create_post_type_liver', 0);

//カスタム投稿タイプ「インタビュー」
function create_post_type_interview()
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
add_action('init', 'create_post_type_interview', 0);

//カスタムタクソノミー「ライバータグ」
function create_liver_taxonomy()
{
  register_taxonomy(
    'liver_tag',
    'liver',
    array(
      'labels' => array(
        'name'                       => 'ライバータグ',
        'singular_name'              => 'ライバータグ',
        'menu_name'                  => 'ライバータグ',
        'all_items'                  => 'すべてのタグ',
        'parent_item'                => '親タグ',
        'parent_item_colon'          => '親タグ:',
        'new_item_name'              => '新しいタグ名',
        'add_new_item'               => '新しいタグを追加',
        'edit_item'                  => 'タグを編集',
        'update_item'                => 'タグを更新',
        'view_item'                  => 'タグを表示',
        'separate_items_with_commas' => 'タグをカンマで区切る',
        'add_or_remove_items'        => 'タグを追加または削除',
        'choose_from_most_used'      => 'よく使われるタグから選択',
        'popular_items'              => '人気のタグ',
        'search_items'               => 'タグを検索',
        'not_found'                  => 'タグが見つかりません',
        'no_terms'                   => 'タグがありません',
        'items_list'                 => 'タグリスト',
        'items_list_navigation'      => 'タグリストナビゲーション',
      ),
      'hierarchical'               => false,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'show_in_rest'               => true,
      'rewrite'                    => array('slug' => 'liver-tag'),
    )
  );
}
add_action('init', 'create_liver_taxonomy', 0);

//カスタムタクソノミー「ライバーカテゴリー」
function create_liver_category_taxonomy()
{
  register_taxonomy(
    'liver_category',
    'liver',
    array(
      'labels' => array(
        'name'                       => 'ライバーカテゴリー',
        'singular_name'              => 'ライバーカテゴリー',
        'menu_name'                  => 'ライバーカテゴリー',
        'all_items'                  => 'すべてのカテゴリー',
        'parent_item'                => '親カテゴリー',
        'parent_item_colon'          => '親カテゴリー:',
        'new_item_name'              => '新しいカテゴリー名',
        'add_new_item'               => '新しいカテゴリーを追加',
        'edit_item'                  => 'カテゴリーを編集',
        'update_item'                => 'カテゴリーを更新',
        'view_item'                  => 'カテゴリーを表示',
        'separate_items_with_commas' => 'カテゴリーをカンマで区切る',
        'add_or_remove_items'        => 'カテゴリーを追加または削除',
        'choose_from_most_used'      => 'よく使われるカテゴリーから選択',
        'popular_items'              => '人気のカテゴリー',
        'search_items'               => 'カテゴリーを検索',
        'not_found'                  => 'カテゴリーが見つかりません',
        'no_terms'                   => 'カテゴリーがありません',
        'items_list'                 => 'カテゴリーリスト',
        'items_list_navigation'      => 'カテゴリーリストナビゲーション',
      ),
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'show_in_rest'               => true,
      'rewrite'                    => array('slug' => 'liver-category'),
    )
  );
}
add_action('init', 'create_liver_category_taxonomy', 0);

//カスタムタクソノミー「インタビューカテゴリー」
function create_interview_category_taxonomy()
{
  register_taxonomy(
    'interview_category',
    'interview',
    array(
      'labels' => array(
        'name'                       => 'インタビューカテゴリー',
        'singular_name'              => 'インタビューカテゴリー',
        'menu_name'                  => 'インタビューカテゴリー',
        'all_items'                  => 'すべてのカテゴリー',
        'parent_item'                => '親カテゴリー',
        'parent_item_colon'          => '親カテゴリー:',
        'new_item_name'              => '新しいカテゴリー名',
        'add_new_item'               => '新しいカテゴリーを追加',
        'edit_item'                  => 'カテゴリーを編集',
        'update_item'                => 'カテゴリーを更新',
        'view_item'                  => 'カテゴリーを表示',
        'separate_items_with_commas' => 'カテゴリーをカンマで区切る',
        'add_or_remove_items'        => 'カテゴリーを追加または削除',
        'choose_from_most_used'      => 'よく使われるカテゴリーから選択',
        'popular_items'              => '人気のカテゴリー',
        'search_items'               => 'カテゴリーを検索',
        'not_found'                  => 'カテゴリーが見つかりません',
        'no_terms'                   => 'カテゴリーがありません',
        'items_list'                 => 'カテゴリーリスト',
        'items_list_navigation'      => 'カテゴリーリストナビゲーション',
      ),
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'show_in_rest'               => true,
      'rewrite'                    => array('slug' => 'interview-category'),
    )
  );
}
add_action('init', 'create_interview_category_taxonomy', 0);

//カスタムタクソノミー「インタビュータグ」
function create_interview_tag_taxonomy()
{
  register_taxonomy(
    'interview_tag',
    'interview',
    array(
      'labels' => array(
        'name'                       => 'インタビュータグ',
        'singular_name'              => 'インタビュータグ',
        'menu_name'                  => 'インタビュータグ',
        'all_items'                  => 'すべてのタグ',
        'parent_item'                => '親タグ',
        'parent_item_colon'          => '親タグ:',
        'new_item_name'              => '新しいタグ名',
        'add_new_item'               => '新しいタグを追加',
        'edit_item'                  => 'タグを編集',
        'update_item'                => 'タグを更新',
        'view_item'                  => 'タグを表示',
        'separate_items_with_commas' => 'タグをカンマで区切る',
        'add_or_remove_items'        => 'タグを追加または削除',
        'choose_from_most_used'      => 'よく使われるタグから選択',
        'popular_items'              => '人気のタグ',
        'search_items'               => 'タグを検索',
        'not_found'                  => 'タグが見つかりません',
        'no_terms'                   => 'タグがありません',
        'items_list'                 => 'タグリスト',
        'items_list_navigation'      => 'タグリストナビゲーション',
      ),
      'hierarchical'               => false,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'show_in_rest'               => true,
      'rewrite'                    => array('slug' => 'interview-tag'),
    )
  );
}
add_action('init', 'create_interview_tag_taxonomy', 0);

//カスタムタクソノミー「イベントカテゴリー」
function create_event_category_taxonomy()
{
  register_taxonomy(
    'event_category',
    'event',
    array(
      'labels' => array(
        'name'                       => 'イベントカテゴリー',
        'singular_name'              => 'イベントカテゴリー',
        'menu_name'                  => 'イベントカテゴリー',
        'all_items'                  => 'すべてのカテゴリー',
        'parent_item'                => '親カテゴリー',
        'parent_item_colon'          => '親カテゴリー:',
        'new_item_name'              => '新しいカテゴリー名',
        'add_new_item'               => '新しいカテゴリーを追加',
        'edit_item'                  => 'カテゴリーを編集',
        'update_item'                => 'カテゴリーを更新',
        'view_item'                  => 'カテゴリーを表示',
        'separate_items_with_commas' => 'カテゴリーをカンマで区切る',
        'add_or_remove_items'        => 'カテゴリーを追加または削除',
        'choose_from_most_used'      => 'よく使われるカテゴリーから選択',
        'popular_items'              => '人気のカテゴリー',
        'search_items'               => 'カテゴリーを検索',
        'not_found'                  => 'カテゴリーが見つかりません',
        'no_terms'                   => 'カテゴリーがありません',
        'items_list'                 => 'カテゴリーリスト',
        'items_list_navigation'      => 'カテゴリーリストナビゲーション',
      ),
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'show_in_rest'               => true,
      'rewrite'                    => array('slug' => 'event-category'),
    )
  );
}
add_action('init', 'create_event_category_taxonomy', 0);

//カスタムタクソノミー「イベントタグ」
function create_event_tag_taxonomy()
{
  register_taxonomy(
    'event_tag',
    'event',
    array(
      'labels' => array(
        'name'                       => 'イベントタグ',
        'singular_name'              => 'イベントタグ',
        'menu_name'                  => 'イベントタグ',
        'all_items'                  => 'すべてのタグ',
        'parent_item'                => '親タグ',
        'parent_item_colon'          => '親タグ:',
        'new_item_name'              => '新しいタグ名',
        'add_new_item'               => '新しいタグを追加',
        'edit_item'                  => 'タグを編集',
        'update_item'                => 'タグを更新',
        'view_item'                  => 'タグを表示',
        'separate_items_with_commas' => 'タグをカンマで区切る',
        'add_or_remove_items'        => 'タグを追加または削除',
        'choose_from_most_used'      => 'よく使われるタグから選択',
        'popular_items'              => '人気のタグ',
        'search_items'               => 'タグを検索',
        'not_found'                  => 'タグが見つかりません',
        'no_terms'                   => 'タグがありません',
        'items_list'                 => 'タグリスト',
        'items_list_navigation'      => 'タグリストナビゲーション',
      ),
      'hierarchical'               => false,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'show_in_rest'               => true,
      'rewrite'                    => array('slug' => 'event-tag'),
    )
  );
}
add_action('init', 'create_event_tag_taxonomy', 0);


//共通ライバーサムネイル画像のURLを定義
define('COMMON_LIVER_THUMBNAIL_URL', get_template_directory_uri() . '/assets/images/liver-thumbnail/liver-thumbnail-01.webp');

// カスタム投稿タイプ登録後にパーマリンクを更新
function flush_rewrite_rules_on_activation()
{
  create_post_type_liver();
  create_post_type_interview();
  create_post_type_event();
  create_liver_taxonomy();
  create_liver_category_taxonomy();
  create_interview_category_taxonomy();
  create_interview_tag_taxonomy();
  create_event_category_taxonomy();
  create_event_tag_taxonomy();
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');

// カスタム投稿タイプのアーカイブリンクをメニューに追加するための設定
function add_custom_post_types_to_nav_menus()
{
  add_post_type_support('liver', 'nav-menus');
  add_post_type_support('interview', 'nav-menus');
}
add_action('init', 'add_custom_post_types_to_nav_menus');

// メニュー管理画面にカスタム投稿タイプのアーカイブを強制的に追加
function add_cpt_archives_to_menus()
{
  global $wp_post_types;

  // ライバーのアーカイブを追加
  if (isset($wp_post_types['liver'])) {
    $wp_post_types['liver']->show_in_nav_menus = true;
  }

  // インタビューのアーカイブを追加
  if (isset($wp_post_types['interview'])) {
    $wp_post_types['interview']->show_in_nav_menus = true;
  }
}
add_action('init', 'add_cpt_archives_to_menus', 20);

// カスタム投稿タイプのアーカイブリンクをメニューボックスに追加
function add_cpt_archives_to_menu_metabox()
{
  add_meta_box(
    'add-liver-archive',
    'ライバー',
    'liver_archive_menu_metabox',
    'nav-menus',
    'side',
    'default'
  );

  add_meta_box(
    'add-interview-archive',
    'インタビュー',
    'interview_archive_menu_metabox',
    'nav-menus',
    'side',
    'default'
  );
}
add_action('admin_head-nav-menus.php', 'add_cpt_archives_to_menu_metabox');

// ライバーアーカイブのメタボックス内容
function liver_archive_menu_metabox()
{
  $archive_url = get_post_type_archive_link('liver');
  ?>
  <div id="liver-archive-div">
    <ul>
      <li>
        <label class="menu-item-title">
          <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1">
          ライバー一覧
        </label>
        <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
        <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="ライバー一覧">
        <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="<?php echo $archive_url; ?>">
      </li>
    </ul>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="メニューに追加" name="add-liver-archive-menu-item" id="submit-liver-archive">
      </span>
    </p>
  </div>
<?php
}

// インタビューアーカイブのメタボックス内容
function interview_archive_menu_metabox()
{
  $archive_url = get_post_type_archive_link('interview');
?>
  <div id="interview-archive-div">
    <ul>
      <li>
        <label class="menu-item-title">
          <input type="checkbox" class="menu-item-checkbox" name="menu-item[-2][menu-item-object-id]" value="-2">
          インタビュー一覧
        </label>
        <input type="hidden" class="menu-item-type" name="menu-item[-2][menu-item-type]" value="custom">
        <input type="hidden" class="menu-item-title" name="menu-item[-2][menu-item-title]" value="インタビュー一覧">
        <input type="hidden" class="menu-item-url" name="menu-item[-2][menu-item-url]" value="<?php echo $archive_url; ?>">
      </li>
    </ul>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="メニューに追加" name="add-interview-archive-menu-item" id="submit-interview-archive">
      </span>
    </p>
  </div>
<?php
}


function custom_excerpt_length($length)
{
  return 40; // 60文字に設定
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// カスタム投稿タイプのメニュー追加用JavaScript
function cpt_menu_admin_script()
{
?>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      // ライバーのメニュー追加処理
      $('#submit-liver-archive').click(function(e) {
        var item = $('#liver-archive-div input[type="checkbox"]:checked');
        if (item.length) {
          var menuItem = {
            'menu-item-title': 'ライバー一覧',
            'menu-item-url': '<?php echo get_post_type_archive_link("liver"); ?>',
            'menu-item-type': 'custom'
          };
          wpNavMenu.addItemToMenu(menuItem, $('#liver-archive-div'));
        }
        return false;
      });

      // インタビューのメニュー追加処理
      $('#submit-interview-archive').click(function(e) {
        var item = $('#interview-archive-div input[type="checkbox"]:checked');
        if (item.length) {
          var menuItem = {
            'menu-item-title': 'インタビュー一覧',
            'menu-item-url': '<?php echo get_post_type_archive_link("interview"); ?>',
            'menu-item-type': 'custom'
          };
          wpNavMenu.addItemToMenu(menuItem, $('#interview-archive-div'));
        }
        return false;
      });
    });
  </script>
<?php
}
add_action('admin_footer-nav-menus.php', 'cpt_menu_admin_script');

// メニュー編集画面にカスタム投稿タイプを強制的に表示する追加方法
function custom_post_type_nav_menu_metabox()
{
  add_meta_box(
    'liver-nav-menu',
    'ライバー',
    'liver_nav_menu_metabox_content',
    'nav-menus',
    'side',
    'default'
  );

  add_meta_box(
    'interview-nav-menu',
    'インタビュー',
    'interview_nav_menu_metabox_content',
    'nav-menus',
    'side',
    'default'
  );
}

function liver_nav_menu_metabox_content()
{
  $posts = get_posts(array(
    'post_type' => 'liver',
    'numberposts' => 10,
    'post_status' => 'publish'
  ));
?>
  <div id="posttype-liver" class="posttypediv">
    <ul id="liver-checklist" class="categorychecklist form-no-clear">
      <li>
        <label class="menu-item-title">
          <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1">
          ライバー一覧（アーカイブ）
        </label>
        <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
        <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="ライバー一覧">
        <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="<?php echo get_post_type_archive_link('liver'); ?>">
      </li>
      <?php foreach ($posts as $post): ?>
        <li>
          <label class="menu-item-title">
            <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $post->ID; ?>][menu-item-object-id]" value="<?php echo $post->ID; ?>">
            <?php echo esc_html($post->post_title); ?>
          </label>
          <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $post->ID; ?>][menu-item-type]" value="post_type">
          <input type="hidden" class="menu-item-object" name="menu-item[<?php echo $post->ID; ?>][menu-item-object]" value="liver">
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="メニューに追加" name="add-post-type-menu-item" id="submit-posttype-liver">
      </span>
    </p>
  </div>
<?php
}

function interview_nav_menu_metabox_content()
{
  $posts = get_posts(array(
    'post_type' => 'interview',
    'numberposts' => 10,
    'post_status' => 'publish'
  ));
?>
  <div id="posttype-interview" class="posttypediv">
    <ul id="interview-checklist" class="categorychecklist form-no-clear">
      <li>
        <label class="menu-item-title">
          <input type="checkbox" class="menu-item-checkbox" name="menu-item[-2][menu-item-object-id]" value="-2">
          インタビュー一覧（アーカイブ）
        </label>
        <input type="hidden" class="menu-item-type" name="menu-item[-2][menu-item-type]" value="custom">
        <input type="hidden" class="menu-item-title" name="menu-item[-2][menu-item-title]" value="インタビュー一覧">
        <input type="hidden" class="menu-item-url" name="menu-item[-2][menu-item-url]" value="<?php echo get_post_type_archive_link('interview'); ?>">
      </li>
      <?php foreach ($posts as $post): ?>
        <li>
          <label class="menu-item-title">
            <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $post->ID; ?>][menu-item-object-id]" value="<?php echo $post->ID; ?>">
            <?php echo esc_html($post->post_title); ?>
          </label>
          <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $post->ID; ?>][menu-item-type]" value="post_type">
          <input type="hidden" class="menu-item-object" name="menu-item[<?php echo $post->ID; ?>][menu-item-object]" value="interview">
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="メニューに追加" name="add-post-type-menu-item" id="submit-posttype-interview">
      </span>
    </p>
  </div>
<?php
}

add_action('admin_head-nav-menus.php', 'custom_post_type_nav_menu_metabox');

// パーマリンクとメニューの設定を強制更新
function force_cpt_menu_update()
{
  // パーマリンク構造を強制的に更新
  flush_rewrite_rules();

  // WordPressキャッシュをクリア
  if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
  }
}
add_action('admin_init', 'force_cpt_menu_update');

// デバッグ用：カスタム投稿タイプの登録状況を確認
function debug_cpt_registration()
{
  if (current_user_can('manage_options') && isset($_GET['debug_cpt'])) {
    echo '<pre>';
    echo "Registered Post Types:\n";
    print_r(get_post_types(array(), 'objects'));
    echo "\n\nLiver Post Type:\n";
    print_r(get_post_type_object('liver'));
    echo "\n\nInterview Post Type:\n";
    print_r(get_post_type_object('interview'));
    echo '</pre>';
    exit;
  }
}
add_action('init', 'debug_cpt_registration', 99);

// カスタム投稿タイプページでメニューにアクティブクラスを追加
function add_active_class_to_custom_post_type_menu($classes, $item, $args)
{
  // ヘッダーメニューのみに適用
  if ($args->theme_location !== 'header') {
    return $classes;
  }

  // 現在のページがカスタム投稿タイプの場合
  if (is_singular('liver') || is_post_type_archive('liver')) {
    // メニュー項目がライバーアーカイブページの場合
    if ($item->url === get_post_type_archive_link('liver')) {
      $classes[] = 'current-menu-item';
    }
  }

  if (is_singular('interview') || is_post_type_archive('interview')) {
    // メニュー項目がインタビューアーカイブページの場合
    if ($item->url === get_post_type_archive_link('interview')) {
      $classes[] = 'current-menu-item';
    }
  }

  return $classes;
}
add_filter('nav_menu_css_class', 'add_active_class_to_custom_post_type_menu', 10, 3);

// メニュー項目のアクティブ状態をより詳細に制御
function custom_nav_menu_active_class($classes, $item, $args)
{
  if ($args->theme_location !== 'header') {
    return $classes;
  }

  global $post;

  // 現在のページのURLと比較
  $current_url = home_url($_SERVER['REQUEST_URI']);
  $menu_url = $item->url;

  // URLが完全一致する場合
  if ($current_url === $menu_url) {
    $classes[] = 'current-menu-item';
  }

  // カスタム投稿タイプの詳細ページでアーカイブメニューをアクティブにする
  if (is_single()) {
    $post_type = get_post_type();
    if ($post_type === 'liver' && $menu_url === get_post_type_archive_link('liver')) {
      $classes[] = 'current-post-type';
    }
    if ($post_type === 'interview' && $menu_url === get_post_type_archive_link('interview')) {
      $classes[] = 'current-post-type';
    }
  }

  return $classes;
}
add_filter('nav_menu_css_class', 'custom_nav_menu_active_class', 20, 3);


//カスタム投稿タイプ[イベント]の作成
function create_post_type_event()
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
add_action('init', 'create_post_type_event', 0);


//カスタム投稿タイプの管理画面がクラシックエディターになっているので、Gutenbergエディターに変更
function enable_gutenberg_for_custom_post_types($can_edit, $post_type)
{
  // カスタム投稿タイプでGutenbergエディターを強制的に有効化
  if (in_array($post_type, array('liver', 'interview', 'event'))) {
    return true;
  }
  return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'enable_gutenberg_for_custom_post_types', 10, 2);

// Gutenbergエディターを確実に有効化（追加の設定）
function force_gutenberg_for_custom_post_types()
{
  // Classic Editorプラグインが有効な場合でも、カスタム投稿タイプではGutenbergを使用
  add_filter('use_block_editor_for_post', function ($use_block_editor, $post) {
    if (in_array($post->post_type, array('liver', 'interview', 'event'))) {
      return true;
    }
    return $use_block_editor;
  }, 10, 2);
}
add_action('init', 'force_gutenberg_for_custom_post_types');

// カスタム投稿タイプでREST APIサポートを確実にする（Gutenbergに必要）
function ensure_rest_support_for_custom_post_types()
{
  // ライバー投稿タイプ
  $liver_post_type = get_post_type_object('liver');
  if ($liver_post_type) {
    $liver_post_type->show_in_rest = true;
    $liver_post_type->rest_base = 'livers';
  }

  // インタビュー投稿タイプ
  $interview_post_type = get_post_type_object('interview');
  if ($interview_post_type) {
    $interview_post_type->show_in_rest = true;
    $interview_post_type->rest_base = 'interviews';
  }

  // イベント投稿タイプ
  $event_post_type = get_post_type_object('event');
  if ($event_post_type) {
    $event_post_type->show_in_rest = true;
    $event_post_type->rest_base = 'events';
  }
}
add_action('init', 'ensure_rest_support_for_custom_post_types', 20);

// ===== ランキング用のギフト数カスタムフィールド（ACF使用） =====

// ACF（Advanced Custom Fields）を使用してライバー情報を管理
// カスタムフィールド:
// - gift_count: 総ギフト数
// - monthly_gifts: 今月のギフト数 
// - weekly_gifts: 今週のギフト数
// - daily_gifts: 今日のギフト数
// - gift_trend: トレンド（up/down/stable）

// ACFによる自動フィールド管理（ACFプラグインで設定）

// ACFによる自動保存（保存処理は不要）

// サブクエリを使用したランキング取得関数（既存ACFフィールド対応）
function get_liver_ranking($period = 'total', $limit = 10, $offset = 0)
{
  global $wpdb;

  // 既存のACFフィールド構成に合わせる
  $field_name_map = [
    'total' => 'gift',
    'monthly' => 'gift',  // 同じフィールドを使用
    'weekly' => 'gift',   // 同じフィールドを使用
    'daily' => 'gift'     // 同じフィールドを使用
  ];

  $field_name = $field_name_map[$period] ?? 'gift';
  $limit_clause = $limit > 0 ? "LIMIT {$limit}" : '';
  $offset_clause = $offset > 0 ? "OFFSET {$offset}" : '';

  // サブクエリでライバーIDをギフト数順に取得
  $subquery = $wpdb->prepare("
    SELECT p.ID 
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'liver' 
    AND p.post_status = 'publish'
    AND pm.meta_key = %s
    AND pm.meta_value != ''
    ORDER BY CAST(pm.meta_value AS SIGNED) DESC
    {$limit_clause} {$offset_clause}
  ", $field_name);

  $liver_ids = $wpdb->get_col($subquery);

  if (empty($liver_ids)) {
    return new WP_Query(['post__in' => [0]]);
  }

  // メインクエリで投稿データを取得（順序を保持）
  $args = [
    'post_type' => 'liver',
    'post_status' => 'publish',
    'post__in' => $liver_ids,
    'orderby' => 'post__in',
    'posts_per_page' => -1
  ];

  return new WP_Query($args);
}

// サブクエリ対応Ajax処理用のアクション
function ajax_get_ranking_data()
{
  $period = sanitize_text_field($_POST['period'] ?? 'total');
  $limit = intval($_POST['limit'] ?? 10);
  $offset = intval($_POST['offset'] ?? 0);

  $ranking = get_liver_ranking($period, $limit, $offset);

  $data = [];
  $rank = $offset + 1;

  if ($ranking->have_posts()) {
    while ($ranking->have_posts()) {
      $ranking->the_post();

      $field_name_map = [
        'total' => 'gift',
        'monthly' => 'gift',
        'weekly' => 'gift',
        'daily' => 'gift'
      ];

      // ACFフィールドから値を取得（フォールバック対応）
      if (function_exists('get_field')) {
        $gift_count = get_field('gift') ?: 0;
        $creator_name = get_field('creator_name') ?: get_the_title();
        $creator_account = get_field('creator_account') ?: '';
        $feature = get_field('feature') ?: '';
        $delivery = get_field('delivery') ?: '';
        $delivery_time = get_field('delivery_time') ?: '';
        $account_url = get_field('account_url') ?: '';
      } else {
        // WordPressの標準メタフィールドを使用
        $gift_count = get_post_meta(get_the_ID(), 'gift', true) ?: 0;
        $creator_name = get_post_meta(get_the_ID(), 'creator_name', true) ?: get_the_title();
        $creator_account = get_post_meta(get_the_ID(), 'creator_account', true) ?: '';
        $feature = get_post_meta(get_the_ID(), 'feature', true) ?: '';
        $delivery = get_post_meta(get_the_ID(), 'delivery', true) ?: '';
        $delivery_time = get_post_meta(get_the_ID(), 'delivery_time', true) ?: '';
        $account_url = get_post_meta(get_the_ID(), 'account_url', true) ?: '';
      }
      $data[] = [
        'rank' => $rank,
        'id' => get_the_ID(),
        'name' => $creator_name,
        'creator_account' => $creator_account,
        'gift_count' => intval($gift_count),
        'feature' => $feature,
        'delivery' => $delivery,
        'delivery_time' => $delivery_time,
        'account_url' => $account_url,
        'avatar' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: get_template_directory_uri() . '/src/assets/images/24401878_s.jpg',
        'url' => get_permalink()
      ];

      $rank++;
    }
  }

  wp_reset_postdata();
  wp_send_json_success($data);
}
add_action('wp_ajax_get_ranking_data', 'ajax_get_ranking_data');
add_action('wp_ajax_nopriv_get_ranking_data', 'ajax_get_ranking_data');

// ACFプラグイン状況の確認とデバッグ用
function check_acf_status()
{
  if (current_user_can('administrator')) {
    if (function_exists('get_field')) {
      error_log('ACF Plugin: Active');
    } else {
      error_log('ACF Plugin: Not found - Using WordPress meta fields');
    }
  }
}
add_action('init', 'check_acf_status');

// ACF用ヘルパー関数
// サブクエリベースの最適化されたヘルパー関数
function get_liver_total_count()
{
  global $wpdb;

  $count = $wpdb->get_var("
    SELECT COUNT(p.ID) 
    FROM {$wpdb->posts} p 
    WHERE p.post_type = 'liver' 
    AND p.post_status = 'publish'
  ");

  return intval($count);
}

function get_total_gifts_count()
{
  global $wpdb;

  $total = $wpdb->get_var("
    SELECT SUM(CAST(pm.meta_value AS SIGNED)) 
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'liver' 
    AND p.post_status = 'publish'
    AND pm.meta_key = 'gift'
    AND pm.meta_value != ''
    AND pm.meta_value IS NOT NULL
  ");

  return intval($total ?: 0);
}

function get_top_liver_name()
{
  global $wpdb;

  $top_liver_id = $wpdb->get_var("
    SELECT p.ID 
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'liver' 
    AND p.post_status = 'publish'
    AND pm.meta_key = 'gift'
    AND pm.meta_value != ''
    ORDER BY CAST(pm.meta_value AS SIGNED) DESC
    LIMIT 1
  ");

  if ($top_liver_id) {
    // クリエイター名を優先表示、なければタイトル
    if (function_exists('get_field')) {
      $creator_name = get_field('creator_name', $top_liver_id);
    } else {
      $creator_name = get_post_meta($top_liver_id, 'creator_name', true);
    }
    return $creator_name ?: get_the_title($top_liver_id);
  }

  return 'データなし';
}

// キャッシュ付きの統計情報取得（パフォーマンス向上）
function get_ranking_stats_cached()
{
  $cache_key = 'liver_ranking_stats';
  $cached_stats = wp_cache_get($cache_key);

  if ($cached_stats === false) {
    $stats = [
      'total_livers' => get_liver_total_count(),
      'total_gifts' => get_total_gifts_count(),
      'top_liver' => get_top_liver_name()
    ];

    // 5分間キャッシュ
    wp_cache_set($cache_key, $stats, '', 300);
    return $stats;
  }

  return $cached_stats;
}

// 管理画面の投稿一覧にサムネイルカラムを追加
// ライバー投稿タイプ用
function add_liver_thumbnail_column($columns) {
  $new_columns = array();
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = 'サムネイル';
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}
add_filter('manage_liver_posts_columns', 'add_liver_thumbnail_column');

function display_liver_thumbnail_column($column_name, $post_id) {
  if ($column_name === 'thumbnail') {
    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
    echo $thumbnail ? $thumbnail : '—';
  }
}
add_action('manage_liver_posts_custom_column', 'display_liver_thumbnail_column', 10, 2);

// インタビュー投稿タイプ用
function add_interview_thumbnail_column($columns) {
  $new_columns = array();
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = 'サムネイル';
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}
add_filter('manage_interview_posts_columns', 'add_interview_thumbnail_column');

function display_interview_thumbnail_column($column_name, $post_id) {
  if ($column_name === 'thumbnail') {
    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
    echo $thumbnail ? $thumbnail : '—';
  }
}
add_action('manage_interview_posts_custom_column', 'display_interview_thumbnail_column', 10, 2);

// イベント投稿タイプ用
function add_event_thumbnail_column($columns) {
  $new_columns = array();
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = 'サムネイル';
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}
add_filter('manage_event_posts_columns', 'add_event_thumbnail_column');

function display_event_thumbnail_column($column_name, $post_id) {
  if ($column_name === 'thumbnail') {
    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
    echo $thumbnail ? $thumbnail : '—';
  }
}
add_action('manage_event_posts_custom_column', 'display_event_thumbnail_column', 10, 2);

// サムネイルカラムの幅を調整するCSS
function add_admin_thumbnail_column_css() {
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
add_action('admin_head', 'add_admin_thumbnail_column_css');

// ライバーアーカイブページの表示順をランダムにする
function randomize_liver_archive_query($query) {
  // 管理画面やメインクエリでない場合は何もしない
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // ライバーアーカイブページの場合のみランダム表示
  if (is_post_type_archive('liver')) {
    $query->set('orderby', 'rand');
  }
}
add_action('pre_get_posts', 'randomize_liver_archive_query');
