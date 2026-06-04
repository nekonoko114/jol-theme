<?php get_header(); ?>

<div class="mysterious-theme">
    <div class="hedding-area inner">
        <p class="page-subtitle">RANKING ARCHIVES</p>
        <h1 class="page-title">過去のランキング</h1>
        <div class="breadcrumbs agency-breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </div>
    </div>

    <main class="agency-main inner">
        <?php if (have_posts()) : ?>
            <div class="ranking-archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="ranking-archive-card">
                        <a href="<?php the_permalink(); ?>" class="ranking-card-link">
                            <div class="ranking-card-image-wrap">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <!-- デフォルトのトロフィーSVGアイコンを表示 -->
                                    <div class="default-ranking-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="trophy-svg">
                                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                                            <path d="M4 22h16" />
                                            <path d="M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34" />
                                            <path d="M12 2a6 6 0 0 1 6 6v3a6 6 0 0 1-6 6 6 6 0 0 1-6-6V8a6 6 0 0 1 6-6Z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="ranking-card-overlay">
                                    <span class="btn-detail-view">VIEW DETAIL</span>
                                </div>
                            </div>
                            <div class="ranking-card-content">
                                <span class="ranking-card-tag">ARCHIVE</span>
                                <h2 class="ranking-card-title"><?php the_title(); ?></h2>
                                <p class="ranking-card-date"><?php echo get_the_date('Y.m.d'); ?></p>
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
            <div class="no-posts">
                <p>過去のランキングデータがまだ登録されていません。</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>
