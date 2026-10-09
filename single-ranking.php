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
                        $event_livers = get_field('event_ranking_livers') ?: get_post_meta(get_the_ID(), 'event_ranking_livers', true);
                        if (is_string($event_livers)) {
                            $event_livers = maybe_unserialize($event_livers);
                        }
                        if ($event_livers && is_array($event_livers)) : 

                            $rank = 1;
                            foreach ($event_livers as $post_or_id) :
                                $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                                $liver_post = get_post($liver_id);
                                if ($liver_post && in_array($liver_post->post_status, array('publish', 'draft'))) :
                                    if ($rank > 5) break;
                                    setup_postdata($GLOBALS['post'] =& $liver_post);
                                    $creator_name = $liver_post->post_title ?: get_post_meta($liver_id, 'creator_name', true);
                                    $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                    $tiktok_url = get_post_meta($liver_id, 'account_url', true);
                                    if (!$tiktok_url && $creator_account) {
                                        $tiktok_url = 'https://www.tiktok.com/@' . ltrim($creator_account, '@');
                                    }
                                    $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/assets/images/default-avatar.png';
                                    $permalink = get_permalink($liver_id);
                                    $is_draft = ($liver_post->post_status === 'draft');
                        ?>
                                    <div class="ranking-list-item">
                                        <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                            <span class="rank-number"><?php echo $rank; ?></span>
                                        </div>
                                        <div class="liver-avatar-wrap">
                                            <?php if ($is_draft) : ?>
                                                <?php if ($tiktok_url) : ?>
                                                    <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </a>
                                                <?php else : ?>
                                                    <div class="draft-avatar-placeholder" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <a href="<?php echo esc_url($permalink); ?>">
                                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="liver-details">
                                            <h3 class="creator-name">
                                                <?php if ($is_draft) : ?>
                                                    <?php if ($tiktok_url) : ?>
                                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($creator_name); ?></a>
                                                    <?php else : ?>
                                                        <span><?php echo esc_html($creator_name); ?></span>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                                <?php endif; ?>
                                            </h3>
                                            <?php if ($creator_account) : ?>
                                                <p class="creator-id">
                                                    <?php if ($tiktok_url) : ?>
                                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer">@<?php echo esc_html($creator_account); ?></a>
                                                    <?php else : ?>
                                                        @<?php echo esc_html($creator_account); ?>
                                                    <?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($tiktok_url) : ?>
                                            <a href="<?php echo esc_url($tiktok_url); ?>" class="tiktok-link-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($creator_name); ?>のTikTokプロフィール">
                                                <svg class="tiktok-icon" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
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

                <!-- 新人王ランキング（登録がある場合のみ、または4カラム時表示） -->
                <?php 
                $rookie_livers = get_field('rookie_ranking_livers') ?: get_post_meta(get_the_ID(), 'rookie_ranking_livers', true);
                if (is_string($rookie_livers)) {
                    $rookie_livers = maybe_unserialize($rookie_livers);
                }
                if ($rookie_livers && is_array($rookie_livers)) : 
                ?>
                <section class="ranking-column-section rookie-rank-col">
                    <h2 class="ranking-column-title">
                        <span class="title-en">ROOKIE RANKING</span>
                        <span class="title-ja">J.O.L 新人王</span>
                    </h2>
                    
                    <div class="ranking-item-list">
                        <?php 
                        $rank = 1;
                        foreach ($rookie_livers as $post_or_id) :
                            $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                            $liver_post = get_post($liver_id);
                            if ($liver_post && in_array($liver_post->post_status, array('publish', 'draft'))) :
                                if ($rank > 5) break;
                                setup_postdata($GLOBALS['post'] =& $liver_post);
                                $creator_name = $liver_post->post_title ?: get_post_meta($liver_id, 'creator_name', true);
                                $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                $tiktok_url = get_post_meta($liver_id, 'account_url', true);
                                if (!$tiktok_url && $creator_account) {
                                    $tiktok_url = 'https://www.tiktok.com/@' . ltrim($creator_account, '@');
                                }
                                $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/assets/images/default-avatar.png';
                                $permalink = get_permalink($liver_id);
                                $is_draft = ($liver_post->post_status === 'draft');
                        ?>
                                <div class="ranking-list-item">
                                    <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                        <span class="rank-number"><?php echo $rank; ?></span>
                                    </div>
                                    <div class="liver-avatar-wrap">
                                        <?php if ($is_draft) : ?>
                                            <?php if ($tiktok_url) : ?>
                                                <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                </a>
                                            <?php else : ?>
                                                <div class="draft-avatar-placeholder" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <a href="<?php echo esc_url($permalink); ?>">
                                                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="liver-details">
                                        <h3 class="creator-name">
                                            <?php if ($is_draft) : ?>
                                                <?php if ($tiktok_url) : ?>
                                                    <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($creator_name); ?></a>
                                                <?php else : ?>
                                                    <span><?php echo esc_html($creator_name); ?></span>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                            <?php endif; ?>
                                        </h3>
                                        <?php if ($creator_account) : ?>
                                            <p class="creator-id">
                                                <?php if ($tiktok_url) : ?>
                                                    <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer">@<?php echo esc_html($creator_account); ?></a>
                                                <?php else : ?>
                                                    @<?php echo esc_html($creator_account); ?>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($tiktok_url) : ?>
                                        <a href="<?php echo esc_url($tiktok_url); ?>" class="tiktok-link-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($creator_name); ?>のTikTokプロフィール">
                                            <svg class="tiktok-icon" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                        <?php 
                                $rank++;
                            endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- 右カラム: ダイヤモンドランキング -->
                <section class="ranking-column-section diamond-rank-col">
                    <h2 class="ranking-column-title">
                        <span class="title-en">DIAMOND RANKING</span>
                        <span class="title-ja">ダイヤモンドランキング</span>
                    </h2>
                    
                    <div class="ranking-item-list">
                        <?php 
                        $diamond_livers = get_field('diamond_ranking_livers') ?: get_post_meta(get_the_ID(), 'diamond_ranking_livers', true);
                        if (is_string($diamond_livers)) {
                            $diamond_livers = maybe_unserialize($diamond_livers);
                        }
                        if ($diamond_livers && is_array($diamond_livers)) : 
                            $rank = 1;
                            foreach ($diamond_livers as $post_or_id) :
                                $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                                $liver_post = get_post($liver_id);
                                if ($liver_post && in_array($liver_post->post_status, array('publish', 'draft'))) :
                                    if ($rank > 5) break;
                                    setup_postdata($GLOBALS['post'] =& $liver_post);
                                    $creator_name = $liver_post->post_title ?: get_post_meta($liver_id, 'creator_name', true);
                                    $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                    $tiktok_url = get_post_meta($liver_id, 'account_url', true);
                                    if (!$tiktok_url && $creator_account) {
                                        $tiktok_url = 'https://www.tiktok.com/@' . ltrim($creator_account, '@');
                                    }
                                    $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/assets/images/default-avatar.png';
                                    $permalink = get_permalink($liver_id);
                                    $is_draft = ($liver_post->post_status === 'draft');
                        ?>
                                    <div class="ranking-list-item">
                                        <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                            <span class="rank-number"><?php echo $rank; ?></span>
                                        </div>
                                        <div class="liver-avatar-wrap">
                                            <?php if ($is_draft) : ?>
                                                <?php if ($tiktok_url) : ?>
                                                    <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </a>
                                                <?php else : ?>
                                                    <div class="draft-avatar-placeholder" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <a href="<?php echo esc_url($permalink); ?>">
                                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="liver-details">
                                            <h3 class="creator-name">
                                                <?php if ($is_draft) : ?>
                                                    <?php if ($tiktok_url) : ?>
                                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($creator_name); ?></a>
                                                    <?php else : ?>
                                                        <span><?php echo esc_html($creator_name); ?></span>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                                <?php endif; ?>
                                            </h3>
                                            <?php if ($creator_account) : ?>
                                                <p class="creator-id">
                                                    <?php if ($tiktok_url) : ?>
                                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer">@<?php echo esc_html($creator_account); ?></a>
                                                    <?php else : ?>
                                                        @<?php echo esc_html($creator_account); ?>
                                                    <?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($tiktok_url) : ?>
                                            <a href="<?php echo esc_url($tiktok_url); ?>" class="tiktok-link-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($creator_name); ?>のTikTokプロフィール">
                                                <svg class="tiktok-icon" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
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
                        $delivery_time_livers = get_field('delivery_time_ranking_livers') ?: get_field('delivery_time_ranking_liver') ?: get_post_meta(get_the_ID(), 'delivery_time_ranking_livers', true);
                        if (is_string($delivery_time_livers)) {
                            $delivery_time_livers = maybe_unserialize($delivery_time_livers);
                        }
                        if ($delivery_time_livers && is_array($delivery_time_livers)) : 
                            $rank = 1;
                            foreach ($delivery_time_livers as $post_or_id) :
                                $liver_id = is_object($post_or_id) ? $post_or_id->ID : $post_or_id;
                                $liver_post = get_post($liver_id);
                                if ($liver_post && in_array($liver_post->post_status, array('publish', 'draft'))) :
                                    if ($rank > 5) break;
                                    setup_postdata($GLOBALS['post'] =& $liver_post);
                                    $creator_name = $liver_post->post_title ?: get_post_meta($liver_id, 'creator_name', true);
                                    $creator_account = get_post_meta($liver_id, 'creator_account', true);
                                    $tiktok_url = get_post_meta($liver_id, 'account_url', true);
                                    if (!$tiktok_url && $creator_account) {
                                        $tiktok_url = 'https://www.tiktok.com/@' . ltrim($creator_account, '@');
                                    }
                                    $avatar_url = get_the_post_thumbnail_url($liver_id, 'thumbnail') ?: get_template_directory_uri() . '/assets/images/default-avatar.png';
                                    $permalink = get_permalink($liver_id);
                                    $is_draft = ($liver_post->post_status === 'draft');
                        ?>
                                    <div class="ranking-list-item">
                                        <div class="rank-badge-wrap rank-num-<?php echo $rank; ?>">
                                            <span class="rank-number"><?php echo $rank; ?></span>
                                        </div>
                                        <div class="liver-avatar-wrap">
                                            <?php if ($is_draft) : ?>
                                                <?php if ($tiktok_url) : ?>
                                                    <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </a>
                                                <?php else : ?>
                                                    <div class="draft-avatar-placeholder" style="border-radius: 50%; overflow: hidden; display: block; aspect-ratio: 1/1;">
                                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <a href="<?php echo esc_url($permalink); ?>">
                                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="liver-details">
                                            <h3 class="creator-name">
                                                <?php if ($is_draft) : ?>
                                                    <?php if ($tiktok_url) : ?>
                                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($creator_name); ?></a>
                                                    <?php else : ?>
                                                        <span><?php echo esc_html($creator_name); ?></span>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($creator_name); ?></a>
                                                <?php endif; ?>
                                            </h3>
                                            <?php if ($creator_account) : ?>
                                                <p class="creator-id">
                                                    <?php if ($tiktok_url) : ?>
                                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer">@<?php echo esc_html($creator_account); ?></a>
                                                    <?php else : ?>
                                                        @<?php echo esc_html($creator_account); ?>
                                                    <?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($tiktok_url) : ?>
                                            <a href="<?php echo esc_url($tiktok_url); ?>" class="tiktok-link-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($creator_name); ?>のTikTokプロフィール">
                                                <svg class="tiktok-icon" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
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
