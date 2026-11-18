<?php get_header(); ?>

<div class="relative w-full h-screen flex items-center justify-center overflow-hidden bg-black z-10 before:content-[''] before:absolute before:inset-0 before:bg-[radial-gradient(circle_at_center,transparent_30%,rgba(0,0,0,0.5)_100%)] before:z-[1] before:pointer-events-none">
    <div id="particles-js" class="absolute inset-0 w-full h-full z-[1] pointer-events-auto"></div>
    <div class="fv-content relative z-[2] text-jol-text py-12 px-8 md:py-16 md:px-20 bg-[#0f0f14]/35 border border-white/[0.07] backdrop-blur-[25px] rounded-lg max-w-[840px] w-[92%] md:w-[90%] box-border shadow-[0_30px_60px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.06)]">
        <h1 class="fv-title text-[2.1rem] md:text-[3.4rem] font-light mb-5 md:mb-6 tracking-[0.12em] leading-[1.5] bg-gradient-to-br from-jol-cyan via-white to-jol-purple bg-clip-text text-transparent text-center">未来のスターが、<span class="block md:inline font-extrabold [text-shadow:0_0_30px_rgba(138,43,226,0.3)]">ここから生まれる。</span></h1>
        <p class="text-[0.95rem] md:text-[1.25rem] tracking-[0.12em] leading-[1.9] text-white/80 mb-8 md:mb-12 font-light text-center">J.O.Lの仲間と共に<br>『人生謳歌』しましょう。</p>
        
        <!-- CTAボタン -->
        <div class="flex flex-col md:flex-row justify-center items-center gap-4 md:gap-8">
            <a href="<?php echo esc_url(home_url('/livers')); ?>" class="group relative inline-flex items-center justify-center w-full md:w-auto md:min-w-[230px] px-6 py-4 md:px-9 md:py-4 rounded text-[0.95rem] md:text-[0.98rem] font-semibold tracking-[1.5px] no-underline text-white bg-white/[0.03] box-border overflow-hidden transition-all duration-300 ease-editorial border border-jol-cyan/25 shadow-[0_4px_15px_rgba(0,0,0,0.2),0_0_10px_rgba(0,255,255,0.08)] hover:-translate-y-1 hover:bg-jol-cyan/[0.08] hover:border-jol-cyan hover:shadow-[0_12px_30px_rgba(0,0,0,0.4),0_0_25px_rgba(0,255,255,0.5)] hover:text-white">
                <span class="pointer-events-none absolute top-0 -left-[150%] w-[60%] h-full bg-gradient-to-r from-transparent via-white/15 to-transparent -skew-x-[25deg] z-[1] transition-all duration-700 group-hover:left-[200%]"></span>
                所属ライバーを見る
                <span class="ml-2.5 text-[1.1rem] transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
            </a>
            <a href="./contact" class="group relative inline-flex items-center justify-center w-full md:w-auto md:min-w-[230px] px-6 py-4 md:px-9 md:py-4 rounded text-[0.95rem] md:text-[0.98rem] font-semibold tracking-[1.5px] no-underline text-white bg-white/[0.03] box-border overflow-hidden transition-all duration-300 ease-editorial border border-jol-red/20 shadow-[0_4px_15px_rgba(0,0,0,0.2),0_0_10px_rgba(255,0,0,0.05)] hover:-translate-y-1 hover:bg-jol-red/[0.08] hover:border-jol-red hover:shadow-[0_12px_30px_rgba(0,0,0,0.4),0_0_25px_rgba(255,0,0,0.4)] hover:text-white">
                <span class="pointer-events-none absolute top-0 -left-[150%] w-[60%] h-full bg-gradient-to-r from-transparent via-white/15 to-transparent -skew-x-[25deg] z-[1] transition-all duration-700 group-hover:left-[200%]"></span>
                事務所に応募する
                <span class="ml-2.5 text-[1.1rem] transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
            </a>
        </div>
    </div>
    
    <!-- スクロールインジケーター -->
    <div class="fv-scroll absolute bottom-6 md:bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center z-[2] pointer-events-none" aria-hidden="true">
        <span class="text-[0.72rem] font-semibold text-white/40 tracking-[5px] mb-3 uppercase">SCROLL</span>
        <span class="fv-scroll-line relative w-[2px] h-[45px] md:h-[60px] bg-white/[0.08] overflow-hidden"></span>
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
<section class="relative overflow-hidden py-24 md:py-32 border-b border-jol-cyan/30">
    <div class="inner">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4">
            <h2 class="top-liver-title">J.O.L LIVER</h2>
            <div class="liver-more"><a href="<?php echo esc_url(home_url('/livers')); ?>">所属ライバー一覧へ</a></div>
        </div>
        <p class="text-sm md:text-base text-white/70 mb-10 md:mb-12">J.O.Lには個性豊かなライバーが多数所属しています。あなたにぴったりのライバーを見つけてください。</p>
    </div>

    <div class="liver-slider w-full overflow-hidden">
        <div class="liver-swiper relative w-full pb-14">
            <div class="swiper-wrapper items-center">
                <?php
                // カスタム投稿タイプ「liver」からライバー情報を取得
                $target_accounts = [
                    't.o.p_u_jin_',      // ユージン
                    'l5332541',          // さくら
                    'mizuki2525214',     // 一条美月
                    'chirin5497',        // Chiho
                    'mrm0115'            // まみ
                ];

                $liver_args = [
                    'post_type'      => 'liver',
                    'post_status'    => 'publish',
                    'posts_per_page' => count($target_accounts),
                    'meta_query'     => [
                        [
                            'key'     => 'creator_account',
                            'value'   => $target_accounts,
                            'compare' => 'IN',
                        ],
                    ],
                    'orderby'        => 'meta_value',
                    'meta_key'       => 'creator_account',
                    'order'          => 'ASC',
                ];

                $liver_query = new WP_Query($liver_args);

                if ($liver_query->have_posts()) :
                    // $target_accounts の順番通りに投稿を並び替える
                    $posts = $liver_query->posts;
                    usort($posts, function($a, $b) use ($target_accounts) {
                        $account_a = get_post_meta($a->ID, 'creator_account', true);
                        $account_b = get_post_meta($b->ID, 'creator_account', true);
                        $pos_a = array_search($account_a, $target_accounts);
                        $pos_b = array_search($account_b, $target_accounts);
                        if ($pos_a === false) $pos_a = PHP_INT_MAX;
                        if ($pos_b === false) $pos_b = PHP_INT_MAX;
                        return $pos_a - $pos_b;
                    });

                    foreach ($posts as $post) :
                        setup_postdata($GLOBALS['post'] = $post);
                        $creator_name    = get_post_meta(get_the_ID(), 'creator_name', true);
                        $creator_account = get_post_meta(get_the_ID(), 'creator_account', true);
                        $display_name    = get_the_title() ?: $creator_name;
                        $channel_info    = $creator_account ? 'TikTok' : 'Live Streaming';
                        $thumbnail_url   = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : COMMON_LIVER_THUMBNAIL_URL;
                        $post_url        = get_permalink(get_the_ID());
                ?>
                <div class="swiper-slide !w-[280px] sm:!w-[320px] md:!w-[380px] aspect-[3/4] relative rounded-lg overflow-hidden border border-white/15 bg-jol-surface select-none">
                    <a href="<?php echo esc_url($post_url); ?>" class="group block relative w-full h-full no-underline text-white">
                        <div class="absolute inset-0 w-full h-full overflow-hidden">
                            <img src="<?php echo esc_url($thumbnail_url); ?>"
                                alt="<?php echo esc_attr($display_name); ?>のプロフィール画像"
                                class="expo-slide-img w-full h-full object-cover will-change-transform">
                        </div>
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                        <div class="expo-slide-content absolute inset-x-0 bottom-0 p-5 md:p-6 z-10">
                            <span class="inline-block px-2 py-1 text-[0.7rem] font-mono tracking-[0.08em] uppercase rounded border border-jol-cyan/40 bg-jol-cyan/10 text-jol-cyan mb-2">
                                <?php echo esc_html($channel_info); ?>
                            </span>
                            <p class="text-xl md:text-2xl font-bold text-white tracking-tight leading-snug mb-1 group-hover:text-jol-cyan transition-colors duration-200 ease-editorial">
                                <?php echo esc_html($display_name); ?>
                            </p>
                            <?php if ($creator_account) : ?>
                                <p class="text-xs md:text-sm font-mono text-white/70 tracking-wide">@<?php echo esc_html($creator_account); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php
                    endforeach;
                    wp_reset_postdata();
                else :
                    for ($i = 1; $i <= 5; $i++) :
                ?>
                <div class="swiper-slide !w-[280px] sm:!w-[320px] md:!w-[380px] aspect-[3/4] relative rounded-lg overflow-hidden border border-white/10 bg-jol-surface select-none">
                    <div class="relative w-full h-full">
                        <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>"
                            alt="ライバープロフィール画像"
                            class="expo-slide-img w-full h-full object-cover opacity-50">
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                        <div class="expo-slide-content absolute inset-x-0 bottom-0 p-5 md:p-6 z-10">
                            <span class="inline-block px-2 py-1 text-[0.7rem] font-mono tracking-[0.08em] uppercase rounded border border-white/20 bg-white/10 text-white/70 mb-2">Coming Soon</span>
                            <p class="text-xl font-bold text-white/80">準備中</p>
                        </div>
                    </div>
                </div>
                <?php
                    endfor;
                endif;
                ?>
            </div>
            <div class="liver-pagination flex justify-center items-center gap-2 mt-8"></div>
        </div>
    </div>
</section>


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
    <!-- 背後の巨大なデコレーション文字 -->
    <div class="event-bg-text" aria-hidden="true">EVENT</div>
    
    <div class="event-inner inner">
        <h2 class="event-title">Event</h2>
        <!-- <p class="event-subtitle">イベント</p> -->
        <?php if ($event_query->have_posts()) : ?>
            <div class="event-container">
                <?php while ($event_query->have_posts()) : $event_query->the_post(); ?>
                    <a class="event-item" href="<?php the_permalink(); ?>">
                        <!-- ホバー時の閃光エフェクト用ライン -->
                        <span class="event-item-glint"></span>
                        
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
                        
                        <!-- 丸型矢印ボタン -->
                        <span class="event-item-arrow">
                            <span class="arrow-text">→</span>
                        </span>
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