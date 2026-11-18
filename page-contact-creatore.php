<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
    $subtitle = function_exists('get_field') ? get_field('subtitle') : get_post_meta(get_the_ID(), 'subtitle', true);
    $subtitle = $subtitle ?: 'AUDITION & ENTRY';
    $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
?>

<div class="bg-black text-jol-text">
    <!-- 洗練されたヒーローヘッダー -->
    <section class="relative overflow-hidden border-b border-white/10 pt-10 pb-16 md:pt-14 md:pb-24">
        <?php if ($thumbnail_url) : ?>
            <div class="pointer-events-none absolute inset-0 z-0 opacity-15">
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="" class="w-full h-full object-cover blur-2xl scale-110">
            </div>
        <?php endif; ?>
        <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-jol-red/10 blur-[120px] z-0"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-jol-cyan/10 blur-[120px] z-0"></div>

        <div class="inner relative z-10">
            <div class="breadcrumbs mb-8" typeof="BreadcrumbList" vocab="https://schema.org/">
                <?php if (function_exists('jol_breadcrumbs')) {
                    jol_breadcrumbs();
                } ?>
            </div>

            <div class="grid grid-cols-1 <?php echo $thumbnail_url ? 'lg:grid-cols-12' : ''; ?> gap-8 md:gap-12 items-center">
                <div class="<?php echo $thumbnail_url ? 'lg:col-span-7' : 'max-w-3xl'; ?>">
                    <p class="font-mono text-xs md:text-sm tracking-[0.2em] uppercase text-jol-cyan mb-3">
                        <?php echo esc_html($subtitle); ?>
                    </p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white leading-tight mb-4">
                        <?php the_title(); ?>
                    </h1>
                    <div class="w-16 h-[2px] bg-gradient-to-r from-jol-red to-jol-cyan"></div>
                </div>

                <?php if ($thumbnail_url) : ?>
                    <div class="lg:col-span-5">
                        <div class="relative max-w-[460px] lg:ml-auto mr-2 mb-2">
                            <div class="pointer-events-none absolute inset-0 translate-x-2 translate-y-2 rounded-lg border border-jol-red/40 bg-jol-red/10" aria-hidden="true"></div>
                            <div class="relative z-10 aspect-[16/10] rounded-lg overflow-hidden border border-white/15 bg-jol-surface">
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- フロー＆LINE応募エリア -->
    <main class="inner pt-12 md:pt-20 pb-24 md:pb-32">
        <div class="contact-creatore-content">
            <?php the_content(); ?>
        </div>

        <!-- LINE公式アカウント応募カード -->
        <section class="mt-8 md:mt-12 max-w-4xl mx-auto">
            <div class="relative rounded-lg bg-jol-surface border border-white/15 p-6 sm:p-10 md:p-12 overflow-hidden">
                <div class="pointer-events-none absolute -top-24 -right-24 w-64 h-64 rounded-full bg-[#06C755]/10 blur-[90px]" aria-hidden="true"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 md:gap-12">
                    <div class="flex-1 text-center md:text-left">
                        <div class="inline-flex items-center gap-2 mb-3">
                            <span class="w-2 h-2 rounded-full bg-[#06C755]" aria-hidden="true"></span>
                            <span class="font-mono text-xs tracking-[0.16em] uppercase text-[#06C755]">OFFICIAL LINE ENTRY</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mb-4">
                            公式LINEから簡単エントリー
                        </h2>
                        <p class="text-sm md:text-base text-white/70 leading-7 mb-8">
                            ライバー応募・ご相談は J.O.L 公式 LINE にて受け付けております。<br class="hidden sm:inline">
                            友だち追加後、案内に沿ってメッセージをお送りください。
                        </p>

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <a href="https://line.me/R/ti/p/%40840eqwou" target="_blank" rel="noopener noreferrer" class="group inline-flex items-center justify-center gap-3 w-full sm:w-auto px-8 py-4 rounded bg-[#06C755] text-white font-bold text-base tracking-wide no-underline transition-all duration-200 ease-editorial hover:bg-[#05b34c] hover:-translate-y-0.5">
                                <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63h2.386c.349 0 .63.285.63.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63.349 0 .631.285.631.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                                <span>LINEで応募・相談する</span>
                                <span class="transition-transform duration-200 group-hover:translate-x-1">→</span>
                            </a>
                            <span class="font-mono text-xs text-white/50">ID: @840eqwou</span>
                        </div>
                    </div>

                    <div class="shrink-0 flex flex-col items-center p-4 rounded-lg bg-white/[0.03] border border-white/10">
                        <div class="p-2 bg-white rounded">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/contact/line-qr.webp'); ?>"
                                alt="J.O.L 公式LINE QRコード"
                                class="w-36 h-36 object-contain block">
                        </div>
                        <span class="mt-3 font-mono text-[0.68rem] tracking-[0.12em] uppercase text-white/50">SCAN QR CODE</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<?php endwhile; endif; ?>

<?php get_template_part('template-parts/content/l-explore'); ?>
<?php get_footer(); ?>
