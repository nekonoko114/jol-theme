<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">EVENT</p>
    <h1 class="page-title">イベント一覧</h1>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('jol_breadcrumbs')) {
            jol_breadcrumbs();
        } ?>
    </div>
</div>

<main class="inner pt-12 md:pt-20 pb-24 md:pb-32">
    <?php
    $categories = array(
        array(
            'slug'         => 'event-news',
            'en'           => 'EVENT',
            'name'         => 'イベント',
            'bar_class'    => 'bg-jol-cyan',
            'badge_class'  => 'border-jol-cyan/40 bg-jol-cyan/10 text-jol-cyan',
            'hover_border' => 'hover:border-jol-cyan/60',
            'hover_text'   => 'group-hover:text-jol-cyan',
        ),
        array(
            'slug'         => 'battle',
            'en'           => 'BATTLE',
            'name'         => 'ガチバトル',
            'bar_class'    => 'bg-jol-red',
            'badge_class'  => 'border-jol-red/40 bg-jol-red/10 text-red-400',
            'hover_border' => 'hover:border-jol-red/60',
            'hover_text'   => 'group-hover:text-red-400',
        ),
        array(
            'slug'         => 'liver-news',
            'en'           => 'LIVER NEWS',
            'name'         => 'ライバーニュース',
            'bar_class'    => 'bg-jol-purple',
            'badge_class'  => 'border-jol-purple/50 bg-jol-purple/15 text-purple-300',
            'hover_border' => 'hover:border-jol-purple/60',
            'hover_text'   => 'group-hover:text-purple-300',
        ),
    );

    $sections = array();
    foreach ($categories as $category) {
        $q = new WP_Query(array(
            'post_type'      => 'event',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $category['slug'],
                ),
            ),
        ));
        if ($q->have_posts()) {
            $sections[] = array('meta' => $category, 'query' => $q);
        } else {
            wp_reset_postdata();
        }
    }

    // カテゴリー未設定のイベント
    $uncategorized_q = new WP_Query(array(
        'post_type'      => 'event',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'event_category',
                'operator' => 'NOT EXISTS',
            ),
        ),
    ));
    if ($uncategorized_q->have_posts()) {
        $sections[] = array(
            'meta' => array(
                'slug'         => 'other',
                'en'           => 'OTHERS',
                'name'         => 'その他のイベント',
                'bar_class'    => 'bg-white/60',
                'badge_class'  => 'border-white/20 bg-white/5 text-white/80',
                'hover_border' => 'hover:border-white/40',
                'hover_text'   => 'group-hover:text-white',
            ),
            'query' => $uncategorized_q,
        );
    } else {
        wp_reset_postdata();
    }

    if (!empty($sections)) :
    ?>
        <!-- カテゴリージャンプナビゲーション -->
        <?php if (count($sections) > 1) : ?>
            <nav class="flex flex-wrap items-center gap-2 mb-12 md:mb-16 pb-6 border-b border-white/10" aria-label="イベントカテゴリー">
                <?php foreach ($sections as $sec) : ?>
                    <a href="#event-cat-<?php echo esc_attr($sec['meta']['slug']); ?>" class="inline-flex items-center gap-2 px-3 py-1.5 rounded border border-white/15 bg-jol-surface text-xs font-mono text-white/80 no-underline transition-all duration-200 ease-editorial hover:border-jol-cyan/60 hover:text-jol-cyan">
                        <span><?php echo esc_html($sec['meta']['name']); ?></span>
                        <span class="text-white/40"><?php echo intval($sec['query']->found_posts); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <div class="space-y-20 md:space-y-24">
            <?php foreach ($sections as $sec) :
                $meta  = $sec['meta'];
                $query = $sec['query'];
            ?>
                <section id="event-cat-<?php echo esc_attr($meta['slug']); ?>" class="scroll-mt-8">
                    <div class="flex items-end justify-between gap-4 mb-8 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <span class="w-1 h-8 rounded-full <?php echo esc_attr($meta['bar_class']); ?>" aria-hidden="true"></span>
                            <div>
                                <p class="font-mono text-[0.7rem] tracking-[0.15em] uppercase text-white/45 leading-none mb-1.5"><?php echo esc_html($meta['en']); ?></p>
                                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight leading-none m-0"><?php echo esc_html($meta['name']); ?></h2>
                            </div>
                        </div>
                        <span class="inline-block px-2.5 py-1 rounded border font-mono text-xs <?php echo esc_attr($meta['badge_class']); ?>">
                            <?php echo intval($query->found_posts); ?> ITEMS
                        </span>
                    </div>

                    <div class="grid grid-cols-1 <?php echo ($query->found_posts > 1) ? 'lg:grid-cols-2' : ''; ?> gap-6">
                        <?php while ($query->have_posts()) : $query->the_post();
                            $raw_content = trim(wp_strip_all_tags(get_the_content()));
                            $raw_excerpt = $raw_content ?: trim(str_replace('もっと見る', '', wp_strip_all_tags(get_the_excerpt())));
                            $excerpt     = $raw_excerpt ? mb_strimwidth($raw_excerpt, 0, ($query->found_posts === 1 ? 160 : 100), '...') : '';
                            $thumb_url   = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large') : '';
                            $fallback_thumb = get_template_directory_uri() . '/assets/images/explore/explore-events.webp';
                        ?>
                            <article class="group relative rounded-lg overflow-hidden bg-jol-surface/90 border border-white/10 transition-all duration-300 ease-editorial hover:-translate-y-1 <?php echo esc_attr($meta['hover_border']); ?>">
                                <a href="<?php the_permalink(); ?>" class="flex flex-col sm:flex-row h-full no-underline text-inherit">
                                    <?php if ($thumb_url) : ?>
                                        <div class="relative shrink-0 w-full <?php echo ($query->found_posts === 1) ? 'sm:w-[240px] md:w-[280px] aspect-video sm:aspect-[16/10]' : 'sm:w-[190px] aspect-[16/10] sm:aspect-square'; ?> overflow-hidden bg-black border-b sm:border-b-0 sm:border-r border-white/10">
                                            <img src="<?php echo esc_url($thumb_url); ?>"
                                                alt="<?php echo esc_attr(get_the_title()); ?>"
                                                onerror="this.onerror=null;this.src='<?php echo esc_url($fallback_thumb); ?>';"
                                                class="w-full h-full object-cover transition-transform duration-500 ease-editorial group-hover:scale-105">
                                        </div>
                                    <?php else : ?>
                                        <div class="shrink-0 w-full sm:w-[140px] flex sm:flex-col items-center sm:items-start justify-between sm:justify-center gap-2 p-4 sm:p-5 bg-black/50 border-b sm:border-b-0 sm:border-r border-white/10">
                                            <span class="font-mono text-xs tracking-[0.12em] text-white/45"><?php echo esc_html(get_the_date('Y')); ?></span>
                                            <span class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-white/85 leading-none"><?php echo esc_html(get_the_date('m.d')); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex flex-col flex-1 justify-between p-5 md:p-6 min-w-0">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                <span class="inline-block px-2 py-0.5 text-[0.68rem] font-mono tracking-[0.06em] rounded border <?php echo esc_attr($meta['badge_class']); ?>">
                                                    <?php echo esc_html($meta['name']); ?>
                                                </span>
                                                <time class="font-mono text-xs text-jol-muted" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
                                            </div>
                                            <h3 class="text-base md:text-lg font-bold text-jol-text leading-snug mb-2 transition-colors duration-200 <?php echo esc_attr($meta['hover_text']); ?>">
                                                <?php the_title(); ?>
                                            </h3>
                                            <?php if ($excerpt) : ?>
                                                <p class="text-xs md:text-sm text-white/60 leading-6 line-clamp-2 m-0"><?php echo esc_html($excerpt); ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between text-xs font-mono tracking-[0.08em] uppercase text-white/55 transition-colors duration-200 <?php echo esc_attr($meta['hover_text']); ?>">
                                            <span>READ MORE</span>
                                            <span class="transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>
                <?php wp_reset_postdata(); ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="rounded-lg border border-dashed border-white/15 p-12 text-center text-jol-muted">
            <p>現在、予定されているイベントはありません。</p>
        </div>
    <?php endif; ?>
</main>

<?php get_template_part('template-parts/content/l-explore'); ?>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>
