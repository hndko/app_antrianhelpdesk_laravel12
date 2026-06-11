# AGENTS.md - Aturan Kerja Project Service Display Helpdesk

Dokumen ini adalah patokan wajib untuk semua agent, developer, AI assistant, atau kontributor saat mengubah source code project **Service Display Helpdesk**.

Jika ada konflik antara kebiasaan umum development dan dokumen ini, maka ikuti aturan di `AGENTS.md`.

---

# 1. Konteks Project

## 1.1 Nama Project

**Service Display Helpdesk - Sistem Antrian dan Digital Signage**

## 1.2 Deskripsi Singkat

Aplikasi ini adalah sistem manajemen antrian helpdesk/service center berbasis web yang mendukung:

- Tampilan antrian publik untuk TV/monitor.
- Panel admin untuk operator/helpdesk.
- Manajemen data antrian service.
- Manajemen teknisi.
- Pengaturan judul aplikasi, running text, logo, dan video display.
- Video display menggunakan YouTube ID atau URL YouTube.
- Countdown estimasi pengerjaan untuk antrian yang sedang diproses.
- Laporan harian jumlah pekerjaan selesai per teknisi.
- Update tampilan real-time ringan menggunakan Livewire polling.

Aplikasi ini cocok untuk helpdesk IT, service center, bengkel, klinik, loket pelayanan, atau unit layanan internal yang membutuhkan papan informasi antrian.

## 1.3 Stack Utama

- PHP 8.2+
- Laravel 12
- MySQL / MariaDB
- Blade
- Livewire 3
- Tailwind CSS 4
- Vite
- IziToast untuk toast notification
- Laravel Queue table tersedia, tetapi hanya digunakan jika fitur async dibutuhkan
- Pest / PHPUnit untuk testing

## 1.4 Area Akses Utama

Saat ini project menggunakan akses sederhana:

- Public display tanpa login.
- Admin/operator dengan login.

Belum ada RBAC, role table, permission table, atau Spatie Laravel Permission di implementasi aktual.

Jika di masa depan ditambahkan role, gunakan minimal:

- `admin`
- `operator`
- `viewer`

Role hanya boleh ditambahkan jika benar-benar dibutuhkan oleh fitur.

## 1.5 Modul Utama

- Authentication
- Public Display
- Admin Dashboard
- Queue Management
- Technician Management
- Display Settings
- Daily Report
- Toast Notification

---

# 2. Prinsip Umum Development

- Ikuti pola yang sudah ada di project.
- Jangan memindahkan struktur besar tanpa alasan kuat.
- Jangan menghapus file, logic, migration, atau data yang tidak terkait dengan task.
- Perubahan harus fokus sesuai permintaan.
- Setiap perubahan relevan pada fitur, struktur folder, setup, aturan kerja, atau status project wajib diikuti update `README.md`.
- Utamakan maintainability, security, dan user experience pada layar publik.
- Hindari membuat logic bisnis besar langsung di Blade.
- Hindari query model langsung di Blade.
- Hindari duplikasi logic status antrian, filter display, dan laporan teknisi.
- Semua fitur penting harus memiliki validasi, authorization, dan error handling.
- Untuk fitur baru yang berdampak ke data, wajib pertimbangkan:
    - Security
    - Race condition
    - Transaction database
    - Index database
    - N+1 query
    - Error logging
    - Fallback jika media display gagal
    - Pagination jika data besar

---

# 3. Struktur Folder Project

Gunakan struktur Laravel yang rapi dan konsisten dengan implementasi saat ini.

```text
app/
├── Http/
│   └── Controllers/
│       └── Auth/
├── Livewire/
├── Models/
└── Providers/

database/
├── migrations/
├── seeders/
└── factories/

resources/
├── views/
│   ├── admin/
│   │   ├── reports/
│   │   └── technicians/
│   ├── auth/
│   ├── components/
│   └── livewire/
├── css/
└── js/

routes/
├── web.php
└── console.php

public/
└── assets/

storage/
└── app/public/
```

Jika fitur makin besar, boleh menambahkan folder `app/Services`, tetapi jangan membuat service hanya untuk logic kecil yang masih wajar di Livewire component.

