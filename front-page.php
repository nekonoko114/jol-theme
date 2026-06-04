<?php get_header(); ?>

<div class="fv">
    <div id="particles-js"></div>
    <div class="fv-content">
        <h1>未来のスターが、<span>ここから生まれる。</span></h1>
        <p>J.O.Lの仲間と共に<br>『人生謳歌』しましょう。</p>
    </div>
</div>
<section class="giver">
    <h2 class="giver-title">ライバー活動と成功を<br>サポートします</h2>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <h3>1. プロのサポートで成長を加速</h3>
                <p>個人で配信を始める場合、何をどうすればいいか分からず、手探りでの活動になりがちです。しかし、事務所に所属すれば、ライブ配信やコンテンツ制作のプロがあなたの成長をサポートしてくれます。</p>
            </div>
            <div class="swiper-slide">
                <h3>2. 安心できる活動環境</h3>
                <p>ライブ配信は、時に誹謗中傷や予期せぬトラブルに巻き込まれるリスクがあります。個人で全てを対応するのは精神的にも大きな負担です。<br>
                    事務所に所属すれば、こうしたトラブル発生時の対応を任せることができ、<br>
                    安心してクリエイティブな活動に集中できます。また、著作権や肖像権といった専門的な知識もサポートしてもらえるため、リスクを最小限に抑えられます。
                </p>
            </div>
            <div class="swiper-slide">
                <h3>3. 広がるチャンスと人脈</h3>
                <p>事務所は、プラットフォーム運営や企業との間に独自のネットワークを持っています。これにより、個人では獲得が難しい企業案件や、特別なイベントへの出演機会が生まれます。<br>
                    また、同じ事務所に所属する仲間と交流することで、コラボ配信を通じて互いのファン層を広げたり、悩みを共有し、高め合ったりすることができます。
                </p>
            </div>
            <div class="swiper-slide">
                <h3>4. 活動への集中と効率化</h3>
                <p>
                    動画編集やスケジュール管理、収益の税務処理など、配信以外にもやるべきことはたくさんあります。これらすべてを一人でこなすのは大変な作業です。<br>
                    事務所がこれらの事務的な作業やマネジメントを代行してくれることで、あなたは「配信する」という本来の活動に集中できます。
                </p>
            </div>
            <div class="swiper-slide">
                <h3>1. プロのサポートで成長を加速</h3>
                <p>個人で配信を始める場合、何をどうすればいいか分からず、手探りでの活動になりがちです。しかし、事務所に所属すれば、ライブ配信やコンテンツ制作のプロがあなたの成長をサポートしてくれます。</p>
            </div>
            <div class="swiper-slide">
                <h3>2. 安心できる活動環境</h3>
                <p>ライブ配信は、時に誹謗中傷や予期せぬトラブルに巻き込まれるリスクがあります。個人で全てを対応するのは精神的にも大きな負担です。<br>
                    事務所に所属すれば、こうしたトラブル発生時の対応を任せることができ、<br>
                    安心してクリエイティブな活動に集中できます。また、著作権や肖像権といった専門的な知識もサポートしてもらえるため、リスクを最小限に抑えられます。
                </p>
            </div>
            <div class="swiper-slide">
                <h3>3. 広がるチャンスと人脈</h3>
                <p>事務所は、プラットフォーム運営や企業との間に独自のネットワークを持っています。これにより、個人では獲得が難しい企業案件や、特別なイベントへの出演機会が生まれます。<br>
                    また、同じ事務所に所属する仲間と交流することで、コラボ配信を通じて互いのファン層を広げたり、悩みを共有し、高め合ったりすることができます。
                </p>
            </div>
            <div class="swiper-slide">
                <h3>4. 活動への集中と効率化</h3>
                <p>
                    動画編集やスケジュール管理、収益の税務処理など、配信以外にもやるべきことはたくさんあります。これらすべてを一人でこなすのは大変な作業です。<br>
                    事務所がこれらの事務的な作業やマネジメントを代行してくれることで、あなたは「配信する」という本来の活動に集中できます。
                </p>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>
