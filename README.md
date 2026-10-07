# renewed-alisveris-sepeti-eticaret
# Alışveriş — Teknoloji Seçkisi

## Demo Hesabı
- Kullanıcı Adı: demo
- Şifre : 123

XAMPP üzerinde çalışan, PHP ve MariaDB/MySQL tabanlı modern e-ticaret demo uygulaması. Proje; ürün kataloğu, varyant seçimi, sepet, üyelik, adres yönetimi, demo ödeme akışı, sipariş geçmişi ve yönetim paneli özelliklerini içerir.

## Özellikler

### Mağaza

- Modern ve responsive ana sayfa
- Hero slider ve kampanya alanları
- Ürün arama ve kategori navigasyonu
- Model, renk ve hafıza varyantları
- Ürün detay sayfası
- Stok durumu ve ürün puanı gösterimi
- Kullanıcıya özel sepet
- Sepet adet artırma, azaltma ve temizleme
- Profil ve adres yönetimi
- İl, ilçe ve mahalle seçimi
- Demo ödeme ve sipariş oluşturma akışı
- Sipariş geçmişi
- Responsive mobil görünüm

### Yönetim paneli

- Admin yetkilendirmeli dashboard
- Kullanıcı, ürün, sipariş ve stok özetleri
- Modern ürün listesi
- Ürün arama ve düşük stok filtresi
- Güvenli POST tabanlı ürün silme
- Ürün ekleme formu
- JPG, PNG ve WEBP görsel yükleme
- Görsel MIME type ve dosya boyutu kontrolü
- Model ekleme ve kayıtlı model listesi
- Duplicate model kontrolü
- Responsive sidebar ve mobil admin menüsü
- Bildirim alanı ve mağazaya hızlı erişim

## Teknolojiler

- PHP 8.2+
- MariaDB / MySQL
- Apache
- HTML5
- CSS3
- Bootstrap 5 yardımcı sınıfları ve ikonları
- Vanilla JavaScript
- mysqli prepared statements

## Gereksinimler

- Windows
- XAMPP
- Apache
- MariaDB veya MySQL
- PHP 8.2 veya üzeri

Composer, npm veya Node.js kurulumu gerektirmez. Bootstrap dosyaları proje içinde tutulmaktadır.

## Kurulum

### 1. Projeyi XAMPP htdocs klasörüne yerleştirin

```text
C:\xampp\htdocs\alisveris
```

### 2. Apache ve MySQL servislerini çalıştırın

XAMPP Control Panel üzerinden:

- Apache
- MySQL

servislerini başlatın.

### 3. Veritabanını oluşturun

phpMyAdmin'i açın:

```text
http://localhost/phpmyadmin
```

`alisveris` isimli bir veritabanı oluşturun ve şu dosyayı içe aktarın:

```text
sql/alisveris.sql
```

Mevcut kurulumda kullanıcı şifreleri eski ve kısa formatta ise bcrypt desteği için şu migration dosyasını da çalıştırın:

```text
sql/upgrade_accounts.sql
```

### 4. Uygulamayı açın

```text
http://localhost/alisveris/
```

## Veritabanı bağlantısı

Bağlantı ayarları şu dosyada merkezi olarak tutulur:

```text
admin/baglanti.php
```

Varsayılan demo bağlantısı:

```text
Sunucu: localhost
Kullanıcı: root
Şifre: boş
Veritabanı: alisveris
Karakter seti: utf8mb4
```

Üretim ortamında varsayılan root hesabı kullanılmamalı, ayrı ve sınırlı yetkili bir veritabanı kullanıcısı tanımlanmalıdır.

## Yönetim paneli

Admin paneli:

```text
http://localhost/alisveris/admin/admin.php
```

Admin sayfaları `admin/baglanti.php` içindeki `checkAdmin()` kontrolünü kullanır. Kullanıcının hem aktif oturumu hem de `rol = admin` değeri bulunmalıdır.

Yönetim panelinden:

- Ürün eklenebilir
- Model oluşturulabilir
- Ürünler aranabilir
- Düşük stoklar filtrelenebilir
- Ürün silinebilir
- Kullanıcı ve mağaza özetleri görüntülenebilir

## Dizin yapısı

```text
alisveris/
├── admin/                 # Yönetim paneli ve admin ortak parçaları
│   └── a-parts/           # Admin head, sidebar ve üst bar parçaları
├── assets/
│   ├── css/               # Mağaza, hesap, auth ve admin stilleri
│   ├── img/               # Ürün, logo ve slider görselleri
│   └── js/                # Ortak, auth ve admin JavaScript dosyaları
├── parts/                 # Mağaza ortak parçaları ve adres endpointleri
├── sql/                   # Veritabanı dump ve migration dosyaları
├── test/                  # Eski bağımsız PHP pratik dosyaları
├── index.php              # Ana sayfa
├── telefon.php            # Ürün kataloğu
├── detay.php              # Ürün detay ve sepete ekleme
├── sepet.php              # Sepet
├── profil.php             # Profil ve adresler
├── odeme.php              # Demo ödeme ekranı
└── siparisler.php         # Sipariş geçmişi
```

## Doğrulama komutları

Projenin bir build adımı veya PHPUnit test paketi bulunmamaktadır. PHP syntax kontrolü için XAMPP PHP executable'ını kullanın.

Tek dosya kontrolü:

```powershell
& 'C:\xampp\php\php.exe' -l 'C:\xampp\htdocs\alisveris\index.php'
```

Tüm PHP dosyalarını kontrol etme:

```powershell
$files = Get-ChildItem 'C:\xampp\htdocs\alisveris' -Recurse -Filter *.php
foreach ($file in $files) {
  & 'C:\xampp\php\php.exe' -l $file.FullName
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
}
```

Sadece admin dosyalarını kontrol etme:

```powershell
$files = Get-ChildItem 'C:\xampp\htdocs\alisveris\admin' -Recurse -Filter *.php
foreach ($file in $files) {
  & 'C:\xampp\php\php.exe' -l $file.FullName
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
}
```

Basit HTTP kontrolü:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/alisveris/index.php'
Invoke-WebRequest -UseBasicParsing 'http://localhost/alisveris/telefon.php'
```

Admin sayfalarında `200` sonucu tek başına yeterli değildir; oturum yoksa yanıt login sayfasına yönlendirilmiş olabilir. Bu durumda `BaseResponse.ResponseUri` değerini kontrol edin.

## Güvenlik notları

- Kullanıcı kontrollü değerlerde prepared statement kullanın.
- Çıktıları `htmlspecialchars()` ile escape edin.
- Admin erişimini yalnızca `$_SESSION['admin']` varlığına bağlamayın; `checkAdmin()` kullanın.
- Sepet işlemlerinde her zaman mevcut `kullanici_id` koşulunu kullanın.
- Ürün stok durumunu hem `stok_id` hem `stok_adet` üzerinden kontrol edin.
- Sipariş toplamını hidden form değerinden değil, güncel veritabanı sepetinden hesaplayın.
- Gerçek kart bilgilerini bu demo akışına eklemeyin.
- Üretim ortamında veritabanı root hesabı ve boş şifre kullanmayın.
- Yüklenen görseller için MIME type, uzantı ve dosya boyutu kontrollerini koruyun.

## Lisans

Bu proje eğitim, portfolyo ve demo amaçlı hazırlanmıştır. Gerçek ödeme, kargo ve üretim güvenliği entegrasyonları içermez.
