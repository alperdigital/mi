# MI Tema - Clean Code Architecture

## 📁 Dosya Yapısı (Ağaç Diyagramı)

```
mi/
├── functions.php                    # Ana tema dosyası (sadece setup ve autoloader)
├── style.css                        # Ana stil dosyası
├── header.php                       # Header template
├── footer.php                       # Footer template
├── index.php                        # Ana template
├── single.php                       # Tekil yazı template
├── front-page.php                   # Ana sayfa template
├── single-mi_section.php            # Bölüm template
│
├── inc/
│   ├── core/                        # Temel fonksiyonlar (her zaman yüklü)
│   │   ├── autoloader.php          # Dosya yükleme yöneticisi
│   │   ├── template-functions.php  # Template helper fonksiyonları
│   │   ├── social-share.php        # Sosyal medya paylaşım
│   │   ├── social-functions.php    # Sosyal medya fonksiyonları
│   │   ├── breadcrumbs.php         # Breadcrumb navigasyon
│   │   ├── comments.php            # Yorum sistemi
│   │   ├── seo.php                 # SEO optimizasyonları
│   │   ├── mobile-menu.php         # Mobil menü
│   │   ├── scroll-to-top.php       # Yukarı kaydırma butonu
│   │   ├── post-views.php          # Yazı görüntülenme sayacı
│   │   ├── lightbox.php            # Lightbox galeri
│   │   ├── gutenberg-blocks.php    # Gutenberg blokları
│   │   ├── widgets.php             # Widget alanları
│   │   ├── additional-widgets.php  # Ek widget'lar
│   │   ├── popular-posts-widget.php # Popüler yazılar widget'ı
│   │   ├── turkish-archives.php   # Türkçe arşiv desteği
│   │   ├── ajax-handlers.php       # AJAX işleyicileri
│   │   ├── feature-integration.php # Özellik entegrasyonları
│   │   └── modules.php              # Modül sistemi
│   │
│   ├── features/                    # Opsiyonel özellikler (conditional loading)
│   │   ├── dark-mode.php           # Karanlık mod
│   │   ├── infinite-scroll.php    # Sonsuz kaydırma
│   │   ├── masonry-grid.php        # Masonry grid layout
│   │   ├── parallax.php            # Parallax efektleri
│   │   ├── webp-support.php        # WebP görsel desteği
│   │   ├── recaptcha.php           # reCAPTCHA entegrasyonu
│   │   ├── table-of-contents.php   # İçindekiler tablosu
│   │   ├── syntax-highlighting.php # Kod vurgulama
│   │   ├── newsletter.php          # E-posta abonelik
│   │   ├── cookie-consent.php      # Çerez onayı
│   │   ├── loading-skeleton.php    # Yükleme skeleton
│   │   ├── amp.php                 # AMP desteği
│   │   ├── media-player.php        # Medya oynatıcı
│   │   ├── advanced-stats.php      # Gelişmiş istatistikler
│   │   └── rtl-support.php         # RTL dil desteği
│   │
│   ├── admin/                       # Admin paneli dosyaları
│   │   ├── sections.php            # Bölüm yönetimi (clean naming)
│   │   ├── customizer.php          # WordPress Customizer
│   │   ├── options.php             # Tema ayarları sayfası (clean naming)
│   │   ├── ui.php                  # Admin UI iyileştirmeleri (clean naming)
│   │   └── demo.php                # Demo içerik içe aktarma (clean naming)
│   │
│   └── utils/                       # Yardımcı fonksiyonlar
│       └── helpers.php              # Genel helper fonksiyonları
│
├── templates/                       # Section template'leri
│   ├── section-aciklama.php
│   ├── section-manset.php
│   ├── section-kararlar.php
│   ├── section-iletisim.php
│   ├── section-custom.php
│   └── section-default.php
│
└── assets/                          # Statik dosyalar
    ├── css/
    └── js/
```

## 🏗️ Clean Code Prensipleri

### 1. Single Responsibility Principle (SRP)
Her dosya ve fonksiyon tek bir sorumluluğa sahiptir:
- `template-functions.php`: Sadece template helper fonksiyonları
- `social-share.php`: Sadece sosyal medya paylaşım fonksiyonları
- `ajax-handlers.php`: Sadece AJAX işleyicileri

### 2. Dependency Injection
- Autoloader pattern kullanılarak dosyalar otomatik yüklenir
- Conditional loading ile sadece aktif özellikler yüklenir
- Loose coupling: Dosyalar birbirine sıkı bağlı değil

### 3. DRY (Don't Repeat Yourself)
- Helper fonksiyonlar `inc/utils/helpers.php` içinde toplanmış
- Kod tekrarları kaldırılmış
- Ortak fonksiyonlar merkezi konumda

### 4. Separation of Concerns
- **Core**: Temel tema fonksiyonları
- **Features**: Opsiyonel özellikler
- **Admin**: Admin paneli işlevleri
- **Utils**: Yardımcı fonksiyonlar
- **Templates**: Görüntüleme katmanı

### 5. Open/Closed Principle
- Yeni özellikler eklemek için mevcut kodu değiştirmeye gerek yok
- `inc/features/` klasörüne yeni dosya eklenerek özellik eklenebilir
- Autoloader otomatik olarak yeni dosyaları yükler

## 📊 Dosya Yükleme Sırası

1. **Utils** (`inc/utils/helpers.php`) - İlk yüklenir, diğer dosyalar tarafından kullanılır
2. **Core** - Temel fonksiyonlar
3. **Admin** - Sadece admin context'inde
4. **Features** - Sadece aktif olanlar
5. **Integration** - Özellikler arası entegrasyon
6. **Modules** - Modül sistemi

## 🔧 Naming Conventions

- **Fonksiyonlar**: `mi_` prefix ile başlar
- **Class'lar**: `MI_` prefix ile başlar
- **Meta keys**: `_mi_` prefix ile başlar
- **Dosyalar**: kebab-case (örn: `template-functions.php`)

## 🚀 Ölçeklenebilirlik

- Yeni özellik eklemek: `inc/features/` klasörüne dosya ekle
- Yeni admin sayfası: `inc/admin/` klasörüne dosya ekle
- Yeni helper: `inc/utils/helpers.php` dosyasına ekle
- Autoloader otomatik olarak yükler

## 📝 Geliştirme Notları

- Tüm dosyalar `ABSPATH` kontrolü yapar
- Fonksiyonlar `function_exists()` kontrolü yapar
- Conditional loading performansı artırır
- Clean code prensipleri takip edilir

