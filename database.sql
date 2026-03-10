-- ============================================
--  Database: finansial_harian
--  Monitoring Keuangan Sehari-hari
-- ============================================

CREATE DATABASE IF NOT EXISTS finansial_harian
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE finansial_harian;

-- Tabel Kategori
CREATE TABLE IF NOT EXISTS kategori (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100) NOT NULL,
    jenis       ENUM('pemasukan','pengeluaran') NOT NULL,
    ikon        VARCHAR(50) DEFAULT 'tag',
    warna       VARCHAR(7) DEFAULT '#22c55e',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori_id  INT UNSIGNED NOT NULL,
    judul        VARCHAR(200) NOT NULL,
    jumlah       DECIMAL(15,2) NOT NULL,
    jenis        ENUM('pemasukan','pengeluaran') NOT NULL,
    catatan      TEXT,
    tanggal      DATE NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_transaksi_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_tanggal (tanggal),
    INDEX idx_jenis   (jenis)
) ENGINE=InnoDB;

-- Tabel Anggaran (Budget Bulanan)
CREATE TABLE IF NOT EXISTS anggaran (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori_id  INT UNSIGNED NOT NULL,
    bulan        TINYINT UNSIGNED NOT NULL COMMENT '1-12',
    tahun        YEAR NOT NULL,
    batas        DECIMAL(15,2) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_anggaran (kategori_id, bulan, tahun),
    CONSTRAINT fk_anggaran_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
--  Data Default: Kategori
-- ============================================
INSERT INTO kategori (nama, jenis, ikon, warna) VALUES
-- Pemasukan
('Gaji',            'pemasukan',   'briefcase',    '#16a34a'),
('Freelance',       'pemasukan',   'laptop',       '#15803d'),
('Investasi',       'pemasukan',   'trending-up',  '#166534'),
('Bonus',           'pemasukan',   'gift',         '#14532d'),
('Lainnya',         'pemasukan',   'plus-circle',  '#4ade80'),
-- Pengeluaran
('Makan & Minum',   'pengeluaran', 'coffee',       '#ef4444'),
('Transportasi',    'pengeluaran', 'truck',        '#f97316'),
('Belanja',         'pengeluaran', 'shopping-bag', '#eab308'),
('Kesehatan',       'pengeluaran', 'heart',        '#ec4899'),
('Hiburan',         'pengeluaran', 'music',        '#8b5cf6'),
('Tagihan',         'pengeluaran', 'file-text',    '#06b6d4'),
('Pendidikan',      'pengeluaran', 'book',         '#3b82f6'),
('Lainnya',         'pengeluaran', 'minus-circle', '#6b7280');

-- ============================================
--  Data Contoh: Transaksi
-- ============================================
INSERT INTO transaksi (kategori_id, judul, jumlah, jenis, catatan, tanggal) VALUES
(1, 'Gaji Bulan Ini',         5000000, 'pemasukan',   'Transfer dari perusahaan', CURDATE() - INTERVAL 5 DAY),
(2, 'Proyek Website Klien A', 1500000, 'pemasukan',   'Pembayaran tahap 1',       CURDATE() - INTERVAL 3 DAY),
(6, 'Makan Siang Kantor',       45000, 'pengeluaran', 'Nasi padang + es teh',     CURDATE() - INTERVAL 2 DAY),
(7, 'Bensin Motor',             80000, 'pengeluaran', 'Full tank',                CURDATE() - INTERVAL 2 DAY),
(6, 'Sarapan',                  25000, 'pengeluaran', 'Bubur ayam',               CURDATE() - INTERVAL 1 DAY),
(8, 'Belanja Bulanan',         350000, 'pengeluaran', 'Supermarket',              CURDATE() - INTERVAL 1 DAY),
(11,'Listrik & Air',           250000, 'pengeluaran', 'Tagihan bulan ini',        CURDATE()),
(6, 'Makan Malam',              65000, 'pengeluaran', 'Ayam bakar + nasi',        CURDATE()),
(3, 'Dividen Reksa Dana',      200000, 'pemasukan',   'Reksa dana campuran',      CURDATE());
