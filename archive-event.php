<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">EVENT</p>
    <h1 class="page-title">イベント一覧</h1>
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php if (function_exists('bcn_display')) {
            bcn_display();
        } ?>
    </div>
</div>

<!-- タクソノミーごとに投稿を分けて表示 -->

<div class="archiver-event-main">
    <div class="archiver-event-inner inner">
        <?php
        // 各カテゴリーを定義
        $categories = array(
            array(
                'slug' => 'event-news',
                'name' => 'イベント',
                'class' => 'category-event'
            ),
            array(
                'slug' => 'battle',
                'name' => 'ガチバトル',
                'class' => 'category-battle'
            ),
            array(
                'slug' => 'liver-news',
                'name' => 'ライバーニュース',
                'class' => 'category-news'
            )
        );

        // 各カテゴリーごとにループ
        foreach ($categories as $category) :
            // カテゴリーごとのイベント投稿を取得
            $event_args = array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'event_category',
                        'field' => 'slug',
                        'terms' => $category['slug'],
                    )
                )
            );
            $event_query = new WP_Query($event_args);

            // 該当する投稿がある場合のみ表示
            if ($event_query->have_posts()) :
        ?>
                <section class="event-category-section <?php echo esc_attr($category['class']); ?>">
                    <h2 class="event-category-heading">
                        <span class="category-label <?php echo esc_attr($category['class']); ?>">
                            <?php echo esc_html($category['name']); ?>
                        </span>
                    </h2>
                    
                    <div class="event-category-list">
                        <?php while ($event_query->have_posts()) : $event_query->the_post(); ?>
                            <article class="archiver-event-article">
                                <a href="<?php the_permalink(); ?>" class="archiver-event-link">
                                    <div class="event-item-header">
                                        <p class="event-date">
                                            <?php echo get_the_date('Y年'); ?>
                                            <span><?php echo get_the_date('n月j日'); ?></span>
                                        </p>
                                        <div class="event-item-content">
                                            <h3 class="archiver-event-title"><?php the_title(); ?></h3>
                                            <p class="archiver-event-excerpt">
                                                <?php 
                                                $excerpt = get_the_excerpt();
                                                $excerpt = strip_tags($excerpt);
                                                $excerpt = mb_substr($excerpt, 0, 80);
                                                if (mb_strlen(get_the_excerpt()) > 80) {
                                                    $excerpt .= '...';
                                                }
                                                echo esc_html($excerpt);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>
        <?php
            endif;
            wp_reset_postdata();
        endforeach;

        // カテゴリーに属さないイベントがある場合の表示
        $uncategorized_args = array(
            'post_type' => 'event',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'event_category',
                    'operator' => 'NOT EXISTS'
                )
            )
        );
        $uncategorized_query = new WP_Query($uncategorized_args);

        if ($uncategorized_query->have_posts()) :
        ?>
            <section class="event-category-section category-uncategorized">
                <h2 class="event-category-heading">
                    <span class="category-label">その他のイベント</span>
                </h2>
                
                <div class="event-category-list">
                    <?php while ($uncategorized_query->have_posts()) : $uncategorized_query->the_post(); ?>
                        <article class="archiver-event-article">
                            <a href="<?php the_permalink(); ?>" class="archiver-event-link">
                                <div class="event-item-header">
                                    <p class="event-date">
                                        <?php echo get_the_date('Y年'); ?>
                                        <span><?php echo get_the_date('n月j日'); ?></span>
                                    </p>
                                    <div class="event-item-content">
                                        <h3 class="archiver-event-title"><?php the_title(); ?></h3>
                                        <p class="archiver-event-excerpt">
                                            <?php 
                                            $excerpt = get_the_excerpt();
                                            $excerpt = strip_tags($excerpt);
                                            $excerpt = mb_substr($excerpt, 0, 80);
                                            if (mb_strlen(get_the_excerpt()) > 80) {
                                                $excerpt .= '...';
                                            }
                                            echo esc_html($excerpt);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php
        endif;
        wp_reset_postdata();

        // イベント投稿が1件もない場合
        $all_events_args = array(
            'post_type' => 'event',
            'posts_per_page' => 1,
            'post_status' => 'publish'
        );
        $all_events_query = new WP_Query($all_events_args);
        
        if (!$all_events_query->have_posts()) :
        ?>
            <div class="no-events">
                <p>現在、予定されているイベントはありません。</p>
            </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>
    </div>
</div>

<!-- ページネーション -->
<div class="liver-slider-container">
    <div class="liver-slider-wrapper">
        <div class="">
            <?php
            // カスタム投稿タイプ 'liver' の全ライバーを取得
            $args = array(
                'post_type' => 'liver',
                'posts_per_page' => -1, // 全てのライバーを取得
            );
            $liver_query = new WP_Query($args);
            $livers = array();

            if ($liver_query->have_posts()) :
                while ($liver_query->have_posts()) : $liver_query->the_post();
                    $livers[] = array(
                        'name' => get_the_title(),
                        'channel' => get_field('channel_name'), // ACFフィールド 'channel_name' を使用
                        'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : COMMON_LIVER_THUMBNAIL_URL,
                        'permalink' => get_permalink(),
                    );
                endwhile;
                wp_reset_postdata();
            endif;


            ?>
        </div>
    </div>
</div>
</main>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>