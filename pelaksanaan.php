<script>
// --- INISIALISASI VARIABEL GLOBAL ---
const durasiUjianMenit = <?php echo $durasiMenit; ?>;
const waktuMulaiUjian = <?php echo $_SESSION['waktu_mulai']; ?>;
const totalSoal = <?php echo count($soalUjian); ?>;

let soalSekarang = 0;
let fullscreenExitCount = 0;
let cheatingAttemptCount = 0;
const MAX_CHEAT_ATTEMPTS = 3;
let timerInterval;

const formUjian = document.getElementById('form-ujian');
const startOverlay = document.getElementById('start-exam-overlay');
const startButton = document.getElementById('start-exam-button');

function disableDevTools() {
    // Deteksi pembukaan Developer Tools
    let devtools = {
        open: false,
        orientation: null
    };
    
    const threshold = 160;
    setInterval(() => {
        if (window.outerHeight - window.innerHeight > threshold || 
            window.outerWidth - window.innerWidth > threshold) {
            if (!devtools.open) {
                devtools.open = true;
                handleCheatingAttempt('Membuka Developer Tools');
            }
        } else {
            devtools.open = false;
        }
    }, 500);


    document.addEventListener('keydown', function(e) {
        // F12
        if (e.keyCode === 123) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+I
        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+J
        if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+U (View Source)
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+C
        if (e.ctrlKey && e.shiftKey && e.keyCode === 67) {
            e.preventDefault();
            return false;
        }
    });
}

// Fungsi pengecekan resolusi dan perangkat
function checkDeviceAndResolution() {
    const screenWidth = screen.width;
    const screenHeight = screen.height;
    const resolution = `${screenWidth}x${screenHeight}`;
    
    // Deteksi perangkat mobile/tablet berdasarkan resolusi dan user agent
    const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const isTabletResolution = (screenWidth < 1024 && screenHeight < 768) || 
                              (screenWidth < 768 && screenHeight < 1024);
    
    if (isMobileDevice || isTabletResolution) {
        Swal.fire({
            title: 'Perangkat Tidak Diizinkan!',
            html: `Ujian Tidak Boleh Menggunakan Perangkat Handphone!<br>Resolusi layar anda: <strong>${resolution}</strong>`,
            icon: 'error',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.php?error=device_not_supported';
        });
        return false;
    }
    return true;
}

