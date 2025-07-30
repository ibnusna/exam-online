<?php

date_default_timezone_set('Asia/Jakarta');
// Selalu mullatihanai sesi di bagian paling atas
session_start();

// Validasi Sesi dan Data
if (!isset($_SESSION['soal_ujian']) || !isset($_SESSION['user_info'])) {
    header('Location: index.php');
    exit;
}

// Ambil jawaban dari POST saat pertama kali halaman diakses dari ujian.php
// Jika sesi jawaban sudah ada (misal, kembali dari analisa.php), gunakan sesi tersebut.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jawaban'])) {
    $_SESSION['jawaban_user'] = $_POST['jawaban'];
}

// Jika tidak ada data jawaban sama sekali, kembali ke index.
if (!isset($_SESSION['jawaban_user'])) {
    header('Location: index.php');
    exit;
}

// Ambil data dari Sesi
$userInfo = $_SESSION['user_info'];
$soalUjian = $_SESSION['soal_ujian'];
$jawabanUser = $_SESSION['jawaban_user'];

$jumlahBenar = 0;
$totalSoal = count($soalUjian);

// **PERBAIKAN**: Gunakan 'id_unik' sebagai kunci untuk mapping data.
$kunciJawabanBenar = array_column($soalUjian, 'kunci', 'id_unik');
$soalMapping = array_column($soalUjian, null, 'id_unik');

// Inisialisasi untuk analisis detail
$listJawabanBenar = [];
$listJawabanSalah = [];

// Proses setiap jawaban menggunakan ID unik
foreach ($jawabanUser as $idUnikSoal => $jawaban) {
    // Pastikan ID unik dari jawaban pengguna ada di dalam mapping soal kita
    if (isset($soalMapping[$idUnikSoal])) {
        $soalDetail = $soalMapping[$idUnikSoal];
        
        if (isset($kunciJawabanBenar[$idUnikSoal]) && $jawaban === $kunciJawabanBenar[$idUnikSoal]) {
            $jumlahBenar++;
            $listJawabanBenar[] = $soalDetail;
        } else {
            $listJawabanSalah[] = [
                'soal' => $soalDetail,
                'jawaban_user' => $jawaban
            ];
        }
    }
}
$jumlahSalah = $totalSoal - $jumlahBenar;
// Siapkan data dinamis untuk ditampilkan
$nim = htmlspecialchars($userInfo['nim'] ?? 'TIDAK DIKETAHUI'); // <-- Ubah baris ini
$nama = strtoupper(htmlspecialchars($userInfo['nama_lengkap']));
$kelUjian = htmlspecialchars($userInfo['kelas']);
$mataKuliah = strtoupper(htmlspecialchars($userInfo['mata_kuliah']));
$waktuMulaiTimestamp = $_SESSION['waktu_mulai'];
$durasiDetik = $userInfo['durasi'] * 60;
$waktuSelesaiTimestamp = $waktuMulaiTimestamp + $durasiDetik;
$periodeUjian = date('Y-m-d H:i:s', $waktuMulaiTimestamp) . ' - ' . date('Y-m-d H:i:s', $waktuSelesaiTimestamp);
setlocale(LC_TIME, 'id_ID.UTF-8', 'Indonesian');
$waktuCetak = strftime('%A, %d %B %Y %H:%M:%S', time());

// Hitung persentase dan evaluasi
$persentase = round(($jumlahBenar / $totalSoal) * 100, 1);

// Konversi ke Skor Huruf (Grade) dan Analisis Teks
$skorHuruf = '';
$teksAnalisis = '';
$warnaGrade = 'bg-gray-500';
$statusLulus = false;

if ($persentase >= 85) {
    $skorHuruf = 'A';
    $teksAnalisis = 'Luar Biasa! Pemahaman Anda terhadap materi ini sangat baik. Pertahankan prestasi ini!';
    $warnaGrade = 'bg-green-600';
    $statusLulus = true;
} elseif ($persentase >= 70) {
    $skorHuruf = 'B';
    $teksAnalisis = 'Bagus! Anda sudah memahami sebagian besar materi, namun ada beberapa poin yang perlu diasah lagi.';
    $warnaGrade = 'bg-blue-600';
    $statusLulus = true;
} elseif ($persentase >= 55) {
    $skorHuruf = 'C';
    $teksAnalisis = 'Cukup. Anda perlu mengulang beberapa materi penting untuk meningkatkan pemahaman.';
    $warnaGrade = 'bg-yellow-500';
    $statusLulus = true;
} else {
    $skorHuruf = 'D';
    $teksAnalisis = 'Perlu belajar lebih giat. Banyak konsep dasar yang belum dikuasai. Jangan menyerah!';
    $warnaGrade = 'bg-red-600';
    $statusLulus = false;
}

