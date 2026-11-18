<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">LIVER</p>
    <h1 class="page-title">所属ライバー一覧</h1>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('jol_breadcrumbs')) {
            jol_breadcrumbs();
        } ?>
    </div>
</div>

<main class="inner pt-12 md:pt-20 pb-24 md:pb-32">
    <?php
    $target_accounts = [
        'l5332541',          // さくら
        'mizuki2525214',     // 一条美月
        't.o.p_u_jin_',      // ユージン
        'chirin5497',        // Chiho
        'mrm0115'            // まみ
    ];

    $args = array(
        'post_type'      => 'liver',
        'post_status'    => 'publish',
        'posts_per_page' => 5,
        'meta_query'     => [
            [
                'key'     => 'creator_account',
                'value'   => $target_accounts,
                'compare' => 'IN'
            ]
        ],
    );
    $liver_query = new WP_Query($args);

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

    if (!empty($sorted_posts)) : ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            <?php
            $count = 1;
            foreach ($sorted_posts as $post) :
                setup_postdata($post);
                $is_featured     = ($count === 1);
                $creator_account = get_post_meta($post->ID, 'creator_account', true);
                $feature         = get_post_meta($post->ID, 'feature', true);
                $delivery        = get_post_meta($post->ID, 'delivery', true);
                $account_url     = get_post_meta($post->ID, 'account_url', true);
                if ($account_url && !preg_match('/^https?:\/\//', $account_url)) {
                    $account_url = 'https://www.tiktok.com/@' . ltrim($account_url, '/@');
                } elseif (!$account_url && $creator_account) {
                    $account_url = 'https://www.tiktok.com/@' . ltrim($creator_account, '@');
                }
                $thumbnail_url   = has_post_thumbnail($post->ID) ? get_the_post_thumbnail_url($post->ID, 'large') : COMMON_LIVER_THUMBNAIL_URL;
                $bio_raw         = trim(strip_tags($post->post_content));
                $bio_excerpt     = $bio_raw ? mb_strimwidth($bio_raw, 0, $is_featured ? 140 : 76, '...') : '';
                $is_cyan         = ($count % 2 === 1);
                $accent_border   = $is_cyan ? 'hover:border-jol-cyan/60' : 'hover:border-jol-purple/60';
                $accent_text     = $is_cyan ? 'text-jol-cyan' : 'text-purple-300';
                $offset_box      = $is_cyan ? 'border-jol-cyan/50 bg-jol-cyan/10' : 'border-jol-purple/60 bg-jol-purple/15';
            ?>
                <article class="group relative rounded-lg bg-jol-surface/90 border border-white/10 transition-all duration-300 ease-editorial hover:-translate-y-1 <?php echo esc_attr($accent_border); ?> <?php echo $is_featured ? 'lg:col-span-2' : ''; ?>">
                    <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-stretch gap-6 md:gap-7 p-5 md:p-6 h-full">
                        <!-- 1:1 画像エリア（背面に x:8px y:8px ずらした四角図形を配置） -->
                        <div class="relative shrink-0 mb-2 sm:mb-2 sm:mr-1 <?php echo $is_featured ? 'w-[220px] sm:w-[230px] md:w-[250px]' : 'w-[180px] sm:w-[160px] md:w-[180px]'; ?> aspect-square">
                            <div class="pointer-events-none absolute inset-0 translate-x-2 translate-y-2 rounded border <?php echo esc_attr($offset_box); ?> transition-transform duration-300 ease-editorial group-hover:translate-x-3 group-hover:translate-y-3" aria-hidden="true"></div>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="relative z-10 block w-full h-full rounded overflow-hidden bg-black border border-white/15">
                                <img src="<?php echo esc_url($thumbnail_url); ?>"
                                    alt="<?php echo esc_attr(get_the_title($post->ID)); ?>のプロフィール画像"
                                    class="w-full h-full object-cover transition-transform duration-500 ease-editorial group-hover:scale-105">
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            </a>
                        </div>

                        <!-- 情報エリア -->
                        <div class="flex flex-col flex-1 justify-between w-full min-w-0">
                            <div>
                                <?php if ($feature || $delivery) : ?>
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <?php if ($feature) : ?>
                                            <span class="inline-block px-2 py-0.5 text-[0.7rem] font-mono tracking-[0.04em] rounded border border-white/15 bg-white/[0.04] text-white/80"><?php echo esc_html($feature); ?></span>
                                        <?php endif; ?>
                                        <?php if ($delivery) : ?>
                                            <span class="inline-block px-2 py-0.5 text-[0.7rem] font-mono text-jol-muted">DEBUT: <?php echo esc_html($delivery); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <h2 class="text-xl <?php echo $is_featured ? 'md:text-3xl' : 'md:text-2xl'; ?> font-bold text-jol-text tracking-tight leading-snug mb-1">
                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="no-underline text-inherit transition-colors duration-200 hover:<?php echo esc_attr($accent_text); ?>">
                                        <?php echo esc_html(get_the_title($post->ID)); ?>
                                    </a>
                                </h2>

                                <?php if ($creator_account) : ?>
                                    <p class="font-mono text-xs md:text-sm <?php echo esc_attr($accent_text); ?> mb-3">@<?php echo esc_html($creator_account); ?></p>
                                <?php endif; ?>

                                <?php if ($bio_excerpt) : ?>
                                    <p class="text-xs md:text-sm text-white/65 leading-6 line-clamp-3 mb-4"><?php echo esc_html($bio_excerpt); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded border border-white/15 bg-white/[0.04] text-xs font-mono tracking-[0.08em] uppercase text-white no-underline transition-all duration-200 ease-editorial hover:border-jol-cyan hover:text-jol-cyan hover:bg-jol-cyan/10">
                                    <span>VIEW PROFILE</span>
                                    <span class="transition-transform duration-200 group-hover:translate-x-1">→</span>
                                </a>
                                <?php if ($account_url) : ?>
                                    <a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-mono text-white/50 no-underline transition-colors duration-200 hover:text-white">
                                        <span>TikTok</span>
                                        <span>↗</span>
                                    </a>
                                <?php endif; ?>
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
        <div class="rounded-lg border border-dashed border-white/15 p-12 text-center text-jol-muted">
            <p>ライバーが見つかりませんでした。</p>
        </div>
    <?php endif; ?>
</main>

<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>