function mulaiUjian() {
    if (!checkDeviceAndResolution()) {
        return; // Hentikan jika perangkat tidak sesuai
    }
    
    enterFullscreen();
    startOverlay.style.display = 'none';
    formUjian.style.visibility = 'visible';
    
    const waktuSelesai = (waktuMulaiUjian + (durasiUjianMenit * 60)) * 1000;
    timerInterval = setInterval(() => {
        const sisaWaktu = waktuSelesai - new Date().getTime();
        if (sisaWaktu <= 0) {
            clearInterval(timerInterval);
            document.getElementById('timer-display').textContent = "Waktu Habis!";
            Swal.fire({
                title: 'Waktu Habis!', text: 'Jawaban Anda dikirim otomatis.',
                icon: 'warning', allowOutsideClick: false, allowEscapeKey: false, showConfirmButton: false, timer: 3000
            }).then(() => formUjian.submit());
            return;
        }
        const jam = Math.floor((sisaWaktu % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const menit = Math.floor((sisaWaktu % (1000 * 60 * 60)) / (1000 * 60));
        const detik = Math.floor((sisaWaktu % (1000 * 60)) / 1000);
        document.getElementById('timer-display').textContent = `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
    }, 1000);

    tampilkanSoal(0);
}

// === EVENT LISTENERS TAMBAHAN ===

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Aktifkan pencegahan Developer Tools
    disableDevTools();
    
    // Cek perangkat saat halaman dimuat
    checkDeviceAndResolution();
    
    // Event listener untuk tombol panduan ujian
    const panduanButton = document.getElementById('panduan-ujian-button');
    if (panduanButton) {
        panduanButton.addEventListener('click', bukaPanduanUjian);
    }
    
    // Jalankan fungsi yang sudah ada
    perbaruiTampilanSemuaJawaban();
});

// Pencegahan klik kanan tanpa peringatan kecurangan
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Override console methods untuk mencegah console debugging
if (typeof console !== 'undefined') {
    console.log = console.warn = console.error = console.info = console.debug = function() {};
}

// --- FUNGSI UTAMA ---

function perbaruiTampilanSemuaJawaban() {
    document.querySelectorAll('.question-block').forEach((soalBlock, soalIndex) => {
        const radioTerpilih = soalBlock.querySelector('input[type="radio"]:checked');
        soalBlock.querySelectorAll('.option-item').forEach(opt => opt.classList.remove('selected'));
        if (radioTerpilih) {
            const pilihanAbjad = radioTerpilih.value;
            const optionItem = soalBlock.querySelector(`.option-item[data-pilihan="${pilihanAbjad}"]`);
            if (optionItem) optionItem.classList.add('selected');
            const navButton = document.getElementById(`nav-${soalIndex}`);
            if (navButton) {
                navButton.classList.remove('btn-question--unanswered');
                navButton.classList.add('btn-question--answered');
            }
        }
    });
}

function tampilkanSoal(index) {
    document.querySelectorAll('.question-block').forEach(block => block.style.display = 'none');
    document.getElementById(`soal-${index}`).style.display = 'block';
    document.querySelectorAll('.btn-question').forEach(btn => btn.classList.remove('btn-question--current'));
    const navButton = document.getElementById(`nav-${index}`);
    if (!navButton.classList.contains('btn-question--answered')) {
        navButton.classList.add('btn-question--current');
    }
    soalSekarang = index;
}

function checkAllAnswered() {
    const answeredCount = document.querySelectorAll('.btn-question--answered').length;
    if (answeredCount === totalSoal) {
        document.querySelectorAll('.btn-finish-exam').forEach(btn => btn.style.display = 'block');
        Swal.fire({
            title: 'Selesai!', text: 'Anda telah menjawab semua soal. Silakan klik "Selesaikan Ujian".',
            icon: 'success', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000
        });
    }
}

// --- FUNGSI INTI (MULAI UJIAN, TIMER, KEAMANAN) ---

function mulaiUjian() {
    enterFullscreen();
    startOverlay.style.display = 'none';
    formUjian.style.visibility = 'visible';
    
    const waktuSelesai = (waktuMulaiUjian + (durasiUjianMenit * 60)) * 1000;
    timerInterval = setInterval(() => {
        const sisaWaktu = waktuSelesai - new Date().getTime();
        if (sisaWaktu <= 0) {
            clearInterval(timerInterval);
            document.getElementById('timer-display').textContent = "Waktu Habis!";
            Swal.fire({
                title: 'Waktu Habis!', text: 'Jawaban Anda dikirim otomatis.',
                icon: 'warning', allowOutsideClick: false, allowEscapeKey: false, showConfirmButton: false, timer: 3000
            }).then(() => formUjian.submit());
            return;
        }
        const jam = Math.floor((sisaWaktu % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const menit = Math.floor((sisaWaktu % (1000 * 60 * 60)) / (1000 * 60));
        const detik = Math.floor((sisaWaktu % (1000 * 60)) / 1000);
        document.getElementById('timer-display').textContent = `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
    }, 1000);

    tampilkanSoal(0);
}

function enterFullscreen() {
    const elem = document.documentElement;
    if (elem.requestFullscreen) {
        elem.requestFullscreen().catch(err => console.warn("Gagal masuk mode layar penuh:", err.message));
    }
}

function handleCheatingAttempt(reason) {
    cheatingAttemptCount++;
    if (cheatingAttemptCount >= MAX_CHEAT_ATTEMPTS) {
        window.location.href = `index.php?error=cheating_detected&reason=${encodeURIComponent(reason)}`;
    } else {
        Swal.fire({
            title: 'Peringatan Kecurangan!',
            html: `Tindakan terlarang terdeteksi: <strong>${reason}</strong>.<br>Sisa kesempatan: <strong>${MAX_CHEAT_ATTEMPTS - cheatingAttemptCount}</strong>.`,
            icon: 'error',
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    }
}

// --- EVENT LISTENERS ---

startButton.addEventListener('click', mulaiUjian);

// --- EVENT LISTENERS ---

startButton.addEventListener('click', mulaiUjian);

document.querySelectorAll('.option-item').forEach(option => {
    option.addEventListener('click', function() {
        if (this.classList.contains('loading')) return;
        const nomorSoalIndex = this.dataset.nomorSoal;
        const pilihan = this.dataset.pilihan;

        // === BAGIAN YANG DIPERBAIKI ===
        // Menggunakan id_unik untuk memastikan tidak ada konflik, bukan nomor soal asli.
        const idUnikSoal = <?php echo json_encode(array_column($soalUjian, 'id_unik')); ?>[nomorSoalIndex];
        document.getElementById(`opsi-${idUnikSoal}-${pilihan}`).checked = true;
        // === AKHIR BAGIAN YANG DIPERBAIKI ===
        
        document.querySelectorAll(`.option-item[data-nomor-soal="${nomorSoalIndex}"]`).forEach(opt => opt.classList.remove('loading'));
        perbaruiTampilanSemuaJawaban();
        this.classList.add('loading');
        setTimeout(() => this.classList.remove('loading'), 1500);
        checkAllAnswered();
    });
});

// ... sisa kode JavaScript Anda ...

document.addEventListener('keydown', (e) => {
    if (e.key === 'Tab' || e.altKey) {
        e.preventDefault();
        handleCheatingAttempt(e.key === 'Tab' ? 'Menekan tombol Tab' : 'Menekan tombol Alt');
    }
    if (e.metaKey) {
        handleCheatingAttempt('Menekan tombol Windows');
    }
});

window.addEventListener('blur', () => {
    if (!Swal.isVisible()) {
        handleCheatingAttempt('Meninggalkan jendela ujian');
    }
});

document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement) {
        fullscreenExitCount++;
        if (fullscreenExitCount >= 3) {
            window.location.href = 'index.php?error=fullscreen_exit';
        } else {
            Swal.fire({
                title: 'Peringatan!', text: `Anda keluar dari mode layar penuh. Kesempatan tersisa: ${3 - fullscreenExitCount}.`,
                icon: 'warning', confirmButtonText: 'Kembali ke Layar Penuh'
            }).then(() => enterFullscreen());
        }
    }
});

document.addEventListener('keyup', (e) => {
    if (e.key === 'PrintScreen') {
        navigator.clipboard.writeText('');
        handleCheatingAttempt('Screenshot');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    perbaruiTampilanSemuaJawaban();
});

</script>