// Hitung statistik tambahan
$tingkatKesulitan = '';
$rekomendasi = '';

if ($persentase >= 90) {
    $tingkatKesulitan = 'Sangat Mudah';
    $rekomendasi = 'Cobalah soal dengan tingkat kesulitan yang lebih tinggi untuk mengasah kemampuan lebih lanjut.';
} elseif ($persentase >= 75) {
    $tingkatKesulitan = 'Mudah';
    $rekomendasi = 'Pertahankan konsistensi belajar dan coba variasikan metode pembelajaran.';
} elseif ($persentase >= 60) {
    $tingkatKesulitan = 'Sedang';
    $rekomendasi = 'Fokus pada materi yang masih lemah dan perbanyak latihan soal.';
} else {
    $tingkatKesulitan = 'Sulit';
    $rekomendasi = 'Pelajari kembali konsep dasar dan konsultasikan dengan pengajar untuk pemahaman yang lebih baik.';
}

// Analisis pola kesalahan
$polakesalahan = [];
if (!empty($listJawabanSalah)) {
    foreach ($listJawabanSalah as $salah) {
$nomor = $salah['soal']['nomor_tampilan'];
        if ($nomor <= ceil($totalSoal / 3)) {
            $polakesalahan['awal'][] = $nomor;
        } elseif ($nomor <= ceil($totalSoal * 2 / 3)) {
            $polakesalahan['tengah'][] = $nomor;
        } else {
            $polakesalahan['akhir'][] = $nomor;
        }
    }
}

// Hitung waktu pengerjaan
$waktuPengerjaan = isset($_SESSION['waktu_selesai']) ? 
    ($_SESSION['waktu_selesai'] - $waktuMulaiTimestamp) : 
    $durasiDetik;
$waktuPengerjaanMenit = floor($waktuPengerjaan / 60);
$waktuPengerjaanDetik = $waktuPengerjaan % 60;

// Handle mode tampilan (ringkasan atau detail)
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'ringkasan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Hasil Ujian - Sistem Evaluasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <link rel="stylesheet" href="assets/css/hasil.css">

