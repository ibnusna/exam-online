<?php
// Catatan: jangan ubah ID dan variabel
?>
<!-- === AWAL DARI BLOK YANG DIPERBAIKI TOTAL === -->
<div style="max-width: 600px; width: 100%; padding: 24px; background-color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333;">
  
  <div style="text-align: center; margin-bottom: 24px;">
    <img
      src="assets/img/headerpdf.png"
      alt="Header Universitas"
      style="max-width: 350px; width: 100%; display: inline-block;"
    />
  </div>

  <p style="text-align: center; margin-bottom: 24px; font-size: 14px; font-weight: 500;">
    Terima Kasih Anda Telah Mengikuti Ujian Online
  </p>

  <div style="max-width: 500px; margin-left: auto; margin-right: auto; font-size: 14px;">

    <!-- Baris NIM (Latar Belakang Putih) -->
    <div style="display: flex; padding: 6px 4px;">
      <div style="width: 120px;">NIM</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1;"><?php echo htmlspecialchars($nim ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Nama (Latar Belakang Abu-abu) -->
    <div style="display: flex; padding: 6px 4px; background-color: #e5e7eb;">
      <div style="width: 120px;">Nama</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1; text-transform: uppercase;"><?php echo htmlspecialchars($nama ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Kel Ujian (Latar Belakang Putih) -->
    <div style="display: flex; padding: 6px 4px;">
      <div style="width: 120px;">Kel Ujian</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1;"><?php echo htmlspecialchars($kelUjian ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Tanggal Mulai (Latar Belakang Abu-abu) -->
    <div style="display: flex; padding: 6px 4px; background-color: #e5e7eb;">
      <div style="width: 120px;">Tanggal Mulai</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1;"><?php echo htmlspecialchars($periodeUjian ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Matakuliah (Latar Belakang Putih) -->
    <div style="display: flex; padding: 6px 4px;">
      <div style="width: 120px;">Matakuliah</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1; text-transform: uppercase;"><?php echo htmlspecialchars($mataKuliah ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Jumlah Soal (Latar Belakang Abu-abu) -->
    <div style="display: flex; padding: 6px 4px; background-color: #e5e7eb;">
      <div style="width: 120px;">Jumlah Soal</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1;"><?php echo htmlspecialchars($totalSoal ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Jumlah Benar (Latar Belakang Putih) -->
    <div style="display: flex; padding: 6px 4px;">
      <div style="width: 120px;">Jumlah Benar</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1; font-weight: bold;"><?php echo htmlspecialchars($jumlahBenar ?? 'N/A'); ?></div>
    </div>

    <!-- Baris Jumlah Salah (Latar Belakang Abu-abu) -->
    <div style="display: flex; padding: 6px 4px; background-color: #e5e7eb;">
      <div style="width: 120px;">Jumlah Salah</div>
      <div style="width: 20px;">:</div>
      <div style="flex: 1; font-weight: bold;"><?php echo htmlspecialchars($jumlahSalah ?? 'N/A'); ?></div>
    </div>

  </div>

  <p style="text-align: center; margin-top: 24px; margin-bottom: 24px; font-size: 14px;">
    Simpan sebagai bukti bahwa anda telah mengikuti ujian online
  </p>

  <div style="text-align: center; margin-bottom: 24px;">
    <img
      src="assets/img/qrpdf.png"
      alt="QR code bukti ujian"
      style="width: 100px; height: 100px; display: inline-block;"
    />
  </div>

  <hr style="border: none; border-top: 1px solid #e2e8f0; margin-bottom: 12px;" />

  <p style="text-align: center; font-size: 12px; color: #6b7280;">
    Waktu Cetak: <?php echo htmlspecialchars($waktuCetak ?? 'N/A'); ?>
  </p>
</div>
<!-- === AKHIR DARI BLOK YANG DIPERBAIKI === -->
