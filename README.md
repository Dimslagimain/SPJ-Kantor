# SPJ Kantor

Dashboard pengelolaan Surat Pertanggungjawaban (SPJ) untuk membantu user mengajukan dan memantau SPJ, serta membantu bendahara memeriksa, menyetujui, dan memantau seluruh pengajuan.

Aplikasi ini dibangun menggunakan Laravel, Blade, Tailwind CSS, Vite, dan MySQL/MariaDB.

## Fitur Utama

### User

- Login ke dashboard.
- Mengajukan SPJ baru.
- Melihat daftar SPJ miliknya.
- Memantau status pengajuan: menunggu review, approved, atau perlu revisi.
- Mengubah profil dan password.
- Logout dari aplikasi.

### Bendahara

- Melihat ringkasan seluruh pengajuan SPJ.
- Melihat antrean SPJ yang menunggu review.
- Menyetujui SPJ sehingga status berubah menjadi `approved`.
- Mengembalikan SPJ untuk direvisi.
- Melihat laporan berdasarkan data aktual.
- Menambahkan dan menghapus user.
- Mengubah profil dan password.
- Logout dari aplikasi.

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP 8.3 atau lebih baru.
- Composer.
- Node.js dan npm.
- Laragon dengan MySQL atau MariaDB.
- Git.

Ekstensi PHP yang dibutuhkan:

- `pdo_mysql`
- `mbstring`
- `openssl`
- `fileinfo`
- `tokenizer`
- `xml`

## Instalasi dari GitHub

### 1. Clone repository

Buka PowerShell atau terminal, kemudian jalankan:

```powershell
git clone https://github.com/Dimslagimain/SPJ-Kantor.git
cd SPJ-Kantor
```

Jika folder project berada di `C:\laragon\www`, gunakan:

```powershell
cd C:\laragon\www
git clone https://github.com/Dimslagimain/SPJ-Kantor.git
cd SPJ-Kantor
```

### 2. Install dependency PHP

```powershell
composer install
```

### 3. Install dependency frontend

```powershell
npm install
```

### 4. Buat file environment

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Konfigurasi default `.env` sudah disiapkan untuk MySQL Laragon:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spj
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` apabila konfigurasi MySQL Laragon Anda berbeda.

## Menyiapkan Database Laragon

### Pilihan A: Menggunakan migration Laravel

1. Buka Laragon.
2. Jalankan MySQL atau MariaDB.
3. Buat database kosong dengan nama `spj` melalui HeidiSQL atau phpMyAdmin.
4. Jalankan migration:

```powershell
php artisan migrate
```

File `DatabaseSeeder` tidak membuat data demo. Data user dan SPJ dapat dimasukkan melalui aplikasi.

### Pilihan B: Import file SQL

File SQL siap import tersedia di:

```text
database/spj_laragon.sql
```

Langkah import melalui phpMyAdmin:

1. Jalankan Laragon dan aktifkan MySQL.
2. Buka `http://localhost/phpmyadmin`.
3. Pilih menu **Import**.
4. Pilih file `database/spj_laragon.sql`.
5. Jalankan proses import.

File SQL berisi struktur database tanpa data SPJ atau akun dummy.

## Menjalankan Dashboard

### Mode development

Jalankan server Laravel:

```powershell
php artisan serve
```

Pada terminal lain, jalankan Vite:

```powershell
npm run dev
```

Buka dashboard pada:

```text
http://127.0.0.1:8000
```

### Build frontend production

Untuk membuat asset frontend production:

```powershell
npm run build
```

Setelah build selesai, aplikasi tetap dapat dijalankan menggunakan:

```powershell
php artisan serve
```

## Membuat User dan Bendahara

Karena aplikasi tidak lagi menggunakan data dummy, akun harus dibuat dari database atau melalui fitur administrasi yang tersedia.

Contoh membuat akun menggunakan Tinker:

```powershell
php artisan tinker
```

Kemudian jalankan:

```php
use App\Models\User;

User::create([
    'name' => 'Bendahara',
    'email' => 'bendahara@example.com',
    'password' => 'password',
    'role' => 'bendahara',
]);

User::create([
    'name' => 'User SPJ',
    'email' => 'user@example.com',
    'password' => 'password',
    'role' => 'user',
]);
```

Login melalui `/login` menggunakan email dan password yang telah dibuat.

Setelah login sebagai bendahara, user biasa dapat ditambahkan melalui menu **Kelola User**.

## Alur Penggunaan

1. User login ke aplikasi.
2. User mengisi form **Ajukan SPJ**.
3. SPJ muncul pada menu **Pantau SPJ** dengan status menunggu review.
4. Bendahara membuka **Antrean Review**.
5. Bendahara memilih **Setujui SPJ** atau **Minta revisi**.
6. Status SPJ tersimpan di database dan dapat dipantau kembali oleh user.

## Pengujian

Jalankan seluruh test Laravel:

```powershell
php artisan test --compact
```

Format kode PHP menggunakan Laravel Pint:

```powershell
vendor\bin\pint --format agent
```

## Struktur Direktori Penting

```text
app/
  Http/Controllers/       Controller login, SPJ, review, dan user
  Http/Middleware/        Middleware role user/bendahara
  Models/                 Model User dan SPJ
database/
  migrations/             Struktur tabel database
  spj_laragon.sql         Dump SQL untuk Laragon
resources/views/
  auth/                   Halaman login
  pages/                  Dashboard, SPJ, laporan, dan kelola user
  review/                 Halaman review bendahara
routes/web.php             Route aplikasi
```

## Troubleshooting

### `SQLSTATE[HY000] [1049] Unknown database 'spj'`

Buat database `spj` di MySQL Laragon, atau import file `database/spj_laragon.sql`.

### `could not find driver`

Aktifkan ekstensi `pdo_mysql` pada `php.ini` Laragon, kemudian restart Laragon.

### Asset tidak tampil

Jalankan:

```powershell
npm install
npm run build
```

Untuk development gunakan `npm run dev` bersamaan dengan `php artisan serve`.

### Port 8000 sedang digunakan

Gunakan port lain:

```powershell
php artisan serve --port=8001
```

Kemudian buka `http://127.0.0.1:8001`.
