<?php
/**
 * 主要コンテンツへの相互リンクカードセクション（EXPLORE）
 * 現在閲覧中のページを自動除外して1カラムのサムネイル付き横長カードで表示する
 */

$current_key = '';
if (is_page('about')) {
    $current_key = 'about';
} elseif (is_post_type_archive('liver') || is_singular('liver')) {
    $current_key = 'liver';
} elseif (is_post_type_archive('event') || is_singular('event')) {
    $current_key = 'event';
} elseif (is_post_type_archive('ranking') || is_singular('ranking') || is_page_template('page-ranking.php')) {
    $current_key = 'ranking';
}

$theme_uri = get_template_directory_uri();

$explore_items = array(
    'about' => array(
        'en'           => 'ABOUT US',
        'title'        => 'J.O.Lについて',
        'desc'         => '事務所の理念や所属メリット、充実のサポート体制をご紹介します。',
        'url'          => home_url('/about/'),
        'thumb'        => $theme_uri . '/assets/images/explore/explore-about.webp',
        'accent_text'  => 'group-hover:text-jol-cyan',
        'hover_border' => 'hover:border-jol-cyan/60',
        'bar_color'    => 'bg-jol-cyan',
    ),
    'liver' => array(
        'en'           => 'TALENTS',
        'title'        => '所属ライバー',
        'desc'         => 'TikTokで活躍するJ.O.L所属の個性豊かなライバー一覧です。',
        'url'          => get_post_type_archive_link('liver') ?: home_url('/livers/'),
        'thumb'        => $theme_uri . '/assets/images/explore/explore-livers.webp',
        'accent_text'  => 'group-hover:text-purple-300',
        'hover_border' => 'hover:border-jol-purple/60',
        'bar_color'    => 'bg-jol-purple',
    ),
    'event' => array(
        'en'           => 'EVENTS & NEWS',
        'title'        => 'イベント一覧',
        'desc'         => '開催イベントやガチバトル結果、最新ニュースをお届けします。',
        'url'          => get_post_type_archive_link('event') ?: home_url('/events/'),
        'thumb'        => $theme_uri . '/assets/images/explore/explore-events.webp',
        'accent_text'  => 'group-hover:text-red-400',
        'hover_border' => 'hover:border-jol-red/60',
        'bar_color'    => 'bg-jol-red',
    ),
    'ranking' => array(
        'en'           => 'RANKING ARCHIVES',
        'title'        => '過去のランキング',
        'desc'         => '各月のイベント・ダイヤモンド・配信時間等の入賞実績をご覧いただけます。',
        'url'          => get_post_type_archive_link('ranking') ?: home_url('/rankings/'),
        'thumb'        => $theme_uri . '/assets/images/ranking/archive-trophy-cover.webp',
        'accent_text'  => 'group-hover:text-jol-cyan',
        'hover_border' => 'hover:border-jol-cyan/60',
        'bar_color'    => 'bg-jol-cyan',
    ),
);

if ($current_key && isset($explore_items[$current_key])) {
    unset($explore_items[$current_key]);
}
?>

<section class="relative border-t border-white/10 py-16 md:py-24 bg-black">
    <div class="inner">
        <div class="mb-8">
            <p class="font-mono text-xs tracking-[0.2em] uppercase text-jol-cyan mb-2">EXPLORE</p>
            <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight m-0">他のコンテンツを見る</h2>
        </div>

        <div class="flex flex-col gap-4">
            <?php foreach ($explore_items as $item) : ?>
                <a href="<?php echo esc_url($item['url']); ?>" class="group relative flex flex-col md:flex-row md:items-center justify-between gap-5 p-4 md:p-5 rounded-lg bg-jol-surface/90 border border-white/10 no-underline text-inherit transition-all duration-300 ease-editorial hover:translate-x-1 hover:bg-white/[0.04] <?php echo esc_attr($item['hover_border']); ?>">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 md:gap-6 min-w-0 flex-1">
                        <div class="shrink-0 w-full sm:w-[170px] md:w-[200px] aspect-video rounded overflow-hidden border border-white/15 bg-black">
                            <img src="<?php echo esc_url($item['thumb']); ?>"
                                alt="<?php echo esc_attr($item['title']); ?>"
                                class="w-full h-full object-cover brightness-110 transition-transform duration-500 ease-editorial group-hover:scale-105">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="w-1.5 h-1.5 rounded-full <?php echo esc_attr($item['bar_color']); ?>" aria-hidden="true"></span>
                                <span class="font-mono text-[0.7rem] tracking-[0.12em] uppercase text-white/55"><?php echo esc_html($item['en']); ?></span>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-white tracking-tight mb-1 transition-colors duration-200 <?php echo esc_attr($item['accent_text']); ?>">
                                <?php echo esc_html($item['title']); ?>
                            </h3>
                            <p class="text-xs md:text-sm text-white/60 leading-6 m-0">
                                <?php echo esc_html($item['desc']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="shrink-0 inline-flex items-center gap-2 text-xs font-mono tracking-[0.08em] uppercase text-white/55 transition-colors duration-200 <?php echo esc_attr($item['accent_text']); ?> md:pr-2">
                        <span>VIEW PAGE</span>
                        <span class="transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
