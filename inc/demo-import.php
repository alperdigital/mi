<?php
/**
 * Demo Content Import
 */

// Add demo import page
function mi_add_demo_import_page() {
    add_theme_page(
        __('Demo İçerik İçe Aktar', 'mi-theme'),
        __('Demo İçerik', 'mi-theme'),
        'manage_options',
        'mi-demo-import',
        'mi_demo_import_page'
    );
}
add_action('admin_menu', 'mi_add_demo_import_page');

// Demo import page
function mi_demo_import_page() {
    if (isset($_POST['mi_import_demo'])) {
        check_admin_referer('mi_demo_import_nonce');
        
        mi_import_demo_content();
        echo '<div class="notice notice-success"><p>' . __('Demo içerik başarıyla içe aktarıldı!', 'mi-theme') . '</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="demo-import-info">
            <h2><?php _e('Demo İçerik İçe Aktarma', 'mi-theme'); ?></h2>
            <p><?php _e('Bu işlem örnek içerik, sayfalar ve bölümler oluşturacaktır.', 'mi-theme'); ?></p>
            <p><strong><?php _e('Uyarı:', 'mi-theme'); ?></strong> <?php _e('Mevcut içerikleriniz etkilenmeyecektir, ancak yedek almanız önerilir.', 'mi-theme'); ?></p>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field('mi_demo_import_nonce'); ?>
            
            <h3><?php _e('İçe Aktarılacaklar:', 'mi-theme'); ?></h3>
            <ul style="list-style: disc; margin-left: 30px;">
                <li><?php _e('Örnek Sayfalar', 'mi-theme'); ?></li>
                <li><?php _e('Örnek Yazılar', 'mi-theme'); ?></li>
                <li><?php _e('Örnek Bölümler (MANŞET, KARARLAR, İLETİŞİM)', 'mi-theme'); ?></li>
                <li><?php _e('Örnek Kategoriler', 'mi-theme'); ?></li>
                <li><?php _e('Örnek Etiketler', 'mi-theme'); ?></li>
            </ul>
            
            <p class="submit">
                <input type="submit" name="mi_import_demo" class="button button-primary" value="<?php esc_attr_e('Demo İçeriği İçe Aktar', 'mi-theme'); ?>">
            </p>
        </form>
    </div>
    <?php
}

// Import demo content
function mi_import_demo_content() {
    // Create categories
    $categories = array(
        'Gündem' => 'Güncel haberler ve gelişmeler',
        'Spor' => 'Spor haberleri',
        'Ekonomi' => 'Ekonomi haberleri',
        'Teknoloji' => 'Teknoloji haberleri',
        'Kültür' => 'Kültür ve sanat',
    );
    
    $category_ids = array();
    foreach ($categories as $name => $description) {
        $term = wp_insert_term($name, 'category', array('description' => $description));
        if (!is_wp_error($term)) {
            $category_ids[$name] = $term['term_id'];
        }
    }
    
    // Create sample posts
    $sample_posts = array(
        array(
            'title' => 'Teknoloji Sektöründe Yeni Gelişmeler',
            'content' => 'Yapay zeka ve makine öğrenmesi alanında son dönemde yaşanan önemli gelişmeler ve bunların sektöre etkileri. Bu gelişmeler iş dünyasını ve günlük hayatımızı nasıl şekillendiriyor?',
            'category' => 'Teknoloji',
            'excerpt' => 'Yapay zeka ve makine öğrenmesi alanında son dönemde yaşanan önemli gelişmeler.',
        ),
        array(
            'title' => 'Ekonomide Büyüme Beklentileri',
            'content' => 'Ekonomi uzmanları, önümüzdeki dönem için olumlu büyüme beklentileri açıkladı. İhracat ve yatırımların artması bekleniyor. Bu gelişmelerin topluma etkileri neler olacak?',
            'category' => 'Ekonomi',
            'excerpt' => 'Ekonomi uzmanları, önümüzdeki dönem için olumlu büyüme beklentileri açıkladı.',
        ),
        array(
            'title' => 'Spor Dünyasında Transfer Dönemi',
            'content' => 'Transfer döneminde gerçekleşen önemli transferler ve takımların yeni sezon hazırlıkları hakkında detaylar. Hangi oyuncular hangi takımlara transfer oldu?',
            'category' => 'Spor',
            'excerpt' => 'Transfer döneminde gerçekleşen önemli transferler ve takımların yeni sezon hazırlıkları.',
        ),
    );
    
    foreach ($sample_posts as $post_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_excerpt' => $post_data['excerpt'],
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_category' => array($category_ids[$post_data['category']]),
        ));
        
        if ($post_id) {
            // Set random view count
            update_post_meta($post_id, 'mi_post_views_count', rand(50, 500));
        }
    }
    
    // Create sample sections
    $sections = array(
        array(
            'title' => 'MANŞET',
            'name' => 'MANŞET',
            'type' => 'manset',
            'content' => 'Güncel haberler ve gelişmeler',
            'active' => '1',
            'order' => 1,
        ),
        array(
            'title' => 'KARARLAR',
            'name' => 'KARARLAR',
            'type' => 'kararlar',
            'content' => 'Yargıtay kararları ve hukuki gelişmeler',
            'active' => '1',
            'order' => 2,
        ),
        array(
            'title' => 'İLETİŞİM',
            'name' => 'İLETİŞİM',
            'type' => 'iletisim',
            'content' => 'Bizimle iletişime geçin',
            'active' => '1',
            'order' => 3,
        ),
    );
    
    foreach ($sections as $section_data) {
        $section_id = wp_insert_post(array(
            'post_title' => $section_data['title'],
            'post_content' => $section_data['content'],
            'post_status' => 'publish',
            'post_type' => 'mi_section',
            'menu_order' => $section_data['order'],
        ));
        
        if ($section_id) {
            update_post_meta($section_id, '_mi_section_name', $section_data['name']);
            update_post_meta($section_id, '_mi_section_type', $section_data['type']);
            update_post_meta($section_id, '_mi_section_active', $section_data['active']);
            update_post_meta($section_id, '_mi_ui_template', 'top');
            
            if ($section_data['type'] === 'iletisim') {
                update_post_meta($section_id, '_mi_iletisim_email', get_option('admin_email'));
                update_post_meta($section_id, '_mi_iletisim_response_time', '24-48 Saat');
            }
        }
    }
    
    // Create sample pages
    $pages = array(
        array(
            'title' => 'Hakkımızda',
            'content' => 'Sitemiz hakkında bilgiler...',
        ),
        array(
            'title' => 'Gizlilik Politikası',
            'content' => 'Gizlilik politikamız...',
        ),
    );
    
    foreach ($pages as $page_data) {
        wp_insert_post(array(
            'post_title' => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
    }
}

