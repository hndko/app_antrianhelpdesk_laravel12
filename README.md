rapihkan markdown readme ini

# Service Display System 🖥️

Sistem Manajemen Antrian dan _Digital Signage_ (Papan Informasi Digital) modern berbasis web. Dibangun menggunakan **Laravel** dan **Livewire** untuk performa real-time yang ringan tanpa memerlukan konfigurasi WebSocket server yang rumit.

Aplikasi ini cocok digunakan untuk bengkel, service center, klinik, atau kantor pelayanan publik yang membutuhkan tampilan antrian di TV/Monitor dan panel admin untuk operator.

![Service Display Preview](https://github.com/user-attachments/assets/8f02bb20-3fbe-4e9a-8b63-cec86944f7dd)

## ✨ Fitur Utama

### 📺 Public Display (Halaman TV)

-   **Real-time Updates:** Tampilan otomatis berubah setiap 2 detik (menggunakan `wire:poll`) saat Admin mengupdate data.
-   **Fluid Layout:** Desain responsif memenuhi layar (Full Width & Height) tanpa celah kosong.
-   **Multimedia Player:** Mendukung pemutaran video promosi via **Upload Lokal (MP4)** atau **YouTube Embed**.
-   **Dynamic Marquee:** Running text (teks berjalan) dengan kecepatan yang bisa diatur via Admin.
-   **Queue List:** Menampilkan daftar antrian dengan status (Menunggu, Proses, Selesai).
-   **Countdown Timer:** Menghitung mundur estimasi waktu pengerjaan secara otomatis untuk unit yang sedang diproses.
-   **Clock & Date:** Widget jam digital dan tanggal yang akurat (Client-side update).

### 🛠️ Admin Dashboard

-   **Queue Management:** Tambah, Edit, dan Hapus data antrian dengan mudah.
-   **Settings Control:** Ubah Judul Aplikasi, Running Text, Kecepatan Teks, dan Upload Video langsung dari dashboard.
-   **Live Preview:** Angka kecepatan teks dan preview video tampil saat diedit.
-   **Modern UI:** Dibangun dengan Tailwind CSS dan IziToast untuk notifikasi yang elegan.

## 🚀 Teknologi yang Digunakan

-   **Framework:** [Laravel 11](https://laravel.com)
-   **Frontend/Reactivity:** [Livewire 3](https://livewire.laravel.com) & [Alpine.js](https://alpinejs.dev)
-   **Styling:** [Tailwind CSS](https://tailwindcss.com)
-   **Database:** MySQL
-   **Assets Bundle:** Vite

## 📦 Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di komputer lokal Anda:

### 1. Clone Repository

```bash
git clone [https://github.com/username-anda/service-display.git](https://github.com/username-anda/service-display.git)
cd service-display
```

### 2\. Install Dependencies

Pastikan Anda sudah menginstall PHP dan Node.js.

```bash
composer install
npm install
```

### 3\. Konfigurasi Environment

Duplikat file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.

```bash
cp .env.example .env
php artisan key:generate
```

_Edit file `.env` dan pastikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai._

### 4\. Setup Database & Storage

Jalankan migrasi, seeder, dan link storage (PENTING untuk upload video).

```bash
php artisan migrate --seed
php artisan storage:link
```

### 5\. Build Assets & Jalankan Server

Buka dua terminal:

**Terminal 1 (Build CSS/JS):**

```bash
npm run dev
```

**Terminal 2 (Jalankan Laravel):**

```bash
php artisan serve
```

Aplikasi dapat diakses di: `http://127.0.0.1:8000`

## 🔑 Akun Default (Seeder)

Gunakan akun ini untuk masuk ke halaman Admin:

-   **Login URL:** `/login`
-   **Email:** `admin@service.com`
-   **Password:** `admin`

## 📖 Cara Penggunaan

1.  **Login Admin:** Masuk ke `/login` menggunakan akun default.
2.  **Setup Tampilan:**
    -   Pergi ke bagian bawah dashboard (Pengaturan Tampilan).
    -   Upload video promosi atau masukkan link YouTube.
    -   Isi Running Text dan atur kecepatannya (geser slider).
    -   Klik "Simpan".
3.  **Kelola Antrian:**
    -   Input data pelanggan (ID Laptop, Helpdesk, Durasi).
    -   Status awal otomatis "Waiting".
    -   Ubah status ke "Progress" untuk memunculkan Countdown di layar depan.
4.  **Buka Display:**
    -   Buka tab baru atau browser di monitor TV.
    -   Akses URL utama (`/`).
    -   Tekan `F11` untuk mode Fullscreen.

## 🤝 Kontribusi

Pull requests dipersilakan. Untuk perubahan besar, harap buka issue terlebih dahulu untuk mendiskusikan apa yang ingin Anda ubah.

### Tips Tambahan:

Jangan lupa untuk mengambil **Screenshot** dari halaman _Display_ (yang ada videonya) dan halaman _Admin Dashboard_ Anda, lalu upload ke folder project atau image hosting, dan ganti link `https://via.placeholder.com...` di atas dengan link gambar asli Anda agar README terlihat menarik!
