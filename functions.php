<?php
/**
 * Theme Functions
 */

// Theme setup
function mi_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('automatic-feed-links');
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mi-theme'),
        'footer' => __('Footer Menu', 'mi-theme'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
    
    // Add image sizes
    add_image_size('post-thumbnail', 300, 200, true);
    add_image_size('post-large', 800, 500, true);
}
add_action('after_setup_theme', 'mi_theme_setup');

// Enqueue styles and scripts
function mi_enqueue_scripts() {
    wp_enqueue_style('mi-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue scripts if needed
    // wp_enqueue_script('mi-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'mi_enqueue_scripts');

// Custom excerpt length
function mi_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'mi_excerpt_length');

// Custom excerpt more
function mi_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'mi_excerpt_more');

// Add custom body classes
function mi_body_classes($classes) {
    if (is_home() || is_front_page()) {
        $classes[] = 'home-page';
    }
    return $classes;
}
add_filter('body_class', 'mi_body_classes');

