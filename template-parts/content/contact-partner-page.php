<?php
/**
 * 企業・パートナー様向けお問い合わせページ 共通テンプレート
 */
get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        $subtitle      = function_exists('get_field') ? get_field('subtitle') : get_post_meta(get_the_ID(), 'subtitle', true);
        $subtitle      = $subtitle ?: '法人・パートナー様のお問い合わせ';
        $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
?>

<div class="text-jol-text">
    <!-- ヒーローヘッダー -->
    <section class="relative overflow-hidden border-b border-white/10 pt-10 pb-14 md:pt-14 md:pb-20">
        <?php if ($thumbnail_url) : ?>
            <div class="pointer-events-none absolute inset-0 z-0 opacity-15">
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="" class="w-full h-full object-cover blur-2xl scale-110">
            </div>
        <?php endif; ?>
        <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-jol-purple/15 blur-[120px] z-0" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-jol-cyan/10 blur-[120px] z-0" aria-hidden="true"></div>

        <div class="inner relative z-10">
            <div class="breadcrumbs mb-8" typeof="BreadcrumbList" vocab="https://schema.org/">
                <?php if (function_exists('jol_breadcrumbs')) {
                    jol_breadcrumbs();
                } ?>
            </div>

            <div class="grid grid-cols-1 <?php echo $thumbnail_url ? 'lg:grid-cols-12' : ''; ?> gap-8 md:gap-12 items-center">
                <div class="<?php echo $thumbnail_url ? 'lg:col-span-7' : 'max-w-3xl'; ?>">
                    <div class="inline-flex items-center gap-2.5 mb-3">
                        <span class="w-7 h-[2px] bg-gradient-to-r from-jol-cyan to-jol-purple"></span>
                        <p class="text-xs md:text-sm font-bold tracking-[0.18em] text-jol-cyan">
                            <?php echo esc_html($subtitle); ?>
                        </p>
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white leading-tight mb-4">
                        <?php the_title(); ?>
                    </h1>
                    <p class="text-sm md:text-base text-white/75 leading-relaxed">
                        タイアップ案件、キャスティング、代理店連携、取材等のご相談は下記フォームよりお気軽にお問い合わせください。担当者より折り返しご連絡いたします。
                    </p>
                </div>

                <?php if ($thumbnail_url) : ?>
                    <div class="lg:col-span-5">
                        <div class="relative max-w-[460px] lg:ml-auto mr-2 mb-2">
                            <div class="pointer-events-none absolute inset-0 translate-x-2 translate-y-2 rounded-lg border border-jol-purple/40 bg-jol-purple/10" aria-hidden="true"></div>
                            <div class="relative z-10 aspect-[16/10] rounded-lg overflow-hidden border border-white/15 bg-jol-surface">
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- フォーム・本文エリア -->
    <main class="inner pt-10 md:pt-16 pb-24 md:pb-32">
        <div class="contact-partner-content max-w-3xl mx-auto">
            <?php the_content(); ?>
        </div>
    </main>
</div>

<?php
    endwhile;
endif;

get_footer();
