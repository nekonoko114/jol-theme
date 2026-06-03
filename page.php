<?php get_header(); ?>
<div class="hedding-area inner">
    <?php if (function_exists('the_field')) { ?>
        <p class="page-subtitle"><?php the_field('subtitle'); ?></p>
        <h1 class="page-title"><?php the_title(); ?></h1>
        <p>
        <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </div>
        </p>
    <?php } ?>
</div>

<div class="l-common-page">
    <main class="main">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <div class="page-thumbnail">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('full');
                    } ?>
                </div>
                <div class="l-common-container">
                    <?php the_content(); ?>
                </div>
        <?php endwhile;
        endif; ?>
    </main>
</div>
<!-- お問い合わせセクション ただしいパスにして-->
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>