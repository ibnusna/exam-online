<?php
// Selalu mulai sesi di bagian paling atas
session_start();

// --- FITUR KEAMANAN (PHP) ---
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|rim)|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($userAgent, 0, 4));

if ($isMobile) {
    header('Location: index.php?error=device_not_supported');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['soal_text'])) {
    require_once 'includes/parser.php';
    $soalMentah = $_POST['soal_text'];
    $semua_soal = parseSoalFromText($soalMentah);
    
    if (!empty($semua_soal)) {
        shuffle($semua_soal);
        $_SESSION['soal_ujian'] = $semua_soal;
        $_SESSION['waktu_mulai'] = time();
    }
}

if (!isset($_SESSION['soal_ujian']) || !isset($_SESSION['user_info'])) {
    header('Location: index.php?error=session_invalid');
    exit;
}

$userInfo = $_SESSION['user_info'];
$soalUjian = $_SESSION['soal_ujian'];
$durasiMenit = $userInfo['durasi'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian: <?php echo htmlspecialchars($userInfo['mata_kuliah']); ?> - <?php echo htmlspecialchars($userInfo['nama_lengkap']); ?></title>
    <link rel="stylesheet" href="assets/css/ujian.css">
        <link rel="stylesheet" href="assets/css/ancime.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<!-- oncontextmenu="return false;" ditambahkan/dipastikan ada untuk memblokir klik kanan -->
<body oncontextmenu="return false;" oncut="return false;" oncopy="return false;" onpaste="return false;">

<!-- Tombol Mulai Ujian -->
<div id="start-exam-overlay">
    <button id="start-exam-button" type="button">Mulai Ujian Sekarang</button>
</div>

<form id="form-ujian" action="hasil.php" method="POST" style="visibility: hidden;">
    <div class="exam-container">
        <div class="exam-content">
            <div class="timer-badge">
                <i class="far fa-clock timer-icon"></i>
                <span id="timer-display">Menunggu...</span>
            </div>
            
            <?php foreach ($soalUjian as $index => $soal): ?>
            <div class="question-block" id="soal-<?php echo $index; ?>" style="<?php echo $index > 0 ? 'display:none;' : ''; ?>">
                <div class="question-text"><?php echo ($index + 1) . ". " . htmlspecialchars($soal['soal']); ?></div>
                <div class="question-options">
<?php
    // --- LOGIKA PENGACAKAN PILIHAN JAWABAN YANG DIPERBAIKI ---

    // 1. Ambil semua pilihan jawaban asli dari soal.
    $pilihan_asli = $soal['pilihan'];

    // 2. Buat array baru yang berisi hanya teks pilihan jawaban, lalu acak urutannya.
    $pilihan_teks_acak = array_values($pilihan_asli);
    shuffle($pilihan_teks_acak);

    $abjad_tampilan_list = ['A', 'B', 'C', 'D', 'E'];
    $abjad_tampilan_index = 0;

    // 4. Loop melalui teks yang sudah diacak untuk menampilkannya.
    foreach ($pilihan_teks_acak as $teksPilihan) {
        // Ambil abjad baru untuk ditampilkan (A, lalu B, lalu C, dst.)
        $abjad_untuk_tampil = $abjad_tampilan_list[$abjad_tampilan_index];

        // Cari tahu abjad ASLI dari teks ini untuk disimpan sebagai jawaban.
        // Ini adalah kunci dari perbaikan.
        $abjad_asli_untuk_submit = array_search($teksPilihan, $pilihan_asli);
?>
    <div class="option-item" data-nomor-soal="<?php echo $index; ?>" data-pilihan="<?php echo htmlspecialchars($abjad_asli_untuk_submit); ?>">
        <span class="option-text">
            <!-- Tampilkan abjad baru yang berurutan -->
            <span><?php echo $abjad_untuk_tampil; ?>.</span> 
            <!-- Tampilkan teks pilihan yang sudah diacak -->
            <?php echo htmlspecialchars($teksPilihan); ?>
        </span>
        <span class="loading-text"><i class="fas fa-spinner fa-spin"></i> Jawaban tersimpan...</span>
    </div>
<?php
        $abjad_tampilan_index++; // Naikkan index untuk abjad berikutnya
    }
?>
                </div>
                <?php foreach ($soal['pilihan'] as $abjad => $teksPilihan): ?>
                <!-- Gunakan ID UNIK untuk name dan id radio button -->
                <input type="radio" name="jawaban[<?php echo $soal['id_unik']; ?>]" value="<?php echo $abjad; ?>" id="opsi-<?php echo $soal['id_unik'].'-'.$abjad; ?>" style="display:none;">
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <hr class="question-divider">
        </div>
        
        <!-- SIDEBAR NAVIGASI -->
        <div class="exam-sidebar">
            <h3 class="nav-title">Nomor Soal Pilihan Ganda</h3>
            <div class="question-grid">
                <?php foreach ($soalUjian as $index => $soal): ?>
                <button type="button" class="btn-question btn-question--unanswered" id="nav-<?php echo $index; ?>" onclick="tampilkanSoal(<?php echo $index; ?>)">
                    <?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>
                </button>
                <?php endforeach; ?>
            </div>
                                <h3 class="nav-title">Nomor Soal Essay</h3>
            <button type="submit" class="btn-finish-exam" style="display:none;">Selesaikan Ujian</button>
        </div>

    </div>
</form>
            <?php include 'pelaksanaan.php'; ?>


</body>
</html>
