<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Ini adalah aplikasi untuk kebutuhan Absensi sekolah SMA. Aplikasi Absensi ini menggunakan Handphone sebagai perangkat absennya, dan menggunakan fitur kamera. Dimana kamera di arahkan ke QR Code yang telah di cetak dalam bentuk kartu (QR Code setiap siswa di buat di dalam aplikasi ini).

Framework UI yang digunakan menggunakan AdminLTE.

Aplikasi ini di bangun menggunakan Laravel 10 dengan versi PHP minimal 8.1.0.

---

## 🚀 Cara Install & Menjalankan Project

1. **Clone repository ini:**
   ```bash
   git clone <repo-url>
   cd absensi-sekolah-qr-laravel
   ```
2. **Install dependency PHP:**
   ```bash
   composer install
   ```
3. **Copy file environment:**
   ```bash
   cp .env.example .env
   ```
4. **Edit file `.env`**
   - Ganti konfigurasi database sesuai server Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
5. **Generate key aplikasi:**
   ```bash
   php artisan key:generate
   ```
6. **Install dependency frontend (jika ada):**
   ```bash
   npm install
   # lalu
   npm run dev # atau npm run build / npm run prod sesuai kebutuhan
   ```
7. **Jalankan migrasi database:**
   ```bash
   php artisan migrate
   ```
8. **Jalankan server lokal:**
   ```bash
   php artisan serve
   ```

Akses aplikasi di [http://localhost:8000](http://localhost:8000)

---

## ℹ️ Catatan OpenSource & Perubahan

Repo ini merupakan hasil pengembangan dari aplikasi absensi open source yang dimodifikasi. Perubahan dan penambahan utama:

1. **Memperbaiki kamera yang tidak menyala** pada beberapa device.
2. **Menambahkan fitur import file siswa** melalui Excel/CSV.
3. **Menambahkan fitur export rekap absensi** (CSV) per kelas.
4. **Menambahkan fitur input izin manual** untuk siswa (langsung dari dashboard).

Kontribusi dan issue sangat diterima!
