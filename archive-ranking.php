<?php get_header(); ?>

<div class="mysterious-theme">
    <div class="hedding-area inner">
        <p class="page-subtitle">RANKING ARCHIVES</p>
        <h1 class="page-title">過去のランキング</h1>
        <div class="breadcrumbs agency-breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php if (function_exists('jol_breadcrumbs')) { jol_breadcrumbs(); } ?>
        </div>
    </div>

    <main class="inner pt-12 md:pt-20 pb-24 md:pb-32">
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mt-8 mb-16">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="group relative rounded-lg overflow-hidden bg-jol-surface/90 border border-white/10 transition-all duration-300 ease-editorial hover:-translate-y-1.5 hover:border-jol-cyan/60 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6),0_0_24px_rgba(0,255,255,0.12)]">
                        <a href="<?php the_permalink(); ?>" class="flex flex-col h-full no-underline text-inherit">
                            <div class="relative aspect-[16/10] overflow-hidden bg-black border-b border-white/10">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover transition-transform duration-500 ease-editorial group-hover:scale-105')); ?>
                                <?php else : ?>
                                    <!-- デフォルトのトロフィーカバー画像を表示 -->
                                    <div class="relative w-full h-full">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ranking/archive-trophy-cover.webp'); ?>"
                                            alt="<?php echo esc_attr(get_the_title()); ?>"
                                            class="w-full h-full object-cover brightness-110 transition-all duration-500 ease-editorial group-hover:scale-105 group-hover:brightness-125">
                                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>
                                        <div class="pointer-events-none absolute inset-x-0 bottom-0 p-4 flex items-end justify-end">
                                            <span class="font-mono text-xs text-white/60"><?php echo get_the_date('Y.m'); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-col flex-1 justify-between p-6">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="inline-block px-2 py-0.5 text-[0.7rem] font-mono tracking-[0.08em] uppercase rounded border border-jol-cyan/40 bg-jol-cyan/10 text-jol-cyan">ARCHIVE</span>
                                        <time class="font-mono text-xs text-jol-muted" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo get_the_date('Y.m.d'); ?></time>
                                    </div>
                                    <h2 class="text-lg md:text-xl font-bold text-jol-text leading-snug tracking-tight mb-4 transition-colors duration-200 group-hover:text-jol-cyan">
                                        <?php the_title(); ?>
                                    </h2>
                                </div>

                                <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs font-mono tracking-[0.08em] uppercase text-white/60 transition-colors duration-200 group-hover:text-jol-cyan">
                                    <span>VIEW RANKING</span>
                                    <span class="transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- ページネーション -->
            <div class="pagination agency-pagination">
                <?php
                the_posts_pagination(array(
                    'prev_text' => '&laquo; PREV',
                    'next_text' => 'NEXT &raquo;',
                ));
                ?>
            </div>
        <?php else : ?>
            <div class="rounded-lg border border-dashed border-white/15 p-12 text-center text-jol-muted">
                <p>過去のランキングデータがまだ登録されていません。</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php get_template_part('template-parts/content/l-explore'); ?>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>
