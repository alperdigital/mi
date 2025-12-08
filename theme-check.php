<?php
/**
 * Theme Check Script
 * Run this file to verify your theme is properly configured
 */

// Check if running in WordPress context
if (!defined('ABSPATH')) {
    // If not in WordPress, check file structure
    $theme_dir = __DIR__;
    
    echo "=== MI Theme Configuration Check ===\n\n";
    
    // Required files
    $required_files = [
        'style.css',
        'functions.php',
        'index.php',
        'header.php',
        'footer.php'
    ];
    
    $all_ok = true;
    
    foreach ($required_files as $file) {
        $path = $theme_dir . '/' . $file;
        if (file_exists($path)) {
            echo "✓ {$file} exists\n";
        } else {
            echo "✗ {$file} MISSING!\n";
            $all_ok = false;
        }
    }
    
    // Check style.css header
    $style_css = $theme_dir . '/style.css';
    if (file_exists($style_css)) {
        $content = file_get_contents($style_css);
        if (strpos($content, 'Theme Name:') !== false) {
            echo "✓ style.css has theme header\n";
        } else {
            echo "✗ style.css missing theme header!\n";
            $all_ok = false;
        }
    }
    
    // Check directory structure
    $required_dirs = ['inc', 'assets', 'templates'];
    foreach ($required_dirs as $dir) {
        $path = $theme_dir . '/' . $dir;
        if (is_dir($path)) {
            echo "✓ {$dir}/ directory exists\n";
        } else {
            echo "✗ {$dir}/ directory MISSING!\n";
            $all_ok = false;
        }
    }
    
    echo "\n";
    if ($all_ok) {
        echo "✓ All checks passed!\n";
        echo "\n";
        echo "IMPORTANT: Make sure this theme folder is located at:\n";
        echo "wp-content/themes/mi/\n";
        echo "\n";
        echo "If WordPress still shows 'Stylesheet is missing', try:\n";
        echo "1. Clear WordPress cache\n";
        echo "2. Check file permissions (should be 644 for files, 755 for directories)\n";
        echo "3. Verify style.css is readable\n";
    } else {
        echo "✗ Some checks failed. Please fix the issues above.\n";
    }
} else {
    // Running in WordPress context
    echo "Theme is active in WordPress!\n";
    echo "Theme Name: " . wp_get_theme()->get('Name') . "\n";
    echo "Theme Version: " . wp_get_theme()->get('Version') . "\n";
}

