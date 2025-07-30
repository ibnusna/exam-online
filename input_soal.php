<?php
// Selalu mulai sesi di bagian paling atas
session_start();

// Panggil file parser agar kita bisa menguji format soal nanti (opsional)
require_once 'includes/parser.php';

// Cek apakah data dari index.php baru saja dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_lengkap'])) {
    // Simpan informasi pengguna ke sesi agar tidak hilang
    $_SESSION['user_info'] = [
        'nim'          => htmlspecialchars($_POST['nim']),
        'nama_lengkap' => htmlspecialchars($_POST['nama_lengkap']),
        'kelas'        => htmlspecialchars($_POST['kelas']),
        'mata_kuliah'  => htmlspecialchars($_POST['mata_kuliah']),
        'durasi'       => (int)$_POST['durasi']
    ];
}

// Jika tidak ada data pengguna di sesi (misal, akses langsung ke halaman ini),
// kembalikan ke halaman awal.
if (!isset($_SESSION['user_info'])) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Soal Ujian</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/input_soal.css">
</head>
<body>
    <div class="container">
        <h1>Langkah 2: Masukkan Soal Ujian</h1>
        <p>Salin dan tempel (copy-paste) semua soal Anda ke dalam kotak di bawah ini. Pastikan formatnya sesuai dengan yang telah ditentukan.</p>

        <div class="format-info">
            <strong>Contoh Format yang Benar:</strong><br>
            <pre>1. Pertanyaan soal nomor satu...
A. Pilihan A
B. Pilihan B
C. Pilihan C
Kunci Jawaban: A

2. Pertanyaan soal nomor dua...
A. Pilihan A
B. Pilihan B
C. Pilihan C
D. Pilihan D
Kunci Jawaban: D</pre>
        </div>

        <form action="ujian.php" method="POST">
            <label for="soal_text">Kotak Input Soal:</label>
            <textarea id="soal_text" name="soal_text" placeholder="Tempelkan semua soal Anda di sini..." required></textarea>
            <button type="submit">Proses Soal & Lanjutkan ke Ujian</button>
<div style="display: flex; justify-content: center; margin-top: 20px;">
    <button id="panduan-ujian-button" type="button" class="pulse-btn">Tata Cara Ujian</button>
</div>
<script>
    // Fungsi untuk membuka panduan ujian
    function bukaPanduanUjian() {
        const panduanURL = 'assets/css/assets/PANDUAN_UJIAN_ONLINE_MHS_UBSI.pdf';
        window.open(panduanURL, '_blank');
    }
    document.getElementById('panduan-ujian-button').addEventListener('click', bukaPanduanUjian);
</script>
<style>
.pulse-btn {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 14px 32px;
    border-radius: 30px;
    font-size: 1.1rem;
    cursor: pointer;
    outline: none;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    position: relative;
    transition: background 0.2s;
    animation: pulse 1.2s infinite;
}
.pulse-btn:hover {
    background: #0056b3;
}
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0,123,255,0.7);
    }
    70% {
        box-shadow: 0 0 0 16px rgba(0,123,255,0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0,123,255,0);
    }
}
</style>
        </form>
    </div>
<script>
// Fungsi untuk mengecek tipe perangkat dan resolusi layar
function checkDeviceAndResolution() {
    // Pola regex untuk mendeteksi user agent mobile
    const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Jika terdeteksi sebagai perangkat mobile
    if (isMobileDevice) {
        // Tampilkan pop-up error menggunakan SweetAlert2
        Swal.fire({
            title: 'Perangkat Tidak Diizinkan!',
            html: `Ujian ini dirancang untuk perangkat desktop. <br>Anda tidak dapat melanjutkan menggunakan perangkat ini.`,
            icon: 'error',
            allowOutsideClick: false, // Mencegah pengguna menutup pop-up dengan klik di luar
            allowEscapeKey: false, // Mencegah pengguna menutup dengan tombol Esc
            confirmButtonText: 'Kembali ke Halaman Awal'
        }).then(() => {
            // Arahkan ke halaman index setelah pop-up ditutup
            window.location.href = 'index.php?error=device_not_supported';
        });
        return false; // Hentikan eksekusi lebih lanjut
    }
    return true; // Perangkat diizinkan
}

// Jalankan pengecekan saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    checkDeviceAndResolution();
});
</script>
    <!-- JS Lokal -->
<script src="assets/js/storage_handler.js"></script>



</body>
</html>