---

# 4. Struktur View

## 4.1 Layout

Gunakan layout yang sudah ada dan konsisten.

```text
resources/views/components/
├── display.blade.php
├── guest.blade.php
├── guest-layout.blade.php
├── layout.blade.php
└── pagination-custom.blade.php
```

## 4.2 Penggunaan Layout

| Layout | File | Penggunaan |
| --- | --- | --- |
| Display | `components.display` | Tampilan TV/monitor publik |
| Admin | `components.layout` | Dashboard admin dan halaman internal |
| Guest/Auth | `components.guest` atau `components.guest-layout` | Login dan halaman guest |

## 4.3 Struktur View Modul

```text
resources/views/
├── auth/
│   └── login.blade.php
├── admin/
│   ├── technicians/
│   │   └── index.blade.php
│   └── reports/
│       └── daily.blade.php
├── livewire/
│   ├── admin-dashboard.blade.php
│   ├── daily-report.blade.php
│   ├── public-display.blade.php
│   └── technician-manager.blade.php
└── components/
```

---

# 5. Aturan Blade

- Tampilan admin wajib menggunakan layout `components.layout`.
- Tampilan public display wajib menggunakan layout `components.display`.
- Halaman auth wajib menggunakan layout guest/auth yang sudah ada.
- Gunakan `@vite(['resources/css/app.css', 'resources/js/app.js'])` untuk asset utama.
- Gunakan `@livewireStyles` dan `@livewireScripts` pada layout Livewire.
- Jangan query model langsung di Blade.
- Jangan memakai `@php` untuk logic bisnis.
- Jangan memakai `{!! !!}` untuk data user kecuali sudah disanitasi.
- Semua form non-Livewire wajib memiliki `@csrf`.
- Form logout wajib menggunakan method `POST`.
- Form Livewire wajib tetap memiliki validasi di component.
- Gunakan toast notification untuk feedback, bukan alert browser bawaan.
- Tampilan public display harus responsif, jelas dari jarak jauh, dan cocok untuk layar TV.
- Hindari perubahan UI yang membuat display sulit dibaca dari jauh.

---

# 6. Aturan Livewire Component

Livewire adalah pusat interaksi utama project ini.

- Component hanya menangani state, validasi, query tampilan, dan orchestration sederhana.
- Logic yang mulai panjang, dipakai ulang, atau rawan bug harus dipindahkan ke service.
- Gunakan validasi eksplisit pada action seperti create, update, delete, dan save settings.
- Hindari query yang menyebabkan N+1; gunakan `with()` untuk relasi seperti `Queue::with('technician')`.
- Gunakan pagination untuk data admin yang bisa bertambah besar.
- Gunakan event toast seperti `show-toast` untuk feedback user.
- Jangan menyimpan input mentah tanpa validasi.
- Gunakan `findOrFail()` untuk aksi yang harus gagal jelas saat data tidak ditemukan.
- Untuk operasi delete, pastikan dampaknya terhadap laporan dan tampilan display sudah dipertimbangkan.
- Untuk tampilan public display, query harus ringan karena dapat dipolling berkala.

---

# 7. Aturan Controller

Controller saat ini terutama dipakai untuk authentication.

- Controller hanya menangani request, validasi, authentication, authorization, dan response.
- Jangan memakai `$request->all()`.
- Gunakan `$request->validate()` atau `$request->only([...])`.
- Setelah login berhasil, session wajib diregenerate.
- Setelah logout, session wajib diinvalidate dan token wajib diregenerate.
- Return view boleh langsung jika controller sederhana.
- Jika controller makin kompleks, gunakan array `$data` dan hindari `compact()`.

Contoh login yang sesuai:

```php
$credentials = $request->validate([
    'username' => ['required'],
    'password' => ['required'],
]);

if (Auth::attempt($credentials)) {
    $request->session()->regenerate();

    return redirect()->intended('admin');
}
```

---

# 8. Aturan Model

Model utama saat ini:

- `User`
- `Queue`
- `Technician`
- `Setting`

Aturan model:

