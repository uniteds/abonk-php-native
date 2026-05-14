# Panduan Pengoperasian & Deployment Produksi (Nginx)
**Domain:** `css.web.id`  
**Sistem Operasi:** Ubuntu / Debian (Linux Server)  
**Web Server:** Nginx & PHP-FPM (PHP 8.1 / 8.2 / 8.3)

---

## 1. Persyaratan Sistem
Pastikan server Anda telah menginstal komponen berikut:
```bash
sudo apt update
sudo apt install nginx php-fpm php-mysql php-gd php-mbstring php-xml php-curl git unzip -y
```

---

## 2. Struktur Direktori Web
Kloning atau salin seluruh berkas aplikasi **Abonk CMS** ke direktori publik server web Anda:
```bash
sudo mkdir -p /var/www/css.web.id
sudo chown -R $USER:$USER /var/www/css.web.id
# (Lakukan rsync atau git clone ke dalam direktori ini)
```

### Konfigurasi Izin Akses (Permissions)
Direktori unggahan (`assets/uploads/` dan `assets/imgs/`) wajib dapat ditulis oleh Nginx (`www-data`):
```bash
sudo chown -R www-data:www-data /var/www/css.web.id/assets/uploads
sudo chown -R www-data:www-data /var/www/css.web.id/assets/imgs
sudo chmod -R 755 /var/www/css.web.id
sudo chmod -R 775 /var/www/css.web.id/assets/uploads
```

### ⚠️ Catatan Penting Mengenai Aset (`assets/js/vendor` & `assets/css/vendor`)
Jika Anda menggunakan Git untuk men-deploy ke server produksi, pastikan aturan di berkas `.gitignore` Anda menggunakan `/vendor/` (dengan garis miring di awal), bukan sekadar `vendor/`. Jika tidak, Git akan mengabaikan folder pustaka pihak ketiga di `assets/js/vendor` dan `assets/css/vendor` yang menyebabkan galat 404 (Not Found) serta `Uncaught ReferenceError: jQuery is not defined` di peramban produksi Anda!

---

## 3. Konfigurasi Database & Lingkungan (`config.php`)
Pastikan berkas `config/config.php` di server produksi telah disesuaikan dengan kredensial database MySQL produksi Anda:
```php
define('DB_HOST', 'localhost'); // Atau IP Server Database
define('DB_NAME', 'nama_database_produksi');
define('DB_USER', 'user_database_produksi');
define('DB_PASS', 'password_sangat_rahasia');

// Konfigurasi URL Utama
define('BASE_URL', 'https://css.web.id');
```
*Catatan: Anda dapat menjalankan `https://css.web.id/install.php` untuk memigrasikan tabel awal, kemudian **hapus** atau amankan berkas `install.php` setelah selesai.*

---

## 4. Konfigurasi Server Block Nginx
Buat berkas konfigurasi virtual host baru di Nginx:
```bash
sudo nano /etc/nginx/sites-available/css.web.id
```

Salin dan tempel konfigurasi produksi berikut:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name css.web.id www.css.web.id;

    # Ganti dengan path direktori root Anda
    root /var/www/css.web.id;
    index index.php index.html index.htm;

    # Log Akses & Log Error
    access_log /var/log/nginx/css.web.id_access.log;
    error_log /var/log/nginx/css.web.id_error.log;

    # Header Keamanan
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # Front Controller Routing (Wajib untuk MVC Native)
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Blokir akses ke direktori sensitif (.git, .env, dsb)
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Blokir akses langsung ke folder internal (opsional)
    location ~* ^/(app|config|storage)/ {
        deny all;
    }

    # Optimasi Caching untuk Berkas Statis & Gambar (WebP, CSS, JS)
    location ~* \.(jpg|jpeg|png|webp|gif|svg|ico|css|js|woff2|woff|ttf)$ {
        expires max;
        add_header Cache-Control "public, no-transform";
        access_log off;
        log_not_found off;
    }

    # Penanganan Skrip PHP melalui PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        
        # Sesuikan versi PHP-FPM Anda (contoh: php8.2-fpm.sock)
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_intercept_errors on;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }
}
```

---

## 5. Mengaktifkan Nginx & Pemasangan Sertifikat SSL / HTTPS (Gratis)
1. Aktifkan konfigurasi Nginx yang baru saja dibuat:
```bash
sudo ln -s /etc/nginx/sites-available/css.web.id /etc/nginx/sites-enabled/
```
2. Lakukan uji sintaks konfigurasi Nginx:
```bash
sudo nginx -t
```
3. Jika pengujian berhasil tanpa galat, muat ulang Nginx:
```bash
sudo systemctl reload nginx
```
4. Pasang sertifikat SSL resmi gratis menggunakan **Certbot (Let's Encrypt)**:
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d css.web.id -d www.css.web.id
```
Ikuti petunjuk di layar. Certbot akan secara otomatis memperbarui konfigurasi Nginx Anda untuk mendukung HTTPS/SSL dan mengaktifkan pengalihan (*redirect*) otomatis dari HTTP ke HTTPS.

---

## 6. Verifikasi Akhir
Buka peramban Anda dan kunjungi:
**`https://css.web.id`**

Selamat! Situs portal modern Anda kini beroperasi dengan aman, stabil, dan berkecepatan tinggi di lingkungan produksi.
