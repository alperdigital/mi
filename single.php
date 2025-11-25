<?php
/**
 * Single Post Template
 */

get_header();
?>

<main>
    <div class="container">
        <div class="content-wrapper <?php echo mi_has_sidebar() ? 'has-sidebar' : 'no-sidebar'; ?>">
            <div class="main-content">
        <?php while (have_posts()) : the_post(); ?>
            <?php mi_breadcrumbs(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                <header class="post-header">
                    <?php if (get_the_category()) : ?>
                        <div class="post-category-badge">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    
                    <div class="post-meta">
                        <span class="post-author">
                            <span class="author-icon">✍️</span>
                            <span><?php the_author(); ?></span>
                        </span>
                        <span class="post-date">
                            <span class="date-icon">📅</span>
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date('d F Y H:i'); ?>
                            </time>
                        </span>
                        <span class="post-views-meta">
                            <?php echo mi_display_post_views(); ?>
                        </span>
                        <?php do_action('mi_post_meta'); ?>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-featured-image">
                        <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
                    </div>
                <?php endif; ?>
                
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
                
                <div class="post-share-section">
                    <h3 class="share-title">Bu Haberi Paylaş</h3>
                    <?php mi_render_social_share(get_the_ID(), false); ?>
                </div>
                
                <?php
                // Tags
                $tags = get_the_tags();
                if ($tags) :
                    ?>
                    <div class="post-tags">
                        <span class="tags-label">Etiketler:</span>
                        <?php
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link">' . esc_html($tag->name) . '</a>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </article>
            
            <?php
            // Comments - Opsiyonel, default kapalı
            $enable_comments = get_option('mi_enable_comments', '0') === '1';
            if ($enable_comments && (comments_open() || get_comments_number())) {
                comments_template();
            }
            ?>
            
            <?php
            // Related posts
            $related = get_posts(array(
                'category__in' => wp_get_post_categories(get_the_ID()),
                'numberposts' => 3,
                'post__not_in' => array(get_the_ID())
            ));
            
            if ($related) :
                ?>
                <div class="related-posts">
                    <h3 class="related-title">İlgili Haberler</h3>
                    <div class="related-posts-grid">
                        <?php foreach ($related as $post) : setup_postdata($post); ?>
                            <article class="related-post-item">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="related-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <h4 class="related-post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="related-post-meta">
                                    <span><?php echo get_the_date(); ?></span>
                                </div>
                            </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php endwhile; ?>
            </div>
            
            <?php if (mi_has_sidebar()) : ?>
                <?php get_sidebar(); ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
?>

