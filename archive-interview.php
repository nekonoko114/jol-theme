<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">INTERVIEW LIST</p>
    <h1 class="page-title">インタビュー一覧</h1>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('bcn_display')) {
            bcn_display();
        } ?>
    </div>
</div>

<main class="main-content">
    <div class="profile-image">
        <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>" alt="<?php the_title(); ?>のプロフィール画像">
    </div>
    <div class="inner interview-archive-list">
        <?php if (have_posts()) : ?>
            <div class="interview-archive-container">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="interview-archive-item">
                        <div class="interview-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="interview-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="interview-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/interview-default.jpg" alt="<?php the_title(); ?>のインタビュー画像">
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="interview-content">
                                <h2 class="interview-content-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="interview-meta">
                                    <?php
                                    $terms = get_the_terms($post->ID, 'interview_category');
                                    if ($terms && !is_wp_error($terms)) :
                                        foreach ($terms as $term) :
                                            echo '<span class="interview-category">' . esc_html($term->name) . '</span>';
                                        endforeach;
                                    endif;
                                    ?>
                                </div>

                                <div class="interview-excerpt">
                                    <?php echo get_the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <!-- ページネーション -->
            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'prev_text' => '&laquo; 前のページ',
                    'next_text' => '次のページ &raquo;',
                ));
                ?>
            </div>
        <?php else : ?>
            <div class="no-posts">
                <p>インタビューが見つかりませんでした。</p>
            </div>
        <?php endif; ?>
    </div>


</main>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>