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
    <div id="splash" class="fixed inset-0 w-full h-full bg-black z-[9999999] text-center text-white">
        <div id="splash-logo" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
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

        <header class="py-3 md:py-4 z-[1000]">
            <div class="inner flex flex-col md:flex-row justify-between items-center text-center md:text-left">
                <div>
                    <a class="header-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                </div>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'header',
                        'menu_class' => 'header-nav flex ml-auto gap-5',
                        'menu_id' => 'header-nav',
                        'container' => 'nav',
                        'container_class' => 'hidden lg:block',
                        'depth' => 0,
                    )
                );
                ?>
            </div>
        </header>