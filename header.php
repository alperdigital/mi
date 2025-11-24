<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header>
        <div class="container">
            <div class="header-top">
                <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
                <?php if (get_bloginfo('description')) : ?>
                    <p class="site-description"><?php bloginfo('description'); ?></p>
                <?php endif; ?>
            </div>
            
            <nav class="main-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-menu',
                    'container' => false,
                    'fallback_cb' => 'default_nav_menu',
                ));
                ?>
            </nav>
        </div>
    </header>
    
    <?php
    // Fallback menu if no menu is assigned
    function default_nav_menu() {
        echo '<ul class="nav-menu">';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Ana Sayfa</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Gündem</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Yazarlar</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Siyaset</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Ekonomi</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Dünya</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Spor</a></li>';
        echo '<li><a href="' . esc_url(home_url('/')) . '">Yaşam</a></li>';
        echo '</ul>';
    }
    ?>

