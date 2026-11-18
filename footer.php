<footer class="p-10 border-t border-jol-red">
    <div class="inner">
        <div>
            <div class="mb-5 border-b border-jol-text py-5 md:py-16 text-center md:text-left">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?><span class="text-[0.85rem] block md:inline md:ml-24">ライバー</span><span class="text-[0.85rem] block md:inline md:ml-4">楽しく</span><span class="text-[0.85rem] block md:inline md:ml-4">配信するなら</span></a>

            </div>
            <div class="hidden lg:block">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_class' => 'flex gap-5 list-none m-0 py-10 justify-around',
                        'menu_id' => 'footer-menu',
                        'container' => 'ul',
                        'depth' => 0,
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="text-sm text-center">© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</div>
</footer>

<?php if ( ! is_singular('ranking') && ! is_post_type_archive('ranking') ) : ?>
    <?php if (get_post_type_archive_link('ranking')) : ?>
        <a href="<?php echo esc_url(get_post_type_archive_link('ranking')); ?>" class="hidden lg:inline-flex items-center gap-3 fixed bottom-10 right-10 z-[9999] px-6 py-4 bg-[#12121c]/65 border border-white/10 rounded backdrop-blur-md shadow-[0_10px_30px_rgba(0,0,0,0.5)] no-underline transition-all duration-300 ease-editorial hover:-translate-y-1.5 hover:border-jol-cyan hover:bg-[#19142d]/85 hover:shadow-[0_15px_35px_rgba(0,255,255,0.25),0_5px_15px_rgba(0,0,0,0.4)] group">
            <span class="flex items-center justify-center text-[1.2rem] text-jol-cyan transition-transform duration-300 ease-editorial group-hover:scale-115 group-hover:rotate-12 group-hover:text-white"><i class="fas fa-trophy"></i></span>
            <span class="font-display text-[0.85rem] font-medium text-[#f5f5f5] tracking-[0.15em] whitespace-nowrap">RANKING ARCHIVES</span>
        </a>
    <?php endif; ?>
<?php endif; ?>

</div><!-- #container end -->

<?php wp_footer(); ?>
</body>

</html>