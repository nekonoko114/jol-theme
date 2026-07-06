<!-- ハンバーガーメニューとドロワーナビゲーション -->
<div class="hamburger-icon" id="hamburger-icon">
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="hamburger-menu" id="hamburger-menu">
    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'drawer',
            'menu_class' => 'hamburger-nav',
            'menu_id' => 'hamburger-nav',
            'container' => 'nav',
            'depth' => 0,
        )
    );
    ?>
</div>
