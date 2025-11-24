<?php
/**
 * Template: Manşet (Haber Listesi) - AJAX ile İşlevsel
 */

$post_id = get_the_ID();
$is_front_page = is_front_page();
?>

<div class="manset-section <?php echo $is_front_page ? 'front-page-manset' : ''; ?>">
    <div class="manset-filters">
        <div class="filter-group">
            <label for="filter-category">📂 Kategori:</label>
            <select id="filter-category" class="filter-select">
                <option value="">Tümü</option>
                <?php
                $categories = get_categories(array('hide_empty' => false));
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="filter-author">✍️ Yazar:</label>
            <select id="filter-author" class="filter-select">
                <option value="">Tümü</option>
                <?php
                $authors = get_users(array('who' => 'authors'));
                foreach ($authors as $author) {
                    echo '<option value="' . esc_attr($author->ID) . '">' . esc_html($author->display_name) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="filter-sort">🔀 Sırala:</label>
            <select id="filter-sort" class="filter-select">
                <option value="date-desc">Tarih (Yeni → Eski)</option>
                <option value="date-asc">Tarih (Eski → Yeni)</option>
                <option value="popular-desc">Popülerlik (Yüksek → Düşük)</option>
                <option value="popular-asc">Popülerlik (Düşük → Yüksek)</option>
                <option value="title-asc">Başlık (A → Z)</option>
                <option value="title-desc">Başlık (Z → A)</option>
            </select>
        </div>
        
        <button type="button" class="filter-reset-btn" id="reset-filters">🔄 Sıfırla</button>
    </div>
    
    <div class="manset-loading" id="manset-loading" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Yükleniyor...</p>
    </div>
    
    <div class="manset-articles" id="manset-articles">
        <?php
        $posts_per_page = get_post_meta($post_id, '_mi_manset_posts_per_page', true) ?: 12;
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($posts_per_page),
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $category = get_the_category();
                $category_name = !empty($category) ? $category[0]->name : 'Genel';
                $category_id = !empty($category) ? $category[0]->term_id : 0;
                $views = function_exists('mi_get_post_views') ? mi_get_post_views(get_the_ID()) : 0;
                ?>
                <article class="manset-article" 
                         data-category="<?php echo esc_attr($category_id); ?>"
                         data-author="<?php echo esc_attr(get_the_author_meta('ID')); ?>"
                         data-date="<?php echo esc_attr(get_the_date('Y-m-d H:i:s')); ?>"
                         data-views="<?php echo esc_attr($views); ?>"
                         data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="article-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', array('alt' => get_the_title())); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="article-header">
                        <span class="article-category"><?php echo esc_html($category_name); ?></span>
                        <?php if ($views > 0) : ?>
                            <span class="article-views">👁️ <?php echo number_format($views); ?></span>
                        <?php endif; ?>
                    </div>
                    
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
                            echo esc_html(wp_trim_words($content, 25, '...'));
                        }
                        ?>
                    </div>
                    
                    <div class="article-meta">
                        <span class="article-author">✍️ <?php the_author(); ?></span>
                        <span class="article-date">📅 <?php echo get_the_date('d F Y H:i'); ?></span>
                    </div>
                    
                    <div class="article-share">
                        <?php if (function_exists('mi_render_social_share')) : ?>
                            <?php mi_render_social_share(get_the_ID(), true); ?>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="article-read-more">Devamını Oku →</a>
                </article>
            <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="no-articles">Henüz haber bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
    
    <div class="manset-pagination" id="manset-pagination"></div>
</div>

<script>
jQuery(document).ready(function($) {
    var currentPage = 1;
    var loading = false;
    
    function loadArticles(page, category, author, sort) {
        if (loading) return;
        loading = true;
        
        $('#manset-loading').show();
        $('#manset-articles').fadeOut(200);
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
                    data: {
                        action: 'mi_filter_manset_articles',
                        page: page,
                        category: category,
                        author: author,
                        sort: sort,
                        section_id: <?php echo $post_id; ?>,
                        nonce: '<?php echo wp_create_nonce('mi_manset_filter'); ?>'
                    },
            success: function(response) {
                if (response.success) {
                    $('#manset-articles').html(response.data.html).fadeIn(300);
                    if (response.data.pagination) {
                        $('#manset-pagination').html(response.data.pagination);
                    }
                } else {
                    $('#manset-articles').html('<p class="no-articles">' + response.data.message + '</p>').fadeIn(300);
                }
                loading = false;
                $('#manset-loading').hide();
            },
            error: function() {
                $('#manset-articles').html('<p class="no-articles">Bir hata oluştu. Lütfen tekrar deneyin.</p>').fadeIn(300);
                loading = false;
                $('#manset-loading').hide();
            }
        });
    }
    
    $('#filter-category, #filter-author, #filter-sort').on('change', function() {
        currentPage = 1;
        var category = $('#filter-category').val();
        var author = $('#filter-author').val();
        var sort = $('#filter-sort').val();
        loadArticles(currentPage, category, author, sort);
    });
    
    $('#reset-filters').on('click', function() {
        $('#filter-category').val('');
        $('#filter-author').val('');
        $('#filter-sort').val('date-desc');
        currentPage = 1;
        loadArticles(currentPage, '', '', 'date-desc');
    });
    
    $(document).on('click', '.manset-pagination a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (page) {
            currentPage = page;
            var category = $('#filter-category').val();
            var author = $('#filter-author').val();
            var sort = $('#filter-sort').val();
            loadArticles(page, category, author, sort);
            $('html, body').animate({ scrollTop: $('.manset-section').offset().top - 100 }, 500);
        }
    });
});
</script>

<?php
// AJAX handler for manset filtering
function mi_filter_manset_articles() {
    check_ajax_referer('mi_manset_filter', 'nonce');
    
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
        case 'date-asc':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
        case 'popular-desc':
            $args['meta_key'] = '_mi_post_views_count';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'popular-asc':
            $args['meta_key'] = '_mi_post_views_count';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'title-asc':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'title-desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
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
                <?php if (has_post_thumbnail()) : ?>
                    <div class="article-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large', array('alt' => get_the_title())); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="article-header">
                    <span class="article-category"><?php echo esc_html($category_name); ?></span>
                    <?php if ($views > 0) : ?>
                        <span class="article-views">👁️ <?php echo number_format($views); ?></span>
                    <?php endif; ?>
                </div>
                
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
                        echo esc_html(wp_trim_words($content, 25, '...'));
                    }
                    ?>
                </div>
                
                <div class="article-meta">
                    <span class="article-author">✍️ <?php the_author(); ?></span>
                    <span class="article-date">📅 <?php echo get_the_date('d F Y H:i'); ?></span>
                </div>
                
                <div class="article-share">
                    <?php if (function_exists('mi_render_social_share')) : ?>
                        <?php mi_render_social_share(get_the_ID(), true); ?>
                    <?php endif; ?>
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
            } else {
                $pagination .= '<a href="#" data-page="' . $i . '" class="pagination-link">' . $i . '</a>';
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
add_action('wp_ajax_mi_filter_manset_articles', 'mi_filter_manset_articles');
add_action('wp_ajax_nopriv_mi_filter_manset_articles', 'mi_filter_manset_articles');
?>
