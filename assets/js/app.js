// ============================================
//  assets/js/app.js
//  Finansial Harian – JavaScript Utama
// ============================================

const App = {

    // === Modal ===
    bukaModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('aktif');
    },

    tutupModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('aktif');
    },

    tutupSemua() {
        document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('aktif'));
    },

    // === Jenis Toggle (Pemasukan / Pengeluaran) ===
    setJenis(jenis) {
        const input = document.getElementById('jenis_input');
        const btnMasuk  = document.getElementById('btn_masuk');
        const btnKeluar = document.getElementById('btn_keluar');
        const katSelect = document.getElementById('kategori_id');

        if (!input) return;
        input.value = jenis;

        btnMasuk.classList.toggle('aktif',  jenis === 'pemasukan');
        btnMasuk.classList.toggle('masuk',  jenis === 'pemasukan');
        btnKeluar.classList.toggle('aktif', jenis === 'pengeluaran');
        btnKeluar.classList.toggle('keluar',jenis === 'pengeluaran');

        // Filter opsi kategori sesuai jenis
        if (katSelect) {
            Array.from(katSelect.options).forEach(opt => {
                if (opt.value === '') return;
                opt.hidden = (opt.dataset.jenis !== jenis);
            });
            katSelect.value = '';
        }
    },

    // === Konfirmasi Hapus ===
    konfirmasiHapus(id, judul) {
        document.getElementById('hapus_id').value    = id;
        document.getElementById('hapus_judul').textContent = judul;
        App.bukaModal('modal-hapus');
    },

    // === Edit Transaksi ===
    editTransaksi(data) {
        document.getElementById('edit_id').value          = data.id;
        document.getElementById('edit_judul').value       = data.judul;
        document.getElementById('edit_jumlah').value      = data.jumlah;
        document.getElementById('edit_catatan').value     = data.catatan || '';
        document.getElementById('edit_tanggal').value     = data.tanggal;
        document.getElementById('edit_jenis_input').value = data.jenis;
        document.getElementById('edit_kategori').value    = data.kategori_id;

        const btnMasuk  = document.getElementById('edit_btn_masuk');
        const btnKeluar = document.getElementById('edit_btn_keluar');
        const aktifMasuk = data.jenis === 'pemasukan';
        btnMasuk.classList.toggle('aktif', aktifMasuk);
        btnMasuk.classList.toggle('masuk', aktifMasuk);
        btnKeluar.classList.toggle('aktif', !aktifMasuk);
        btnKeluar.classList.toggle('keluar', !aktifMasuk);

        // Filter kategori
        const katSelect = document.getElementById('edit_kategori');
        Array.from(katSelect.options).forEach(opt => {
            if (opt.value === '') return;
            opt.hidden = (opt.dataset.jenis !== data.jenis);
        });

        App.bukaModal('modal-edit');
    },

    setJenisEdit(jenis) {
        const input = document.getElementById('edit_jenis_input');
        const btnMasuk  = document.getElementById('edit_btn_masuk');
        const btnKeluar = document.getElementById('edit_btn_keluar');
        const katSelect = document.getElementById('edit_kategori');

        input.value = jenis;
        btnMasuk.classList.toggle('aktif',  jenis === 'pemasukan');
        btnMasuk.classList.toggle('masuk',  jenis === 'pemasukan');
        btnKeluar.classList.toggle('aktif', jenis === 'pengeluaran');
        btnKeluar.classList.toggle('keluar',jenis === 'pengeluaran');

        Array.from(katSelect.options).forEach(opt => {
            if (opt.value === '') return;
            opt.hidden = (opt.dataset.jenis !== jenis);
        });
        katSelect.value = '';
    },

    // === Format Angka Input ===
    formatRupiah(input) {
        let val = input.value.replace(/\D/g, '');
        input.value = val;
    },

    // === Auto-hide flash ===
    initFlash() {
        const flash = document.querySelector('.flash');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity .5s';
                flash.style.opacity    = '0';
                setTimeout(() => flash.remove(), 500);
            }, 3500);
        }
    },

    // === Chart Donut SVG ===
    renderDonut(canvasId, data) {
        const svg = document.getElementById(canvasId);
        if (!svg) return;

        const total = data.reduce((s, d) => s + d.nilai, 0);
        if (total === 0) return;

        const cx = 70, cy = 70, r = 55, stroke = 22;
        const circ = 2 * Math.PI * r;
        let offset = 0;

        data.forEach(item => {
            const pct  = item.nilai / total;
            const dash = pct * circ;
            const gap  = circ - dash;

            const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            circle.setAttribute('cx', cx);
            circle.setAttribute('cy', cy);
            circle.setAttribute('r', r);
            circle.setAttribute('fill', 'none');
            circle.setAttribute('stroke', item.warna);
            circle.setAttribute('stroke-width', stroke);
            circle.setAttribute('stroke-dasharray', `${dash} ${gap}`);
            circle.setAttribute('stroke-dashoffset', -offset);
            svg.appendChild(circle);

            offset += dash;
        });
    },

    // === Inisialisasi ===
    init() {
        this.initFlash();

        // Tutup modal saat klik overlay
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if (e.target === overlay) App.tutupSemua();
            });
        });

        // Set tanggal default hari ini
        const tglInputs = document.querySelectorAll('input[type="date"]');
        const today = new Date().toISOString().split('T')[0];
        tglInputs.forEach(inp => { if (!inp.value) inp.value = today; });

        // Init jenis toggle default
        const jInput = document.getElementById('jenis_input');
        if (jInput && jInput.value) App.setJenis(jInput.value);
    }
};

document.addEventListener('DOMContentLoaded', () => App.init());
