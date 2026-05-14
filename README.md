# 🚀 Abonk CMS

> Sistem Manajemen Konten (CMS) berbasis PHP Native OOP & MVC yang Cepat, Ringan, Aman, dan Bertenaga dengan Desain Premium.

![Abonk CMS Cover](assets/uploads/logo_6a054cd68a43b.webp)

---

## ✨ Fitur & Keunggulan Utama

- **🚀 Native PHP 8+ OOP & MVC Architecture**: Dibangun dari nol tanpa framework berat. Menggunakan pemisahan logika yang bersih dan terstruktur antara **Model, View, dan Controller**.
- **🔗 Clean URLs & SEO Friendly**: URL bersih otomatis tanpa prefix `index.php`, didukung oleh Custom Regex Front Controller Router.
- **🖼️ Auto-Konversi WebP & GD Library**: Sistem pengunggah gambar cerdas yang otomatis mengonversi unggahan foto (JPG/PNG) menjadi format **WebP** super ringan (kompresi 85%), menjaga transparansi PNG, dan mendongkrak performa *loading* halaman secara drastis.
- **📄 Manajemen Halaman Statis (Static Pages)**: Buat dan publikasikan halaman informasi independen (seperti "Tentang Kami", "Kontak", atau "Kebijakan Privasi") tanpa tercampur dengan alur pos artikel reguler.
- **👤 Profil Pengguna & Boks Penulis Premium**: 
  - Setiap pengguna/penulis dapat mengatur foto avatar, menulis biografi (*bio*), serta menautkan media sosial lengkap (Facebook, Twitter/X, Instagram, LinkedIn, GitHub).
  - Pembaca disajikan dengan *Author Box* berdesain premium di bagian bawah setiap artikel.
- **✍️ Rich Text Editor Terintegrasi**: Pengeditan konten pos dan halaman statis didukung penuh oleh **CKEditor 5** dengan kustomisasi tema hangat yang elegan.
- **📊 Dasbor Admin Terpusat & Informatif**: Dilengkapi dengan ikhtisar statistik seluruh entitas konten, tabel aktivitas artikel terkini, serta pintasan aksi cepat untuk efisiensi kerja.
- **🧭 Dynamic Navigation Management**: Atur tautan navigasi atas secara dinamis (tambah, edit, hapus, urutkan) langsung dari antarmuka Panel Admin.
- **🛡️ Keamanan Lapis Baja**:
  - **Anti SQL Injection** menggunakan PDO Prepared Statements.
  - **Anti XSS** dengan sanitasi masukan dan luaran tanpa risiko *double-encoding*.
  - **Anti CSRF** dengan token acak dinamis di setiap formulir.
- **🐳 Dukungan Lingkungan Docker**: Siap dijalankan dalam hitungan detik menggunakan lingkungan terisolasi Docker (PHP 8.2 Apache + Ekstensi GD & MySQL 8.0).
- **⚡ Web Installer Otomatis**: Instalasi database, tabel skema, dan data awal (*seeder*) dilakukan secara interaktif lewat browser.

---

## 📂 Struktur Direktori

```text
abonk-cms/
├── app/                  # Aplikasi Utama
│   ├── Controllers/      # Logika Pengendali (HomeController, PageController, PostController, dll.)
│   ├── Models/           # Model Database (Post, Page, Category, User, Setting, NavigationMenu)
│   └── Views/            # Tampilan Antarmuka (Admin, Frontend, Layouts)
├── assets/               # Aset Statis (CSS, Gambar, Uploads)
├── config/               # Konfigurasi Sistem & Parameter Konstanta
├── core/                 # Mesin Inti (Database, Router, Controller, Request, Session)
├── docker-compose.yml    # Konfigurasi Orkestrasi Docker
├── Dockerfile            # Spesifikasi Build Image PHP + Ekstensi GD/PDO
├── index.php             # Front Controller (Entry Point & Router)
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
2. Jalankan perintah build dan up kontainer:
   ```bash
   docker compose up -d --build
   ```
3. Buka browser dan akses:
   ```text
   http://localhost:8080
   ```
4. Jika database belum diinisialisasi, sistem akan otomatis mengarahkan Anda ke halaman **Web Installer** untuk menyelesaikan penyiapan awal.

---

### Opsi B: Instalasi Manual (XAMPP / Laragon / MAMP)

1. Salin folder repositori ini ke dalam direktori `htdocs` atau `www` Anda.
2. Pastikan ekstensi `pdo_mysql` dan `gd` aktif di `php.ini`.
3. Buat database baru di MySQL (contoh: `php_abonk_cms`).
4. Sesuaikan parameter koneksi database di file `config/config.php`.
5. Akses situs melalui browser (sistem akan mengarahkan ke `install.php` untuk pembuatan tabel otomatis).

---

## 🔐 Akun Akses Default Admin

Setelah instalasi selesai, Anda dapat masuk ke Panel Admin melalui `http://localhost:8080/admin/login` menggunakan kredensial bawaan berikut:

- **Username**: `admin`
- **Password**: `admin123`

> **Peringatan**: Segera ganti kata sandi default Anda setelah berhasil masuk pertama kali melalui menu **Profil Saya** atau **Pengguna** di Panel Admin!

---

## 🛠️ Kontribusi & Lisensi

Proyek ini bersifat *Open Source* dan dirilis di bawah lisensi **MIT**. Anda bebas untuk memodifikasi, mendistribusikan, dan mengembangkannya untuk keperluan pribadi maupun komersial.

Dibuat dengan ❤️ untuk pencinta web PHP Native yang mengutamakan kecepatan, estetika, dan kode yang bersih.