- Semua model Eloquent yang membutuhkan factory gunakan `HasFactory`.
- Gunakan `$fillable` secara eksplisit.
- Hindari penggunaan `$guarded = []` tanpa alasan kuat.
- Definisikan relasi model secara jelas dan konsisten.
- Gunakan casting untuk datetime, boolean, array, dan json jika dibutuhkan.
- Simpan logic bisnis besar di service, bukan di model.
- Accessor boleh dipakai untuk label tampilan ringan seperti status label dan warna status.

Relasi utama:

```php
Queue belongsTo Technician
Technician hasMany Queue
```

---

# 9. Struktur Database

## 9.1 Tabel Utama

### `users`

Menyimpan akun admin/operator.

Kolom penting:

- `id`
- `name`
- `username`
- `email`
- `email_verified_at`
- `password`
- `remember_token`
- `created_at`
- `updated_at`

### `queues`

Menyimpan data antrian service.

Kolom penting:

- `id`
- `queue_number`
- `user_name`
- `laptop_id`
- `technician_id`
- `status`
- `duration_minutes`
- `description`
- `created_at`
- `updated_at`

### `technicians`

Menyimpan data teknisi/helpdesk.

Kolom penting:

- `id`
- `name`
- `status`
- `created_at`
- `updated_at`

### `settings`

Menyimpan konfigurasi display.

Kolom penting:

- `id`
- `app_title`
- `logo_url`
- `video_url`
- `video_type`
- `running_text`
- `marquee_speed`
- `created_at`
- `updated_at`

## 9.2 Tabel Bawaan Laravel

