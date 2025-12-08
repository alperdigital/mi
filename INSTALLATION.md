# MI Theme Kurulum Talimatları

## Sorun: "Stylesheet is missing" Hatası

WordPress'te "Stylesheet is missing" hatası alıyorsanız, aşağıdaki adımları takip edin:

### 1. Tema Klasörünün Konumu

Tema klasörü şu konumda olmalıdır:
```
wp-content/themes/mi/
```

**Önemli:** Tema klasörünün adı `mi` olmalıdır.

### 2. Gerekli Dosyalar

Aşağıdaki dosyalar tema klasörünün root'unda olmalıdır:
- ✓ `style.css` (tema başlık bilgileri ile)
- ✓ `functions.php`
- ✓ `index.php`
- ✓ `header.php`
- ✓ `footer.php`

### 3. Dosya İzinleri

Dosya izinlerini kontrol edin:
```bash
# Dosyalar için
chmod 644 *.php *.css

# Klasörler için
chmod 755 inc assets templates amp
```

### 4. style.css Kontrolü

`style.css` dosyasının en üstünde şu bilgiler olmalıdır:
```css
/*
Theme Name: MI Theme
Theme URI: https://github.com/alperdigital/mi
Author: Alperdigital
Author URI: https://github.com/alperdigital
Description: A custom WordPress theme inspired by Cumhuriyet.com.tr
Version: 1.0
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: mi-theme
Tags: custom, responsive, modern
*/
```

### 5. WordPress Cache Temizleme

1. WordPress admin paneline giriş yapın
2. Eklentiler > Cache eklentilerini devre dışı bırakın
3. Tema klasörünü kontrol edin
4. WordPress'i yeniden başlatın (gerekirse)

### 6. Manuel Kontrol

Tema klasörünü kontrol etmek için:
```bash
cd wp-content/themes/mi
ls -la style.css functions.php index.php
```

Bu dosyaların hepsi görünüyor olmalı.

### 7. Hala Sorun Varsa

1. Tema klasörünü silin
2. Temiz bir kopya yükleyin
3. Dosya izinlerini düzeltin
4. WordPress cache'ini temizleyin

## Hızlı Çözüm

Eğer tema `wp-content/themes/` dışındaysa:

```bash
# Tema klasörünü WordPress teması klasörüne kopyalayın
cp -r /path/to/mi /path/to/wordpress/wp-content/themes/

# Veya taşıyın
mv /path/to/mi /path/to/wordpress/wp-content/themes/
```

Sonra WordPress admin panelinde Temalar sayfasını yenileyin.

