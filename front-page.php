<?php
/**
 * Front Page Template
 * Ana sayfa template'i - Section'ları güzel bir şekilde gösterir
 */

get_header();
?>

<main class="front-page-main">
    <?php
    // Aktif section'ları sırayla göster
    $sections = get_posts(array(
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
    ));
    
    if (!empty($sections)) :
        foreach ($sections as $section) :
            $section_type = get_post_meta($section->ID, '_mi_section_type', true);
            $section_name = mi_get_section_name($section->ID);
            ?>
            <section class="front-page-section section-<?php echo esc_attr($section_type); ?>" id="section-<?php echo esc_attr($section->ID); ?>">
                <?php
                // Section içeriğini göster
                setup_postdata($section);
                
                // Section tipine göre template yükle
                if (function_exists('mi_render_section_template')) {
                    // Tüm section tiplerinde başlık göster
                    ?>
                    <div class="container">
                        <div class="section-header">
                            <h2 class="section-title" <?php if (strpos($section_name, '#') !== false) : ?>data-has-hash="true"<?php endif; ?>><?php 
                                // # içeren başlıklarda her hashtag'i ayrı satıra al
                                if (strpos($section_name, '#') !== false) {
                                    // Her # karakterinden önceki boşluğu yeni satıra çevir
                                    $formatted_name = preg_replace('/\s+#/', "\n#", $section_name);
                                    // Eğer başta # varsa ve önünde boşluk yoksa, ilk #'dan sonraki boşluğu kontrol et
                                    // Her # karakterinden sonraki kelimeyi ayrı satıra al
                                    $formatted_name = preg_replace('/(#\S+)\s+(#\S+)/', "$1\n$2", $formatted_name);
                                    echo nl2br(esc_html($formatted_name));
                                } else {
                                    echo esc_html($section_name);
                                }
                            ?></h2>
                        </div>
                    </div>
                    <?php
                    mi_render_section_template($section);
                } else {
                    // Fallback: Basit içerik gösterimi
                    ?>
                    <div class="container">
                        <div class="section-header">
                            <h2 class="section-title" <?php if (strpos($section_name, '#') !== false) : ?>data-has-hash="true"<?php endif; ?>><?php 
                                // # içeren başlıklarda her hashtag'i ayrı satıra al
                                if (strpos($section_name, '#') !== false) {
                                    // Her # karakterinden önceki boşluğu yeni satıra çevir
                                    $formatted_name = preg_replace('/\s+#/', "\n#", $section_name);
                                    // Eğer başta # varsa ve önünde boşluk yoksa, ilk #'dan sonraki boşluğu kontrol et
                                    // Her # karakterinden sonraki kelimeyi ayrı satıra al
                                    $formatted_name = preg_replace('/(#\S+)\s+(#\S+)/', "$1\n$2", $formatted_name);
                                    echo nl2br(esc_html($formatted_name));
                                } else {
                                    echo esc_html($section_name);
                                }
                            ?></h2>
                        </div>
                        <div class="section-content">
                            <?php 
                            // Sadece içeriği göster, excerpt gösterme
                            $content = apply_filters('the_content', $section->post_content);
                            echo $content;
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </section>
            <?php
        endforeach;
        wp_reset_postdata();
    else :
        // Section yoksa normal blog listesi göster
        ?>
        <div class="container">
            <div class="content-wrapper">
                <div class="main-content">
                    <?php if (have_posts()) : ?>
                        <div class="posts-container">
                            <?php while (have_posts()) : the_post(); ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', array('alt' => get_the_title())); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-content-wrapper">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="post-meta">
                                            <span class="post-date">
                                                <time datetime="<?php echo get_the_date('c'); ?>">
                                                    <?php echo get_the_date(); ?>
                                                </time>
                                            </span>
                                        </div>
                                        
                                        <div class="post-excerpt">
                                            <?php 
                                            if (has_excerpt()) {
                                                echo wp_kses_post(get_the_excerpt());
                                            } else {
                                                $content = get_the_content();
                                                $content = strip_tags($content);
                                                $excerpt = wp_trim_words($content, 30, '...');
                                                echo esc_html($excerpt);
                                            }
                                            ?>
                                        </div>
                                        
                                        <div class="post-actions">
                                            <a href="<?php the_permalink(); ?>" class="read-more-link">Devamını Oku →</a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    endif;
    ?>
</main>

<?php
get_footer();
?>

