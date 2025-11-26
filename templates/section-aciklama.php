<?php
/**
 * Template: Açıklama (Sadece İçerik)
 * Yazının iki kere geçmemesi için sadece içeriği gösterir
 */

$post_id = get_the_ID();
$signature_count = intval(get_post_meta($post_id, '_mi_aciklama_signatures', true));
$user_signed = isset($_COOKIE['mi_signed_' . $post_id]) && $_COOKIE['mi_signed_' . $post_id] === '1';
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
        
        <div class="aciklama-signature">
            <button type="button" 
                    class="signature-btn <?php echo $user_signed ? 'signed' : ''; ?>" 
                    data-post-id="<?php echo esc_attr($post_id); ?>"
                    <?php echo $user_signed ? 'disabled' : ''; ?>>
                <span class="signature-icon">✍️</span>
                <span class="signature-text"><?php echo $user_signed ? 'İmzanız Atıldı' : 'İmza At'; ?></span>
                <span class="signature-count">(<span class="count-number"><?php echo number_format_i18n($signature_count); ?></span>)</span>
            </button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.signature-btn').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var postId = $btn.data('post-id');
        
        if ($btn.hasClass('signed') || $btn.prop('disabled')) {
            return;
        }
        
        // AJAX request
        $.ajax({
            url: mi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'mi_add_signature',
                post_id: postId,
                nonce: mi_ajax.nonce
            },
            beforeSend: function() {
                $btn.prop('disabled', true);
                $btn.addClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    $btn.addClass('signed');
                    $btn.find('.signature-text').text('İmzanız Atıldı');
                    $btn.find('.count-number').text(response.data.count);
                    
                    // Cookie set et (30 gün)
                    var date = new Date();
                    date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
                    document.cookie = 'mi_signed_' + postId + '=1; expires=' + date.toUTCString() + '; path=/';
                } else {
                    alert(response.data.message || 'Bir hata oluştu.');
                    $btn.prop('disabled', false);
                    $btn.removeClass('loading');
                }
            },
            error: function() {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                $btn.prop('disabled', false);
                $btn.removeClass('loading');
            }
        });
    });
});
</script>

