<?php
/**
 * Social Media Share Functions
 */

// Get social share URLs
function mi_get_facebook_share_url($url, $title = '') {
    return 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url);
}

function mi_get_twitter_share_url($url, $title = '') {
    $text = $title ?: get_the_title();
    return 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($text);
}

function mi_get_linkedin_share_url($url, $title = '') {
    return 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url);
}

function mi_get_whatsapp_share_url($url, $title = '') {
    $text = $title ?: get_the_title();
    return 'https://wa.me/?text=' . urlencode($text . ' ' . $url);
}

function mi_get_telegram_share_url($url, $title = '') {
    $text = $title ?: get_the_title();
    return 'https://t.me/share/url?url=' . urlencode($url) . '&text=' . urlencode($text);
}

function mi_get_email_share_url($url, $title = '') {
    $subject = $title ?: get_the_title();
    $body = __('Bu haberi paylaşmak istiyorum: ', 'mi-theme') . $title . ' - ' . $url;
    return 'mailto:?subject=' . urlencode($subject) . '&body=' . urlencode($body);
}

// Render social share buttons
function mi_render_social_share($post_id = null, $compact = false) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $url = get_permalink($post_id);
    $title = get_the_title($post_id);
    $excerpt = get_the_excerpt($post_id);
    
    $class = $compact ? 'social-share-compact' : 'social-share';
    
    ?>
    <div class="<?php echo esc_attr($class); ?>">
        <span class="share-label">Paylaş:</span>
        <div class="share-buttons">
            <a href="<?php echo esc_url(mi_get_facebook_share_url($url, $title)); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="share-btn share-facebook" 
               title="Facebook'ta Paylaş"
               aria-label="Facebook'ta Paylaş">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <?php if (!$compact) : ?><span>Facebook</span><?php endif; ?>
            </a>
            
            <a href="<?php echo esc_url(mi_get_twitter_share_url($url, $title)); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="share-btn share-twitter" 
               title="Twitter'da Paylaş"
               aria-label="Twitter'da Paylaş">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
                <?php if (!$compact) : ?><span>Twitter</span><?php endif; ?>
            </a>
            
            <a href="<?php echo esc_url(mi_get_linkedin_share_url($url, $title)); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="share-btn share-linkedin" 
               title="LinkedIn'de Paylaş"
               aria-label="LinkedIn'de Paylaş">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                <?php if (!$compact) : ?><span>LinkedIn</span><?php endif; ?>
            </a>
            
            <a href="<?php echo esc_url(mi_get_whatsapp_share_url($url, $title)); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="share-btn share-whatsapp" 
               title="WhatsApp'ta Paylaş"
               aria-label="WhatsApp'ta Paylaş">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <?php if (!$compact) : ?><span>WhatsApp</span><?php endif; ?>
            </a>
            
            <a href="<?php echo esc_url(mi_get_telegram_share_url($url, $title)); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="share-btn share-telegram" 
               title="Telegram'da Paylaş"
               aria-label="Telegram'da Paylaş">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15.056-.213s.13-.08.188-.048c.06.027.38 1.182.543 1.697.14.39.28.59.51.59.13 0 .28-.05.38-.14a.9.9 0 0 1 .54-.19c.1 0 .2.01.3.03.1.02.2.05.3.08.1.03.2.07.3.1.1.03.2.06.3.09.1.03.2.05.3.07.1.02.2.03.3.04.1.01.2.02.3.02.1 0 .2.01.3.01.1 0 .2 0 .3-.01.1-.01.2-.02.3-.03.1-.01.2-.03.3-.05.1-.02.2-.04.3-.07.1-.03.2-.06.3-.1.1-.03.2-.06.3-.08.1-.02.2-.04.3-.06.1-.02.2-.03.3-.04.1-.01.2-.01.3-.01z"/>
                </svg>
                <?php if (!$compact) : ?><span>Telegram</span><?php endif; ?>
            </a>
            
            <a href="<?php echo esc_url(mi_get_email_share_url($url, $title)); ?>" 
               class="share-btn share-email" 
               title="E-posta ile Paylaş"
               aria-label="E-posta ile Paylaş">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                </svg>
                <?php if (!$compact) : ?><span>E-posta</span><?php endif; ?>
            </a>
        </div>
    </div>
    <?php
}

