<?php
/**
 * The main template file
 */

get_header();
?>

<main>
    <div class="container">
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
                                <?php if (get_the_category()) : ?>
                                    <span class="post-category">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="post-read-more">
                                <a href="<?php the_permalink(); ?>" class="read-more-link">Devamını Oku →</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'prev_text' => '&laquo; Önceki',
                    'next_text' => 'Sonraki &raquo;',
                    'mid_size' => 2,
                ));
                ?>
            </div>
        <?php else : ?>
            <div class="no-posts">
                <p>Henüz içerik bulunmamaktadır.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>

