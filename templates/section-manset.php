<?php
/**
 * Template: Manşet (Haber Listesi) - AJAX ile İşlevsel
 */

$post_id = get_the_ID();
$is_front_page = is_front_page();

// Filtreleme seçenekleri
$show_category = get_post_meta($post_id, '_mi_manset_filter_category', true) === '1';
$show_author = get_post_meta($post_id, '_mi_manset_filter_author', true) !== '0'; // Default: göster
$show_sort = get_post_meta($post_id, '_mi_manset_filter_sort', true) !== '0'; // Default: göster
$default_sort = get_post_meta($post_id, '_mi_manset_default_sort', true) ?: 'date-desc';
?>

<div class="manset-section <?php echo $is_front_page ? 'front-page-manset' : ''; ?>">
    <div class="container">
        <div class="manset-filters">
            <?php if ($show_category) : ?>
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
            <?php endif; ?>
            
            <?php if ($show_author) : ?>
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
            <?php endif; ?>
            
            <?php if ($show_sort) : ?>
            <div class="filter-group">
                <label for="filter-sort">🔀 Sırala:</label>
                <select id="filter-sort" class="filter-select">
                    <option value="date-desc" <?php selected($default_sort, 'date-desc'); ?>>Tarih (Yeniden Eskiye)</option>
                    <option value="date-asc" <?php selected($default_sort, 'date-asc'); ?>>Tarih (Eskiden Yeniye)</option>
                    <option value="editor-choice" <?php selected($default_sort, 'editor-choice'); ?>>Editörün Seçimi</option>
                </select>
            </div>
            <?php endif; ?>
            
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
            
            // Varsayılan sıralamayı uygula
            switch ($default_sort) {
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
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p class="no-articles">Henüz haber bulunmamaktadır.</p>
            <?php endif; ?>
        </div>
        
        <div class="manset-pagination" id="manset-pagination"></div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var currentPage = 1;
    var defaultSort = '<?php echo esc_js($default_sort); ?>';
    
    function loadArticles(page, category, author, sort) {
        $('#manset-loading').fadeIn(200);
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
                    $('#manset-pagination').html('');
                }
                $('#manset-loading').fadeOut(200);
            },
            error: function() {
                $('#manset-articles').html('<p class="no-articles">Bir hata oluştu. Lütfen tekrar deneyin.</p>').fadeIn(300);
                $('#manset-loading').fadeOut(200);
            }
        });
    }
    
    <?php if ($show_category || $show_author || $show_sort) : ?>
    $('#filter-category, #filter-author, #filter-sort').on('change', function() {
        currentPage = 1;
        var category = $('#filter-category').val() || '';
        var author = $('#filter-author').val() || '';
        var sort = $('#filter-sort').val() || defaultSort;
        loadArticles(currentPage, category, author, sort);
    });
    <?php endif; ?>
    
    $('#reset-filters').on('click', function() {
        <?php if ($show_category) : ?>$('#filter-category').val('');<?php endif; ?>
        <?php if ($show_author) : ?>$('#filter-author').val('');<?php endif; ?>
        <?php if ($show_sort) : ?>$('#filter-sort').val(defaultSort);<?php endif; ?>
        currentPage = 1;
        loadArticles(currentPage, '', '', defaultSort);
    });
    
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        currentPage = page;
        var category = $('#filter-category').val() || '';
        var author = $('#filter-author').val() || '';
        var sort = $('#filter-sort').val() || defaultSort;
        loadArticles(page, category, author, sort);
    });
});
</script>
