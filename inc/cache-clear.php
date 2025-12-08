<?php
/**
 * Cache Clear Utility
 * 
 * Bu dosya WordPress cache'lerini temizlemek için kullanılabilir.
 * Sadece admin panelinden çalıştırılmalıdır.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Clear all WordPress caches
 */
function mi_clear_all_caches() {
    // Clear WordPress object cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    // Clear transients
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
    
    // Clear rewrite rules
    delete_option('rewrite_rules');
    
    return true;
}

/**
 * Reset theme options to defaults
 */
function mi_reset_theme_options() {
    // Reset all mi_ prefixed options
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'mi_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_mi_%'");
    
    // Reset theme mods
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'theme_mods_%'");
    
    return true;
}

// Admin notice for cache clearing
if (is_admin() && isset($_GET['mi_clear_cache']) && current_user_can('manage_options')) {
    check_admin_referer('mi_clear_cache');
    mi_clear_all_caches();
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Cache temizlendi!</p></div>';
    });
}

// Admin notice for resetting options
if (is_admin() && isset($_GET['mi_reset_options']) && current_user_can('manage_options')) {
    check_admin_referer('mi_reset_options');
    mi_reset_theme_options();
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Tema ayarları sıfırlandı!</p></div>';
    });
}

/**
 * Flush rewrite rules
 */
function mi_flush_rewrite_rules_manual() {
    if (function_exists('mi_register_sections_post_type')) {
        mi_register_sections_post_type();
    }
    flush_rewrite_rules();
    return true;
}

// Admin notice for flushing rewrite rules
if (is_admin() && isset($_GET['mi_flush_rewrite']) && current_user_can('manage_options')) {
    check_admin_referer('mi_flush_rewrite');
    mi_flush_rewrite_rules_manual();
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Permalink yapısı yenilendi! Menü linkleri artık doğru çalışmalı.</p></div>';
    });
}

