<?php
// pages/partials/modals.php
// Pastikan $semuaKategori & $kategoriModel sudah tersedia di halaman pemanggil
if (!isset($semuaKategori)) {
    require_once __DIR__ . '/../../classes/Kategori.php';
    $semuaKategori = (new Kategori())->getAll();
}

// Tentukan action URL berdasarkan kedalaman halaman
$aksiUrl = (basename(dirname($_SERVER['PHP_SELF'])) === 'pages')
    ? 'aksi.php'
    : 'pages/aksi.php';
?>

<!-- ===== MODAL TAMBAH ===== -->
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <span>✨ Tambah Transaksi</span>
            <button class="modal-close" onclick="App.tutupModal('modal-tambah')">✕</button>
        </div>
        <form method="POST" action="<?= $aksiUrl ?>">
            <input type="hidden" name="aksi"  value="tambah">
            <input type="hidden" name="jenis" id="jenis_input" value="pengeluaran">

            <div class="modal-body">

                <!-- Jenis Toggle -->
                <div class="form-group">
                    <label class="form-label">Jenis Transaksi</label>
                    <div class="jenis-toggle">
                        <button type="button" id="btn_masuk"
                            class="jenis-btn"
                            onclick="App.setJenis('pemasukan')">
                            ↑ Pemasukan
                        </button>
                        <button type="button" id="btn_keluar"
                            class="jenis-btn aktif keluar"
                            onclick="App.setJenis('pengeluaran')">
                            ↓ Pengeluaran
                        </button>
                    </div>
                </div>

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label" for="judul">Keterangan</label>
                    <input type="text" id="judul" name="judul"
                        class="form-control"
                        placeholder="Mis: Makan siang, Gaji, dll."
                        required>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label class="form-label" for="jumlah">Jumlah (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah"
                        class="form-control"
                        placeholder="0"
                        min="1" step="any" required>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label" for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="form-control" required>
                        <option value="">— Pilih Kategori —</option>
                        <?php foreach ($semuaKategori as $k): ?>
                        <option value="<?= $k['id'] ?>"
                            data-jenis="<?= $k['jenis'] ?>"
                            hidden>
                            <?= Helper::bersihkan($k['nama']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="form-group">
                    <label class="form-label" for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>" required>
                </div>

                <!-- Catatan -->
                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan (opsional)</label>
                    <textarea id="catatan" name="catatan"
                        class="form-control"
                        rows="2"
                        placeholder="Detail tambahan..."></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="App.tutupModal('modal-tambah')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT ===== -->
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <span>✏️ Edit Transaksi</span>
            <button class="modal-close" onclick="App.tutupModal('modal-edit')">✕</button>
        </div>
        <form method="POST" action="<?= $aksiUrl ?>">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id"   id="edit_id">
            <input type="hidden" name="jenis" id="edit_jenis_input" value="pengeluaran">

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label">Jenis Transaksi</label>
                    <div class="jenis-toggle">
                        <button type="button" id="edit_btn_masuk"
                            class="jenis-btn"
                            onclick="App.setJenisEdit('pemasukan')">
                            ↑ Pemasukan
                        </button>
                        <button type="button" id="edit_btn_keluar"
                            class="jenis-btn"
                            onclick="App.setJenisEdit('pengeluaran')">
                            ↓ Pengeluaran
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <input type="text" id="edit_judul" name="judul"
                        class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" id="edit_jumlah" name="jumlah"
                        class="form-control" min="1" step="any" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select id="edit_kategori" name="kategori_id" class="form-control" required>
                        <option value="">— Pilih Kategori —</option>
                        <?php foreach ($semuaKategori as $k): ?>
                        <option value="<?= $k['id'] ?>"
                            data-jenis="<?= $k['jenis'] ?>">
                            <?= Helper::bersihkan($k['nama']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" id="edit_tanggal" name="tanggal"
                        class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea id="edit_catatan" name="catatan"
                        class="form-control" rows="2"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="App.tutupModal('modal-edit')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL HAPUS ===== -->
<div class="modal-overlay" id="modal-hapus">
    <div class="modal" style="max-width:380px;">
        <div class="modal-header">
            <span>🗑️ Konfirmasi Hapus</span>
            <button class="modal-close" onclick="App.tutupModal('modal-hapus')">✕</button>
        </div>
        <form method="POST" action="<?= $aksiUrl ?>">
            <input type="hidden" name="aksi" value="hapus">
            <input type="hidden" name="id"   id="hapus_id">

            <div class="modal-body">
                <p style="font-size:.9rem;color:var(--abu-700);line-height:1.6;">
                    Apakah kamu yakin ingin menghapus transaksi
                    <strong id="hapus_judul"></strong>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="App.tutupModal('modal-hapus')">Batal</button>
                <button type="submit" class="btn" style="background:var(--merah);color:#fff;">🗑️ Hapus</button>
            </div>
        </form>
    </div>
</div>
