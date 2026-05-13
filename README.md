# 🚀 Abonk CMS

> Sistem Manajemen Konten (CMS) berbasis PHP Native OOP & MVC yang Cepat, Ringan, Aman, dan Bertenaga.

![Abonk CMS Cover](assets/uploads/kotapadang.jpeg)

---

## ✨ Fitur Utama

- **🚀 Native PHP 8+ OOP & MVC Architecture**: Dibangun dari nol tanpa framework berat. Menggunakan pemisahan logika yang bersih antara **Model, View, dan Controller**.
- **🔗 Clean URLs (SEO Friendly)**: URL bersih otomatis tanpa prefix `index.php`, didukung oleh Custom Regex Front Controller Router.
- **📰 Editorial Grid Layout**: Tampilan beranda bergaya majalah premium dengan pembagian *Featured Post* (Card Besar) dan *Rekomendasi Terbaru* (Card Kecil).
- **🧭 Dynamic Navigation Management**: Atur tautan navigasi atas secara dinamis (tambah, edit, hapus, urutkan) langsung dari antarmuka Panel Admin.
- **🛡️ Keamanan Lapis Baja**:
  - **Anti SQL Injection** dengan PDO Prepared Statements.
  - **Anti XSS** menggunakan pembersihan sanitasi output ketat.
  - **Anti CSRF** dengan token acak dinamis di setiap formulir admin.
- **🐳 Dukungan Docker Compose**: Siap dijalankan dalam hitungan detik menggunakan lingkungan terisolasi Docker (PHP Apache & MySQL 8.0).
- **⚡ Web Installer Otomatis**: Instalasi database, tabel skema, dan data awal (*seeder*) dilakukan secara interaktif lewat browser.

---

## 📂 Struktur Direktori

```text
abonk-cms/
├── app/                  # Aplikasi Utama
│   ├── Controllers/      # Logika Pengendali (HomeController, AdminController, dll.)
│   ├── Models/           # Model Database (Post, Category, User, NavigationMenu)
│   └── Views/            # Tampilan Antarmuka (Admin, Frontend, Layouts)
├── assets/               # Aset Statis (CSS, Gambar, Uploads)
├── config/               # Konfigurasi Sistem & Autoloader
├── core/                 # Mesin Inti (Database, Router, Controller, Request, Session)
├── docker-compose.yml    # Konfigurasi Orkestrasi Docker
├── index.php             # Front Controller (Entry Point & Routes)
└── install.php           # Skrip Web Installer Otomatis
```

---

## 🚀 Cara Instalasi & Penggunaan

### Opsi A: Menggunakan Docker (Sangat Direkomendasikan)

Pastikan Docker dan Docker Compose telah terinstal di komputer Anda.

1. Kloning repositori ini:
   ```bash
   git clone https://github.com/username/abonk-cms.local.git
   cd abonk-cms
   ```
2. Jalankan perintah Docker Compose:
   ```bash
   docker-compose up -d --build
   ```
3. Buka browser dan akses:
   ```text
   http://localhost:8080
   ```
4. Jika database belum diinisialisasi, sistem akan otomatis mengarahkan Anda ke halaman **Web Installer** untuk menyelesaikan penyiapan awal.

---

### Opsi B: Instalasi Manual (XAMPP / Laragon / MAMP)

1. Salin folder repositori ini ke dalam direktori `htdocs` atau `www` Anda.
2. Buat database baru di MySQL (contoh: `php_abonk_cms`).
3. Sesuaikan parameter koneksi database di file `config/config.php`.
4. Akses situs melalui browser (sistem akan mengarahkan ke `install.php` untuk pembuatan tabel otomatis).

---

## 🔐 Akun Akses Default Admin

Setelah instalasi selesai, Anda dapat masuk ke Panel Admin melalui `http://localhost:8080/admin/login` menggunakan kredensial bawaan berikut:

- **Username**: `admin`
- **Password**: `admin123`

> **Peringatan**: Segera ganti kata sandi default Anda setelah berhasil masuk pertama kali melalui menu **Pengguna** di Panel Admin!

---

## 🛠️ Kontribusi & Lisensi

Proyek ini bersifat *Open Source* dan dirilis di bawah lisensi **MIT**. Anda bebas untuk memodifikasi, mendistribusikan, dan mengembangkannya untuk keperluan pribadi maupun komersial.

Dibuat dengan ❤️ untuk komunitas pengembang web PHP Indonesia.
