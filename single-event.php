<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">EVENT</p>
    <h1 class="page-title"><?php the_title(); ?></h1>
    <div class="single-event-meta">
        <span class="category">プレスリリース</span>
        <span class="post-date"><?php the_time('Y年n月j日'); ?></span>
     </div>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('bcn_display')) {
            bcn_display();
        } ?>
    </div>
</div>
<main class="main-content single-event-main">
    <div class="single-event-inner inner">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <div class="single-event-container">
                     <?php the_content(); ?>
                </div>
                <div class="single-event-thumbnail">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('middle'); ?>
                    <?php endif; ?>
                </div>
        <?php endwhile;
        endif; ?>
    </div>
</main>

<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>