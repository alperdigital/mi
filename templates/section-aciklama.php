<?php
/**
 * Template: Açıklama (Sadece İçerik)
 * Yazının iki kere geçmemesi için sadece içeriği gösterir
 */

$post_id = get_the_ID();
?>

<div class="aciklama-section">
    <div class="container">
        <div class="aciklama-content-wrapper">
            <?php 
            // Sadece içeriği göster, excerpt gösterme
            // the_content() kullanarak otomatik excerpt oluşturulmasını engelle
            $content = get_the_content();
            // Excerpt'i temizle
            remove_filter('the_content', 'wpautop');
            $content = apply_filters('the_content', $content);
            add_filter('the_content', 'wpautop');
            echo $content;
            ?>
        </div>
    </div>
</div>

