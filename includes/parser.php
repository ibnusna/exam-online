<?php
/**
 * File: includes/parser.php
 * Deskripsi: File ini berisi fungsi untuk mem-parsing soal dari teks.
 * VERSI PERBAIKAN: Menambahkan ID unik internal untuk setiap soal
 * agar tidak bergantung pada nomor soal dari input pengguna.
 */

function parseSoalFromText($text) {
    $content = trim($text);
    if (empty($content)) {
        return [];
    }

    // Regex untuk memecah teks menjadi blok-blok soal berdasarkan pola "nomor diikuti titik".
    $questionBlocks = preg_split('/^\s*(?=\d+\.)/m', $content, -1, PREG_SPLIT_NO_EMPTY);

    $soalTerstruktur = [];
    $id_counter = 0; // Counter untuk ID unik

    foreach ($questionBlocks as $block) {
        $block = trim($block);
        if (empty($block)) continue;

        $id_counter++; // Increment counter untuk setiap soal yang valid

        $kunci = '';
        if (preg_match('/Kunci Jawaban:\s*([A-E])/i', $block, $matchesKunci)) {
            $kunci = strtoupper(trim($matchesKunci[1]));
            $block = preg_replace('/Kunci Jawaban:\s*[A-E]/i', '', $block);
        }

        $lines = preg_split('/\\r\\n|\\r|\\n/', trim($block));
        $soalText = array_shift($lines);

        $nomorTampilan = 0; // Nomor yang akan ditampilkan ke pengguna
        if (preg_match('/^(\d+)\.\s*(.*)/s', $soalText, $matchesNomor)) {
            $nomorTampilan = (int)$matchesNomor[1];
            $soalText = trim($matchesNomor[2]);
        }

        $pilihan = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^([A-E])\.\s*(.*)/s', $line, $matchesPilihan)) {
                $abjad = strtoupper(trim($matchesPilihan[1]));
                $teksPilihan = trim($matchesPilihan[2]);
                $pilihan[$abjad] = $teksPilihan;
            }
        }
        
        if ($nomorTampilan > 0 && !empty($soalText) && !empty($pilihan) && !empty($kunci)) {
            $soalTerstruktur[] = [
                'id_unik' => 'q' . $id_counter, // ID unik internal (misal: q1, q2, dst)
                'nomor_tampilan' => $nomorTampilan, // Nomor asli dari inputan
                'soal'    => $soalText,
                'pilihan' => $pilihan,
                'kunci'   => $kunci
            ];
        }
    }

    return $soalTerstruktur;
}
?>
