<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
    $subtitle = function_exists('get_field') ? get_field('subtitle') : get_post_meta(get_the_ID(), 'subtitle', true);
    $subtitle = $subtitle ?: 'ABOUT US';
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
        <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-jol-cyan/10 blur-[120px] z-0"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-jol-purple/15 blur-[120px] z-0"></div>

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
                    <div class="w-16 h-[2px] bg-gradient-to-r from-jol-cyan to-jol-purple"></div>
                </div>

                <?php if ($thumbnail_url) : ?>
                    <div class="lg:col-span-5">
                        <div class="relative max-w-[460px] lg:ml-auto mr-2 mb-2">
                            <div class="pointer-events-none absolute inset-0 translate-x-2 translate-y-2 rounded-lg border border-jol-cyan/40 bg-jol-cyan/10" aria-hidden="true"></div>
                            <div class="relative z-10 aspect-[16/10] rounded-lg overflow-hidden border border-white/15 bg-jol-surface">
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- 本文エリア -->
    <main class="inner py-16 md:py-24">
        <div class="about-editorial-content">
            <?php the_content(); ?>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.about-editorial-content img').forEach(function (img) {
        img.addEventListener('error', function () {
            img.style.display = 'none';
        });
        if (img.complete && img.naturalWidth === 0) {
            img.style.display = 'none';
        }
    });
});
</script>

<?php endwhile; endif; ?>

<?php get_template_part('template-parts/content/l-explore'); ?>
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>
