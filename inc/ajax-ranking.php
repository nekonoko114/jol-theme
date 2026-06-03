<?php
// ===== ランキング用のギフト数カスタムフィールド（ACF使用） =====

// サブクエリを使用したランキング取得関数（既存ACFフィールド対応）
function jol_get_liver_ranking($period = 'total', $limit = 10, $offset = 0)
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
function jol_ajax_get_ranking_data()
{
  $period = sanitize_text_field($_POST['period'] ?? 'total');
  $limit = intval($_POST['limit'] ?? 10);
  $offset = intval($_POST['offset'] ?? 0);

  $ranking = jol_get_liver_ranking($period, $limit, $offset);

  $data = [];
  $rank = $offset + 1;

  if ($ranking->have_posts()) {
    while ($ranking->have_posts()) {
      $ranking->the_post();

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
add_action('wp_ajax_get_ranking_data', 'jol_ajax_get_ranking_data');
add_action('wp_ajax_nopriv_get_ranking_data', 'jol_ajax_get_ranking_data');

// ACF用ヘルパー関数
// サブクエリベースの最適化されたヘルパー関数
function jol_get_liver_total_count()
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

function jol_get_total_gifts_count()
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

function jol_get_top_liver_name()
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
function jol_get_ranking_stats_cached()
{
  $cache_key = 'liver_ranking_stats';
  $cached_stats = wp_cache_get($cache_key);

  if ($cached_stats === false) {
    $stats = [
      'total_livers' => jol_get_liver_total_count(),
      'total_gifts' => jol_get_total_gifts_count(),
      'top_liver' => jol_get_top_liver_name()
    ];

    // 5分間キャッシュ
    wp_cache_set($cache_key, $stats, '', 300);
    return $stats;
  }

  return $cached_stats;
}
