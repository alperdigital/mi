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
}
add_action('after_setup_theme', 'mi_theme_setup');

// Enqueue styles and scripts
function mi_enqueue_scripts() {
    wp_enqueue_style('mi-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mi_enqueue_scripts');

