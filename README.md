# 🚀 Abonk CMS

> Sistem Manajemen Konten (CMS) berbasis PHP Native OOP & MVC yang Cepat, Ringan, Aman, dan Bertenaga dengan Desain Premium.

![Abonk CMS Cover](assets/uploads/logo_6a054cd68a43b.webp)

---

## ✨ Fitur & Keunggulan Utama

- **🚀 Native PHP 8+ OOP & MVC Architecture**: Dibangun dari nol tanpa framework berat. Menggunakan pemisahan logika yang bersih dan terstruktur antara **Model, View, dan Controller**.
- **🔗 Clean URLs & SEO Friendly**: URL bersih otomatis tanpa prefix `index.php`, didukung oleh Custom Regex Front Controller Router.
- **⚙️ Konfigurasi Variabel Lingkungan (`.env`)**: Memisahkan kredensial sensitif basis data dan parameter sistem dari kode aplikasi murni dengan parser `.env` mandiri tanpa dependensi eksternal. Mode produksi (`APP_ENV=production`) secara otomatis menyembunyikan galat demi keamanan server.
- **🛡️ Keamanan Login Lapis Baja (Cloudflare Turnstile)**: Perlindungan cerdas terhadap serangan *Bruteforce* dan bot otomatis di halaman login admin dengan verifikasi ganda di sisi frontend dan backend. Kunci API dapat dikonfigurasi langsung via dasbor admin.
- **🔒 Pengamanan Otomatis Installer (`.installed` Lock)**: Skrip web installer otomatis terkunci pasca-instalasi untuk mencegah eksekusi ulang atau penimpaan data yang tidak disengaja.
- **🖼️ Auto-Konversi WebP & GD Library**: Sistem pengunggah gambar cerdas yang otomatis mengonversi foto (JPG/PNG) menjadi format **WebP** super ringan (kompresi 85%), menjaga transparansi PNG, dan mendongkrak performa *loading* halaman.
- **📄 Manajemen Halaman Statis Dinamis**: Buat halaman informasi independen (Tentang Kami, Kontak, dll.) dengan opsi untuk menampilkannya secara dinamis di **Bilah Navigasi Atas (*Top Bar*)** serta terintegrasi otomatis ke dalam menu seluler responsif.
- **📈 Statistik Tayangan Artikel Nyata (*Real Views Counter*)**: Setiap kali artikel dibaca, sistem secara waktu nyata mencatat tayangan, menyajikannya di beranda dan halaman pos, serta mengakumulasikannya ke dalam kartu analitik di Dasbor Admin.
- **👤 Profil Pengguna & Boks Penulis Premium**: 
  - Setiap pengguna/penulis dapat mengatur foto avatar, menulis biografi, serta menautkan media sosial lengkap.
  - Pembaca disajikan dengan *Author Box* berdesain premium di bagian bawah setiap artikel.
- **✍️ Rich Text Editor Terintegrasi**: Pengeditan konten didukung penuh oleh **CKEditor 5** dengan kustomisasi tema hangat yang elegan.
- **📊 Dasbor Admin Terpusat & Informatif**: Dilengkapi dengan ikhtisar statistik seluruh entitas konten, tabel aktivitas artikel terkini, serta manajemen sponsor dan iklan *sidebar*.
- **🧭 Dynamic Navigation Management**: Atur tautan navigasi utama secara dinamis (tambah, edit, hapus, urutkan) langsung dari antarmuka Panel Admin.

---

## 📂 Struktur Direktori

```text
abonk-cms/
├── app/                  # Aplikasi Utama
│   ├── Controllers/      # Logika Pengendali (HomeController, PageController, PostController, AuthController, dll.)
│   ├── Models/           # Model Database (Post, Page, Category, User, Setting, NavigationMenu)
│   └── Views/            # Tampilan Antarmuka (Admin, Frontend, Layouts)
├── assets/               # Aset Statis (CSS, Gambar, Uploads)
├── config/               # Konfigurasi Sistem & Pemuat Lingkungan
├── core/                 # Mesin Inti (Database, Router, Controller, Request, Session)
├── docker-compose.yml    # Konfigurasi Orkestrasi Docker
├── Dockerfile            # Spesifikasi Build Image PHP + Ekstensi GD/PDO
├── index.php             # Front Controller (Entry Point & Router)
├── install.php           # Skrip Web Installer Otomatis
└── .env.example          # Contoh Berkas Variabel Lingkungan
```

---

## 🚀 Cara Instalasi & Penggunaan

### Opsi A: Menggunakan Docker (Sangat Direkomendasikan)

Pastikan Docker dan Docker Compose telah terinstal di komputer Anda.

1. Kloning repositori ini:
   ```bash
   git clone https://github.com/uniteds/abonk-php-native.git
   cd abonk-cms
   ```
2. Salin berkas lingkungan awal:
   ```bash
   cp .env.example .env
   ```
3. Jalankan perintah build dan up kontainer:
   ```bash
   docker compose up -d --build
   ```
4. Buka browser dan akses:
   ```text
   http://localhost:8080
   ```
5. Jika database belum diinisialisasi, sistem akan otomatis mengarahkan Anda ke halaman **Web Installer** untuk menyelesaikan penyiapan awal.

---

### Opsi B: Instalasi Manual (XAMPP / Laragon / MAMP)

1. Salin folder repositori ini ke dalam direktori `htdocs` atau `www` Anda.
2. Salin berkas `.env.example` menjadi `.env` dan sesuaikan parameter kredensial basis data Anda.
3. Pastikan ekstensi `pdo_mysql` dan `gd` aktif di `php.ini`.
4. Akses situs melalui browser (sistem akan mengarahkan ke `install.php` untuk instalasi tabel dan data awal otomatis).

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
