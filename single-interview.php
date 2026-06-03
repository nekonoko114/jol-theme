<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article class="single-interview">
            <div class="hedding-area inner">
                <p class="page-subtitle">Interview</p>
                <!-- breadcrumbs -->
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
                <div class="liver-profile">
                    <div class="inner">
                        <div class="features">
                            <div class="tags">
                                <?php
                                // カスタムタクソノミー 'interview_category' のターム（カテゴリー）を表示
                                $terms = get_the_terms($post->ID, 'interview_category');
                                if ($terms && ! is_wp_error($terms)) :
                                    foreach ($terms as $term) :
                                        echo '<span>#' . esc_html($term->name) . '</span>';
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            <p class="creator-feature">インタビュー情報</p>
                            <h2 class="creator-name">名前：<?php the_title(); ?></h2>
                            <div class="live-info">
                                <p>公開日：<?php echo get_the_date('Y年m月d日'); ?></p>
                                <?php if (function_exists('get_field') && get_field('interview_subtitle')) : ?>
                                    <p>サブタイトル：<?php echo esc_html(get_field('interview_subtitle')); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="liver-actions">
                            <?php if (function_exists('get_field') && get_field('interview_video_url')) : ?>
                                <div class="btn-tiktok"><a href="<?php echo esc_url(get_field('interview_video_url')); ?>" target="_blank">動画を見る</a></div>
                            <?php endif; ?>
                            <?php if (function_exists('get_field') && get_field('related_link_url')) : ?>
                                <div class="btn-tiktok"><a href="<?php echo esc_url(get_field('related_link_url')); ?>" target="_blank"><?php echo (function_exists('get_field') && get_field('related_link_text')) ? esc_html(get_field('related_link_text')) : '関連リンク'; ?></a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="profile-image-thumbnail">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium', array('alt' => get_the_title() . 'のインタビュー画像')); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/interview-default.jpg" alt="<?php the_title(); ?>のインタビュー画像">
                        <?php endif; ?>
                    </div>


                    <div class="profile-section interview-content inner">
                        <h3>INTERVIEW</h3>
                        <div class="profile-content">
                            <?php the_content(); ?>
                        </div>

                        <?php if (function_exists('get_field') && get_field('interview_summary')) : ?>
                            <div class="interview-summary">
                                <h4>インタビューまとめ</h4>
                                <div class="summary-content">
                                    <?php echo wp_kses_post(get_field('interview_summary')); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="back-button">
                        <a href="<?php echo esc_url(get_post_type_archive_link('interview')); ?>" class="btn-back">
                            インタビュー一覧へ戻る
                        </a>
                    </div>
            </main>
        </article>

<?php endwhile;
endif; ?>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>