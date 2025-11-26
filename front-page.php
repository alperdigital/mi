<?php
/**
 * Front Page Template
 * Ana sayfa template'i - Başyazı bölümünün içeriği ve görünümüyle aynı
 */

get_header();
?>

<main class="front-page-main">
    <?php
    // Başyazı bölümünü bul
    $basyazi_section = get_posts(array(
        'post_type' => 'mi_section',
        'posts_per_page' => 1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_mi_section_active',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_mi_section_type',
                'value' => 'aciklama',
                'compare' => '='
            )
        ),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    
    if (!empty($basyazi_section)) {
        $section = $basyazi_section[0];
        setup_postdata($section);
        
        // Başyazı bölümünün template'ini kullan
        if (function_exists('mi_render_section_template')) {
            mi_render_section_template($section);
        } else {
            // Fallback: Basit içerik gösterimi
            ?>
            <div class="aciklama-section">
                <div class="container">
                    <div class="aciklama-content-wrapper">
                        <?php 
                        $content = get_the_content();
                        remove_filter('the_content', 'wpautop');
                        $content = apply_filters('the_content', $content);
                        add_filter('the_content', 'wpautop');
                        echo $content;
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    }
    ?>
</main>

<?php
get_footer();
?>

