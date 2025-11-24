<?php
/**
 * Template: İletişim (Form)
 */

$post_id = get_the_ID();
$contact_email = get_post_meta($post_id, '_mi_iletisim_email', true) ?: get_option('admin_email');
$response_time = get_post_meta($post_id, '_mi_iletisim_response_time', true) ?: '24-48 Saat';
?>

<div class="iletisim-section">
    <div class="iletisim-intro">
        <div class="intro-icon">📧</div>
        <h2>Bize Ulaşın</h2>
        <h1>Yazılarınızı Paylaşın</h1>
        <p>Görüşlerinizi, önerilerinizi ve yazılarınızı bizimle paylaşın. Değerli katkılarınız yayınlanabilir ve toplumla paylaşılabilir.</p>
    </div>
    
    <div class="iletisim-content">
        <div class="iletisim-form-wrapper">
            <h2>✍️ Bize Yazın</h2>
            <p>Aşağıdaki formu doldurarak bizimle iletişime geçebilirsiniz. Yazılarınız, görüşleriniz ve önerileriniz değerlendirilerek yayınlanabilir.</p>
            
            <form id="iletisim-form" class="iletisim-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('mi_contact_form', 'mi_contact_nonce'); ?>
                <input type="hidden" name="action" value="mi_handle_contact_form">
                
                <div class="form-group">
                    <label for="contact-name">👤 Adınız Soyadınız</label>
                    <input type="text" id="contact-name" name="contact_name" 
                           placeholder="Adınız ve soyadınız" required />
                </div>
                
                <div class="form-group">
                    <label for="contact-email">📧 E-posta Adresiniz</label>
                    <input type="email" id="contact-email" name="contact_email" 
                           placeholder="ornek@email.com" required />
                    <p class="form-hint">Yazınız yayınlandığında size bilgi verilecektir.</p>
                </div>
                
                <div class="form-group">
                    <label for="contact-subject">📝 Konu</label>
                    <select id="contact-subject" name="contact_subject" required>
                        <option value="">Konu seçiniz</option>
                        <option value="yazi-gonderimi">Yazı Gönderimi</option>
                        <option value="gorus-oneri">Görüş ve Öneri</option>
                        <option value="soru">Soru</option>
                        <option value="diger">Diğer</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="contact-message">💬 Mesajınız / Yazınız</label>
                    <textarea id="contact-message" name="contact_message" rows="8" 
                              placeholder="Mesajınızı veya yazınızı buraya yazın..." required></textarea>
                    <p class="form-hint">
                        <strong>💡 İpucu:</strong> Yazılarınız yayınlanmak üzere değerlendirilecektir. Yazılarınızın orijinal ve özgün olması önemlidir.
                    </p>
                </div>
                
                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="publish_permission" value="1" required />
                        Yazımın yayınlanmasına izin veriyorum
                    </label>
                </div>
                
                <button type="submit" class="submit-btn">
                    <span>📤</span>
                    <span>Gönder</span>
                </button>
            </form>
        </div>
        
        <div class="iletisim-info">
            <h2>📞 İletişim Bilgileri</h2>
            
            <div class="info-item">
                <div class="info-icon">📧</div>
                <div class="info-content">
                    <h3>E-posta</h3>
                    <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
                    <p>Yazılarınızı ve görüşlerinizi e-posta ile gönderebilirsiniz.</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">⏰</div>
                <div class="info-content">
                    <h3>Yanıt Süresi</h3>
                    <p class="info-value"><?php echo esc_html($response_time); ?></p>
                    <p>Mesajlarınıza en kısa sürede yanıt vermeye çalışıyoruz.</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">📝</div>
                <div class="info-content">
                    <h3>Yazı Değerlendirme</h3>
                    <p class="info-value">3-5 Gün</p>
                    <p>Gönderdiğiniz yazılar değerlendirilerek yayınlanabilir.</p>
                </div>
            </div>
        </div>
        
        <div class="iletisim-rules">
            <h2>📋 Yazı Gönderme Kuralları</h2>
            
            <div class="rule-item">
                <div class="rule-icon">✅</div>
                <div class="rule-content">
                    <h3>Orijinal İçerik</h3>
                    <p>Gönderdiğiniz yazıların orijinal ve özgün olması gerekmektedir. Başka kaynaklardan alıntı yapıyorsanız kaynak belirtiniz.</p>
                </div>
            </div>
            
            <div class="rule-item">
                <div class="rule-icon">📝</div>
                <div class="rule-content">
                    <h3>Uygun Dil</h3>
                    <p>Yazılarınızda saygılı ve uygun bir dil kullanınız. Nefret söylemi, ayrımcılık veya saldırgan içerik içeren yazılar yayınlanmayacaktır.</p>
                </div>
            </div>
            
            <div class="rule-item">
                <div class="rule-icon">📏</div>
                <div class="rule-content">
                    <h3>Uzunluk</h3>
                    <p>Yazılarınızın en az 200 kelime olması önerilir. Ancak daha kısa veya uzun yazılar da değerlendirilebilir.</p>
                </div>
            </div>
            
            <div class="rule-item">
                <div class="rule-icon">🔍</div>
                <div class="rule-content">
                    <h3>Değerlendirme</h3>
                    <p>Gönderilen tüm yazılar editörlerimiz tarafından değerlendirilir. Yayınlanma kararı editörlerimize aittir.</p>
                </div>
            </div>
        </div>
        
        <div class="iletisim-quick">
            <h2>🚀 Hızlı İletişim</h2>
            <p>Doğrudan e-posta göndermek isterseniz:</p>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>?subject=Yazı Gönderimi&body=Merhaba,%0D%0A%0D%0AYazımı paylaşmak istiyorum." 
               class="quick-email-btn">
                <span>📧</span>
                <span>E-posta Gönder</span>
            </a>
        </div>
    </div>
</div>

<?php
// Handle form submission
function mi_handle_contact_form() {
    if (!isset($_POST['mi_contact_nonce']) || !wp_verify_nonce($_POST['mi_contact_nonce'], 'mi_contact_form')) {
        wp_die('Güvenlik kontrolü başarısız.');
    }
    
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    $publish_permission = isset($_POST['publish_permission']) ? 'Evet' : 'Hayır';
    
    // Email gönder
    $to = get_option('admin_email');
    $email_subject = 'Yeni İletişim Formu: ' . $subject;
    $email_body = "Ad Soyad: $name\nE-posta: $email\nKonu: $subject\n\nMesaj:\n$message\n\nYayın İzni: $publish_permission";
    
    wp_mail($to, $email_subject, $email_body, array('From: ' . $name . ' <' . $email . '>'));
    
    wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_mi_handle_contact_form', 'mi_handle_contact_form');
add_action('admin_post_nopriv_mi_handle_contact_form', 'mi_handle_contact_form');
?>

