<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    $creator_name = get_post_meta(get_the_ID(), 'creator_name', true);
    $delivery = get_post_meta(get_the_ID(), 'delivery', true);
    $delivery_time = get_post_meta(get_the_ID(), 'delivery_time', true);
    $creator_account = get_post_meta(get_the_ID(), 'creator_account', true);
    $account_url = get_post_meta(get_the_ID(), 'account_url', true);
    $gift = get_post_meta(get_the_ID(), 'gift', true);
    $feature = get_post_meta(get_the_ID(), 'feature', true);

    if ($account_url && !preg_match('/^https?:\/\//', $account_url)) {
        $account_url = 'https://www.tiktok.com/@' . ltrim($account_url, '/@');
    }
?>
<div class="mysterious-theme">
    <article class="single-liver-agency">
        <div class="agency-hero">
            <div class="hero-bg">
                <?php 
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                if ($thumbnail_url) : ?>
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="" class="bg-blur">
                <?php endif; ?>
            </div>
            <div class="hero-content inner">
                <div class="breadcrumbs agency-breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                    <?php if (function_exists('jol_breadcrumbs')) { jol_breadcrumbs(); } ?>
                </div>
                
                <div class="hero-profile-flex">
                    <div class="hero-image">
                        <?php if ($thumbnail_url) : ?>
                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>">
                        <?php else : ?>
                            <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="hero-text">
                        <?php if ($feature) : ?>
                            <p class="agency-subtitle magazine-feature"><?php echo esc_html($feature); ?></p>
                        <?php else : ?>
                            <p class="agency-subtitle">TALENT</p>
                        <?php endif; ?>
                        
                        <h1 class="agency-title">
                            <?php if ($account_url) : ?>
                                <a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener noreferrer" class="magazine-title-link">
                                    <?php the_title(); ?>
                                </a>
                            <?php else : ?>
                                <?php the_title(); ?>
                            <?php endif; ?>
                        </h1>
                        
                        <?php if ($creator_account) : ?>
                            <p class="agency-id">@<?php echo esc_html($creator_account); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <main class="agency-main inner">
            <div class="agency-details-grid">
                
                <!-- プロフィールセクション -->
                <section class="agency-section profile-section">
                    <h3 class="section-heading">PROFILE</h3>
                    <div class="agency-profile-content">
                        <?php echo nl2br(esc_html(strip_tags(get_the_content()))); ?>
                    </div>
                </section>
                
                <!-- データセクション -->
                <section class="agency-section data-section">
                    <h3 class="section-heading">DATA</h3>
                    <ul class="agency-data-list">
                        <?php if ($delivery) : ?>
                            <li><span class="data-label">DEBUT</span> <span class="data-val"><?php echo esc_html($delivery); ?></span></li>
                        <?php endif; ?>
                        <?php if ($delivery_time) : ?>
                            <li><span class="data-label">HOURS</span> <span class="data-val"><?php echo esc_html($delivery_time); ?></span></li>
                        <?php endif; ?>
                        <?php if ($gift) : ?>
                            <li><span class="data-label">GIFTS</span> <span class="data-val"><?php echo number_format(intval($gift)); ?></span></li>
                        <?php endif; ?>
                        
                        <?php 
                        $categories = get_the_terms(get_the_ID(), 'liver_category');
                        if ($categories && !is_wp_error($categories)) : ?>
                            <li><span class="data-label">CATEGORY</span> 
                                <span class="data-val">
                                    <?php 
                                    $cat_names = wp_list_pluck($categories, 'name');
                                    echo esc_html(implode(' / ', $cat_names));
                                    ?>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <?php
                    // 過去のランキング（post_type: ranking）から入賞歴を集計
                    $current_liver_id = get_the_ID();
                    $ranking_posts = get_posts(array(
                        'post_type'      => 'ranking',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ));

                    $ranking_categories = array(
                        'event_ranking_livers'         => 'イベントランキング',
                        'rookie_ranking_livers'        => 'J.O.L 新人王',
                        'diamond_ranking_livers'       => 'ダイヤモンドランキング',
                        'delivery_time_ranking_livers' => '配信時間ランキング',
                    );

                    $award_records = array();
                    foreach ($ranking_posts as $r_post) {
                        foreach ($ranking_categories as $meta_key => $cat_label) {
                            $livers_list = function_exists('get_field')
                                ? get_field($meta_key, $r_post->ID)
                                : get_post_meta($r_post->ID, $meta_key, true);

                            if (empty($livers_list) && $meta_key === 'delivery_time_ranking_livers') {
                                $livers_list = get_post_meta($r_post->ID, 'delivery_time_ranking_liver', true);
                            }
                            if (is_string($livers_list)) {
                                $livers_list = maybe_unserialize($livers_list);
                            }
                            if (!is_array($livers_list)) {
                                continue;
                            }

                            $rank_pos = 1;
                            foreach ($livers_list as $item) {
                                if ($rank_pos > 5) {
                                    break;
                                }
                                $item_id = is_object($item) ? (int) $item->ID : (int) $item;
                                if ($item_id === (int) $current_liver_id) {
                                    $award_records[] = array(
                                        'title'    => get_the_title($r_post->ID),
                                        'url'      => get_permalink($r_post->ID),
                                        'date'     => get_the_date('Y.m', $r_post->ID),
                                        'category' => $cat_label,
                                        'rank'     => $rank_pos,
                                    );
                                    break;
                                }
                                $rank_pos++;
                            }
                        }
                    }
                    ?>

                    <div class="mt-8">
                        <div class="flex items-center justify-between border-b border-white/15 pb-2 mb-4">
                            <span class="font-mono text-xs tracking-[0.15em] uppercase text-white/50">RANKING AWARDS</span>
                            <?php if (!empty($award_records)) : ?>
                                <span class="font-mono text-xs text-jol-cyan"><?php echo count($award_records); ?>件</span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($award_records)) : ?>
                            <ul class="space-y-3 list-none p-0 m-0">
                                <?php foreach ($award_records as $award) :
                                    $rank_num = (int) $award['rank'];
                                    if ($rank_num === 1) {
                                        $rank_badge_class = 'border-yellow-400/60 bg-yellow-400/15 text-yellow-300';
                                    } elseif ($rank_num === 2) {
                                        $rank_badge_class = 'border-slate-300/60 bg-slate-300/15 text-slate-200';
                                    } elseif ($rank_num === 3) {
                                        $rank_badge_class = 'border-amber-600/60 bg-amber-600/15 text-amber-400';
                                    } else {
                                        $rank_badge_class = 'border-jol-cyan/40 bg-jol-cyan/10 text-jol-cyan';
                                    }
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url($award['url']); ?>" class="group flex items-center justify-between gap-3 p-3 rounded border border-white/10 bg-white/[0.02] no-underline transition-all duration-200 ease-editorial hover:border-jol-cyan/50 hover:bg-white/[0.05]">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-mono text-[0.7rem] text-white/45"><?php echo esc_html($award['date']); ?></span>
                                                    <span class="font-mono text-[0.7rem] text-white/70 truncate"><?php echo esc_html($award['category']); ?></span>
                                                </div>
                                                <p class="text-sm font-medium text-white group-hover:text-jol-cyan transition-colors duration-200 truncate m-0">
                                                    <?php echo esc_html($award['title']); ?>
                                                </p>
                                            </div>
                                            <span class="shrink-0 inline-flex items-center justify-center min-w-[44px] px-2 py-1 rounded border font-mono text-xs font-bold <?php echo esc_attr($rank_badge_class); ?>">
                                                <?php echo esc_html($rank_num); ?>位
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-xs font-mono text-white/40 py-4">まだランキング入賞歴はありません。</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <?php 
            // コンテンツ完成後にここを true に変更すると INTERVIEW と AWARDS が表示されます
            $show_interview_awards = false; 
            if ($show_interview_awards) : 
            ?>
            <!-- 雑誌風 Q&A セクション -->
            <section class="magazine-qa-section">
                <div class="magazine-qa-header">
                    <h2 class="magazine-qa-title">INTERVIEW</h2>
                    <p class="magazine-qa-subtitle">Q&Aで知る、ライバーの素顔</p>
                </div>
                
                <div class="magazine-qa-list">
                    <?php 
                    $qa_text = get_post_meta(get_the_ID(), 'liver_qa_text', true);
                    $has_qa = false;
                    
                    if (!empty($qa_text)) :
                        // ユーザーが文字としての「\n」や「\r\n」（円マーク/バックスラッシュ+n）を入力した場合、本物の改行に置換
                        $qa_text = str_replace(array('\\r\\n', '\\r', '\\n'), "\n", $qa_text);
                        
                        // 「---」でQ&Aの各ブロックに分割
                        $blocks = preg_split('/^---+\s*$/m', $qa_text);
                        foreach ($blocks as $block) :
                            $block = trim($block);
                            if (empty($block)) continue;
                            
                            // 各種改行コードに対応して分割
                            $lines = preg_split('/\r\n|\r|\n/', $block);
                            $q = '';
                            $a = array();
                            $in_a = false;
                            
                            foreach ($lines as $line) {
                                $line = trim($line);
                                if (empty($line)) continue;
                                
                                // Q. や Q:、あるいはスペース区切りに対応
                                if (preg_match('/^[qQ][.:：\s]\s*(.*)$/u', $line, $matches)) {
                                    $q = $matches[1];
                                    $in_a = false;
                                } elseif (preg_match('/^[aA][.:：\s]\s*(.*)$/u', $line, $matches)) {
                                    $a[] = $matches[1];
                                    $in_a = true;
                                } else {
                                    if ($in_a) {
                                        $a[] = $line;
                                    } else {
                                        $q .= ($q ? "\n" : "") . $line;
                                    }
                                }
                            }
                            
                            $a_text = implode("\n", $a);
                            if ($q && $a_text) :
                                $has_qa = true;
                    ?>
                        <details class="qa-accordion">
                            <summary class="qa-q">
                                <span class="qa-mark">Q.</span> <?php echo esc_html($q); ?>
                                <span class="qa-icon"></span>
                            </summary>
                            <div class="qa-a"><span class="qa-mark">A.</span> <p><?php echo nl2br(esc_html($a_text)); ?></p></div>
                        </details>
                    <?php 
                            endif;
                        endforeach;
                    endif;
                    
                    // Q&Aデータがない、またはパースに失敗した場合はダミーを表示
                    if (!$has_qa) :
                    ?>
                        <details class="qa-accordion">
                            <summary class="qa-q">
                                <span class="qa-mark">Q.</span> 配信で一番大切にしていることは？
                                <span class="qa-icon"></span>
                            </summary>
                            <div class="qa-a"><span class="qa-mark">A.</span> <p>来てくれたリスナーさんが「今日も楽しかった！」と笑顔で寝れるような、最高の時間を共有することです。常に全力で楽しむことをモットーにしています！</p></div>
                        </details>
                        <details class="qa-accordion">
                            <summary class="qa-q">
                                <span class="qa-mark">Q.</span> 今後の目標を教えてください！
                                <span class="qa-icon"></span>
                            </summary>
                            <div class="qa-a"><span class="qa-mark">A.</span> <p>イベントで1位を取るのはもちろんですが、もっと大きなステージに立って、みんなと一緒に新しい景色を見たいです！これからも応援よろしくお願いします✨</p></div>
                        </details>
                    <?php endif; ?>
                </div>
            </section>

            <!-- 雑誌風 入賞歴 セクション -->
            <section class="magazine-awards-section">
                <div class="magazine-qa-header">
                    <h2 class="magazine-qa-title">AWARDS</h2>
                    <p class="magazine-qa-subtitle">輝かしい功績と歩み</p>
                </div>
                
                <div class="magazine-awards-list">
                    <?php 
                    $liver_id = get_the_ID();
                    
                    // ランキング/イベント投稿からこのライバーが選ばれている記事を取得
                    $awards_query = new WP_Query(array(
                        'post_type' => 'event',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'OR',
                            array(
                                'key' => 'event_ranking_livers',
                                'value' => '"' . $liver_id . '"',
                                'compare' => 'LIKE'
                            ),
                            array(
                                'key' => 'diamond_ranking_livers',
                                'value' => '"' . $liver_id . '"',
                                'compare' => 'LIKE'
                            ),
                            array(
                                'key' => 'delivery_time_ranking_livers',
                                'value' => '"' . $liver_id . '"',
                                'compare' => 'LIKE'
                            ),
                            array(
                                'key' => 'delivery_time_ranking_liver',
                                'value' => '"' . $liver_id . '"',
                                'compare' => 'LIKE'
                            )
                        ),
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));

                    if ($awards_query->have_posts()) : 
                        while ($awards_query->have_posts()) : $awards_query->the_post(); 
                            $event_date = get_the_date('Y.m');
                    ?>
                        <div class="award-item">
                            <div class="award-date"><?php echo esc_html($event_date); ?></div>
                            <div class="award-name">
                                <a href="<?php the_permalink(); ?>" class="magazine-title-link">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                        </div>
                    <?php 
                        endwhile; 
                        wp_reset_postdata();
                    else : 
                        // 入賞歴（関連イベント）がない場合の雑誌風ダミーテキスト
                    ?>
                        <div class="award-item">
                            <div class="award-date">2023.12</div>
                            <div class="award-name">新人ライバーの祭典 グランプリ受賞🏆</div>
                        </div>
                        <div class="award-item">
                            <div class="award-date">2024.03</div>
                            <div class="award-name">春の大型イベント 総合1位達成✨</div>
                        </div>
                        <div class="award-item">
                            <div class="award-date">2024.05</div>
                            <div class="award-name">フォロワー1万人突破記念 殿堂入り！</div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>
            <div class="agency-action">
                <?php if ($account_url) : ?>
                    <a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener noreferrer" class="btn-agency-tiktok">
                        <span>TikTok Profile</span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="agency-back">
                <a href="<?php echo esc_url(get_post_type_archive_link('liver')); ?>" class="btn-agency-back">
                    &lt; RETURN TO TALENTS
                </a>
            </div>
        </main>
    </article>
</div>
<?php endwhile; endif; ?>
<?php get_template_part('template-parts/content/l-explore'); ?>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>