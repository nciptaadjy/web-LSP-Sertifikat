<?php
// pages/partials/sidebar.php
$halamanSaat = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo">
            <div class="logo-icon">💰</div>
            <div>
                <div class="logo-text">Finansial</div>
                <div class="logo-sub">Monitoring Harian</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="<?= strpos($halamanSaat, 'index') !== false ? 'index.php' : '../index.php' ?>"
           class="nav-item <?= $halamanSaat === 'index.php' ? 'aktif' : '' ?>">
            <i data-lucide="layout-dashboard"></i>
            Dashboard
        </a>

        <a href="<?= strpos($halamanSaat, 'pages') === false ? 'pages/transaksi.php' : 'transaksi.php' ?>"
           class="nav-item <?= $halamanSaat === 'transaksi.php' ? 'aktif' : '' ?>">
            <i data-lucide="list"></i>
            Semua Transaksi
        </a>

        <div class="nav-label" style="margin-top:8px;">Cepat Tambah</div>

        <div class="nav-item" onclick="App.bukaModal('modal-tambah'); App.setJenis('pemasukan');" style="cursor:pointer;">
            <i data-lucide="trending-up"></i>
            Catat Pemasukan
        </div>

        <div class="nav-item" onclick="App.bukaModal('modal-tambah'); App.setJenis('pengeluaran');" style="cursor:pointer;">
            <i data-lucide="trending-down"></i>
            Catat Pengeluaran
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="tanggal-sidebar">
            <?= date('l, d F Y') ?>
        </div>
    </div>
</aside>
