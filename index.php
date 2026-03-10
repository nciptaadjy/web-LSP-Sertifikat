<?php
// ============================================
//  index.php
//  Halaman Dashboard Utama – Finansial Harian
// ============================================

session_start();
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/classes/Transaksi.php';
require_once __DIR__ . '/classes/Kategori.php';
require_once __DIR__ . '/classes/Helper.php';

$transaksiModel = new Transaksi();
$kategoriModel  = new Kategori();

// Data Dashboard
$ringkasanBulan  = $transaksiModel->getRingkasanBulanIni();
$ringkasanHari   = $transaksiModel->getRingkasanHariIni();
$transaksiHari   = $transaksiModel->getHariIni();
$pengeluaranKat  = $transaksiModel->getPengeluaranPerKategori();
$tren7Hari       = $transaksiModel->getTren7Hari();
$semuaKategori   = $kategoriModel->getAll();
$flash           = Helper::getFlash();

$totalPengeluaranKat = array_sum(array_column($pengeluaranKat, 'total'));

$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<?php include __DIR__ . '/pages/partials/sidebar.php'; ?>

<!-- ===== KONTEN UTAMA ===== -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-title">
            Dashboard <span><?= $namaBulan[(int)date('m')] ?> <?= date('Y') ?></span>
        </div>
        <div class="topbar-right">
            <button class="btn-tambah" onclick="App.bukaModal('modal-tambah')">
                <i data-lucide="plus"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <div class="page">

        <!-- Flash Message -->
        <?php if ($flash): ?>
        <div class="flash <?= $flash['tipe'] ?>">
            <i data-lucide="<?= $flash['tipe']==='sukses' ? 'check-circle' : 'alert-circle' ?>"></i>
            <?= $flash['pesan'] ?>
        </div>
        <?php endif; ?>

        <!-- ===== KARTU STATISTIK ===== -->
        <div class="stats-grid">

            <div class="stat-card saldo">
                <div class="stat-label">
                    <i data-lucide="wallet" style="width:14px;height:14px;"></i> Saldo Bulan Ini
                </div>
                <div class="stat-value"><?= Helper::rupiah($ringkasanBulan['saldo'], true) ?></div>
                <div class="stat-sub"><?= $ringkasanBulan['total_transaksi'] ?> total transaksi</div>
            </div>

            <div class="stat-card masuk">
                <div class="stat-label">
                    <i data-lucide="trending-up" style="width:14px;height:14px;"></i> Total Pemasukan
                </div>
                <div class="stat-value"><?= Helper::rupiah($ringkasanBulan['total_pemasukan'], true) ?></div>
                <div class="stat-sub">Bulan <?= $namaBulan[(int)date('m')] ?></div>
            </div>

            <div class="stat-card keluar">
                <div class="stat-label">
                    <i data-lucide="trending-down" style="width:14px;height:14px;"></i> Total Pengeluaran
                </div>
                <div class="stat-value"><?= Helper::rupiah($ringkasanBulan['total_pengeluaran'], true) ?></div>
                <div class="stat-sub">Bulan <?= $namaBulan[(int)date('m')] ?></div>
            </div>

            <div class="stat-card transaksi">
                <div class="stat-label">
                    <i data-lucide="calendar" style="width:14px;height:14px;"></i> Hari Ini
                </div>
                <div class="stat-value" style="color:#3b82f6;">
                    <?= Helper::rupiah($ringkasanHari['pemasukan'] - $ringkasanHari['pengeluaran'], true) ?>
                </div>
                <div class="stat-sub">
                    Masuk: <?= Helper::rupiah($ringkasanHari['pemasukan'], true) ?> |
                    Keluar: <?= Helper::rupiah($ringkasanHari['pengeluaran'], true) ?>
                </div>
            </div>

        </div>

        <!-- ===== GRID KONTEN ===== -->
        <div class="content-grid">

            <!-- KIRI: Transaksi Hari Ini -->
            <div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="clock"></i>
                            Transaksi Hari Ini
                        </div>
                        <a href="pages/transaksi.php" class="card-link">Lihat Semua →</a>
                    </div>

                    <?php if (empty($transaksiHari)): ?>
                    <div class="tabel-kosong">
                        <div class="icon">📭</div>
                        <p>Belum ada transaksi hari ini.<br>Mulai catat sekarang!</p>
                    </div>
                    <?php else: ?>
                    <table class="tabel-transaksi">
                        <thead>
                            <tr>
                                <th>KETERANGAN</th>
                                <th>KATEGORI</th>
                                <th>JUMLAH</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transaksiHari as $t): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;font-size:.875rem;">
                                        <?= Helper::bersihkan($t['judul']) ?>
                                    </div>
                                    <?php if ($t['catatan']): ?>
                                    <div style="font-size:.72rem;color:var(--abu-400);">
                                        <?= Helper::bersihkan($t['catatan']) ?>
                                    </div>
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
                                        <button class="btn-aksi edit" title="Edit"
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
                                        <button class="btn-aksi hapus" title="Hapus"
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

                <!-- Tren 7 Hari -->
                <?php if (!empty($tren7Hari)): ?>
                <div class="card" style="margin-top:20px;">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="bar-chart-2"></i>
                            Tren 7 Hari Terakhir
                        </div>
                    </div>
                    <div style="padding:16px 22px;overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:.8rem;">
                            <thead>
                                <tr>
                                    <th style="text-align:left;padding:6px 8px;color:var(--abu-400);font-weight:600;text-transform:uppercase;font-size:.65rem;">HARI</th>
                                    <th style="text-align:right;padding:6px 8px;color:var(--hijau-600);font-size:.65rem;font-weight:700;text-transform:uppercase;">MASUK</th>
                                    <th style="text-align:right;padding:6px 8px;color:var(--merah);font-size:.65rem;font-weight:700;text-transform:uppercase;">KELUAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tren7Hari as $h): ?>
                                <tr style="border-top:1px solid var(--abu-100);">
                                    <td style="padding:10px 8px;font-weight:600;"><?= $h['hari'] ?></td>
                                    <td style="padding:10px 8px;text-align:right;" class="jumlah-masuk">
                                        <?= $h['pemasukan'] > 0 ? '+'.Helper::rupiah($h['pemasukan'], true) : '—' ?>
                                    </td>
                                    <td style="padding:10px 8px;text-align:right;" class="jumlah-keluar">
                                        <?= $h['pengeluaran'] > 0 ? '-'.Helper::rupiah($h['pengeluaran'], true) : '—' ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- KANAN: Sidebar Cards -->
            <div style="display:flex;flex-direction:column;gap:20px;">

                <!-- Ringkasan Hari Ini -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="sun"></i>
                            Ringkasan Hari Ini
                        </div>
                    </div>
                    <div class="ringkasan-hari">
                        <div class="ringkasan-item">
                            <div class="ringkasan-ikon hijau">↑</div>
                            <div class="ringkasan-info">
                                <div class="label">Pemasukan</div>
                                <div class="nilai hijau"><?= Helper::rupiah($ringkasanHari['pemasukan']) ?></div>
                            </div>
                        </div>
                        <div class="ringkasan-item">
                            <div class="ringkasan-ikon merah">↓</div>
                            <div class="ringkasan-info">
                                <div class="label">Pengeluaran</div>
                                <div class="nilai merah"><?= Helper::rupiah($ringkasanHari['pengeluaran']) ?></div>
                            </div>
                        </div>
                        <div style="border-top:1px solid var(--abu-100);padding-top:12px;margin-top:2px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:.8rem;font-weight:600;color:var(--abu-500);">Selisih Hari Ini</span>
                                <?php $selisih = $ringkasanHari['pemasukan'] - $ringkasanHari['pengeluaran']; ?>
                                <span style="font-weight:800;font-size:1rem;font-family:'DM Mono',monospace;color:<?= $selisih >= 0 ? 'var(--hijau-600)' : 'var(--merah)' ?>">
                                    <?= ($selisih >= 0 ? '+' : '') . Helper::rupiah($selisih) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran per Kategori -->
                <?php if (!empty($pengeluaranKat)): ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="pie-chart"></i>
                            Pengeluaran per Kategori
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <!-- Donut Chart SVG -->
                        <div class="donut-chart">
                            <svg id="donut-svg" width="140" height="140" viewBox="0 0 140 140"></svg>
                            <div class="donut-center">
                                <div class="angka"><?= Helper::rupiah($totalPengeluaranKat, true) ?></div>
                                <div class="sub">Total</div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="legend-list">
                            <?php foreach ($pengeluaranKat as $pk): ?>
                            <div class="legend-item">
                                <div class="legend-dot" style="background:<?= $pk['warna'] ?>"></div>
                                <div class="legend-nama"><?= Helper::bersihkan($pk['nama']) ?></div>
                                <div class="legend-persen">
                                    <?= Helper::persen($pk['total'], $totalPengeluaranKat) ?>%
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Progress Bulan -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="activity"></i>
                            Kesehatan Keuangan
                        </div>
                    </div>
                    <div style="padding:16px 22px;">
                        <?php
                        $total = $ringkasanBulan['total_pemasukan'] + $ringkasanBulan['total_pengeluaran'];
                        $pctMasuk  = Helper::persen($ringkasanBulan['total_pemasukan'],   $total ?: 1);
                        $pctKeluar = Helper::persen($ringkasanBulan['total_pengeluaran'], $total ?: 1);
                        $rasio = $ringkasanBulan['total_pemasukan'] > 0
                            ? round(($ringkasanBulan['total_pengeluaran'] / $ringkasanBulan['total_pemasukan']) * 100)
                            : 0;
                        ?>
                        <div style="margin-bottom:14px;">
                            <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:6px;">
                                <span style="color:var(--hijau-600);font-weight:600;">Pemasukan <?= $pctMasuk ?>%</span>
                                <span style="color:var(--merah);font-weight:600;">Pengeluaran <?= $pctKeluar ?>%</span>
                            </div>
                            <div style="height:10px;border-radius:99px;background:var(--abu-100);overflow:hidden;">
                                <div style="height:100%;width:<?= $pctMasuk ?>%;background:linear-gradient(90deg,var(--hijau-400),var(--hijau-600));border-radius:99px;transition:width .5s;"></div>
                            </div>
                        </div>
                        <div style="text-align:center;padding:12px;background:var(--abu-50);border-radius:var(--radius-sm);">
                            <div style="font-size:.72rem;color:var(--abu-400);margin-bottom:4px;">Rasio Pengeluaran</div>
                            <div style="font-size:1.5rem;font-weight:800;color:<?= $rasio <= 70 ? 'var(--hijau-600)' : ($rasio <= 90 ? '#f59e0b' : 'var(--merah)') ?>;">
                                <?= $rasio ?>%
                            </div>
                            <div style="font-size:.72rem;color:var(--abu-400);margin-top:4px;">
                                <?php
                                if ($rasio <= 70)      echo '✅ Keuangan sehat!';
                                elseif ($rasio <= 90)  echo '⚠️ Waspadai pengeluaran';
                                else                   echo '🔴 Pengeluaran berlebihan';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- /page -->
</div><!-- /main-content -->

<!-- Modals -->
<?php include __DIR__ . '/pages/partials/modals.php'; ?>

<script src="assets/js/app.js"></script>
<script>
    lucide.createIcons();
    App.setJenis('pengeluaran');

    // Render donut chart
    <?php if (!empty($pengeluaranKat)): ?>
    App.renderDonut('donut-svg', <?= json_encode(array_map(fn($k) => [
        'nilai' => (float)$k['total'],
        'warna' => $k['warna'],
    ], $pengeluaranKat)) ?>);
    <?php endif; ?>
</script>
</body>
</html>
