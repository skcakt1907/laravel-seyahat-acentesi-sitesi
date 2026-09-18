# Seyahat Acentesi Web Sitesi

Tur ve transfer satisi yapan seyahat acentesi icin cok dilli tanitim ve talep sitesi.

## Ozellikler

- Tur/paket katalogu ve talep formlari
- Cok dilli on yuz (SetLocale middleware)
- Yonetim paneli, icerik ve gorsel yonetimi
- E-posta bildirimleri ve SMTP entegrasyonu

## Kullanilan teknolojiler

Laravel 12 - PHP 8.2 - MySQL - Blade

## Bu depo hakkinda

Gercek bir musteri projesinin **portfolyo icin yayinlanmis** surumudur.
Yayina hazirlanirken canli alan adlari, gercek iletisim bilgileri, musteri
kayitlari ve uygulama anahtarlari ornek degerlerle degistirilmistir.
Kod ve mimari oldugu gibidir; veri gercek degildir.

## Kurulum

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
