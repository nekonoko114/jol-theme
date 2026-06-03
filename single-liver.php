<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article class="single-liver">
            <div class="hedding-area inner">
                <p class="page-subtitle">Liver Profile</p>
                <p class="page-title"><?php the_title(); ?></p>
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
                    <div class="profile-image-thumbnail">
                        <!-- サムネイルの取得 -->
                        <?php
                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        if ($thumbnail_url) : ?>
                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>のプロフィール画像">
                        <?php else : ?>
                            <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>" alt="<?php the_title(); ?>のプロフィール画像">
                        <?php endif; ?>
                    </div>
                    <div class="inner">
                        <div class="features">
                            <p class="creator-feature">特徴</p>
                            <h2 class="creator-name">クリエイター名：<?php the_title(); ?></h2>

                            <!-- カスタムタクソノミー（カテゴリ）を表示 -->
                            <div class="liver-taxonomies">
                                <!-- デバッグ情報 -->
                                <?php if (current_user_can('administrator')): ?>
                                    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 0.8rem;">
                                        <strong>デバッグ情報:</strong><br>
                                        投稿ID: <?php echo get_the_ID(); ?><br>
                                        liver_categoryタクソノミー登録済み: <?php echo taxonomy_exists('liver_category') ? 'Yes' : 'No'; ?><br>
                                        liver_tagタクソノミー登録済み: <?php echo taxonomy_exists('liver_tag') ? 'Yes' : 'No'; ?><br>
                                        <?php
                                        $all_categories = get_the_terms(get_the_ID(), 'liver_category');
                                        $all_tags = get_the_terms(get_the_ID(), 'liver_tag');
                                        ?>
                                        カテゴリー数: <?php echo is_array($all_categories) ? count($all_categories) : '0または取得エラー'; ?><br>
                                        タグ数: <?php echo is_array($all_tags) ? count($all_tags) : '0または取得エラー'; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- カテゴリーを表示 -->
                                <div class="liver-categories">
                                    <?php
                                    // カスタムタクソノミー 'liver_category' のターム（カテゴリ）を表示
                                    $categories = get_the_terms($post->ID, 'liver_category');
                                    if ($categories && ! is_wp_error($categories)) :
                                        echo '<div class="taxonomy-group">';
                                        echo '<span class="taxonomy-label">カテゴリ:</span>';
                                        foreach ($categories as $category) :
                                            echo '<span class="liver-category">' . esc_html($category->name) . '</span>';
                                        endforeach;
                                        echo '</div>';
                                    else:
                                        echo '<div class="no-taxonomies">カテゴリーが設定されていません</div>';
                                    endif;
                                    ?>
                                </div>

                                <!-- タグを表示 -->
                                <div class="liver-tags">
                                    <?php
                                    // カスタムタクソノミー 'liver_tag' のターム（タグ）を表示
                                    $tags = get_the_terms($post->ID, 'liver_tag');
                                    if ($tags && ! is_wp_error($tags)) :
                                        echo '<div class="taxonomy-group">';
                                        echo '<span class="taxonomy-label">タグ:</span>';
                                        foreach ($tags as $tag) :
                                            echo '<span class="liver-tag">#' . esc_html($tag->name) . '</span>';
                                        endforeach;
                                        echo '</div>';
                                    else:
                                    // echo '<div class="no-taxonomies">タグが設定されていません</div>';
                                    endif;
                                    ?>
                                </div>
                            </div>

                            <div class="live-info">
                                <?php
                                // カスタムフィールドの取得（新しいフィールド構造に対応）
                                $creator_name = get_post_meta(get_the_ID(), 'creator_name', true);
                                $delivery = get_post_meta(get_the_ID(), 'delivery', true); // 配信開始日
                                $delivery_time = get_post_meta(get_the_ID(), 'delivery_time', true);
                                $creator_account = get_post_meta(get_the_ID(), 'creator_account', true);
                                $account_url = get_post_meta(get_the_ID(), 'account_url', true); // URLフィールド名変更
                                $gift = get_post_meta(get_the_ID(), 'gift', true);

                                // URLに https://www.tiktok.com/ が含まれていない場合は追加
                                if ($account_url && !preg_match('/^https?:\/\//', $account_url)) {
                                    $account_url = 'https://www.tiktok.com/@' . ltrim($account_url, '/@');
                                }

                                // デバッグ:全てのカスタムフィールドを取得
                                $all_custom_fields = get_post_meta(get_the_ID());
                                ?>

                                <!-- 配信情報カード -->
                                <div class="creator-info-cards">
                                    <?php if ($creator_name) : ?>
                                        <div class="info-card creator-card">
                                            <div class="card-icon">🎭</div>
                                            <div class="card-content">
                                                <span class="card-label">アカウント名</span>
                                                <span class="card-value">
                                                    <?php if ($account_url) : ?>
                                                        <a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener noreferrer" class="account-link">
                                                            <?php echo esc_html($creator_name); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <?php echo esc_html($creator_name); ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($delivery) : ?>
                                        <div class="info-card date-card">
                                            <div class="card-icon">📅</div>
                                            <div class="card-content">
                                                <span class="card-label">配信開始日</span>
                                                <span class="card-value"><?php echo esc_html($delivery); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($delivery_time) : ?>
                                        <div class="info-card time-card">
                                            <div class="card-icon">⏱️</div>
                                            <div class="card-content">
                                                <span class="card-label">総配信時間</span>
                                                <span class="card-value"><?php echo esc_html($delivery_time); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($creator_account) : ?>
                                        <div class="info-card account-card">
                                            <div class="card-icon">📱</div>
                                            <div class="card-content">
                                                <span class="card-label">TikTokアカウント</span>
                                                <span class="card-value">
                                                    <?php if ($account_url) : ?>
                                                        <a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener noreferrer" class="account-link">
                                                            @<?php echo esc_html($creator_account); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        @<?php echo esc_html($creator_account); ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($gift) : ?>
                                        <div class="info-card gift-card">
                                            <div class="card-icon">🎁</div>
                                            <div class="card-content">
                                                <span class="card-label">ギフト数</span>
                                                <span class="card-value"><?php echo number_format(intval($gift)); ?>個</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <!-- デバッグ用：管理者のみ -->
                                <?php if (current_user_can('administrator')) :
                                    $all_meta = get_post_meta(get_the_ID());
                                    if (!empty($all_meta)) : ?>
                                        <div class="debug-info">
                                            <details>
                                                <summary>🔧 デバッグ情報（管理者のみ）</summary>
                                                <div class="debug-content">
                                                    <pre><?php print_r($all_meta); ?></pre>
                                                </div>
                                            </details>
                                        </div>
                                <?php endif;
                                endif; ?>
                            </div>
                        </div>
                        <div class="profile-section inner">
                        <h3>PROFILE</h3>
                        <div class="profile-content">
                            <?php echo  get_the_content(); ?>
                        </div>
                    </div>

                        <div class="liver-actions">
                            <?php
                            // TikTokリンクがある場合は美しいボタンとして表示
                            $account_url_for_button = get_post_meta(get_the_ID(), 'account_url', true);
                            $creator_account_for_button = get_post_meta(get_the_ID(), 'creator_account', true);

                            // URLに https://www.tiktok.com/ が含まれていない場合は追加
                            if ($account_url_for_button && !preg_match('/^https?:\/\//', $account_url_for_button)) {
                                $account_url_for_button = 'https://www.tiktok.com/@' . ltrim($account_url_for_button, '/@');
                            }

                            if ($account_url_for_button) : ?>
                                <div class="action-buttons">
                                    <a href="<?php echo esc_url($account_url_for_button); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn-tiktok-enhanced">
                                        <span class="btn-icon">🎵</span>
                                        <span class="btn-text">TikTokで応援する</span>
                                        <span class="btn-arrow">→</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    

                    <div class="back-button">
                        <a href="<?php echo esc_url(get_post_type_archive_link('liver')); ?>" class="btn-back">
                            ライバー一覧へ戻る
                        </a>
                    </div>
            </main>
        </article>

<?php endwhile;
endif; ?>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>