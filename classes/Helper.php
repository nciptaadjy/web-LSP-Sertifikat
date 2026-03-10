<?php
// ============================================
//  classes/Helper.php
//  Fungsi-fungsi utilitas
// ============================================

class Helper
{
    /** Format angka ke mata uang Rupiah */
    public static function rupiah(float $angka, bool $singkat = false): string
    {
        if ($singkat) {
            if ($angka >= 1_000_000_000) return 'Rp ' . number_format($angka / 1_000_000_000, 1, ',', '.') . 'M';
            if ($angka >= 1_000_000)     return 'Rp ' . number_format($angka / 1_000_000,     1, ',', '.') . 'jt';
            if ($angka >= 1_000)         return 'Rp ' . number_format($angka / 1_000,          0, ',', '.') . 'rb';
        }
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    /** Format tanggal ke Bahasa Indonesia */
    public static function tanggalIndo(string $tanggal): string
    {
        $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                       'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $ts = strtotime($tanggal);
        return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
    }

    /** Bersihkan input dari XSS */
    public static function bersihkan(mixed $input): string
    {
        return htmlspecialchars(trim((string)$input), ENT_QUOTES, 'UTF-8');
    }

    /** Redirect */
    public static function redirect(string $url): never
    {
        header("Location: $url");
        exit;
    }

    /** Set pesan flash */
    public static function setFlash(string $tipe, string $pesan): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
    }

    /** Ambil dan hapus pesan flash */
    public static function getFlash(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /** Hitung persentase (aman dari division by zero) */
    public static function persen(float $nilai, float $total): float
    {
        if ($total <= 0) return 0;
        return round(($nilai / $total) * 100, 1);
    }
}
