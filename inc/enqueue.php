<?php
// CSSとJSの読み込み
function jol_enqueue_theme_assets()
{
  // CSS Reset（バージョン固定）
  wp_enqueue_style('modern-css-reset', 'https://unpkg.com/modern-css-reset@1.4.0/dist/reset.min.css', array(), '1.4.0');

  // Google Fonts
  wp_enqueue_style('google-fonts-base', 'https://fonts.googleapis.com/css2?family=Condiment&family=M+PLUS+Rounded+1c&display=swap', array(), null);
  wp_enqueue_style('google-fonts-premium', 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,300;0,400;0,600;1,400&family=Noto+Sans+JP:wght@300;400;500;700&display=swap', array(), null);

  // Font Awesome
  wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v6.2.0/css/all.css', array(), '6.2.0');

  // Swiper CSS（パッチバージョンまで固定）
  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11.2.6/swiper-bundle.min.css', array(), '11.2.6');

  // メインスタイルシート（Tailwind CSS v4 + ネイティブCSS統合ビルド）
  $tailwind_css_path = get_template_directory() . '/assets/css/tailwind.css';
  if (file_exists($tailwind_css_path)) {
    wp_enqueue_style('theme-style', get_template_directory_uri() . '/assets/css/tailwind.css', array('modern-css-reset'), filemtime($tailwind_css_path));
  }

  // jQuery設定（WordPress標準のjQueryを使用）
  if (!is_admin()) {
    wp_enqueue_script('jquery');

    // WordPress標準jQueryはnoConflictモードのため、既存スクリプト互換用に$エイリアスを確保
    wp_add_inline_script('jquery-core', 'window.jQuery = window.$ = jQuery;');
  }

  // particles.js (FVパーティクル用 - トップページのみ)
  if (is_front_page() || is_home()) {
    wp_enqueue_script('particles-js', 'https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js', array(), '2.0.0', true);

    // 常にビルドされている前提でバージョンパラメータにfilemtimeを使う（軽量化）
    $particles_file = get_template_directory() . '/assets/js/particles-config.js';
    $version = file_exists($particles_file) ? filemtime($particles_file) : '1.0.0';
    wp_enqueue_script('particles-config', get_template_directory_uri() . '/assets/js/particles-config.js', array('particles-js'), $version, true);
  }

  // Swiper JavaScript（パッチバージョンまで固定）
  wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11.2.6/swiper-bundle.min.js', array('jquery'), '11.2.6', true);

  // ランキングページ用スクリプト
  if (is_page_template('page-ranking.php')) {
    $ranking_js_path = get_template_directory() . '/assets/js/ranking.js';
    $ranking_js_ver  = file_exists($ranking_js_path) ? filemtime($ranking_js_path) : '1.0.0';
    wp_enqueue_script('ranking-manager', get_template_directory_uri() . '/assets/js/ranking.js', array('jquery'), $ranking_js_ver, true);
  }

  // メインアプリケーション（Viteで各モジュールを統合バンドル済み）
  $app_js_path = get_template_directory() . '/assets/js/app.js';
  $app_js_ver  = file_exists($app_js_path) ? filemtime($app_js_path) : '1.0.0';
  wp_enqueue_script('theme-app', get_template_directory_uri() . '/assets/js/app.js', array('jquery', 'swiper-js'), $app_js_ver, true);

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
/**
 * 管理画面用のスクリプトとスタイル
 *
 * @param string $hook
 */
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
/**
 * DNSプリフェッチとプリロードの追加
 *
 * @param array  $urls
 * @param string $relation_type
 * @return array
 */
function jol_add_resource_hints($urls, $relation_type)
{
  if (wp_installing() || is_admin()) {
    return $urls;
  }

  switch ($relation_type) {
    case 'dns-prefetch':
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

// jQueryの読み込み順序を確保（WordPress標準jQueryを使用）
function jol_ensure_jquery_loaded()
{
  if (!is_admin() && !wp_script_is('jquery', 'done') && !wp_script_is('jquery', 'enqueued')) {
    wp_enqueue_script('jquery');
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
/**
 * アセットのバージョン管理
 *
 * @param string $file_path
 * @return string|int
 */
function jol_get_asset_version($file_path)
{
  if (WP_DEBUG) {
    return filemtime(get_template_directory() . $file_path);
  }
  return wp_get_theme()->get('Version');
}

// キャッシュバスティング（開発環境用）
/**
 * キャッシュバスティングの追加
 *
 * @param string $src
 * @param string $handle
 * @return string
 */
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
