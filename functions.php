<?php
/**
 * Theme functions and definitions
 *
 * @package jol-themes
 */

// テーマ設定（テーマサポート、カスタムヘッダーなど）
require_once get_template_directory() . '/inc/setup.php';

// ローカル・本番両用（WordPress側から直接Pullするプログラム）
require_once get_template_directory() . '/inc/liver-sync-pull.php';

// アセット（CSS/JS）の読み込み
require_once get_template_directory() . '/inc/enqueue.php';

// カスタム投稿タイプの登録
require_once get_template_directory() . '/inc/custom-post-types.php';

// カスタムタクソノミーの登録
require_once get_template_directory() . '/inc/taxonomies.php';

// カスタムメニュー・管理画面メニューの設定
require_once get_template_directory() . '/inc/menus.php';

// 管理画面のカスタマイズ（ウィジェット、サムネイルカラムなど）
require_once get_template_directory() . '/inc/admin.php';

// ランキング用Ajax処理およびACF対応ヘルパー
require_once get_template_directory() . '/inc/ajax-ranking.php';

// クエリ制御
require_once get_template_directory() . '/inc/queries.php';

// All in One WP Migrationのエクスポートからnode_modulesを除外
add_filter( 'ai1wm_exclude_themes_from_export', function ( $exclude_filters ) {
  $exclude_filters[] = 'jol-themes/node_modules';
  return $exclude_filters;
} );
