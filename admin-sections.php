<?php
/**
 * Admin Panel - Bölüm Yönetimi
 */

// Custom Post Type: Bölümler
function mi_register_sections_post_type() {
    $labels = array(
        'name' => 'Bölümler',
        'singular_name' => 'Bölüm',
        'menu_name' => 'Bölümler',
        'add_new' => 'Yeni Bölüm Ekle',
        'add_new_item' => 'Yeni Bölüm Ekle',
        'edit_item' => 'Bölümü Düzenle',
        'new_item' => 'Yeni Bölüm',
        'view_item' => 'Bölümü Görüntüle',
        'search_items' => 'Bölüm Ara',
        'not_found' => 'Bölüm bulunamadı',
        'not_found_in_trash' => 'Çöp kutusunda bölüm bulunamadı'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'bolum'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-admin-page',
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    );

    register_post_type('mi_section', $args);
}
add_action('init', 'mi_register_sections_post_type');

// Meta Box: Bölüm Ayarları
function mi_add_section_meta_boxes() {
    add_meta_box(
        'mi_section_settings',
        'Bölüm Ayarları',
        'mi_section_settings_callback',
        'mi_section',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mi_add_section_meta_boxes');

// Meta Box Callback
function mi_section_settings_callback($post) {
    wp_nonce_field('mi_section_meta_box', 'mi_section_meta_box_nonce');
    
    $section_name = get_post_meta($post->ID, '_mi_section_name', true);
    $section_type = get_post_meta($post->ID, '_mi_section_type', true);
    $ui_template = get_post_meta($post->ID, '_mi_ui_template', true);
    $menu_order = $post->menu_order;
    $is_active = get_post_meta($post->ID, '_mi_section_active', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="mi_section_name">Bölüm İsmi</label></th>
            <td>
                <input type="text" id="mi_section_name" name="mi_section_name" 
                       value="<?php echo esc_attr($section_name ?: $post->post_title); ?>" 
                       class="regular-text" />
                <p class="description">Menüde görünecek bölüm ismi</p>
            </td>
        </tr>
        <tr>
            <th><label for="mi_section_type">Sayfa Tipi</label></th>
            <td>
                <select id="mi_section_type" name="mi_section_type" class="regular-text">
                    <option value="default" <?php selected($section_type, 'default'); ?>>Varsayılan</option>
                    <option value="manset" <?php selected($section_type, 'manset'); ?>>Manşet (Haber Listesi)</option>
                    <option value="kararlar" <?php selected($section_type, 'kararlar'); ?>>Kararlar (Karar Listesi)</option>
                    <option value="iletisim" <?php selected($section_type, 'iletisim'); ?>>İletişim (Form)</option>
                    <option value="custom" <?php selected($section_type, 'custom'); ?>>Özel İçerik</option>
                </select>
                <p class="description">Sayfanın görünüm tipini seçin</p>
            </td>
        </tr>
        <tr>
            <th><label for="mi_ui_template">UI Tasarım Konumu</label></th>
            <td>
                <select id="mi_ui_template" name="mi_ui_template" class="regular-text">
                    <option value="default" <?php selected($ui_template, 'default'); ?>>Varsayılan</option>
                    <option value="top" <?php selected($ui_template, 'top'); ?>>Üstte</option>
                    <option value="bottom" <?php selected($ui_template, 'bottom'); ?>>Altta</option>
                    <option value="sidebar" <?php selected($ui_template, 'sidebar'); ?>>Yan Bar</option>
                    <option value="none" <?php selected($ui_template, 'none'); ?>>Gösterme</option>
                </select>
                <p class="description">UI tasarım bileşeninin sayfadaki konumu</p>
            </td>
        </tr>
        <tr>
            <th><label for="menu_order">Sıralama</label></th>
            <td>
                <input type="number" id="menu_order" name="menu_order" 
                       value="<?php echo esc_attr($menu_order); ?>" 
                       class="small-text" min="0" />
                <p class="description">Menüdeki sıralama (düşük sayı = üstte)</p>
            </td>
        </tr>
        <tr>
            <th><label for="mi_section_active">Aktif</label></th>
            <td>
                <label>
                    <input type="checkbox" id="mi_section_active" name="mi_section_active" 
                           value="1" <?php checked($is_active, '1'); ?> />
                    Bu bölümü menüde göster
                </label>
            </td>
        </tr>
    </table>
    
    <h3>Sayfa Tipi Özel Ayarları</h3>
    <div id="manset-settings" class="section-type-settings" style="display:none;">
        <table class="form-table">
            <tr>
                <th><label>Filtreleme Seçenekleri</label></th>
                <td>
                    <label><input type="checkbox" name="manset_filter_category" value="1" checked /> Kategori</label><br>
                    <label><input type="checkbox" name="manset_filter_author" value="1" checked /> Yazar</label><br>
                    <label><input type="checkbox" name="manset_filter_sort" value="1" checked /> Sıralama</label>
                </td>
            </tr>
        </table>
    </div>
    
    <div id="kararlar-settings" class="section-type-settings" style="display:none;">
        <table class="form-table">
            <tr>
                <th><label>Filtreleme Seçenekleri</label></th>
                <td>
                    <label><input type="checkbox" name="kararlar_filter_dava" value="1" checked /> Dava Türü</label><br>
                    <label><input type="checkbox" name="kararlar_filter_mahkeme" value="1" checked /> Mahkeme</label><br>
                    <label><input type="checkbox" name="kararlar_filter_sort" value="1" checked /> Sıralama</label>
                </td>
            </tr>
        </table>
    </div>
    
    <div id="iletisim-settings" class="section-type-settings" style="display:none;">
        <table class="form-table">
            <tr>
                <th><label for="iletisim_email">E-posta Adresi</label></th>
                <td>
                    <input type="email" id="iletisim_email" name="iletisim_email" 
                           value="<?php echo esc_attr(get_post_meta($post->ID, '_mi_iletisim_email', true)); ?>" 
                           class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="iletisim_response_time">Yanıt Süresi</label></th>
                <td>
                    <input type="text" id="iletisim_response_time" name="iletisim_response_time" 
                           value="<?php echo esc_attr(get_post_meta($post->ID, '_mi_iletisim_response_time', true) ?: '24-48 Saat'); ?>" 
                           class="regular-text" />
                </td>
            </tr>
        </table>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        function toggleSectionSettings() {
            $('.section-type-settings').hide();
            var type = $('#mi_section_type').val();
            if (type) {
                $('#' + type + '-settings').show();
            }
        }
        
        $('#mi_section_type').on('change', toggleSectionSettings);
        toggleSectionSettings();
    });
    </script>
    <?php
}

// Save Meta Box Data
function mi_save_section_meta_box($post_id) {
    if (!isset($_POST['mi_section_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['mi_section_meta_box_nonce'], 'mi_section_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['mi_section_name'])) {
        update_post_meta($post_id, '_mi_section_name', sanitize_text_field($_POST['mi_section_name']));
    }

    if (isset($_POST['mi_section_type'])) {
        update_post_meta($post_id, '_mi_section_type', sanitize_text_field($_POST['mi_section_type']));
    }

    if (isset($_POST['mi_ui_template'])) {
        update_post_meta($post_id, '_mi_ui_template', sanitize_text_field($_POST['mi_ui_template']));
    }

    if (isset($_POST['mi_section_active'])) {
        update_post_meta($post_id, '_mi_section_active', '1');
    } else {
        update_post_meta($post_id, '_mi_section_active', '0');
    }

    // İletişim özel ayarları
    if (isset($_POST['iletisim_email'])) {
        update_post_meta($post_id, '_mi_iletisim_email', sanitize_email($_POST['iletisim_email']));
    }

    if (isset($_POST['iletisim_response_time'])) {
        update_post_meta($post_id, '_mi_iletisim_response_time', sanitize_text_field($_POST['iletisim_response_time']));
    }
}
add_action('save_post', 'mi_save_section_meta_box');

// Admin Columns
function mi_section_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Bölüm Adı';
    $new_columns['section_type'] = 'Sayfa Tipi';
    $new_columns['menu_order'] = 'Sıralama';
    $new_columns['active'] = 'Aktif';
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_mi_section_posts_columns', 'mi_section_columns');

function mi_section_column_content($column, $post_id) {
    switch ($column) {
        case 'section_type':
            $type = get_post_meta($post_id, '_mi_section_type', true);
            $types = array(
                'default' => 'Varsayılan',
                'manset' => 'Manşet',
                'kararlar' => 'Kararlar',
                'iletisim' => 'İletişim',
                'custom' => 'Özel'
            );
            echo isset($types[$type]) ? $types[$type] : 'Varsayılan';
            break;
        case 'active':
            $active = get_post_meta($post_id, '_mi_section_active', true);
            echo $active == '1' ? '<span style="color:green;">✓ Aktif</span>' : '<span style="color:red;">✗ Pasif</span>';
            break;
    }
}
add_action('manage_mi_section_posts_custom_column', 'mi_section_column_content', 10, 2);

// Make menu_order sortable
function mi_section_sortable_columns($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}
add_filter('manage_edit-mi_section_sortable_columns', 'mi_section_sortable_columns');

