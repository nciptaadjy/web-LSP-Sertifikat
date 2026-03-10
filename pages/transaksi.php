<?php
// ============================================
//  pages/transaksi.php
//  Halaman Semua Transaksi
// ============================================

session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../classes/Transaksi.php';
require_once __DIR__ . '/../classes/Kategori.php';
require_once __DIR__ . '/../classes/Helper.php';

$transaksiModel = new Transaksi();
$kategoriModel  = new Kategori();

// Filter
$filter = [
    'bulan'      => $_GET['bulan']      ?? date('m'),
    'tahun'      => $_GET['tahun']      ?? date('Y'),
    'jenis'      => $_GET['jenis']      ?? '',
    'kategori_id'=> $_GET['kategori_id']?? '',
];

$daftarTransaksi = $transaksiModel->getAll($filter);
$semuaKategori   = $kategoriModel->getAll();
$flash           = Helper::getFlash();

$bulanList = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>

<!-- Sidebar -->
<?php include __DIR__ . '/partials/sidebar.php'; ?>

<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-title">Semua <span>Transaksi</span></div>
        <div class="topbar-right">
            <button class="btn-tambah" onclick="App.bukaModal('modal-tambah')">
                <i data-lucide="plus"></i> Tambah
            </button>
        </div>
    </div>

    <div class="page">
        <?php if ($flash): ?>
        <div class="flash <?= $flash['tipe'] ?>">
            <i data-lucide="<?= $flash['tipe']==='sukses' ? 'check-circle' : 'x-circle' ?>"></i>
            <?= $flash['pesan'] ?>
        </div>
        <?php endif; ?>

        <!-- Filter -->
        <form method="GET" class="filter-bar">
            <select name="bulan" class="form-control">
                <?php foreach ($bulanList as $n => $nm): ?>
                <option value="<?= $n ?>" <?= $filter['bulan'] == $n ? 'selected' : '' ?>><?= $nm ?></option>
                <?php endforeach; ?>
            </select>
            <select name="tahun" class="form-control">
                <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                <option value="<?= $y ?>" <?= $filter['tahun'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <select name="jenis" class="form-control">
                <option value="">Semua Jenis</option>
                <option value="pemasukan"   <?= $filter['jenis']==='pemasukan'   ? 'selected' : '' ?>>Pemasukan</option>
                <option value="pengeluaran" <?= $filter['jenis']==='pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
            <select name="kategori_id" class="form-control">
                <option value="">Semua Kategori</option>
                <?php foreach ($semuaKategori as $k): ?>
                <option value="<?= $k['id'] ?>" <?= $filter['kategori_id']==$k['id'] ? 'selected' : '' ?>>
                    <?= Helper::bersihkan($k['nama']) ?> (<?= $k['jenis'] ?>)
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="filter" style="width:14px;height:14px;"></i> Filter
            </button>
            <a href="transaksi.php" class="btn btn-secondary">Reset</a>
        </form>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i data-lucide="list"></i>
                    Daftar Transaksi
                </div>
                <span style="font-size:.8rem;color:var(--abu-400)">
                    <?= count($daftarTransaksi) ?> data ditemukan
                </span>
            </div>
            <?php if (empty($daftarTransaksi)): ?>
            <div class="tabel-kosong">
                <div class="icon">📭</div>
                <p>Belum ada transaksi di periode ini.</p>
            </div>
            <?php else: ?>
            <table class="tabel-transaksi">
                <thead>
                    <tr>
                        <th>TANGGAL</th>
                        <th>KETERANGAN</th>
                        <th>KATEGORI</th>
                        <th>JUMLAH</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daftarTransaksi as $t): ?>
                    <tr>
                        <td style="color:var(--abu-500);font-size:.8rem;white-space:nowrap;">
                            <?= Helper::tanggalIndo($t['tanggal']) ?>
                        </td>
                        <td>
                            <div style="font-weight:600"><?= Helper::bersihkan($t['judul']) ?></div>
                            <?php if ($t['catatan']): ?>
                            <div style="font-size:.75rem;color:var(--abu-400)"><?= Helper::bersihkan($t['catatan']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge-kategori">
                                <span class="dot" style="background:<?= $t['warna'] ?>"></span>
                                <?= Helper::bersihkan($t['kategori_nama']) ?>
                            </span>
                        </td>
                        <td class="<?= $t['jenis']==='pemasukan' ? 'jumlah-masuk' : 'jumlah-keluar' ?>">
                            <?= $t['jenis']==='pemasukan' ? '+' : '-' ?>
                            <?= Helper::rupiah($t['jumlah']) ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <button class="btn-aksi edit"
                                    title="Edit"
                                    onclick='App.editTransaksi(<?= json_encode([
                                        "id"          => $t["id"],
                                        "judul"       => $t["judul"],
                                        "jumlah"      => $t["jumlah"],
                                        "jenis"       => $t["jenis"],
                                        "catatan"     => $t["catatan"],
                                        "tanggal"     => $t["tanggal"],
                                        "kategori_id" => $t["kategori_id"],
                                    ]) ?>)'>
                                    <i data-lucide="pencil"></i>
                                </button>
                                <button class="btn-aksi hapus"
                                    title="Hapus"
                                    onclick='App.konfirmasiHapus(<?= $t["id"] ?>, "<?= addslashes($t["judul"]) ?>")'>
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Tambah & Edit & Hapus -->
<?php include __DIR__ . '/partials/modals.php'; ?>

<script src="../assets/js/app.js"></script>
<script>
    lucide.createIcons();
    App.setJenis('pengeluaran');
</script>
</body>
</html>