- `sessions`
- `password_reset_tokens`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`

---

# 10. Naming Convention

| Item | Konvensi | Contoh |
| --- | --- | --- |
| Controller | PascalCase + Controller | LoginController |
| Livewire Component | PascalCase | AdminDashboard |
| Model | PascalCase Singular | Queue |
| Migration | snake_case | create_queues_table |
| Seeder | PascalCaseSeeder | InitialDataSeeder |
| View Folder | lowercase atau kebab-case | admin/technicians |
| Route Name | dot notation | admin.dashboard |
| Variable | camelCase | selectedTechnician |
| Database Column | snake_case | technician_id |
| Status Value | lowercase snake/kebab sederhana | waiting |

---

# 11. Route, Auth, dan Middleware

- Public display `/` boleh diakses tanpa login.
- Login hanya untuk guest.
- Halaman admin wajib menggunakan middleware `auth`.
- Logout wajib menggunakan method `POST`.
- Semua route penting wajib memiliki route name.
- Jangan mengandalkan tampilan menu untuk keamanan akses.
- Jika role/permission ditambahkan nanti, route sensitif wajib menggunakan middleware authorization.

Route utama saat ini:

```text
GET  /                         home
GET  /login                    login
POST /login                    login submit
POST /logout                   logout
GET  /admin                    admin.dashboard
GET  /admin/technicians        admin.technicians.index
GET  /admin/reports/daily      admin.reports.daily
```

---

# 12. Authentication Rules

- Login menggunakan `username` dan `password`.
- Password wajib di-hash.
- Seeder default boleh membuat akun admin untuk development.
- Jangan menampilkan password di view, log, atau dokumentasi publik kecuali untuk akun demo development.
- Tambahkan throttle login jika aplikasi dipakai production.
- Gunakan middleware `auth` untuk semua halaman admin.

Akun default development:

```text
username: helpdesk
email: admin@service.com
password: admin
```

Jika akun default berubah, update `README.md`.

---

# 13. Queue/Antrian Rules

Status antrian yang digunakan:

```text
waiting
progress
done
completed
```

Aturan status:

- `waiting`: antrian baru atau belum diproses.
- `progress`: sedang dikerjakan dan menampilkan countdown.
- `done`: selesai dan masih tampil sementara di display.
- `completed`: status selesai alternatif; gunakan hati-hati karena sebagian logic utama memakai `done`.

Aturan nomor antrian:

- Nomor antrian dibuat berdasarkan nomor terbesar pada hari berjalan.
- Jangan menggunakan total semua data sebagai nomor antrian.
- Jika aplikasi dipakai banyak operator bersamaan, pembuatan nomor antrian wajib diperkuat dengan transaction/locking untuk menghindari duplikasi.

Aturan display:

- Antrian `waiting` dan `progress` tetap tampil.
- Antrian `done` hanya tampil sementara sesuai logic display.
- Urutan prioritas tampilan: `progress`, `waiting`, lalu `done`.
- Query public display harus eager load teknisi.

---

# 14. Technician Rules

- Nama teknisi wajib diisi.
- Teknisi dapat memiliki banyak antrian.
- Menghapus teknisi yang sudah punya riwayat antrian harus dipertimbangkan dampaknya.
- Jika data teknisi penting untuk audit/laporan, gunakan nonaktifkan status daripada delete permanen.
- Laporan harian teknisi dihitung dari antrian selesai pada tanggal tertentu.

---

# 15. Display Settings Rules

Setting display disimpan di tabel `settings` dan umumnya hanya membutuhkan satu baris data.

Setting yang didukung:

- Judul aplikasi.
- Logo URL.
- Video URL atau YouTube ID.
- Tipe video.
- Running text.
- Kecepatan marquee.

Aturan:

- Validasi `app_title` wajib ada.
- Validasi `marquee_speed` wajib integer dengan batas wajar.
- YouTube URL harus diekstrak menjadi ID bersih sebelum disimpan.
- Jangan simpan embed HTML mentah dari input user.
- Jika local video diaktifkan lagi, upload wajib menggunakan `Storage::disk('public')`.

---

# 16. Report Rules

Laporan saat ini adalah laporan harian teknisi.

Aturan:

- Filter wajib menggunakan teknisi dan tanggal.
- Hitung hanya status selesai.
- Status selesai yang saat ini dihitung: `done` dan `completed`.
- Jangan export semua data tanpa filter jika fitur export ditambahkan.
- Jika laporan makin kompleks, pindahkan query ke service atau query object.

---

# 17. Upload File Rules

Saat ini fitur upload lokal video tidak aktif penuh, tetapi jika diaktifkan:

Gunakan:

```php
Storage::disk('public')
```

Validasi logo:

```text
image
mimes:jpg,jpeg,png,webp,svg
max:2048
```

Validasi video:

```text
mimes:mp4,webm
max:51200
```

Aturan upload:

- Gunakan nama file aman seperti UUID atau `hashName()`.
- Jangan menyimpan file upload di `public/` langsung tanpa storage disk.
- Hapus file lama jika diganti dan tidak dipakai lagi.
- Berikan fallback tampilan jika video gagal dimuat.

---

# 18. UI/UX Rules

- UI admin harus modern, ringan, dan mudah digunakan operator.
- Tampilan public display harus jelas dari jarak jauh.
- Teks nomor antrian, status, teknisi, dan countdown harus menjadi prioritas visual.
- Running text tidak boleh mengganggu informasi utama.
- Gunakan toast notification untuk feedback.
- Jangan gunakan alert browser bawaan kecuali fallback darurat.
- Tabel admin harus memiliki pagination jika data banyak.
- Hindari desain yang terlalu ramai pada layar TV.
- Gunakan warna status yang konsisten:
    - Waiting: abu-abu.
    - Progress: kuning/biru sesuai desain aktif.
    - Done: hijau.

---

# 19. Notification dan Toast

- Semua notifikasi admin menggunakan toast.
- Event toast Livewire menggunakan nama `show-toast`.
- Pesan harus singkat, jelas, dan ramah pengguna.
- Validation error harus mudah dipahami.
- Jangan menampilkan stack trace kepada user.

Contoh pesan:

```text
Antrian baru berhasil ditambahkan.
Data teknisi berhasil diperbarui.
Pengaturan display berhasil disimpan.
```

---

# 20. Security Rules

- Password wajib di-hash.
- Gunakan `.env` untuk konfigurasi sensitif.
- Jangan commit `.env`.
- Gunakan CSRF protection.
- Validasi semua input.
- Escape output user.
- Jangan menyimpan HTML mentah dari input user.
- Admin route wajib dilindungi middleware `auth`.
- Tambahkan throttle login untuk production.
- Jangan tampilkan error teknis ke public display.

---

# 21. Error Handling dan Logging

- Jangan tampilkan stack trace di production.
- Catat error upload file.
- Catat error parsing YouTube URL jika logic diperluas.
- Catat error penting pada proses create/update/delete antrian.
- Berikan pesan yang mudah dipahami pengguna.
- Untuk operasi yang mengubah banyak data, gunakan `DB::transaction()`.

---

# 22. Seeder Rules

Seeder minimal harus membuat:

- User admin default untuk development.
- Setting display default.

Seeder boleh menambahkan data dummy antrian atau teknisi hanya jika dibutuhkan untuk demo/development.

Jika akun default atau setting default berubah, update `README.md`.

---

# 23. Dokumentasi dan Commit

## 23.1 README

- `README.md` wajib menggunakan bahasa Indonesia.
- `README.md` harus menjelaskan project sebagai Service Display Helpdesk, bukan template Laravel bawaan.
- Update `README.md` setiap ada perubahan relevan pada:
    - Fitur atau modul.
    - Struktur folder.
    - Setup dan verifikasi.
    - Akun demo atau seeder penting.
    - Aturan development yang berdampak ke workflow.
- Jangan biarkan `README.md` kembali menjadi template Laravel bawaan.

## 23.2 Commit Message

Gunakan Conventional Commits dengan format:

```text
<type>(<scope>): <subject>
```

Scope bersifat opsional jika perubahan bersifat umum.

Tipe commit yang diperbolehkan:

- `feat`: Penambahan fitur baru.
- `fix`: Perbaikan bug atau error.
- `docs`: Perubahan dokumentasi, seperti `README.md` atau `AGENTS.md`.
- `refactor`: Merapikan kode atau optimasi tanpa fitur baru dan tanpa bugfix.
- `style`: Perubahan format visual/penulisan tanpa mengubah logic.
- `test`: Menambah atau mengubah test.
- `chore`: Maintenance rutin, build task, atau pembaruan package.

Aturan penulisan:

- Gunakan bahasa Inggris.
- Gunakan kata kerja bentuk imperatif/present tense, misalnya `Add`, `Fix`, `Update`, `Remove`.
- Subject harus ringkas dan maksimal 50 karakter jika memungkinkan.
- Jika memakai body commit, batasi tiap baris maksimal 72 karakter.
- Jangan akhiri subject dengan tanda titik.
- Fokus pada alasan perubahan, bukan hanya detail teknis implementasi.

Contoh benar:

```text
docs: update agent rules for service display
feat(queue): add queue status filter
fix(auth): validate username login
```

Contoh salah:

```text
fix benerin antrian
Added technician crud.
update file
```

---

# 24. Testing dan Verifikasi

Minimal lakukan pengujian:

- Login admin.
- Logout admin.
- Public display terbuka tanpa login.
- Admin dashboard hanya bisa dibuka setelah login.
- Tambah antrian.
- Edit status antrian ke `progress`.
- Countdown tampil pada public display.
- Edit status antrian ke `done`.
- Tambah/edit/hapus teknisi.
- Laporan harian teknisi.
- Simpan pengaturan display.

Aturan eksekusi command:

- Jangan menjalankan `npm run build` secara otomatis. Build frontend dilakukan manual oleh pemilik project kecuali diminta eksplisit.
- Jangan menjalankan `php artisan serve` secara otomatis. Server lokal dijalankan manual oleh pemilik project kecuali diminta eksplisit.
- Agent boleh menjalankan verifikasi non-server seperti:
    - `php artisan test`
    - `php artisan route:list`
    - `php artisan migrate:status`
    - pengecekan syntax PHP

---

# 25. Catatan Pengembangan Masa Depan

Fitur berikut boleh ditambahkan jika diminta, tetapi jangan diasumsikan sudah ada:

- Role dan permission.
- Multi loket atau multi divisi.
- Audit log.
- Export Excel/PDF.
- Upload video lokal.
- Pengumuman suara.
- Statistik performa teknisi.
- Integrasi WhatsApp/Telegram.
- API untuk display eksternal.

Jika fitur masa depan ditambahkan, update migration, model, route, view, testing, dan `README.md` secara konsisten.
