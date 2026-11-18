<?php get_header(); ?>

<?php
// トップページ共通で利用する所属ライバー5名を取得（HEROコラージュ＋スライダーで共有）
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
$featured_livers = [];

if ($liver_query->have_posts()) {
    $posts = $liver_query->posts;
    usort($posts, function ($a, $b) use ($target_accounts) {
        $account_a = get_post_meta($a->ID, 'creator_account', true);
        $account_b = get_post_meta($b->ID, 'creator_account', true);
        $pos_a = array_search($account_a, $target_accounts);
        $pos_b = array_search($account_b, $target_accounts);
        if ($pos_a === false) $pos_a = PHP_INT_MAX;
        if ($pos_b === false) $pos_b = PHP_INT_MAX;
        return $pos_a - $pos_b;
    });
    $featured_livers = $posts;
}
wp_reset_postdata();
?>

<div class="relative w-full min-h-[calc(100vh-76px)] flex items-center overflow-hidden bg-black z-10 py-14 md:py-20 before:content-[''] before:absolute before:inset-0 before:bg-[radial-gradient(circle_at_center,transparent_30%,rgba(0,0,0,0.55)_100%)] before:z-[1] before:pointer-events-none">
    <div id="particles-js" class="absolute inset-0 w-full h-full z-[1] pointer-events-auto"></div>

    <div class="inner relative z-[2] w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <!-- 左カラム：コピー＆CTA -->
            <div class="lg:col-span-7 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded border border-jol-cyan/35 bg-jol-cyan/10 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-jol-cyan shadow-[0_0_8px_#00FFFF]" aria-hidden="true"></span>
                    <span class="font-mono text-[0.7rem] tracking-[0.16em] uppercase text-jol-cyan">TIKTOK LIVE STREAMING AGENCY</span>
                </div>

                <h1 class="fv-title text-3xl sm:text-5xl lg:text-[3.4rem] font-light mb-6 tracking-[0.06em] leading-[1.35] text-white">
                    未来のスターが、<br class="hidden sm:inline">
                    <span class="font-extrabold bg-gradient-to-r from-jol-cyan via-white to-jol-purple bg-clip-text text-transparent">ここから生まれる。</span>
                </h1>

                <p class="text-base md:text-lg tracking-[0.06em] leading-8 text-white/75 mb-10 max-w-xl mx-auto lg:mx-0 font-light">
                    J.O.Lの仲間と共に『人生謳歌』しましょう。<br class="hidden sm:inline">
                    あなたの個性と挑戦を、プロフェッショナルな体制でサポートします。
                </p>

                <!-- CTAボタン -->
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start items-center gap-4">
                    <a href="<?php echo esc_url(home_url('/livers/')); ?>" class="group relative inline-flex items-center justify-center w-full sm:w-auto sm:min-w-[220px] px-7 py-4 rounded text-[0.95rem] font-semibold tracking-[1.2px] no-underline text-white bg-white/[0.04] box-border overflow-hidden transition-all duration-300 ease-editorial border border-jol-cyan/35 shadow-[0_4px_15px_rgba(0,0,0,0.25),0_0_12px_rgba(0,255,255,0.1)] hover:-translate-y-1 hover:bg-jol-cyan/10 hover:border-jol-cyan hover:shadow-[0_12px_30px_rgba(0,0,0,0.45),0_0_24px_rgba(0,255,255,0.4)] hover:text-white">
                        <span class="pointer-events-none absolute top-0 -left-[150%] w-[60%] h-full bg-gradient-to-r from-transparent via-white/15 to-transparent -skew-x-[25deg] z-[1] transition-all duration-700 group-hover:left-[200%]"></span>
                        <span>所属ライバーを見る</span>
                        <span class="ml-2.5 text-[1.1rem] transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact-creatore/')); ?>" class="group relative inline-flex items-center justify-center w-full sm:w-auto sm:min-w-[220px] px-7 py-4 rounded text-[0.95rem] font-semibold tracking-[1.2px] no-underline text-white bg-white/[0.04] box-border overflow-hidden transition-all duration-300 ease-editorial border border-jol-red/30 shadow-[0_4px_15px_rgba(0,0,0,0.25),0_0_12px_rgba(255,0,0,0.08)] hover:-translate-y-1 hover:bg-jol-red/10 hover:border-jol-red hover:shadow-[0_12px_30px_rgba(0,0,0,0.45),0_0_24px_rgba(255,0,0,0.35)] hover:text-white">
                        <span class="pointer-events-none absolute top-0 -left-[150%] w-[60%] h-full bg-gradient-to-r from-transparent via-white/15 to-transparent -skew-x-[25deg] z-[1] transition-all duration-700 group-hover:left-[200%]"></span>
                        <span>事務所に応募する</span>
                        <span class="ml-2.5 text-[1.1rem] transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                    </a>
                </div>

                <!-- HERO 公式LINE応募カード（QRコード＋ボタン） -->
                <div class="mt-7 max-w-lg mx-auto lg:mx-0 rounded-lg border border-[#06C755]/45 bg-[#08160e]/85 backdrop-blur-md p-4 sm:p-5 shadow-[0_12px_32px_rgba(0,0,0,0.5),0_0_24px_rgba(6,199,85,0.12)]">
                    <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5">
                        <!-- PC用QRコード -->
                        <div class="hidden sm:flex shrink-0 flex-col items-center">
                            <div class="w-24 h-24 rounded-lg bg-white p-1.5 shadow-md">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/contact/line-qr.webp'); ?>"
                                     alt="J.O.L 公式LINE QRコード"
                                     class="w-full h-full object-contain">
                            </div>
                        </div>

                        <!-- テキスト＆友だち追加ボタン -->
                        <div class="flex-1 text-center sm:text-left">
                            <div class="inline-flex items-center gap-2 mb-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#06C755] shadow-[0_0_8px_#06C755]" aria-hidden="true"></span>
                                <span class="text-xs font-bold tracking-wider text-[#06C755]">公式LINE 24時間応募受付中</span>
                            </div>
                            <p class="text-xs sm:text-sm font-bold text-white leading-snug mb-3">
                                スマホでQRを読み取るか、ボタンからすぐに応募・相談できます
                            </p>
                            <a href="https://line.me/R/ti/p/%40840eqwou"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center gap-2.5 w-full sm:w-auto px-5 py-2.5 rounded bg-[#06C755] hover:bg-[#05b34c] text-white font-bold text-sm tracking-wide no-underline transition-all duration-200 ease-editorial hover:-translate-y-0.5 shadow-[0_4px_14px_rgba(6,199,85,0.35)]">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63h2.386c.349 0 .63.285.63.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63.349 0 .631.285.631.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                                <span>公式LINEで友だち追加</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 右カラム：所属ライバー 2x2 コラージュ -->
            <div class="lg:col-span-5">
                <div class="grid grid-cols-2 gap-4 sm:gap-5 max-w-[440px] mx-auto lg:ml-auto pb-6">
                    <?php
                    $hero_livers = array_slice($featured_livers, 0, 4);
                    if (!empty($hero_livers)) :
                        foreach ($hero_livers as $idx => $h_post) :
                            $h_name  = get_the_title($h_post->ID) ?: get_post_meta($h_post->ID, 'creator_name', true);
                            $h_thumb = has_post_thumbnail($h_post->ID) ? get_the_post_thumbnail_url($h_post->ID, 'medium_large') : COMMON_LIVER_THUMBNAIL_URL;
                            $h_url   = get_permalink($h_post->ID);
                            $is_even = ($idx % 2 === 1);
                            $border_hover = $is_even ? 'hover:border-jol-purple/70' : 'hover:border-jol-cyan/70';
                    ?>
                        <a href="<?php echo esc_url($h_url); ?>" class="group relative aspect-square rounded-lg overflow-hidden border border-white/15 bg-jol-surface no-underline transition-all duration-300 ease-editorial hover:-translate-y-1 <?php echo esc_attr($border_hover); ?> <?php echo $is_even ? 'translate-y-4 sm:translate-y-6' : ''; ?>">
                            <img src="<?php echo esc_url($h_thumb); ?>"
                                alt="<?php echo esc_attr($h_name); ?>"
                                class="w-full h-full object-cover transition-transform duration-500 ease-editorial group-hover:scale-105">
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/80 via-black/15 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-300"></div>
                            <div class="absolute inset-x-0 bottom-0 p-3 z-10">
                                <p class="text-xs sm:text-sm font-bold text-white truncate m-0 group-hover:text-jol-cyan transition-colors duration-200"><?php echo esc_html($h_name); ?></p>
                            </div>
                        </a>
                    <?php
                        endforeach;
                    else :
                        for ($i = 0; $i < 4; $i++) :
                            $is_even = ($i % 2 === 1);
                    ?>
                        <div class="relative aspect-square rounded-lg overflow-hidden border border-white/10 bg-jol-surface <?php echo $is_even ? 'translate-y-4 sm:translate-y-6' : ''; ?>">
                            <img src="<?php echo esc_url(COMMON_LIVER_THUMBNAIL_URL); ?>" alt="J.O.L LIVER" class="w-full h-full object-cover opacity-60">
                        </div>
                    <?php
                        endfor;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- スクロールインジケーター -->
    <div class="fv-scroll hidden md:flex absolute bottom-6 left-1/2 -translate-x-1/2 flex-col items-center z-[2] pointer-events-none" aria-hidden="true">
        <span class="text-[0.7rem] font-semibold text-white/40 tracking-[5px] mb-2 uppercase">SCROLL</span>
        <span class="fv-scroll-line relative w-[2px] h-[44px] bg-white/[0.08] overflow-hidden"></span>
    </div>
</div>
<section class="relative overflow-hidden py-24 md:py-32 border-b border-jol-cyan/20">
    <!-- アンビエントグロー＆微細グリッド（背景画像はサイト全体から透過） -->
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -top-24 -left-24 w-[480px] h-[480px] rounded-full bg-jol-cyan/[0.08] blur-[120px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[560px] h-[360px] rounded-full bg-jol-purple/[0.1] blur-[140px]"></div>
        <div class="absolute -bottom-24 -right-24 w-[460px] h-[460px] rounded-full bg-jol-red/[0.07] blur-[120px]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.035)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.035)_1px,transparent_1px)] bg-[size:48px_48px]" style="mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, #000 40%, transparent 100%); -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, #000 40%, transparent 100%);"></div>
    </div>

    <div class="inner relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12 md:mb-16">
            <div>
                <div class="inline-flex items-center gap-2.5 mb-4">
                    <span class="w-8 h-[2px] bg-gradient-to-r from-jol-cyan to-jol-purple"></span>
                    <span class="text-xs font-bold tracking-[0.2em] text-jol-cyan">J.O.Lのサポート体制</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black tracking-tight text-white leading-[1.2]">
                    ライバー活動と成功を<br>サポートします
                </h2>
            </div>
            <p class="text-sm md:text-base text-white/70 max-w-md leading-relaxed">
                配信の始め方からリスク管理、企業案件の獲得まで。クリエイターが配信そのものに集中できる環境をJ.O.Lが全面的にバックアップします。
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
            <!-- 01 -->
            <article class="group relative rounded-lg bg-gradient-to-br from-[#17181f]/95 via-jol-surface/95 to-[#101116]/95 backdrop-blur-md border border-white/10 hover:border-jol-cyan/50 p-7 md:p-9 overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1">
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-jol-cyan via-jol-cyan/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="pointer-events-none absolute -top-12 -right-12 w-36 h-36 rounded-full bg-jol-cyan/[0.08] blur-2xl group-hover:bg-jol-cyan/[0.15] transition-colors duration-300" aria-hidden="true"></div>
                <div class="relative flex items-center justify-between gap-4 mb-6">
                    <div class="w-12 h-12 rounded-lg bg-jol-cyan/10 border border-jol-cyan/30 flex items-center justify-center text-jol-cyan group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 17l6-6 4 4 8-8"></path>
                            <path d="M17 7h4v4"></path>
                        </svg>
                    </div>
                    <span class="font-mono text-2xl md:text-3xl font-black tracking-tight text-jol-cyan/80 group-hover:text-jol-cyan transition-colors">01</span>
                </div>
                <h3 class="relative text-lg md:text-xl font-bold text-white mb-3.5 tracking-tight">プロのサポートで成長を加速</h3>
                <p class="relative text-sm md:text-[15px] text-white/75 leading-[1.85]">
                    個人で配信を始める場合、何をどうすればいいか分からず、手探りでの活動になりがちです。しかし、事務所に所属すれば、ライブ配信やコンテンツ制作のプロがあなたの成長をサポートしてくれます。
                </p>
            </article>

            <!-- 02 -->
            <article class="group relative rounded-lg bg-gradient-to-br from-[#191620]/95 via-jol-surface/95 to-[#121017]/95 backdrop-blur-md border border-white/10 hover:border-jol-purple/55 p-7 md:p-9 overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1">
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-jol-purple via-jol-purple/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="pointer-events-none absolute -top-12 -right-12 w-36 h-36 rounded-full bg-jol-purple/[0.1] blur-2xl group-hover:bg-jol-purple/[0.18] transition-colors duration-300" aria-hidden="true"></div>
                <div class="relative flex items-center justify-between gap-4 mb-6">
                    <div class="w-12 h-12 rounded-lg bg-jol-purple/15 border border-jol-purple/35 flex items-center justify-center text-jol-purple group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3l7 3v6c0 4.5-3 8.2-7 9.5-4-1.3-7-5-7-9.5V6l7-3z"></path>
                            <path d="M9.5 12l1.8 1.8 3.7-3.8"></path>
                        </svg>
                    </div>
                    <span class="font-mono text-2xl md:text-3xl font-black tracking-tight text-jol-purple/80 group-hover:text-jol-purple transition-colors">02</span>
                </div>
                <h3 class="relative text-lg md:text-xl font-bold text-white mb-3.5 tracking-tight">安心できる活動環境</h3>
                <p class="relative text-sm md:text-[15px] text-white/75 leading-[1.85]">
                    ライブ配信は、時に誹謗中傷や予期せぬトラブルに巻き込まれるリスクがあります。個人で全てを対応するのは精神的にも大きな負担です。事務所に所属すれば、トラブル発生時の対応を任せることができ、著作権や肖像権といった専門的な知識もサポートしてもらえるため、安心してクリエイティブな活動に集中できます。
                </p>
            </article>

            <!-- 03 -->
            <article class="group relative rounded-lg bg-gradient-to-br from-[#1c1518]/95 via-jol-surface/95 to-[#141013]/95 backdrop-blur-md border border-white/10 hover:border-jol-red/50 p-7 md:p-9 overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1">
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-jol-red via-jol-red/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="pointer-events-none absolute -top-12 -right-12 w-36 h-36 rounded-full bg-jol-red/[0.08] blur-2xl group-hover:bg-jol-red/[0.15] transition-colors duration-300" aria-hidden="true"></div>
                <div class="relative flex items-center justify-between gap-4 mb-6">
                    <div class="w-12 h-12 rounded-lg bg-jol-red/10 border border-jol-red/30 flex items-center justify-center text-jol-red group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="7" r="3"></circle>
                            <path d="M3 20v-1a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1"></path>
                            <circle cx="17.5" cy="8.5" r="2.5"></circle>
                            <path d="M17.5 14a3.5 3.5 0 0 1 3.5 3.5V20"></path>
                        </svg>
                    </div>
                    <span class="font-mono text-2xl md:text-3xl font-black tracking-tight text-jol-red/80 group-hover:text-jol-red transition-colors">03</span>
                </div>
                <h3 class="relative text-lg md:text-xl font-bold text-white mb-3.5 tracking-tight">広がるチャンスと人脈</h3>
                <p class="relative text-sm md:text-[15px] text-white/75 leading-[1.85]">
                    事務所は、プラットフォーム運営や企業との間に独自のネットワークを持っています。これにより、個人では獲得が難しい企業案件や、特別なイベントへの出演機会が生まれます。また、同じ事務所に所属する仲間と交流することで、コラボ配信を通じて互いのファン層を広げたり、悩みを共有し高め合ったりすることができます。
                </p>
            </article>

            <!-- 04 -->
            <article class="group relative rounded-lg bg-gradient-to-br from-[#161820]/95 via-jol-surface/95 to-[#101218]/95 backdrop-blur-md border border-white/10 hover:border-jol-cyan/50 p-7 md:p-9 overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1">
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-jol-cyan via-jol-purple/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="pointer-events-none absolute -top-12 -right-12 w-36 h-36 rounded-full bg-jol-cyan/[0.08] blur-2xl group-hover:bg-jol-cyan/[0.15] transition-colors duration-300" aria-hidden="true"></div>
                <div class="relative flex items-center justify-between gap-4 mb-6">
                    <div class="w-12 h-12 rounded-lg bg-jol-cyan/10 border border-jol-cyan/30 flex items-center justify-center text-jol-cyan group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="font-mono text-2xl md:text-3xl font-black tracking-tight text-jol-cyan/80 group-hover:text-jol-cyan transition-colors">04</span>
                </div>
                <h3 class="relative text-lg md:text-xl font-bold text-white mb-3.5 tracking-tight">活動への集中と効率化</h3>
                <p class="relative text-sm md:text-[15px] text-white/75 leading-[1.85]">
                    動画編集やスケジュール管理、収益の税務処理など、配信以外にもやるべきことはたくさんあります。これらすべてを一人でこなすのは大変な作業です。事務所がこれらの事務的な作業やマネジメントを代行してくれることで、あなたは「配信する」という本来の活動に集中できます。
                </p>
            </article>
        </div>
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
                // HEROセクションで取得済みのライバー5名（$featured_livers）を再利用
                if (!empty($featured_livers)) :
                    foreach ($featured_livers as $post) :
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

<section class="relative overflow-hidden py-24 md:py-32 border-b border-jol-cyan/20">
    <div class="inner relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 md:mb-12">
            <div>
                <div class="inline-flex items-center gap-2.5 mb-4">
                    <span class="w-8 h-[2px] bg-gradient-to-r from-jol-cyan to-jol-purple"></span>
                    <span class="text-xs font-bold tracking-[0.2em] text-jol-cyan">最新情報</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black tracking-tight text-white leading-[1.2]">
                    イベント・ニュース
                </h2>
            </div>
            <a href="<?php echo esc_url(get_post_type_archive_link('event') ?: home_url('/events')); ?>"
               class="inline-flex items-center gap-2.5 self-start md:self-auto px-5 py-2.5 rounded border border-jol-cyan/35 bg-jol-cyan/[0.06] text-sm font-bold text-jol-cyan hover:bg-jol-cyan/15 hover:border-jol-cyan/60 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                <span>一覧を見る</span>
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="M13 6l6 6-6 6"></path>
                </svg>
            </a>
        </div>

        <?php if ($event_query->have_posts()) : ?>
            <div class="flex flex-col gap-4">
                <?php while ($event_query->have_posts()) : $event_query->the_post();
                    $terms = get_the_terms(get_the_ID(), 'event_category');
                    $raw_excerpt = strip_tags(get_the_excerpt());
                    $excerpt = mb_substr($raw_excerpt, 0, 100);
                    if (mb_strlen($raw_excerpt) > 100) {
                        $excerpt .= '...';
                    }
                ?>
                    <a href="<?php the_permalink(); ?>"
                       class="group relative flex flex-col md:flex-row md:items-center gap-4 md:gap-7 rounded-lg bg-jol-surface/85 backdrop-blur-md border border-white/10 hover:border-jol-cyan/45 p-5 md:p-7 overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-0.5">
                        <!-- ホバー時の柔らかな背景ハイライト -->
                        <span class="pointer-events-none absolute inset-0 bg-gradient-to-r from-jol-cyan/[0.06] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></span>

                        <!-- 日付カラム -->
                        <div class="flex md:flex-col items-baseline md:items-start gap-2 md:gap-0.5 md:w-32 shrink-0 md:border-r md:border-white/10 md:pr-6">
                            <span class="font-mono text-xs font-medium tracking-wider text-white/45">
                                <?php echo esc_html(get_the_date('Y')); ?>
                            </span>
                            <span class="font-mono text-lg md:text-2xl font-black tracking-tight text-jol-cyan">
                                <?php echo esc_html(get_the_date('m.d')); ?>
                            </span>
                        </div>

                        <!-- コンテンツカラム -->
                        <div class="flex-1 min-w-0">
                            <?php if ($terms && !is_wp_error($terms)) : ?>
                                <div class="flex flex-wrap items-center gap-2 mb-2.5">
                                    <?php foreach ($terms as $term) :
                                        $badge_classes = 'bg-white/5 text-white/80 border-white/15';
                                        if ($term->slug === 'event' || $term->name === 'イベント') {
                                            $badge_classes = 'bg-jol-cyan/10 text-jol-cyan border-jol-cyan/30';
                                        } elseif ($term->slug === 'battle' || $term->name === 'ガチバトル') {
                                            $badge_classes = 'bg-jol-red/10 text-red-400 border-jol-red/35';
                                        } elseif ($term->slug === 'news' || $term->name === 'ライバーニュース') {
                                            $badge_classes = 'bg-jol-purple/15 text-purple-300 border-jol-purple/40';
                                        }
                                    ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold border <?php echo esc_attr($badge_classes); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h3 class="text-base md:text-xl font-bold text-white group-hover:text-jol-cyan transition-colors duration-200 leading-snug mb-1.5">
                                <?php the_title(); ?>
                            </h3>

                            <?php if ($excerpt !== '') : ?>
                                <p class="text-sm text-white/65 leading-relaxed line-clamp-2">
                                    <?php echo esc_html($excerpt); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- 右端矢印ボタン -->
                        <div class="hidden md:flex items-center justify-center w-10 h-10 rounded border border-white/10 bg-white/[0.03] text-white/50 group-hover:border-jol-cyan/45 group-hover:bg-jol-cyan/10 group-hover:text-jol-cyan shrink-0 transition-all duration-300">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="M13 6l6 6-6 6"></path>
                            </svg>
                        </div>
                    </a>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="rounded-lg bg-jol-surface/80 border border-white/10 p-8 text-center text-sm text-white/60">
                現在、予定されているイベント・ニュースはありません。
            </div>
        <?php endif; ?>
    </div>
</section>


<?php get_template_part('template-parts/content/l-contact'); ?>




<?php get_footer(); ?>