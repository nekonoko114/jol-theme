<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo(); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- ローディングアニメーション -->
    <div id="splash">
        <div id="splash-logo">
            <?php get_template_part('template-parts/header/splash-logo'); ?>
        </div>
    </div>
    <div class="splashbg"></div>

    <!-- メインコンテナ -->
    <div id="container">
        <!-- 背景幾何学模様SVGデコレーション -->
        <?php get_template_part('template-parts/header/geometric-decorations'); ?>

        <!-- スマホ用ハンバーガーメニュー・ドロワーナビゲーション -->
        <?php get_template_part('template-parts/header/navigation-drawer'); ?>

        <header class="header">
            <div class="header-inner inner">
                <div class="header-logo">
                    <a class="header-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                </div>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'header',
                        'menu_class' => 'header-nav',
                        'menu_id' => 'header-nav',
                        'container' => 'nav',
                        'depth' => 0,
                    )
                );
                ?>
            </div>
        </header>