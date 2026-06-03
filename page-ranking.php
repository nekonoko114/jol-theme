<?php

/**
 * Template Name: ランキングページ
 * Description: ライバーのギフト数ランキング表示ページ
 */
get_header(); ?>

<main class="ranking-page">
    <!-- ランキングヘッダーセクション -->
    <div class="ranking-header">
        <div class="ranking-hero-content">
            <h1 class="ranking-main-title">
                <i class="fas fa-trophy"></i>
                ライバー ギフト ランキング
            </h1>
            <p class="ranking-subtitle">
                頑張っているライバーたちのギフト数ランキング！
            </p>
        </div>
    </div>

    <div class="ranking-main inner">
        <div class="container">
            <!-- 表彰台セクション（TOP3） -->
            <section class="podium-section">
                <h2 class="section-title">
                    <i class="fas fa-trophy"></i>
                    TOP 3 ライバー
                </h2>
                <div class="podium-container">
                    <div class="podium" id="top-ranking">
                        <!-- TOP3ライバー表示（ACF対応） -->
                        <?php
                        // TOP3のライバー情報を取得（既存ACFフィールド対応）
                        $top_ranking = jol_get_liver_ranking('total', 3);
                        $rank = 1;

                        if ($top_ranking && $top_ranking->have_posts()) :
                            while ($top_ranking->have_posts()) : $top_ranking->the_post();
                                // WordPressの標準メタフィールドを使用
                                $gift_count = get_post_meta(get_the_ID(), 'gift', true) ?: 0;
                                $creator_name = get_post_meta(get_the_ID(), 'creator_name', true) ?: get_the_title();
                                $creator_account = get_post_meta(get_the_ID(), 'creator_account', true) ?: '';
                                $feature = get_post_meta(get_the_ID(), 'feature', true) ?: '';
                                $delivery = get_post_meta(get_the_ID(), 'delivery', true) ?: '';
                                $delivery_time = get_post_meta(get_the_ID(), 'delivery_time', true) ?: '';
                                $account_url = get_post_meta(get_the_ID(), 'account_url', true) ?: '';
                                $avatar_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

                                if (!$avatar_url) {
                                    $avatar_url = get_template_directory_uri() . '/src/assets/images/24401878_s.jpg';
                                }
                        ?>
                                <div class="podium-rank rank-<?php echo $rank; ?>" data-rank="<?php echo $rank; ?>">
                                    <?php
                                    $podium_imgs = [
                                        1 => get_template_directory_uri() . '/assets/images/ranking/clown-gold.webp',
                                        2 => get_template_directory_uri() . '/assets/images/ranking/clown-silver.webp',
                                        3 => get_template_directory_uri() . '/assets/images/ranking/clown-brons.webp',
                                    ];
                                    $pod_img = $podium_imgs[$rank] ?? '';
                                    ?>
                                    <div class="rank-badge">
                                        <?php if ($pod_img): ?>
                                            <img src="<?php echo esc_url($pod_img); ?>" alt="rank-<?php echo $rank; ?>" class="rank-icon">
                                        <?php else: ?>
                                            <?php echo $rank; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="liver-avatar">
                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    </div>
                                    <div class="liver-info">
                                        <h3 class="liver-name">
                                            <a href="<?php the_permalink(); ?>"><?php echo esc_html($creator_name); ?></a>
                                        </h3>
                                        <div class="gift-count"><?php echo number_format($gift_count); ?> ギフト</div>
                                        <?php if ($creator_account): ?>
                                            <div class="podium-account">@<?php echo esc_html($creator_account); ?></div>
                                        <?php endif; ?>
                                        <div class="podium-actions">
                                            <a class="btn-view-profile" href="<?php the_permalink(); ?>">
                                                <i class="fas fa-user"></i> プロフィール
                                            </a>
                                            <?php if ($account_url): ?>
                                                <a class="btn-visit-account" href="<?php echo esc_url($account_url); ?>" target="_blank">
                                                    <i class="fas fa-external-link-alt"></i> アカウント
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($feature): ?>
                                            <div class="liver-feature"><?php echo esc_html($feature); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php
                                $rank++;
                            endwhile;
                            wp_reset_postdata();
                        else :
                            ?>
                            <!-- ライバーデータがない場合のメッセージ -->
                            <div class="no-data-message">
                                <p>ライバーデータがまだありません。</p>
                                <p>ACFでライバー情報を登録してください。</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- 一般ランキングセクション（4位以降） -->
            <section class="ranking-list-section">
                <div class="ranking-list-container">
                    <h2 class="section-title">
                        <i class="fas fa-list-ol"></i>
                        全ランキング
                    </h2>
                    <div class="ranking-list" id="general-ranking">
                        <!-- 4位以降のランキング表示（サブクエリ対応） -->
                        <?php
                        // 4位以降のライバー情報を直接取得（3件をスキップして7件取得）
                        $general_ranking = jol_get_liver_ranking('total', 7, 3);
                        $rank = 4; // 4位からスタート

                        if ($general_ranking && $general_ranking->have_posts()) :
                            while ($general_ranking->have_posts()) : $general_ranking->the_post();
                                // WordPressの標準メタフィールドを使用
                                $gift_count = get_post_meta(get_the_ID(), 'gift', true) ?: 0;
                                $creator_name = get_post_meta(get_the_ID(), 'creator_name', true) ?: get_the_title();
                                $creator_account = get_post_meta(get_the_ID(), 'creator_account', true) ?: '';
                                $feature = get_post_meta(get_the_ID(), 'feature', true) ?: '';
                                $delivery = get_post_meta(get_the_ID(), 'delivery', true) ?: '';
                                $delivery_time = get_post_meta(get_the_ID(), 'delivery_time', true) ?: '';
                                $account_url = get_post_meta(get_the_ID(), 'account_url', true) ?: '';
                                $avatar_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

                                if (!$avatar_url) {
                                    $avatar_url = get_template_directory_uri() . '/src/assets/images/24401878_s.jpg';
                                }

                                get_template_part('template-parts/ranking/ranking-card', null, array(
                                    'rank' => $rank,
                                    'liver_id' => get_the_ID(),
                                    'liver_name' => $creator_name,
                                    'creator_account' => $creator_account,
                                    'gift_count' => $gift_count,
                                    'feature' => $feature,
                                    'delivery' => $delivery,
                                    'delivery_time' => $delivery_time,
                                    'account_url' => $account_url,
                                    'avatar_url' => $avatar_url,
                                    'liver_url' => get_permalink()
                                ));

                                $rank++;
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <!-- ライバーデータがない場合のメッセージ -->
                            <div class="no-data-message">
                                <p>ライバーデータがまだありません。</p>
                                <p>管理画面で「ライバー」投稿を作成し、メタフィールドを設定してください。</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ページネーション -->
                    <div class="ranking-pagination">
                        <button class="btn-load-more" id="load-more-ranking">
                            <i class="fas fa-plus"></i>
                            もっと見る
                        </button>
                    </div>
                </div>
            </section>
        </div>
</main>

<!-- ランキング用JavaScript読み込み -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ランキング初期化
        if (typeof RankingManager !== 'undefined') {
            const ranking = new RankingManager();
            ranking.init();
        }
    });
</script>

<?php get_footer(); ?>