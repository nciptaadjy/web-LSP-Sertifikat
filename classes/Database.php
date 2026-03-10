<?php
// ============================================
//  classes/Database.php
//  Koneksi Database – Singleton Pattern
// ============================================

require_once __DIR__ . '/../config/database.php';

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode([
                'error' => true,
                'message' => 'Koneksi database gagal: ' . $e->getMessage()
            ]));
        }
    }

    /** Mendapatkan instance tunggal */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Mengembalikan objek PDO */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /** Shortcut: jalankan query dengan parameter */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Shortcut: ambil semua baris */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /** Shortcut: ambil satu baris */
    public function fetchOne(string $sql, array $params = []): array|false
    {
        return $this->query($sql, $params)->fetch();
    }

    /** Shortcut: last insert id */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    // Cegah clone & unserialize
    private function __clone() {}
    public function __wakeup() {}
}
