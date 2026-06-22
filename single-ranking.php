<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="mysterious-theme">
    <article class="single-ranking">
        <div class="agency-hero">
            <div class="hero-bg">
                <?php 
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                if ($thumbnail_url) : ?>
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="" class="bg-blur">
                <?php endif; ?>
            </div>
            <div class="hero-content inner">
                <div class="breadcrumbs agency-breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                    <?php if (function_exists('bcn_display')) bcn_display(); ?>
                </div>
                
                <div class="hero-profile-flex">
                    <div class="hero-text">
                        <p class="agency-subtitle">RANKING ARCHIVE</p>
                        <h1 class="agency-title"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <main class="agency-main inner">
            <div class="ranking-layout-grid">
                <!-- 左カラム: イベントランキング -->
                <section class="ranking-column-section event-rank-col">
                    <h2 class="ranking-column-title">
                        <span class="title-en">EVENT RANKING</span>
                        <span class="title-ja">イベントランキング</span>
                    </h2>
                    
                    <div class="ranking-item-list">
                        <?php 
                        $event_livers = get_field('event_ranking_livers');
                        if ($event_livers) : 
                            $rank = 1;
                            foreach ($event_livers as $post_or_id) :
                                $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                                $liver_post = get_post($liver_id);
                                if ($liver_post && $liver_post->post_status === 'publish') :
                                    if ($rank > 5) break;
                                    setup_postdata($GLOBALS['post'] =& $liver_post);
                                    $creator_name = get_post_meta($liver_id, 'creator_name', true) ?: $liver_post->post_title;
                                    $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                    $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/src/assets/images/24401878_s.jpg';
                                    $permalink = get_permalink($liver_id);
                        ?>
                                    <div class="ranking-list-item">
                                        <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                            <span class="rank-number"><?php echo $rank; ?></span>
                                        </div>
                                        <div class="liver-avatar-wrap">
                                            <a href="<?php echo esc_url($permalink); ?>">
                                                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                            </a>
                                        </div>
                                        <div class="liver-details">
                                            <h3 class="creator-name">
                                                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                            </h3>
                                            <?php if ($creator_account) : ?>
                                                <p class="creator-id">@<?php echo esc_html($creator_account); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                        <?php 
                                    $rank++;
                                endif;
                            endforeach;
                            wp_reset_postdata();
                        else : ?>
                            <p class="no-data-msg">ランキングデータが登録されていません。</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 右カラム: ダイヤモンドランキング -->
                <section class="ranking-column-section diamond-rank-col">
                    <h2 class="ranking-column-title">
                        <span class="title-en">DIAMOND RANKING</span>
                        <span class="title-ja">ダイヤモンドランキング</span>
                    </h2>
                    
                    <div class="ranking-item-list">
                        <?php 
                        $diamond_livers = get_field('diamond_ranking_livers');
                        if ($diamond_livers) : 
                            $rank = 1;
                            foreach ($diamond_livers as $post_or_id) :
                                $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                                $liver_post = get_post($liver_id);
                                if ($liver_post && $liver_post->post_status === 'publish') :
                                    if ($rank > 5) break;
                                    setup_postdata($GLOBALS['post'] =& $liver_post);
                                    $creator_name = get_post_meta($liver_id, 'creator_name', true) ?: $liver_post->post_title;
                                    $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                    $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/src/assets/images/24401878_s.jpg';
                                    $permalink = get_permalink($liver_id);
                        ?>
                                    <div class="ranking-list-item">
                                        <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                            <span class="rank-number"><?php echo $rank; ?></span>
                                        </div>
                                        <div class="liver-avatar-wrap">
                                            <a href="<?php echo esc_url($permalink); ?>">
                                                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                            </a>
                                        </div>
                                        <div class="liver-details">
                                            <h3 class="creator-name">
                                                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                            </h3>
                                            <?php if ($creator_account) : ?>
                                                <p class="creator-id">@<?php echo esc_html($creator_account); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                        <?php 
                                    $rank++;
                                endif;
                            endforeach;
                            wp_reset_postdata();
                        else : ?>
                            <p class="no-data-msg">ランキングデータが登録されていません。</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 右カラム: 配信時間ランキング -->
                <section class="ranking-column-section delivery-time-rank-col">
                    <h2 class="ranking-column-title">
                        <span class="title-en">DELIVERY TIME RANKING</span>
                        <span class="title-ja">配信時間ランキング</span>
                    </h2>
                    
                    <div class="ranking-item-list">
                        <?php 
                        $delivery_time_livers = get_field('delivery_time_ranking_livers') ?: get_field('delivery_time_ranking_liver');
                        if ($delivery_time_livers) : 
                            $rank = 1;
                            foreach ($delivery_time_livers as $post_or_id) :
                                $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                                $liver_post = get_post($liver_id);
                                if ($liver_post && $liver_post->post_status === 'publish') :
                                    if ($rank > 5) break;
                                    setup_postdata($GLOBALS['post'] =& $liver_post);
                                    $creator_name = get_post_meta($liver_id, 'creator_name', true) ?: $liver_post->post_title;
                                    $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                    $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/src/assets/images/24401878_s.jpg';
                                    $permalink = get_permalink($liver_id);
                        ?>
                                    <div class="ranking-list-item">
                                        <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                            <span class="rank-number"><?php echo $rank; ?></span>
                                        </div>
                                        <div class="liver-avatar-wrap">
                                            <a href="<?php echo esc_url($permalink); ?>">
                                                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                            </a>
                                        </div>
                                        <div class="liver-details">
                                            <h3 class="creator-name">
                                                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                            </h3>
                                            <?php if ($creator_account) : ?>
                                                <p class="creator-id">@<?php echo esc_html($creator_account); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                        <?php 
                                    $rank++;
                                endif;
                            endforeach;
                            wp_reset_postdata();
                        else : ?>
                            <p class="no-data-msg">ランキングデータが登録されていません。</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="agency-back">
                <a href="<?php echo esc_url(get_post_type_archive_link('ranking')); ?>" class="btn-agency-back">
                    &lt; ランキング一覧に戻る
                </a>
            </div>
        </main>
    </article>
</div>
<?php endwhile; endif; ?>

<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>
