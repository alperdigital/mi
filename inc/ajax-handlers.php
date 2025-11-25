<?php
/**
 * AJAX Handlers for Theme
 */

// AJAX Handler for Manşet Articles Filter
add_action('wp_ajax_mi_filter_manset_articles', 'mi_filter_manset_articles');
add_action('wp_ajax_nopriv_mi_filter_manset_articles', 'mi_filter_manset_articles');

if (!function_exists('mi_filter_manset_articles')) {
    function mi_filter_manset_articles() {
        // Nonce kontrolü - daha esnek hale getirildi
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mi_manset_filter')) {
            wp_send_json_error(array('message' => 'Güvenlik kontrolü başarısız.'));
            return;
        }
        
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
        $author = isset($_POST['author']) ? intval($_POST['author']) : 0;
        $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'date-desc';
        $section_id = isset($_POST['section_id']) ? intval($_POST['section_id']) : 0;
        
        $posts_per_page = 12;
        if ($section_id > 0) {
            $posts_per_page = get_post_meta($section_id, '_mi_manset_posts_per_page', true) ?: 12;
        }
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($posts_per_page),
            'paged' => $page,
            'post_status' => 'publish'
        );
        
        // Category filter
        if ($category > 0) {
            $args['cat'] = $category;
        }
        
        // Author filter
        if ($author > 0) {
            $args['author'] = $author;
        }
        
        // Sort
        switch ($sort) {
            case 'date-desc':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            case 'date-asc':
                $args['orderby'] = 'date';
                $args['order'] = 'ASC';
                break;
            case 'editor-choice':
                // Editörün seçimi: menu_order veya featured post'lar
                // Önce featured post'ları, sonra menu_order'a göre sırala
                $args['orderby'] = 'menu_order';
                $args['order'] = 'ASC';
                // Featured post'ları önceliklendirmek için meta query ekle
                $args['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_mi_featured_post',
                        'value' => '1',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_mi_featured_post',
                        'compare' => 'NOT EXISTS'
                    )
                );
                break;
            default:
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $category = get_the_category();
                $category_name = !empty($category) ? $category[0]->name : 'Genel';
                $views = function_exists('mi_get_post_views') ? mi_get_post_views(get_the_ID()) : 0;
                ?>
                <article class="manset-article">
                    <h2 class="article-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <div class="article-excerpt">
                        <?php 
                        if (has_excerpt()) {
                            echo wp_kses_post(get_the_excerpt());
                        } else {
                            $content = get_the_content();
                            $content = strip_tags($content);
                            echo esc_html(wp_trim_words($content, 20, '...'));
                        }
                        ?>
                    </div>
                    
                    <div class="article-footer">
                        <div class="article-author">✍️ <?php the_author(); ?></div>
                        <div class="article-date">📅 <?php echo get_the_date('d F Y'); ?></div>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="article-read-more">Devamını Oku →</a>
                </article>
                <?php
            }
        } else {
            echo '<p class="no-articles">Bu kriterlere uygun haber bulunamadı.</p>';
        }
        wp_reset_postdata();
        $html = ob_get_clean();
        
        // Pagination
        $pagination = '';
        if ($query->max_num_pages > 1) {
            $pagination = '<div class="manset-pagination-links">';
            if ($page > 1) {
                $pagination .= '<a href="#" data-page="' . ($page - 1) . '" class="pagination-link prev">← Önceki</a>';
            }
            for ($i = 1; $i <= $query->max_num_pages; $i++) {
                if ($i == $page) {
                    $pagination .= '<span class="pagination-current">' . $i . '</span>';
                } elseif ($i == 1 || $i == $query->max_num_pages || ($i >= $page - 2 && $i <= $page + 2)) {
                    $pagination .= '<a href="#" data-page="' . $i . '" class="pagination-link">' . $i . '</a>';
                } elseif ($i == $page - 3 || $i == $page + 3) {
                    $pagination .= '<span class="pagination-dots">...</span>';
                }
            }
            if ($page < $query->max_num_pages) {
                $pagination .= '<a href="#" data-page="' . ($page + 1) . '" class="pagination-link next">Sonraki →</a>';
            }
            $pagination .= '</div>';
        }
        
        wp_send_json_success(array(
            'html' => $html,
            'pagination' => $pagination
        ));
    }
}

