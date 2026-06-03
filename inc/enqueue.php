<?php
// CSSとJSの読み込み
function jol_enqueue_theme_assets()
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

    // 常にビルドされている前提でバージョンパラメータにfilemtimeを使う（軽量化）
    $particles_file = get_template_directory() . '/assets/js/particles-config.js';
    $version = file_exists($particles_file) ? filemtime($particles_file) : '1.0.0';
    wp_enqueue_script('particles-config', get_template_directory_uri() . '/assets/js/particles-config.js', array('particles-js'), $version, true);
  }

  // Swiper JavaScript
  wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), '11.0.0', true);

  // モジュール化されたJavaScript
  wp_enqueue_script('loading-animation', get_template_directory_uri() . '/assets/js/modules/loadingAnimation.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/modules/loadingAnimation.js'), true);
  wp_enqueue_script('main-swiper', get_template_directory_uri() . '/assets/js/modules/mainSwiper.js', array('swiper-js'), time(), true);
  wp_enqueue_script('liver-slider', get_template_directory_uri() . '/assets/js/modules/liverSlider.js', array('swiper-js'), time(), true);
  
  // hamburger.jsの読み込み
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
add_action('wp_enqueue_scripts', 'jol_enqueue_theme_assets');

// Twitter埋め込みスクリプトの読み込み
function jol_add_twitter_embed_script()
{
  if (is_active_sidebar('sidebar-1')) { // サイドバーがアクティブなときのみ
    wp_enqueue_script('twitter-widgets', 'https://platform.twitter.com/widgets.js', array(), null, true);
  }
}
add_action('wp_enqueue_scripts', 'jol_add_twitter_embed_script');

// 管理画面用のスクリプトとスタイル
function jol_enqueue_admin_assets($hook)
{
  // 管理画面でも必要に応じてアセットを読み込み
  if ($hook === 'post.php' || $hook === 'post-new.php') {
    $admin_css_path = get_template_directory() . '/assets/css/admin.css';
    if (file_exists($admin_css_path)) {
      wp_enqueue_style('admin-custom', get_template_directory_uri() . '/assets/css/admin.css', array(), filemtime($admin_css_path));
    }
  }
}
add_action('admin_enqueue_scripts', 'jol_enqueue_admin_assets');

// DNSプリフェッチとプリロードの追加
function jol_add_resource_hints($urls, $relation_type)
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
add_filter('wp_resource_hints', 'jol_add_resource_hints', 10, 2);

// jQueryの読み込み順序を確保
function jol_ensure_jquery_loaded()
{
  if (!is_admin() && !wp_script_is('jquery', 'done') && !wp_script_is('jquery', 'enqueued')) {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', false);
  }
}
add_action('wp_enqueue_scripts', 'jol_ensure_jquery_loaded', 5);

// デバッグ用：スクリプトの読み込み状況を出力
function jol_debug_script_loading()
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
add_action('wp_enqueue_scripts', 'jol_debug_script_loading', 999);

// アセットのバージョン管理（開発環境用）
function jol_get_asset_version($file_path)
{
  if (WP_DEBUG) {
    return filemtime(get_template_directory() . $file_path);
  }
  return wp_get_theme()->get('Version');
}

// キャッシュバスティング（開発環境用）
function jol_add_cache_busting($src, $handle)
{
  if (WP_DEBUG && !is_admin()) {
    // 開発環境では現在時刻をパラメータとして追加
    $src = add_query_arg('cb', time(), $src);
  }
  return $src;
}
add_filter('script_loader_src', 'jol_add_cache_busting', 10, 2);
add_filter('style_loader_src', 'jol_add_cache_busting', 10, 2);