<section class="top-liver-list">
    <div class="top-liver-list-heading inner">
        <h2 class="top-liver-title">J.O.L LIVER</h2>
        <div class="liver-more"><a href="<?php echo esc_url(home_url('/livers')); ?>">所属ライバー一覧へ</a></div>
    </div>
    <p class="top-liver-subtitle">J.O.Lには個性豊かなライバーが多数所属しています。あなたにぴったりのライバーを見つけてください。</p>

    <div class="liver-slider">
        <div class="liver-swiper">
            <div class="swiper-wrapper">
                <?php
                // カスタム投稿タイプ「liver」からライバー情報を取得
                // カスタム投稿タイプ「liver」からライバー情報を取得
                $liver_args = [
                    'post_type' => 'liver',
                    'post_status' => 'publish',
                    'posts_per_page' => 12, // 表示する投稿数を調整
                    'orderby' => 'rand', // ランダムに表示
                ];

                // 管理者以外は「creator_name」があるものだけに絞る（通常動作）
                // 管理者の場合はデバッグのために全件表示し、後で状態を確認する
                if (!current_user_can('administrator')) {
                    $liver_args['meta_query'] = [
                        [
                            'key' => 'creator_name',
                            'compare' => 'EXISTS'
                        ]
                    ];
                }

                $liver_query = new WP_Query($liver_args);

                if ($liver_query->have_posts()) :
                        while ($liver_query->have_posts()) : $liver_query->the_post();
                            // カスタムフィールドを取得
                        $creator_name = get_post_meta(get_the_ID(), 'creator_name', true);
                        $creator_account = get_post_meta(get_the_ID(), 'creator_account', true);
                        $account_url = get_post_meta(get_the_ID(), 'account_url', true);

                            // 表示名を決定（カスタムフィールドがない場合はタイトルを使用）
                            $display_name = $creator_name ? $creator_name : get_the_title();

                            // チャンネル情報（TikTokアカウントがある場合）
                            $channel_info = $creator_account ? 'TikTok' : 'Live Streaming';

                            // アイキャッチ画像があるかチェック
                        $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : COMMON_LIVER_THUMBNAIL_URL;

                            // パーマリンクを明示的に取得
                        $post_url = get_permalink(get_the_ID());
                ?>
                <div class="swiper-slide">
                            <a href="<?php echo esc_url($post_url); ?>" class="liver-slide-link">
                                <img src="<?php echo esc_url($thumbnail_url); ?>"
                                    alt="<?php echo esc_attr($display_name); ?>のプロフィール画像">
                                <p class="liver-name"><?php echo esc_html($display_name); ?></p>
                                <p class="liver-channel"><?php echo esc_html($channel_info); ?></p>

                                <?php if ($creator_account) : ?>
                                    <p class="liver-account">@<?php echo esc_html($creator_account); ?></p>
                                <?php endif; ?>
                                
                                <?php 
                                // 管理者用デバッグ表示：メタキー一覧を出力
                                if (current_user_can('administrator')) {
                                    $all_metas = get_post_meta(get_the_ID());
                                    $keys = implode(', ', array_keys($all_metas));
                                    echo '<div style="font-size:10px; color:red; background:#fff; padding:5px;">Debug Keys: ' . esc_html($keys) . '</div>';
                                    if (!$creator_name) echo '<div style="font-size:10px; color:blue; background:#fff; padding:5px;">creator_name is MISSING</div>';
                                }
                                ?>
                            </a>
                        </div>
                <?php
                        endwhile;
                    wp_reset_postdata();
                else :
                    // ライバー投稿がない場合のフォールバック表示
                    for ($i = 1; $i <= 6; $i++) :
                    ?>
                        <div class="swiper-slide">
                            <div class="liver-slide-placeholder">
                                <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>"
                                    alt="ライバープロフィール画像">
                                <p class="liver-name">準備中</p>
                                <p class="liver-channel">Coming Soon</p>
                            </div>
                        </div>
                <?php
                    endfor;
                endif;
                ?>
            </div>
            <div class="liver-pagination"></div>
        </div>
    </div>
</section>


<!-- <?php
// $inteview_args = array(
//     'post_type' => 'interview',
//     'posts_per_page' => -1,
//     'orderby' => 'rand',
//     'post_status' => 'publish',
// );
$interview_query = new WP_Query($inteview_args);

?>
<section class="interview">
    <div class="interview-inner inner">
        <h2 class="interview-title">Interview</h2>
        <div class="interview-archive-container">
            <?php if ($interview_query->have_posts()) : ?>
                <?php while ($interview_query->have_posts()) : $interview_query->the_post(); ?>

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
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-posts">
                    <p>インタビューが見つかりませんでした。</p>

                </div>
            <?php endif; ?>
        </div>
</section> -->

<?php
$event_args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
);
$event_query = new WP_Query($event_args);

// デバッグ情報（管理者のみ）
if (current_user_can('administrator')) {
    echo '<!-- イベントクエリデバッグ: 投稿数 = ' . $event_query->post_count . ' / 全件数 = ' . $event_query->found_posts . ' -->';
}
?>

<section class="event">
    <div class="event-inner inner">
        <h2 class="event-title">Event</h2>
        <!-- <p class="event-subtitle">イベント</p> -->
        <?php if ($event_query->have_posts()) : ?>
            <div class="event-container">
                <?php while ($event_query->have_posts()) : $event_query->the_post(); ?>
                    <a class="event-item" href="<?php the_permalink(); ?>">
                        <p class="event-date">
                            <?php echo get_the_date('Y年'); ?>
                            <span><?php echo get_the_date('n月j日'); ?></span>
                        </p>
                        <div class="event-wrapper">
                            <?php
                            // タクソノミーの取得と表示
                            $terms = get_the_terms(get_the_ID(), 'event_category');
                            ?>
                            
                            <!-- カテゴリータグの表示 -->
                            <?php if ($terms && !is_wp_error($terms)) : ?>
                                <div class="event-categories">
                                    <?php foreach ($terms as $term) : 
                                        // カテゴリーごとにクラスを付与
                                        $category_class = 'event-category-tag';
                                        if ($term->slug === 'event' || $term->name === 'イベント') {
                                            $category_class .= ' category-event';
                                        } elseif ($term->slug === 'battle' || $term->name === 'ガチバトル') {
                                            $category_class .= ' category-battle';
                                        } elseif ($term->slug === 'news' || $term->name === 'ライバーニュース') {
                                            $category_class .= ' category-news';
                                        }
                                    ?>
                                        <span class="<?php echo esc_attr($category_class); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="event-name">
                                <?php the_title(); ?>
                            </h3>
                            <p class="event-description">
                                <?php 
                                // 抜粋を取得し、「もっと見る」リンクを削除
                                $excerpt = get_the_excerpt();
                                // HTMLタグを削除
                                $excerpt = strip_tags($excerpt);
                                // 文字数を制限（100文字）
                                $excerpt = mb_substr($excerpt, 0, 100);
                                if (mb_strlen(get_the_excerpt()) > 100) {
                                    $excerpt .= '...';
                                }
                                echo esc_html($excerpt);
                                ?>
                            </p>
                        </div>
                    </a>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p class="no-events">現在、予定されているイベントはありません。</p>
        <?php endif; ?>
    </div>
</section>

<?php get_template_part('template-parts/content/l-contact'); ?>




<?php get_footer(); ?>