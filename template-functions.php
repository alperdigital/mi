<?php
/**
 * Template Functions - Bölüm Yönetimi ve Görüntüleme
 */

// Get all active sections for menu
function mi_get_active_sections() {
    $args = array(
        'post_type' => 'mi_section',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_mi_section_active',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    
    return get_posts($args);
}

// Get section by slug
function mi_get_section_by_slug($slug) {
    $args = array(
        'post_type' => 'mi_section',
        'name' => $slug,
        'posts_per_page' => 1,
        'post_status' => 'publish'
    );
    
    $posts = get_posts($args);
    return !empty($posts) ? $posts[0] : null;
}

// Get section type
function mi_get_section_type($post_id) {
    return get_post_meta($post_id, '_mi_section_type', true) ?: 'default';
}

// Get section name
function mi_get_section_name($post_id) {
    $name = get_post_meta($post_id, '_mi_section_name', true);
    $name = $name ?: get_the_title($post_id);
    // İLETIŞIM -> İLETİŞİM düzeltmesi (tüm varyasyonlar)
    // Büyük I yerine İ kullanılmalı
    $name = str_replace('İLETIŞIM', 'İLETİŞİM', $name);
    $name = str_replace('İletişim', 'İletişim', $name);
    // Eğer hala yanlış karakter varsa düzelt (I yerine İ)
    $name = preg_replace('/İLET[Iİ]ŞIM/i', 'İLETİŞİM', $name);
    // Tüm büyük harf versiyonları için
    $name = preg_replace('/İLET[Iİ]Ş[Iİ]M/i', 'İLETİŞİM', $name);
    // Küçük harf versiyonları için
    $name = str_replace('iletışim', 'İletişim', $name);
    $name = str_replace('İLETIŞIM', 'İLETİŞİM', $name);
    return $name;
}

// Get UI template position
function mi_get_ui_template_position($post_id) {
    return get_post_meta($post_id, '_mi_ui_template', true) ?: 'default';
}

// Render section template
function mi_render_section_template($post) {
    $section_type = mi_get_section_type($post->ID);
    
    switch ($section_type) {
        case 'aciklama':
            get_template_part('templates/section', 'aciklama');
            break;
        case 'manset':
            get_template_part('templates/section', 'manset');
            break;
        case 'kararlar':
            get_template_part('templates/section', 'kararlar');
            break;
        case 'iletisim':
            get_template_part('templates/section', 'iletisim');
            break;
        case 'custom':
            get_template_part('templates/section', 'custom');
            break;
        default:
            get_template_part('templates/section', 'default');
            break;
    }
}

// Render UI components for sections
function mi_render_ui_components($section_type, $post_id) {
    // This function can be used to render additional UI components
    // Currently, section templates handle their own UI
    do_action('mi_render_section_ui', $section_type, $post_id);
}

// Update navigation menu with sections
function mi_update_navigation_menu() {
    $sections = mi_get_active_sections();
    
    if (empty($sections)) {
        return;
    }
    
    // Create menu items from sections
    $menu_items = array();
    foreach ($sections as $section) {
        $menu_items[] = array(
            'title' => mi_get_section_name($section->ID),
            'url' => get_permalink($section->ID),
            'classes' => array('section-menu-item')
        );
    }
    
    return $menu_items;
}

