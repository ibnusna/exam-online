<?php
// Mulai atau lanjutkan sesi yang ada.
session_start();
session_unset();
session_destroy();

// Mulai lagi sesi baru setelah dihancurkan
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">

<!-- CSS dari CDN (contoh: Bootstrap) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Dashboard Ujian</title>
</head>
<body>

<header class="text-center">
  <img src="assets/img/image.png" alt="Header Ujian" class="img-fluid mb-4">
  <h1>Selamat Datang di Portal Ujian</h1>
  <p>Silakan isi data diri Anda dan pilih pengaturan ujian di bawah ini.</p>
  <hr>
</header>

    <!-- 
        PERUBAHAN: Action form sekarang mengarah ke 'input_soal.php'
    -->
<form action="input_soal.php" method="POST">
    <div class="form-container">
        <div class="form-group">
                <div>
        <label for="nim">NIM:</label>
        <input type="text" id="nim" name="nim" placeholder="Masukkan NIM Anda" required>
    </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <input type="text" id="kelas" name="kelas" placeholder="Contoh: TI-4A" required>
            </div>

            <div class="form-group">
                <label for="mata_kuliah">Mata Kuliah:</label>
                <input type="text" id="mata_kuliah" name="mata_kuliah" placeholder="Contoh: Pemrograman Web Lanjut" required>
            </div>

            <div class="form-group">
                <label for="durasi">Pilih Durasi Pengerjaan:</label>
                <select id="durasi" name="durasi" required>
                    <option value="">-- Pilih Waktu --</option>
                    <option value="15">15 Menit</option>
                    <option value="30">30 Menit</option>
                    <option value="45">45 Menit</option>
                    <option value="60">60 Menit</option>
                </select>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Lanjut ke Input Soal</button>
            
        </div>
    </div>
</form>
    </form>
<script src="assets/js/storage_handler.js"></script>
</body>
</html>
