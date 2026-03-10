<?php
// ============================================
//  classes/Kategori.php
//  Model untuk tabel kategori
// ============================================

require_once __DIR__ . '/Database.php';

class Kategori
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Ambil semua kategori */
    public function getAll(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM kategori ORDER BY jenis, nama"
        );
    }

    /** Ambil kategori berdasarkan jenis */
    public function getByJenis(string $jenis): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM kategori WHERE jenis = ? ORDER BY nama",
            [$jenis]
        );
    }

    /** Ambil satu kategori */
    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM kategori WHERE id = ?",
            [$id]
        );
    }

    /** Tambah kategori */
    public function create(array $data): int
    {
        $this->db->query(
            "INSERT INTO kategori (nama, jenis, ikon, warna) VALUES (?, ?, ?, ?)",
            [$data['nama'], $data['jenis'], $data['ikon'] ?? 'tag', $data['warna'] ?? '#22c55e']
        );
        return (int) $this->db->lastInsertId();
    }
}
