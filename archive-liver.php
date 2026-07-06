<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">Liver</p>
    <h1 class="page-title">ライバー一覧</h1>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('bcn_display')) {
            bcn_display();
        } ?>
    </div>
</div>

<main class="main-content">
    <div class="inner liver-archive-list">
        <?php 
        $target_accounts = [
            'l5332541',          // さくら
            'mizuki2525214',     // 一条美月
            't.o.p_u_jin_',      // ユージン
            'chirin5497',        // Chiho
            'mrm0115'            // まみ
        ];

        $args = array(
            'post_type' => 'liver',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'meta_query' => [
                [
                    'key' => 'creator_account',
                    'value' => $target_accounts,
                    'compare' => 'IN'
                ]
            ],
        );
        $liver_query = new WP_Query($args);

        // 指定されたアカウント順に投稿をソートする
        $sorted_posts = [];
        if ($liver_query->have_posts()) {
            $posts_by_account = [];
            foreach ($liver_query->posts as $p) {
                $acc = get_post_meta($p->ID, 'creator_account', true);
                if ($acc) {
                    $posts_by_account[$acc] = $p;
                }
            }
            foreach ($target_accounts as $acc) {
                if (isset($posts_by_account[$acc])) {
                    $sorted_posts[] = $posts_by_account[$acc];
                }
            }
        }

        $count = 1;

        if (!empty($sorted_posts)) : ?>
            <div class="liver-premium-container">
                <?php foreach ($sorted_posts as $post) : 
                    setup_postdata($post);
                    $layout_class = ($count % 2 === 1) ? 'premium-odd' : 'premium-even';
                    $creator_account = get_post_meta($post->ID, 'creator_account', true);
                    $formatted_count = sprintf('%02d', $count);
                ?>
                    <article class="liver-premium-item <?php echo esc_attr($layout_class); ?>">
                        <!-- 巨大な透かし文字 -->
                        <div class="liver-bg-watermark"><?php echo esc_html($formatted_count); ?></div>
                        
                        <div class="liver-premium-card">
                            <div class="liver-image-wrapper">
                                <a href="<?php the_permalink(); ?>" class="image-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>" alt="<?php the_title(); ?>のプロフィール画像">
                                <?php endif; ?>
                                <div class="image-overlay"></div>
                                </a>
                            </div>
                            
                            <div class="liver-info-wrapper">
                                <div class="liver-number-small">No. <?php echo esc_html($formatted_count); ?></div>
                                <h2 class="liver-name">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <?php if ($creator_account) : ?>
                                    <p class="liver-id">ID: @<?php echo esc_html($creator_account); ?></p>
                                <?php endif; ?>
                                
                                <div class="liver-action">
                                    <a href="<?php the_permalink(); ?>" class="btn-premium">
                                        <span class="btn-text">VIEW PROFILE</span>
                                        <span class="btn-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php 
                    $count++;
                    endforeach; 
                ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="no-posts">
                <p>ライバーが見つかりませんでした。</p>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>