<?php get_header(); ?>

<?php
$post_id  = get_the_ID();
$terms    = get_the_terms($post_id, 'event_category');
$cat_name = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'イベント';
?>

<div class="hedding-area inner">
    <p class="page-subtitle">EVENT</p>
    <h1 class="page-title"><?php the_title(); ?></h1>
    <div class="single-event-meta">
        <span class="category"><?php echo esc_html($cat_name); ?></span>
        <span class="post-date"><?php echo esc_html(get_the_date('Y年n月j日', $post_id)); ?></span>
    </div>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('jol_breadcrumbs')) { jol_breadcrumbs(); } ?>
    </div>
</div>

<main class="main-content single-event-main">
    <div class="single-event-inner inner">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <?php
            // 登録されたギャラリー画像を取得（なければアイキャッチを1枚目として使用）
            $raw_gallery_ids = get_post_meta(get_the_ID(), '_event_gallery_image_ids', true);
            if ((empty($raw_gallery_ids) || !is_array($raw_gallery_ids)) && has_post_thumbnail()) {
                $raw_gallery_ids = array(get_post_thumbnail_id());
            }

            // 有効な画像URLが存在するIDのみを抽出
            $valid_gallery_items = array();
            if (!empty($raw_gallery_ids) && is_array($raw_gallery_ids)) {
                foreach ($raw_gallery_ids as $img_id) {
                    $img_id  = (int) $img_id;
                    $img_src = $img_id ? wp_get_attachment_image_url($img_id, 'full') : false;
                    if ($img_src) {
                        $valid_gallery_items[] = array(
                            'id'  => $img_id,
                            'src' => $img_src,
                        );
                    }
                }
            }
            $img_count = count($valid_gallery_items);
            ?>

            <?php if ($img_count > 0): ?>
                <!-- 上部画像ギャラリー（枚数に応じたレスポンシブグリッド） -->
                <div class="event-gallery-wrap count-<?php echo esc_attr($img_count); ?>">
                    <div class="event-gallery-grid cols-<?php echo esc_attr(min($img_count, 4)); ?>">
                        <?php foreach ($valid_gallery_items as $item): ?>
                            <a href="<?php echo esc_url($item['src']); ?>" target="_blank" rel="noopener noreferrer" class="event-gallery-item" title="タップして拡大表示">
                                <?php echo wp_get_attachment_image($item['id'], 'large', false, array('class' => 'event-gallery-img', 'loading' => 'lazy')); ?>
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
