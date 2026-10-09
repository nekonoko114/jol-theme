<?php get_header(); ?>

<?php
$terms = get_the_terms(get_the_ID(), 'event_category');
$cat_name = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'イベント';
?>

<div class="hedding-area inner">
    <p class="page-subtitle">EVENT</p>
    <h1 class="page-title"><?php the_title(); ?></h1>
    <div class="single-event-meta">
        <span class="category"><?php echo esc_html($cat_name); ?></span>
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
            <?php
            // 登録されたギャラリー画像を取得（なければアイキャッチを1枚目として使用）
            $gallery_ids = get_post_meta(get_the_ID(), '_event_gallery_image_ids', true);
            if (empty($gallery_ids) && has_post_thumbnail()) {
                $gallery_ids = array(get_post_thumbnail_id());
            }
            $img_count = !empty($gallery_ids) && is_array($gallery_ids) ? count($gallery_ids) : 0;
            ?>

            <?php if ($img_count > 0): ?>
                <!-- 上部画像ギャラリー（枚数に応じたレスポンシブグリッド） -->
                <div class="event-gallery-wrap count-<?php echo esc_attr($img_count); ?>">
                    <div class="event-gallery-grid cols-<?php echo esc_attr(min($img_count, 4)); ?>">
                        <?php foreach ($gallery_ids as $index => $img_id): 
                            $img_src = wp_get_attachment_image_url($img_id, 'full');
                            if (!$img_src) continue;
                        ?>
                            <a href="<?php echo esc_url($img_src); ?>" target="_blank" rel="noopener noreferrer" class="event-gallery-item" title="タップして拡大表示">
                                <?php echo wp_get_attachment_image($img_id, 'large', false, array('class' => 'event-gallery-img', 'loading' => 'lazy')); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 本文エリア（詳細説明・ルール等） -->
            <div class="single-event-container">
                <?php the_content(); ?>
            </div>

        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>