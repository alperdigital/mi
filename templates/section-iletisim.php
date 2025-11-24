<?php
/**
 * Template: İletişim
 */

$post_id = get_the_ID();
$is_front_page = is_front_page();
$contact_email = get_post_meta($post_id, '_mi_iletisim_email', true) ?: get_option('admin_email');
$response_time = get_post_meta($post_id, '_mi_iletisim_response_time', true) ?: '24-48 Saat';
$intro_title = get_post_meta($post_id, '_mi_iletisim_intro_title', true) ?: 'Yazılarınızı Paylaşın';
$intro_text = get_post_meta($post_id, '_mi_iletisim_intro_text', true) ?: 'Görüşlerinizi, önerilerinizi ve yazılarınızı bizimle paylaşın. Değerli katkılarınız yayınlanabilir ve toplumla paylaşılabilir.';
?>

<div class="iletisim-section <?php echo $is_front_page ? 'front-page-iletisim' : ''; ?>">
    <div class="iletisim-intro">
        <div class="intro-icon">📧</div>
        <h2>Bize Ulaşın</h2>
        <h1><?php echo esc_html($intro_title); ?></h1>
        <p><?php echo esc_html($intro_text); ?></p>
    </div>
    
    <div class="iletisim-content">
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


