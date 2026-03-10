# 💰 Finansial Harian

Aplikasi monitoring keuangan sehari-hari berbasis PHP dengan tema hijau-putih.

---

## 📁 Struktur File

```
finansial/
├── index.php                   ← Dashboard utama
├── database.sql                ← Schema & data awal MySQL
├── README.md
│
├── config/
│   ├── app.php                 ← Konfigurasi aplikasi
│   └── database.php            ← Konfigurasi koneksi database
│
├── classes/
│   ├── Database.php            ← Singleton PDO (OOP)
│   ├── Transaksi.php           ← Model transaksi (OOP)
│   ├── Kategori.php            ← Model kategori (OOP)
│   └── Helper.php              ← Fungsi utilitas (OOP)
│
├── pages/
│   ├── transaksi.php           ← Halaman semua transaksi
│   ├── aksi.php                ← Handler CRUD (POST)
│   └── partials/
│       ├── sidebar.php         ← Komponen sidebar
│       └── modals.php          ← Modal tambah/edit/hapus
│
└── assets/
    ├── css/
    │   └── style.css           ← Stylesheet tema hijau-putih
    └── js/
        └── app.js              ← JavaScript interaktif
```

---

## ⚙️ Instalasi

### 1. Persyaratan
- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.3+
- Web server (Apache/Nginx) atau PHP built-in server

### 2. Import Database

Buka MySQL / phpMyAdmin dan jalankan:

```sql
SOURCE /path/to/finansial/database.sql;
```

Atau via terminal:
```bash
mysql -u root -p < database.sql
```

### 3. Konfigurasi Koneksi

Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Username MySQL Anda
define('DB_PASS', '');            // Password MySQL Anda
define('DB_NAME', 'finansial_harian');
```

### 4. Jalankan Aplikasi

**Via PHP built-in server:**
```bash
cd finansial
php -S localhost:8000
```
Buka browser: `http://localhost:8000`

**Via XAMPP/WAMP:**
Salin folder `finansial` ke `htdocs/` lalu buka `http://localhost/finansial`

---

## ✨ Fitur

| Fitur | Keterangan |
|-------|------------|
| 📊 Dashboard | Ringkasan saldo, pemasukan, pengeluaran bulan ini |
| ➕ Tambah Transaksi | Form modal dengan toggle pemasukan/pengeluaran |
| ✏️ Edit Transaksi | Ubah data transaksi yang sudah ada |
| 🗑️ Hapus Transaksi | Hapus dengan konfirmasi |
| 🔍 Filter | Filter per bulan, tahun, jenis, dan kategori |
| 🥧 Chart Kategori | Donut chart pengeluaran per kategori |
| 📈 Tren 7 Hari | Tabel tren pemasukan & pengeluaran |
| ❤️ Kesehatan Keuangan | Rasio dan indikator keuangan |

---

## 🏗️ Arsitektur OOP

- **`Database`** — Singleton pattern, satu koneksi PDO di seluruh aplikasi
- **`Transaksi`** — Model dengan metode CRUD + query agregasi
- **`Kategori`** — Model untuk manajemen kategori
- **`Helper`** — Static utility class (format rupiah, tanggal, flash message)

---

## 🎨 Tema

- Warna utama: Hijau (`#22c55e`) & Putih (`#ffffff`)
- Font: Plus Jakarta Sans + DM Mono
- UI: Clean, minimal, responsive
