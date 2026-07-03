# Service Display Helpdesk

Sistem manajemen antrian dan digital signage berbasis web untuk helpdesk IT, service center, bengkel, klinik, loket pelayanan, atau unit layanan internal.

Aplikasi ini menyediakan tampilan publik untuk TV/monitor dan panel pengelolaan untuk mengelola antrian, akun service desk/teknisi, pengaturan display, serta laporan harian pekerjaan teknisi.

## Fitur Utama

### Public Display

- Tampilan antrian publik di `/`.
- Update otomatis menggunakan Livewire polling setiap 2 detik (`wire:poll.2s`).
- Daftar antrian dengan status `waiting`, `progress`, dan `done` beserta status ketersediaan teknisi dan estimasi waktu selesai pada tiap barisnya.
- Panel informasi ketersediaan personil/teknisi siaga (`Ready`, `Visit`, `Support Acara`, `Tidak Tersedia`) beserta estimasi waktu dan catatan.
- Rekapitulasi statistik personil (Teknisi Aktif, Onsite/Ready, dan Remote/Visit).
- Transparansi statistik tiket secara realtime (Sedang Diproses, Menunggu, Selesai Online & Onsite).
- Countdown estimasi pengerjaan untuk status `progress`.
- Running text dengan kecepatan yang dapat diatur.
- Video display berbasis YouTube ID atau URL YouTube.
- Layout responsif untuk layar TV/monitor dan mobile.
- Autoscroll daftar antrian saat data melebihi tinggi panel.
- Tampilan jam dan tanggal real-time dengan format baku Indonesia (misalnya `Jumat, 03 Juli 2026 • 11:30 WIB`) pada header layar.

### Dashboard

- Login operator/teknisi dengan satu halaman auth responsif.
- Layout backend menggunakan sidebar responsif dengan tombol hamburger untuk mobile dan desktop.
- Dashboard fokus sebagai halaman analytics graph.
- Statistik total antrian, antrian menunggu, antrian diproses, dan selesai hari ini.
- Grafik tren antrian 7 hari, komposisi status, dan performa teknisi.

### Manajemen Antrian

- Tambah, edit, dan hapus data antrian.
- Input manual keterangan keluhan perangkat sementara sebelum fitur otomatis dikembangkan.
- Tabel antrian terstruktur dengan 7 kolom utama: Nomor, Nama User, Perangkat, Nama Teknisi, Estimasi Waktu, Status, dan Aksi.
- Assign teknisi ke antrian untuk role `superadmin` dan `service_desk`.
- Teknisi hanya melihat antrian miliknya, bisa membuat antrian, dan bisa mengoper antrian ke teknisi lain.
- History log mencatat pembuatan antrian, perubahan status, perpindahan teknisi, dan penghapusan.

### Pengaturan Display

- Pengaturan judul aplikasi, upload logo/favicon, running text, kecepatan marquee, dan video YouTube khusus `superadmin`.
- Pengaturan favicon browser untuk login, dashboard, dan public display.
- Toast notification menggunakan IziToast.

### Manajemen Akun dan Role

- Role utama: `superadmin`, `service_desk`, dan `technician`.
- Akun `helpdesk` berperan sebagai `superadmin`.
- Superadmin dapat membuat dan mengubah akun service desk, teknisi, dan superadmin, termasuk status ketersediaan personil dan estimasi waktu.
- Setiap teknisi dapat memperbarui status ketersediaan mandiri (Ready, Visit, Support Acara, Tidak Tersedia) secara cepat melalui topbar switcher.
- Akun dengan riwayat antrian dinonaktifkan saat dihapus agar histori tetap aman.
- Setiap user dapat mengubah profil dan password dari menu `Edit Profile`.

### Laporan Harian

- Filter laporan berdasarkan tanggal dan teknisi (opsional; jika tidak dipilih teknisi maka secara default langsung menampilkan rekapitulasi seluruh teknisi).
- Kartu statistik ringkas menampilkan status teknisi, total selesai, total durasi, dan rata-rata durasi penanganan.
- Tabel daftar rincian pekerjaan selesai dengan informasi lengkap termasuk kolom nama teknisi dan catatan penanganan.
- Menghitung pekerjaan selesai dari status `done`.

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

## Format Lokal

- Locale aplikasi dan penanggalan menggunakan standar bahasa Indonesia (`id` / `id_ID`).
- Timezone default menggunakan `Asia/Jakarta` (WIB).
- Seluruh tanggal yang ditampilkan ke pengguna dikonversi dan diformat dalam bahasa Indonesia, misalnya `03 Juli 2026` (sebelumnya `03 July 2026`). Pengaturan ini ditegakkan secara otomatis melalui konfigurasi `.env` dan `AppServiceProvider`.

## Struktur Seeder

Seeder utama menggunakan bawaan Laravel:

```bash
php artisan db:seed
```

Seeder dipecah per tabel/modul:

- `UserSeeder`
- `DisplaySettingSeeder`
- `QueueSeeder`

Akun development:

- Login URL: `/login`
- Username: `helpdesk`
- Email: `operator@example.com`
- Password: `password`
- Role: `superadmin`

Akun demo tambahan:

- Service desk: `servicedesk` / `password`
- Teknisi: `teknisiwaiting`, `teknisiprogress`, `teknisidone`, `teknisibackup` / `password`

Password di seeder ditulis sebagai plain string karena model `User` sudah memakai cast `password => hashed`.

## Struktur Layout

Layout utama berada di `resources/views/components`:

- `app-auth.blade.php` untuk halaman login operator/teknisi.
- `app-backend.blade.php` untuk dashboard dan halaman internal.
- `app-frontend.blade.php` untuk public display.

Login superadmin, service desk, dan teknisi menggunakan satu halaman `/login`, dengan akun tersimpan di tabel `users`.
Tabel teknisi lama sudah dipensiunkan dari fitur utama; assignment antrian memakai `queues.technician_user_id`.

## Brand Asset

Logo dan favicon default tersedia di:

- `/assets/helpdesk-logo-icon.svg`
- `/assets/helpdesk-favicon.svg`

Keduanya dapat diganti dari dashboard melalui tombol `Upload Files` pada pengaturan display, lengkap dengan preview gambar sebelum disimpan. File upload disimpan ke disk `public` Laravel dan ditampilkan melalui path `/storage/...`, sehingga `php artisan storage:link` perlu tersedia di environment lokal/production.

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
- `/dashboard` - analytics dashboard.
- `/profile` - edit profil user login.
- `/queues` - manajemen antrian.
- `/accounts` - manajemen akun khusus superadmin.
- `/display-settings` - pengaturan display khusus superadmin.
- `/reports/daily` - laporan harian.

## Workflow Agent

- Agent wajib mengikuti aturan kerja di `GEMINI.md`.
- Setelah menyelesaikan perubahan, agent melakukan commit dan push otomatis jika remote dan branch tujuan tersedia.
- Agent hanya boleh stage file yang dibuat atau diubah pada task berjalan.
- Perubahan user yang tidak terkait tidak boleh direvert atau ikut distage.
