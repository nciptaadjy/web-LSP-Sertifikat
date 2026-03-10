<?php
// ============================================
//  classes/Transaksi.php
//  Model untuk tabel transaksi
// ============================================

require_once __DIR__ . '/Database.php';

class Transaksi
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Ambil semua transaksi dengan filter opsional */
    public function getAll(array $filter = []): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filter['bulan'])) {
            $where[]  = "MONTH(t.tanggal) = ?";
            $params[] = $filter['bulan'];
        }
        if (!empty($filter['tahun'])) {
            $where[]  = "YEAR(t.tanggal) = ?";
            $params[] = $filter['tahun'];
        }
        if (!empty($filter['jenis'])) {
            $where[]  = "t.jenis = ?";
            $params[] = $filter['jenis'];
        }
        if (!empty($filter['kategori_id'])) {
            $where[]  = "t.kategori_id = ?";
            $params[] = $filter['kategori_id'];
        }

        $sql = "SELECT t.*, k.nama AS kategori_nama, k.ikon, k.warna
                FROM transaksi t
                JOIN kategori k ON t.kategori_id = k.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY t.tanggal DESC, t.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /** Ambil transaksi hari ini */
    public function getHariIni(): array
    {
        return $this->db->fetchAll(
            "SELECT t.*, k.nama AS kategori_nama, k.ikon, k.warna
             FROM transaksi t
             JOIN kategori k ON t.kategori_id = k.id
             WHERE t.tanggal = CURDATE()
             ORDER BY t.created_at DESC"
        );
    }

    /** Ambil transaksi berdasarkan ID */
    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT t.*, k.nama AS kategori_nama
             FROM transaksi t
             JOIN kategori k ON t.kategori_id = k.id
             WHERE t.id = ?",
            [$id]
        );
    }

    /** Tambah transaksi baru */
    public function create(array $data): int
    {
        $this->db->query(
            "INSERT INTO transaksi (kategori_id, judul, jumlah, jenis, catatan, tanggal)
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['kategori_id'],
                $data['judul'],
                $data['jumlah'],
                $data['jenis'],
                $data['catatan'] ?? null,
                $data['tanggal'],
            ]
        );
        return (int) $this->db->lastInsertId();
    }

    /** Update transaksi */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->query(
            "UPDATE transaksi
             SET kategori_id=?, judul=?, jumlah=?, jenis=?, catatan=?, tanggal=?
             WHERE id=?",
            [
                $data['kategori_id'],
                $data['judul'],
                $data['jumlah'],
                $data['jenis'],
                $data['catatan'] ?? null,
                $data['tanggal'],
                $id,
            ]
        );
        return $stmt->rowCount() > 0;
    }

    /** Hapus transaksi */
    public function delete(int $id): bool
    {
        $stmt = $this->db->query(
            "DELETE FROM transaksi WHERE id = ?",
            [$id]
        );
        return $stmt->rowCount() > 0;
    }

    /** Ringkasan bulan ini */
    public function getRingkasanBulanIni(): array
    {
        $row = $this->db->fetchOne(
            "SELECT
                COALESCE(SUM(CASE WHEN jenis='pemasukan'   THEN jumlah END), 0) AS total_pemasukan,
                COALESCE(SUM(CASE WHEN jenis='pengeluaran' THEN jumlah END), 0) AS total_pengeluaran,
                COUNT(*) AS total_transaksi
             FROM transaksi
             WHERE MONTH(tanggal) = MONTH(CURDATE())
               AND YEAR(tanggal)  = YEAR(CURDATE())"
        );

        $row['saldo'] = $row['total_pemasukan'] - $row['total_pengeluaran'];
        return $row;
    }

    /** Ringkasan hari ini */
    public function getRingkasanHariIni(): array
    {
        $row = $this->db->fetchOne(
            "SELECT
                COALESCE(SUM(CASE WHEN jenis='pemasukan'   THEN jumlah END), 0) AS pemasukan,
                COALESCE(SUM(CASE WHEN jenis='pengeluaran' THEN jumlah END), 0) AS pengeluaran
             FROM transaksi WHERE tanggal = CURDATE()"
        );
        return $row ?: ['pemasukan' => 0, 'pengeluaran' => 0];
    }

    /** Pengeluaran per kategori bulan ini (untuk chart) */
    public function getPengeluaranPerKategori(): array
    {
        return $this->db->fetchAll(
            "SELECT k.nama, k.warna, SUM(t.jumlah) AS total
             FROM transaksi t
             JOIN kategori k ON t.kategori_id = k.id
             WHERE t.jenis = 'pengeluaran'
               AND MONTH(t.tanggal) = MONTH(CURDATE())
               AND YEAR(t.tanggal)  = YEAR(CURDATE())
             GROUP BY k.id, k.nama, k.warna
             ORDER BY total DESC"
        );
    }

    /** Tren 7 hari terakhir */
    public function getTren7Hari(): array
    {
        return $this->db->fetchAll(
            "SELECT
                DATE_FORMAT(tanggal,'%d %b') AS hari,
                COALESCE(SUM(CASE WHEN jenis='pemasukan'   THEN jumlah END), 0) AS pemasukan,
                COALESCE(SUM(CASE WHEN jenis='pengeluaran' THEN jumlah END), 0) AS pengeluaran
             FROM transaksi
             WHERE tanggal BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
             GROUP BY tanggal
             ORDER BY tanggal ASC"
        );
    }
}
