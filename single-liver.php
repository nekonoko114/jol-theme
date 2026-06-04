<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    $creator_name = get_post_meta(get_the_ID(), 'creator_name', true);
    $delivery = get_post_meta(get_the_ID(), 'delivery', true);
    $delivery_time = get_post_meta(get_the_ID(), 'delivery_time', true);
    $creator_account = get_post_meta(get_the_ID(), 'creator_account', true);
    $account_url = get_post_meta(get_the_ID(), 'account_url', true);
    $gift = get_post_meta(get_the_ID(), 'gift', true);

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
                    <?php if (function_exists('bcn_display')) bcn_display(); ?>
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
                        <p class="agency-subtitle">TALENT</p>
                        <h1 class="agency-title"><?php the_title(); ?></h1>
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
                        <?php the_content(); ?>
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
                </section>
            </div>
            
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
<?php get_template_part('template-parts/content/l-contact'); ?>
<?php get_footer(); ?>