</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <div class="no-print bg-white shadow-sm border-b">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex space-x-4">
                    <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                        <i class="fas fa-home mr-2"></i>Ujian Baru
                    </a>
                    <button onclick="window.print()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak Hasil
                    </button>
                </div>
                <div class="flex space-x-2">
                    <a href="?mode=ringkasan" class="px-4 py-2 rounded-md transition-colors <?php echo $mode === 'ringkasan' ? 'tab-active' : 'tab-inactive'; ?>">
                        Ringkasan
                    </a>
                    <a href="?mode=analisis" class="px-4 py-2 rounded-md transition-colors <?php echo $mode === 'analisis' ? 'tab-active' : 'tab-inactive'; ?>">
                        Analisis Detail
                    </a>
                    <a href="?mode=evaluasi" class="px-4 py-2 rounded-md transition-colors <?php echo $mode === 'evaluasi' ? 'tab-active' : 'tab-inactive'; ?>">
                        Evaluasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Header Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Hasil Ujian Online</h1>
                <p class="text-gray-600">Sistem Ujian Digital</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Peserta</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">NIM:</span>
                            <span class="font-medium"><?php echo $nim; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium"><?php echo $nama; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium"><?php echo $kelUjian; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mata Kuliah:</span>
                            <span class="font-medium"><?php echo $mataKuliah; ?></span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Ujian</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Periode Ujian:</span>
                            <span class="font-medium text-xs"><?php echo $periodeUjian; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Waktu Pengerjaan:</span>
                            <span class="font-medium"><?php echo $waktuPengerjaanMenit; ?> menit <?php echo $waktuPengerjaanDetik; ?> detik</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Soal:</span>
                            <span class="font-medium"><?php echo $totalSoal; ?> soal</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Waktu Cetak:</span>
                            <span class="font-medium text-xs"><?php echo $waktuCetak; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <?php include 'dog.php'; ?>


        <!-- Mode Ringkasan -->
        <?php if ($mode === 'ringkasan'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Skor Utama -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Hasil Skor</h2>
                <div class="text-center">
                    <div class="text-6xl font-bold text-blue-600 mb-2"><?php echo $persentase; ?><span class="text-2xl text-gray-500">%</span></div>
                    <div class="text-2xl font-bold <?php echo $warnaGrade; ?> text-white rounded-md inline-block px-4 py-2 mb-4"><?php echo $skorHuruf; ?></div>
                    <div class="text-lg <?php echo $statusLulus ? 'text-green-600' : 'text-red-600'; ?> font-semibold">
                        <?php echo $statusLulus ? 'LULUS' : 'TIDAK LULUS'; ?>
                    </div>
                </div>
            </div>

            <!-- Statistik Detail -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistik</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Jawaban Benar:</span>
                        <span class="font-semibold text-green-600"><?php echo $jumlahBenar; ?> soal</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Jawaban Salah:</span>
                        <span class="font-semibold text-red-600"><?php echo $jumlahSalah; ?> soal</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tingkat Kesulitan:</span>
                        <span class="font-semibold text-blue-600"><?php echo $tingkatKesulitan; ?></span>
                    </div>
                    <div class="pt-4 border-t">
                        <canvas id="scoreChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analisis Singkat -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Analisis Performa</h2>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <p class="text-blue-800"><?php echo $teksAnalisis; ?></p>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($mode === 'analisis'): ?>
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-green-700 mb-4">
                    <i class="fas fa-check-circle mr-2"></i>Jawaban Benar (<?php echo $jumlahBenar; ?> Soal)
                </h2>
                <div class="space-y-3">
                    <?php if (empty($listJawabanBenar)): ?>
                        <p class="text-gray-500">Tidak ada jawaban yang benar.</p>
                    <?php else: ?>
<?php foreach ($listJawabanBenar as $item): ?>
    <?php
        $kunciJawabanTeks = isset($item['pilihan'][$item['kunci']])
            ? $item['kunci'] . '. ' . htmlspecialchars($item['pilihan'][$item['kunci']])
            : htmlspecialchars($item['kunci']); // Fallback jika teks tidak ditemukan
    ?>
    <div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
        <p class="text-sm text-gray-700"><?php echo $item['nomor_tampilan'] . '. ' . htmlspecialchars($item['soal']); ?></p>
        <p class="text-xs text-green-600 mt-1"><strong>Jawaban Benar:</strong> <?php echo $kunciJawabanTeks; ?></p>
    </div>
<?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Jawaban Salah -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-red-700 mb-4">
                    <i class="fas fa-times-circle mr-2"></i>Perlu Diperbaiki (<?php echo count($listJawabanSalah); ?> Soal)
                </h2>
                <div class="space-y-4">
                    <?php if (empty($listJawabanSalah)): ?>
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                            <p class="text-green-800 font-semibold">Sempurna! Tidak ada jawaban yang salah.</p>
                        </div>
                    <?php else: ?>
<?php foreach ($listJawabanSalah as $item): ?>
    <?php
        // Mengambil teks lengkap dari jawaban pengguna.
        // Jika tidak dijawab, tampilkan 'Tidak Dijawab'.
        $jawabanUserTeks = isset($item['soal']['pilihan'][$item['jawaban_user']]) 
            ? $item['jawaban_user'] . '. ' . htmlspecialchars($item['soal']['pilihan'][$item['jawaban_user']]) 
            : 'Tidak Dijawab';

        // Mengambil teks lengkap dari jawaban yang benar (kunci).
        $kunciJawabanTeks = $item['soal']['kunci'] . '. ' . htmlspecialchars($item['soal']['pilihan'][$item['soal']['kunci']]);
    ?>
    <div class="bg-red-50 p-4 rounded border-l-4 border-red-400">
        <p class="font-semibold text-gray-800 mb-2"><?php echo $item['soal']['nomor_tampilan'] . '. ' . htmlspecialchars($item['soal']['soal']); ?></p>
        <div class="ml-4 space-y-1">
            <p class="text-red-600 text-sm"><span class="font-medium">Jawaban Anda:</span> <?php echo $jawabanUserTeks; ?></p>
            <p class="text-green-600 text-sm"><span class="font-medium">Jawaban Benar:</span> <?php echo $kunciJawabanTeks; ?></p>
        </div>
    </div>
<?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Mode Evaluasi -->
        <?php if ($mode === 'evaluasi'): ?>
        <div class="space-y-6">
            <!-- Evaluasi Komprehensif -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Evaluasi Komprehensif</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Analisis Performa</h3>
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-3 rounded">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Akurasi:</span>
                                    <span class="font-semibold"><?php echo $persentase; ?>%</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Grade:</span>
                                    <span class="font-semibold"><?php echo $skorHuruf; ?></span>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold <?php echo $statusLulus ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo $statusLulus ? 'LULUS' : 'TIDAK LULUS'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Pola Kesalahan</h3>
                        <div class="space-y-2 text-sm">
                            <?php if (!empty($polakesalahan['awal'])): ?>
                                <div class="bg-red-50 p-2 rounded">
                                    <span class="text-red-700 font-medium">Bagian Awal:</span> 
                                    <?php echo count($polakesalahan['awal']); ?> kesalahan
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($polakesalahan['tengah'])): ?>
                                <div class="bg-yellow-50 p-2 rounded">
                                    <span class="text-yellow-700 font-medium">Bagian Tengah:</span> 
                                    <?php echo count($polakesalahan['tengah']); ?> kesalahan
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($polakesalahan['akhir'])): ?>
                                <div class="bg-orange-50 p-2 rounded">
                                    <span class="text-orange-700 font-medium">Bagian Akhir:</span> 
                                    <?php echo count($polakesalahan['akhir']); ?> kesalahan
                                </div>
                            <?php endif; ?>
                            <?php if (empty($polakesalahan)): ?>
                                <div class="bg-green-50 p-2 rounded">
                                    <span class="text-green-700 font-medium">Tidak ada pola kesalahan yang terdeteksi</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Rekomendasi Pembelajaran</h2>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mb-4">
                    <p class="text-blue-800"><?php echo $rekomendasi; ?></p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <h4 class="font-semibold text-gray-700 mb-2">Kelebihan</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <?php if ($jumlahBenar > 0): ?>
                                <li>• Berhasil menjawab <?php echo $jumlahBenar; ?> soal dengan benar</li>
                            <?php endif; ?>
                            <?php if ($persentase >= 70): ?>
                                <li>• Menunjukkan pemahaman yang baik terhadap materi</li>
                            <?php endif; ?>
                            <?php if ($waktuPengerjaanMenit < $userInfo['durasi']): ?>
                                <li>• Efisien dalam penggunaan waktu</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded">
                        <h4 class="font-semibold text-gray-700 mb-2">Area Perbaikan</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <?php if ($jumlahSalah > 0): ?>
                                <li>• Perlu memperdalam <?php echo $jumlahSalah; ?> konsep yang masih lemah</li>
                            <?php endif; ?>
                            <?php if ($persentase < 70): ?>
                                <li>• Perlukan latihan soal tambahan</li>
                            <?php endif; ?>
                            <?php if (!empty($polakesalahan)): ?>
                                <li>• Perhatikan pola kesalahan yang teridentifikasi</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Plan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Rencana Tindak Lanjut</h2>
                <div class="space-y-3">
                    <?php if (!$statusLulus): ?>
                        <div class="bg-red-50 border border-red-200 p-4 rounded">
                            <h4 class="font-semibold text-red-800 mb-2">PRIORITAS TINGGI</h4>
                            <ul class="text-sm text-red-700 space-y-1">
                                <li>• Ulangi ujian setelah mempelajari materi yang belum dikuasai</li>
                                <li>• Konsultasi dengan pengajar untuk bimbingan tambahan</li>
                                <li>• Perbanyak latihan soal sejenis</li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded">
                        <h4 class="font-semibold text-blue-800 mb-2">LANGKAH SELANJUTNYA</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Review kembali soal-soal yang dijawab salah</li>
                            <li>• Diskusikan hasil dengan teman atau kelompok belajar</li>
                            <li>• Buat catatan dari materi yang masih lemah</li>
                            <?php if ($statusLulus): ?>
                                <li>• Pertahankan metode belajar yang sudah baik</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>


      <?php include 'analisa.php'; ?>

</body>
</html>