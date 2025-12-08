<?php
/**
 * Autoloader - Clean Code Architecture
 * 
 * This file handles automatic loading of theme files based on clean code principles.
 * Files are organized by responsibility and loaded only when needed.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Autoloader Class
 * 
 * Organizes file loading by responsibility:
 * - Core: Essential theme functionality
 * - Features: Optional features (loaded conditionally)
 * - Admin: Admin panel functionality
 * - Utils: Helper functions
 */
class MI_Autoloader {
    
    /**
     * Core files - Always loaded
     */
    private static $core_files = array(
        'template-functions.php',
        'social-share.php',
        'social-functions.php',
        'breadcrumbs.php',
        'comments.php',
        'seo.php',
        'mobile-menu.php',
        'scroll-to-top.php',
        'post-views.php',
        'lightbox.php',
        'gutenberg-blocks.php',
        'widgets.php',
        'additional-widgets.php',
        'popular-posts-widget.php',
        'turkish-archives.php',
        'ajax-handlers.php',
    );
    
    /**
     * Admin files - Loaded in admin context
     * Clean naming: Removed redundant 'admin-' prefix (already in admin/ folder)
     */
    private static $admin_files = array(
        'sections.php',        // Section management (was: admin-sections.php)
        'customizer.php',      // WordPress Customizer
        'options.php',         // Theme options page (was: theme-options.php)
        'ui.php',             // Admin UI enhancements (was: admin-ui.php)
        'demo.php',           // Demo content import (was: demo-import.php)
    );
    
    /**
     * Optional features - Loaded conditionally
     */
    private static $optional_features = array(
        'dark-mode' => array(
            'file' => 'dark-mode.php',
            'check' => 'get_theme_mod',
            'key' => 'mi_enable_dark_mode',
            'default' => false,
        ),
        'infinite-scroll' => array(
            'file' => 'infinite-scroll.php',
            'check' => 'get_option',
            'key' => 'mi_enable_infinite_scroll',
            'default' => false,
        ),
        'masonry-grid' => array(
            'file' => 'masonry-grid.php',
            'check' => 'get_theme_mod',
            'key' => 'mi_enable_masonry',
            'default' => false,
        ),
        'parallax' => array(
            'file' => 'parallax.php',
            'check' => 'get_theme_mod',
            'key' => 'mi_enable_parallax',
            'default' => false,
        ),
        'webp-support' => array(
            'file' => 'webp-support.php',
            'check' => 'get_theme_mod',
            'key' => 'mi_enable_webp',
            'default' => false,
        ),
        'recaptcha' => array(
            'file' => 'recaptcha.php',
            'check' => 'get_theme_mod',
            'key' => 'mi_enable_recaptcha',
            'default' => false,
        ),
        'table-of-contents' => array(
            'file' => 'table-of-contents.php',
            'check' => 'get_option',
            'key' => 'mi_enable_toc',
            'default' => false,
        ),
        'syntax-highlighting' => array(
            'file' => 'syntax-highlighting.php',
            'check' => 'get_option',
            'key' => 'mi_enable_syntax_highlighting',
            'default' => false,
        ),
        'newsletter' => array(
            'file' => 'newsletter.php',
            'check' => 'get_option',
            'key' => 'mi_enable_newsletter',
            'default' => false,
        ),
        'cookie-consent' => array(
            'file' => 'cookie-consent.php',
            'check' => 'get_option',
            'key' => 'mi_enable_cookie_consent',
            'default' => false,
        ),
        'loading-skeleton' => array(
            'file' => 'loading-skeleton.php',
            'check' => 'get_option',
            'key' => 'mi_enable_loading_skeleton',
            'default' => false,
        ),
        'amp' => array(
            'file' => 'amp.php',
            'check' => 'get_option',
            'key' => 'mi_enable_amp',
            'default' => false,
        ),
        'media-player' => array(
            'file' => 'media-player.php',
            'check' => 'get_option',
            'key' => 'mi_enable_media_player',
            'default' => false,
        ),
        'advanced-stats' => array(
            'file' => 'advanced-stats.php',
            'check' => 'get_option',
            'key' => 'mi_enable_advanced_stats',
            'default' => false,
        ),
        'rtl-support' => array(
            'file' => 'rtl-support.php',
            'check' => 'get_option',
            'key' => 'mi_enable_rtl',
            'default' => false,
        ),
    );
    
    /**
     * Load core files
     */
    public static function load_core() {
        $base_path = get_template_directory() . '/inc/core/';
        
        foreach (self::$core_files as $file) {
            $file_path = $base_path . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Load admin files
     */
    public static function load_admin() {
        if (!is_admin()) {
            return;
        }
        
        $base_path = get_template_directory() . '/inc/admin/';
        
        foreach (self::$admin_files as $file) {
            $file_path = $base_path . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Load optional features
     */
    public static function load_features() {
        $base_path = get_template_directory() . '/inc/features/';
        
        foreach (self::$optional_features as $feature => $config) {
            $check_function = $config['check'];
            $key = $config['key'];
            $default = $config['default'];
            
            // Check if feature is enabled
            $is_enabled = false;
            if ($check_function === 'get_theme_mod') {
                $is_enabled = get_theme_mod($key, $default);
            } elseif ($check_function === 'get_option') {
                $is_enabled = get_option($key, $default);
            }
            
            if ($is_enabled) {
                $file_path = $base_path . $config['file'];
                if (file_exists($file_path)) {
                    require_once $file_path;
                }
            }
        }
    }
    
    /**
     * Load utility functions
     */
    public static function load_utils() {
        $file_path = get_template_directory() . '/inc/utils/helpers.php';
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
    
    /**
     * Load integration file
     */
    public static function load_integration() {
        $file_path = get_template_directory() . '/inc/core/feature-integration.php';
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
    
    /**
     * Load modules file
     */
    public static function load_modules() {
        $file_path = get_template_directory() . '/inc/core/modules.php';
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
    
    /**
     * Initialize autoloader
     * Loads files in proper order following dependency chain
     */
    public static function init() {
        // Load utilities first (used by other files)
        self::load_utils();
        
        // Load core files
        self::load_core();
        
        // Load admin files (only in admin context)
        self::load_admin();
        
        // Load optional features (conditionally)
        self::load_features();
        
        // Load integration (connects features)
        self::load_integration();
        
        // Load modules (plugin-like structure)
        self::load_modules();
    }
}

// Initialize autoloader
add_action('after_setup_theme', array('MI_Autoloader', 'init'), 1);

