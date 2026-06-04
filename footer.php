<footer class="footer">
    <div class="footer-inner inner">
        <div class="footer-container">
            <div class="footer-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?><span class="footerfirst-copy">ライバー</span><span>楽しく</span><span>配信するなら</span></a>

            </div>
            <div class="footer-wrapper">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu',
                        'menu_id' => 'footer-menu',
                        'container' => 'ul',
                        'depth' => 0,
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="footer-copy">© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</div>
</footer>

<?php if ( ! is_singular('ranking') && ! is_post_type_archive('ranking') ) : ?>
    <?php if (get_post_type_archive_link('ranking')) : ?>
        <a href="<?php echo esc_url(get_post_type_archive_link('ranking')); ?>" class="floating-ranking-btn">
            <span class="btn-icon"><i class="fas fa-trophy"></i></span>
            <span class="btn-text">RANKING ARCHIVES</span>
        </a>
    <?php endif; ?>
<?php endif; ?>

</div><!-- #container end -->

<?php wp_footer(); ?>
</body>

</html>