<?php
/**
 * Template: Manşet (Haber Listesi)
 */

$post_id = get_the_ID();
?>

<div class="manset-section">
    <div class="manset-filters">
        <div class="filter-group">
            <label for="filter-category">📂 Kategori:</label>
            <select id="filter-category" class="filter-select">
                <option value="">Tümü</option>
                <option value="gundem">Gündem</option>
                <option value="spor">Spor</option>
                <option value="ekonomi">Ekonomi</option>
                <option value="teknoloji">Teknoloji</option>
                <option value="kultur">Kültür</option>
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
                <option value="author-asc">Yazar (A → Z)</option>
                <option value="author-desc">Yazar (Z → A)</option>
            </select>
        </div>
        
        <button type="button" class="filter-reset-btn" id="reset-filters">🔄 Sıfırla</button>
    </div>
    
    <div class="manset-articles" id="manset-articles">
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $category = get_the_category();
                $category_name = !empty($category) ? $category[0]->name : 'Genel';
                $popularity = rand(70, 100); // Placeholder - gerçek popülerlik skoru için meta field kullanılabilir
                ?>
                <article class="manset-article" data-category="<?php echo esc_attr(strtolower($category_name)); ?>" 
                         data-author="<?php echo esc_attr(get_the_author_meta('ID')); ?>">
                    <div class="article-header">
                        <span class="article-category"><?php echo esc_html($category_name); ?></span>
                        <span class="article-popularity">🔥 <?php echo $popularity; ?></span>
                    </div>
                    <h2 class="article-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <p class="article-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                    <div class="article-meta">
                        <span class="article-author">✍️ <?php the_author(); ?></span>
                        <span class="article-date">📅 <?php echo get_the_date('d F Y H:i'); ?></span>
                    </div>
                    <div class="article-share">
                        <?php mi_render_social_share(get_the_ID(), true); ?>
                    </div>
                </article>
            <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="no-articles">Henüz haber bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var $articles = $('.manset-article');
    var $category = $('#filter-category');
    var $author = $('#filter-author');
    var $sort = $('#filter-sort');
    
    function filterArticles() {
        var category = $category.val();
        var author = $author.val();
        var sort = $sort.val();
        
        var $filtered = $articles.filter(function() {
            var matchCategory = !category || $(this).data('category') === category.toLowerCase();
            var matchAuthor = !author || $(this).data('author') == author;
            return matchCategory && matchAuthor;
        });
        
        $articles.hide();
        $filtered.show();
        
        // Sort
        var $container = $('#manset-articles');
        var sorted = $filtered.toArray();
        
        if (sort === 'date-asc') {
            sorted.sort(function(a, b) {
                return $(a).find('.article-date').text() > $(b).find('.article-date').text() ? 1 : -1;
            });
        } else if (sort === 'date-desc') {
            sorted.sort(function(a, b) {
                return $(a).find('.article-date').text() < $(b).find('.article-date').text() ? 1 : -1;
            });
        }
        
        $.each(sorted, function(i, el) {
            $container.append(el);
        });
    }
    
    $category.add($author).add($sort).on('change', filterArticles);
    $('#reset-filters').on('click', function() {
        $category.val('');
        $author.val('');
        $sort.val('date-desc');
        $articles.show();
    });
});
</script>

