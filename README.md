# Service Display Helpdesk

Sistem manajemen antrian dan digital signage berbasis web untuk helpdesk IT, service center, bengkel, klinik, loket pelayanan, atau unit layanan internal.

Aplikasi ini menyediakan tampilan publik untuk TV/monitor dan panel pengelolaan untuk mengelola antrian, teknisi, pengaturan display, serta laporan harian pekerjaan teknisi.

## Fitur Utama

### Public Display

- Tampilan antrian publik di `/`.
- Update otomatis menggunakan Livewire polling.
- Daftar antrian dengan status `waiting`, `progress`, `done`, dan `completed`.
- Countdown estimasi pengerjaan untuk status `progress`.
- Running text dengan kecepatan yang dapat diatur.
- Video display berbasis YouTube ID atau URL YouTube.
- Layout responsif untuk layar TV/monitor dan mobile.
- Autoscroll daftar antrian saat data melebihi tinggi panel.

### Dashboard

- Login operator dengan tampilan responsif.
- Statistik total antrian, antrian menunggu, dan antrian diproses.
- Tambah, edit, dan hapus data antrian.
- Assign teknisi ke antrian.
- Pengaturan judul aplikasi, logo URL, running text, kecepatan marquee, dan video YouTube.
- Toast notification menggunakan IziToast.

### Manajemen Teknisi

- Tambah dan edit teknisi.
- Hapus teknisi jika belum punya riwayat antrian.
- Nonaktifkan teknisi jika sudah memiliki riwayat antrian.
- Jumlah pekerjaan selesai hari ini dihitung tanpa query langsung di Blade.

### Laporan Harian

- Filter laporan berdasarkan teknisi dan tanggal.
- Menghitung pekerjaan selesai dari status `done` dan `completed`.

## Teknologi

- PHP 8.2+
- Laravel 12
- Livewire 3
- Blade
- Tailwind CSS 4
- Vite
- MySQL / MariaDB
- IziToast
- Pest / PHPUnit

## Struktur Seeder

Seeder utama menggunakan bawaan Laravel:

```bash
php artisan db:seed
```

Seeder dipecah per tabel/modul:

- `UserSeeder`
- `DisplaySettingSeeder`
- `SettingSeeder`
- `TechnicianSeeder`
- `QueueSeeder`

Akun development:

- Login URL: `/login`
- Username: `helpdesk`
- Email: `operator@example.com`
- Password: `password`

Password di seeder ditulis sebagai plain string karena model `User` sudah memakai cast `password => hashed`.

## Instalasi

1. Clone repository.

```bash
git clone https://github.com/hndko/app_antrianhelpdesk_laravel12.git
cd app_antrianhelpdesk_laravel12
```

2. Install dependency.

```bash
composer install
npm install
```

3. Siapkan environment.

```bash
cp .env.example .env
php artisan key:generate
```

4. Atur database di `.env`, lalu jalankan migrasi dan seeder.

```bash
php artisan migrate --seed
php artisan storage:link
```

5. Jalankan frontend dan server lokal secara manual.

```bash
npm run dev
php artisan serve
```

Catatan untuk agent: jangan menjalankan `npm run build` atau `php artisan serve` otomatis kecuali diminta eksplisit.

## Route Utama

- `/` - public display.
- `/login` - login operator.
- `/dashboard` - dashboard.
- `/technicians` - manajemen teknisi.
- `/reports/daily` - laporan harian.

## Workflow Agent

- Agent wajib mengikuti aturan kerja di `AGENTS.md`.
- Setelah menyelesaikan perubahan, agent melakukan commit dan push otomatis jika remote dan branch tujuan tersedia.
- Agent hanya boleh stage file yang dibuat atau diubah pada task berjalan.
- Perubahan user yang tidak terkait tidak boleh direvert atau ikut distage.
