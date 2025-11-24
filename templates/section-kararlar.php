<?php
/**
 * Template: Kararlar (Karar Listesi)
 */

$post_id = get_the_ID();
?>

<div class="kararlar-section">
    <div class="kararlar-filters">
        <div class="filter-group">
            <label for="filter-dava">⚖️ Dava Türü:</label>
            <select id="filter-dava" class="filter-select">
                <option value="">Tümü</option>
                <option value="ticaret">Ticaret</option>
                <option value="ceza">Ceza</option>
                <option value="is">İş</option>
                <option value="aile">Aile</option>
                <option value="gayrimenkul">Gayrimenkul</option>
                <option value="tuketici">Tüketici</option>
                <option value="icra">İcra</option>
                <option value="idare">İdare</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="filter-mahkeme">🏛️ Mahkeme:</label>
            <select id="filter-mahkeme" class="filter-select">
                <option value="">Tümü</option>
                <option value="11-hukuk">11. Hukuk Dairesi</option>
                <option value="15-hukuk">15. Hukuk Dairesi</option>
                <option value="2-hukuk">2. Hukuk Dairesi</option>
                <option value="3-ceza">3. Ceza Dairesi</option>
                <option value="6-hukuk">6. Hukuk Dairesi</option>
                <option value="9-hukuk">9. Hukuk Dairesi</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="filter-sort">🔀 Sırala:</label>
            <select id="filter-sort" class="filter-select">
                <option value="date-desc">Tarih (Yeni → Eski)</option>
                <option value="date-asc">Tarih (Eski → Yeni)</option>
                <option value="importance-desc">Önem Derecesi (Yüksek → Düşük)</option>
                <option value="importance-asc">Önem Derecesi (Düşük → Yüksek)</option>
                <option value="number-asc">Karar No (A → Z)</option>
                <option value="number-desc">Karar No (Z → A)</option>
            </select>
        </div>
        
        <button type="button" class="filter-reset-btn" id="reset-filters">🔄 Sıfırla</button>
    </div>
    
    <div class="kararlar-list" id="kararlar-list">
        <?php
        // Custom post type veya meta field'dan kararları çek
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10,
            'meta_key' => '_is_karar',
            'meta_value' => '1',
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $dava_turu = get_post_meta(get_the_ID(), '_karar_dava_turu', true) ?: 'Genel';
                $mahkeme = get_post_meta(get_the_ID(), '_karar_mahkeme', true) ?: 'Genel Mahkeme';
                $karar_no = get_post_meta(get_the_ID(), '_karar_no', true) ?: '2024/' . rand(10000, 99999);
                $importance = get_post_meta(get_the_ID(), '_karar_importance', true) ?: rand(70, 100);
                ?>
                <article class="karar-article" 
                         data-dava="<?php echo esc_attr(strtolower($dava_turu)); ?>"
                         data-mahkeme="<?php echo esc_attr(strtolower(str_replace(' ', '-', $mahkeme))); ?>"
                         data-importance="<?php echo esc_attr($importance); ?>">
                    <div class="karar-header">
                        <span class="karar-dava-turu"><?php echo esc_html($dava_turu); ?></span>
                        <span class="karar-importance">⭐ <?php echo $importance; ?></span>
                    </div>
                    <div class="karar-no">📋 Karar No: <?php echo esc_html($karar_no); ?></div>
                    <h2 class="karar-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <p class="karar-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    <div class="karar-meta">
                        <span class="karar-mahkeme">🏛️ <?php echo esc_html($mahkeme); ?></span>
                        <span class="karar-date">📅 <?php echo get_the_date('d F Y'); ?></span>
                    </div>
                    <div class="karar-share">
                        <?php mi_render_social_share(get_the_ID(), true); ?>
                    </div>
                </article>
            <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="no-kararlar">Henüz karar bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var $articles = $('.karar-article');
    var $dava = $('#filter-dava');
    var $mahkeme = $('#filter-mahkeme');
    var $sort = $('#filter-sort');
    
    function filterKararlar() {
        var dava = $dava.val();
        var mahkeme = $mahkeme.val();
        var sort = $sort.val();
        
        var $filtered = $articles.filter(function() {
            var matchDava = !dava || $(this).data('dava') === dava.toLowerCase();
            var matchMahkeme = !mahkeme || $(this).data('mahkeme') === mahkeme.toLowerCase();
            return matchDava && matchMahkeme;
        });
        
        $articles.hide();
        $filtered.show();
        
        // Sort
        var $container = $('#kararlar-list');
        var sorted = $filtered.toArray();
        
        if (sort === 'importance-desc') {
            sorted.sort(function(a, b) {
                return $(b).data('importance') - $(a).data('importance');
            });
        } else if (sort === 'importance-asc') {
            sorted.sort(function(a, b) {
                return $(a).data('importance') - $(b).data('importance');
            });
        }
        
        $.each(sorted, function(i, el) {
            $container.append(el);
        });
    }
    
    $dava.add($mahkeme).add($sort).on('change', filterKararlar);
    $('#reset-filters').on('click', function() {
        $dava.val('');
        $mahkeme.val('');
        $sort.val('date-desc');
        $articles.show();
    });
});
</script>

