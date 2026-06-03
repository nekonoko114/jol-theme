<?php get_header(); ?>

<div class="hedding-area inner">
    <p class="page-subtitle">Liver</p>
    <h1 class="page-title">ライバー一覧</h1>
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
    <div class="inner liver-archive-list">
        <?php if (have_posts()) : ?>
            <div class="liver-archive-container">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="liver-archive-item">
                        <div class="liver-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="liver-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="liver-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>" alt="<?php the_title(); ?>のプロフィール画像">
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="liver-content">
                                <h2 class="liver-name">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="liver-tags">
                                    <?php
                                    $terms = get_the_terms($post->ID, 'liver_tag');
                                    if ($terms && !is_wp_error($terms)) :
                                        foreach ($terms as $term) :
                                            echo '<span class="tag">#' . esc_html($term->name) . '</span>';
                                        endforeach;
                                    endif;
                                    ?>
                                </div>

                                <div class="liver-excerpt">
                                    <?php 
                                    // 抜粋を取得し、リンクを削除して文字数を制限
                                    $excerpt = get_the_excerpt();
                                    // リンクタグを削除
                                    $excerpt = strip_tags($excerpt);
                                    // 文字数を制限（100文字）
                                    $excerpt = mb_substr($excerpt, 0, 100);
                                    // 末尾が中途半端にならないように調整
                                    if (mb_strlen(get_the_excerpt()) > 100) {
                                        $excerpt .= '...';
                                    }
                                    echo esc_html($excerpt);
                                    ?>
                                </div>
                                <div class="liver-read-more">
                                    <a href="<?php the_permalink(); ?>" class="btn-more">
                                        もっと見る
                                    </a>
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
                <p>ライバーが見つかりませんでした。</p>
            </div>
        <?php endif; ?>
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