<?php
/**
 * Helper Functions - Utility Functions
 * 
 * Contains reusable utility functions following clean code principles.
 * Single Responsibility: Each function has one clear purpose.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fix Turkish characters in text
 * 
 * @param string $text Text to fix
 * @return string Fixed text
 */
function mi_fix_turkish_chars($text) {
    if (empty($text)) {
        return $text;
    }
    
    // Türkçe karakter düzeltmeleri
    $turkish_fixes = array(
        'İLETIŞIM' => 'İLETİŞİM',
        'İLETIŞIM' => 'İLETİŞİM',
        'IÇERIK' => 'İÇERİK',
        'ILGILI' => 'İLGİLİ',
        'IPUCU' => 'İPUCU',
        'IŞ' => 'İŞ',
        'ICRA' => 'İCRA',
        'IDARE' => 'İDARE',
    );
    
    // Regex ile kapsamlı düzeltme
    $text = preg_replace('/İLET[Iİ]Ş[Iİ]M/i', 'İLETİŞİM', $text);
    $text = preg_replace('/İLET[Iİ]ŞIM/i', 'İLETİŞİM', $text);
    
    // Array ile düzeltme
    foreach ($turkish_fixes as $wrong => $correct) {
        $text = str_replace($wrong, $correct, $text);
    }
    
    return $text;
}

/**
 * Get Turkish date format
 * 
 * @param string $format Date format
 * @param int|null $post_id Post ID
 * @return string Formatted Turkish date
 */
function mi_get_turkish_date($format = 'd F Y', $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $date = get_the_date($format, $post_id);
    
    // İngilizce ay isimlerini Türkçe'ye çevir
    $english_months = array(
        'January' => 'Ocak',
        'February' => 'Şubat',
        'March' => 'Mart',
        'April' => 'Nisan',
        'May' => 'Mayıs',
        'June' => 'Haziran',
        'July' => 'Temmuz',
        'August' => 'Ağustos',
        'September' => 'Eylül',
        'October' => 'Ekim',
        'November' => 'Kasım',
        'December' => 'Aralık'
    );
    
    foreach ($english_months as $en => $tr) {
        $date = str_replace($en, $tr, $date);
    }
    
    return $date;
}

/**
 * Check if feature is enabled
 * 
 * @param string $feature_key Feature option key
 * @param string $type 'option' or 'theme_mod'
 * @param mixed $default Default value
 * @return bool True if enabled
 */
function mi_is_feature_enabled($feature_key, $type = 'option', $default = false) {
    if ($type === 'theme_mod') {
        return get_theme_mod($feature_key, $default);
    }
    return get_option($feature_key, $default);
}

/**
 * Sanitize text input
 * 
 * @param string $text Text to sanitize
 * @return string Sanitized text
 */
function mi_sanitize_text($text) {
    return sanitize_text_field($text);
}

/**
 * Sanitize HTML content
 * 
 * @param string $content Content to sanitize
 * @return string Sanitized content
 */
function mi_sanitize_html($content) {
    return wp_kses_post($content);
}

