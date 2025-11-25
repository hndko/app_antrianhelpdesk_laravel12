# Service Display System 🖥️

Sistem Manajemen Antrian dan _Digital Signage_ modern berbasis web. Dibangun menggunakan **Laravel** dan **Livewire** untuk performa real-time yang ringan tanpa memerlukan konfigurasi WebSocket server yang rumit.

Aplikasi ini cocok digunakan untuk bengkel, service center, klinik, atau instansi pelayanan publik yang membutuhkan tampilan antrian di TV/Monitor serta panel admin untuk operator.

![Service Display Preview](https://via.placeholder.com/800x400.png?text=Preview+Display+Dashboard)

> Ganti link gambar di atas dengan screenshot aplikasi Anda.

---

## ✨ Fitur Utama

### 📺 Public Display (TV View)

-   **Real-time Update:** Auto refresh setiap 2 detik menggunakan `wire:poll`
-   **Fluid Layout:** Fullscreen tanpa celah samping
-   **Multimedia Player:** Mendukung video promosi via **Upload Lokal (MP4)** atau YouTube Embed
-   **Dynamic Marquee:** Teks berjalan dengan kecepatan dapat diatur
-   **Queue List:** Menampilkan daftar antrian berdasarkan status
-   **Countdown Timer:** Estimasi pengerjaan otomatis
-   **Clock & Date:** Jam digital & tanggal client-side

### 🛠️ Admin Dashboard

-   Kelola Antrian (Tambah / Edit / Hapus)
-   Ubah Judul Sistem & Running Text
-   Atur Kecepatan Teks
-   Upload Video Promosi
-   Live Preview
-   UI modern (Tailwind + IziToast)

---

## 🚀 Teknologi

-   **Framework:** Laravel 11
-   **Realtime:** Livewire 3 + Alpine.js
-   **Styling:** Tailwind CSS
-   **Database:** MySQL
-   **Bundler:** Vite

---

## 📦 Instalasi

### 1) Clone Repository

```bash
git clone https://github.com/username-anda/service-display.git
cd service-display
```
