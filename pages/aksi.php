<?php
// ============================================
//  pages/aksi.php
//  Handler CRUD Transaksi (POST handler)
// ============================================

session_start();
require_once __DIR__ . '/../classes/Transaksi.php';
require_once __DIR__ . '/../classes/Helper.php';

$transaksi = new Transaksi();
$aksi = $_POST['aksi'] ?? '';

switch ($aksi) {

    // --- Tambah ---
    case 'tambah':
        $kategoriId = (int)($_POST['kategori_id'] ?? 0);
        $judul      = Helper::bersihkan($_POST['judul'] ?? '');
        $jumlah     = (float)($_POST['jumlah'] ?? 0);
        $jenis      = $_POST['jenis'] === 'pemasukan' ? 'pemasukan' : 'pengeluaran';
        $catatan    = Helper::bersihkan($_POST['catatan'] ?? '');
        $tanggal    = $_POST['tanggal'] ?? date('Y-m-d');

        if ($kategoriId && $judul && $jumlah > 0) {
            $transaksi->create([
                'kategori_id' => $kategoriId,
                'judul'       => $judul,
                'jumlah'      => $jumlah,
                'jenis'       => $jenis,
                'catatan'     => $catatan,
                'tanggal'     => $tanggal,
            ]);
            Helper::setFlash('sukses', 'Transaksi berhasil ditambahkan! 🎉');
        } else {
            Helper::setFlash('gagal', 'Data tidak lengkap. Harap isi semua field.');
        }
        break;

    // --- Edit ---
    case 'edit':
        $id         = (int)($_POST['id'] ?? 0);
        $kategoriId = (int)($_POST['kategori_id'] ?? 0);
        $judul      = Helper::bersihkan($_POST['judul'] ?? '');
        $jumlah     = (float)($_POST['jumlah'] ?? 0);
        $jenis      = $_POST['jenis'] === 'pemasukan' ? 'pemasukan' : 'pengeluaran';
        $catatan    = Helper::bersihkan($_POST['catatan'] ?? '');
        $tanggal    = $_POST['tanggal'] ?? date('Y-m-d');

        if ($id && $kategoriId && $judul && $jumlah > 0) {
            $transaksi->update($id, [
                'kategori_id' => $kategoriId,
                'judul'       => $judul,
                'jumlah'      => $jumlah,
                'jenis'       => $jenis,
                'catatan'     => $catatan,
                'tanggal'     => $tanggal,
            ]);
            Helper::setFlash('sukses', 'Transaksi berhasil diperbarui! ✅');
        } else {
            Helper::setFlash('gagal', 'Data tidak valid.');
        }
        break;

    // --- Hapus ---
    case 'hapus':
        $id = (int)($_POST['id'] ?? 0);
        if ($id && $transaksi->delete($id)) {
            Helper::setFlash('sukses', 'Transaksi berhasil dihapus.');
        } else {
            Helper::setFlash('gagal', 'Gagal menghapus transaksi.');
        }
        break;
}

// Redirect kembali
$ref = $_SERVER['HTTP_REFERER'] ?? '../index.php';
Helper::redirect($ref);
