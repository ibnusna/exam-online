<!-- 1. Tombol Pemicu untuk membuka pop-up -->
<div id="pemicuDokumen" class="no-print" style="cursor: pointer; border: 2px dashed #007bff; padding: 20px; text-align: center; color: #007bff; background-color: #f0f8ff; border-radius: 8px;">
    <strong>Lihat Bukti Ujian</strong>
</div>

<!-- 2. Wadah pop-up untuk dokumen (awalnya tersembunyi) -->
<div id="modalDokumen" class="no-print" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7);">
    
    <!-- Kotak putih yang berisi konten -->
    <div style="background-color: #fff; margin: 5% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 700px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        
        <!-- Tombol 'X' untuk menutup -->
        <span id="tutupModal" style="color: #555; position: absolute; top: 10px; right: 25px; font-size: 35px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <!-- Tombol Download PDF -->
        <button id="tombolDownloadPdf" style="position: absolute; top: 15px; left: 25px; padding: 8px 12px; cursor: pointer; background-color: #D32F2F; color: white; border: none; border-radius: 5px; font-size: 14px;">Download PDF</button>

        <h3 style="margin-top:0; text-align:center;">Dokumen Bukti Ujian</h3>
        <hr style="margin-bottom: 20px;">

        <!-- Wrapper untuk konten yang akan di-screenshot menjadi PDF -->
        <div id="kontenUntukPdf">
            <!-- Konten dari dokumen.php akan dimuat di sini -->
            <?php include 'dokumen.php'; ?>
        </div>
    </div>
</div>

<!-- Library untuk membuat PDF. WAJIB ADA. -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- === PENAMBAHAN BARU: Teruskan data PHP ke JavaScript === -->
<script>
    const nim_mahasiswa = "<?php echo htmlspecialchars($nim ?? '00000000'); ?>";
    const matkul_ujian = "<?php echo htmlspecialchars($mataKuliah ?? 'Matakuliah'); ?>";
</script>
<!-- ======================================================= -->

<!-- JavaScript untuk mengatur semuanya -->
<script>
    // Inisialisasi jsPDF
    const { jsPDF } = window.jspdf;

    // Ambil semua elemen yang kita butuhkan berdasarkan ID-nya
    var modal = document.getElementById('modalDokumen');
    var pemicu = document.getElementById('pemicuDokumen');
    var tombolTutup = document.getElementById('tutupModal');
    var tombolDownload = document.getElementById('tombolDownloadPdf');
    var kontenPdf = document.getElementById('kontenUntukPdf');

    // Ketika pengguna mengklik tombol/area pemicu, tampilkan modal
    pemicu.onclick = function() {
        modal.style.display = "block";
    }

    // Ketika pengguna mengklik tombol 'X' (span), sembunyikan modal
    tombolTutup.onclick = function() {
        modal.style.display = "none";
    }

    // Ketika pengguna mengklik di mana saja di luar kotak konten, sembunyikan juga modalnya
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // FUNGSI UNTUK DOWNLOAD LANGSUNG SEBAGAI PDF
    tombolDownload.onclick = function() {
        tombolDownload.innerText = 'Memproses...';
        tombolDownload.disabled = true;
        
        html2canvas(kontenPdf, {
            scale: 2, 
            useCORS: true,
            backgroundColor: '#ffffff' 
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = pdf.internal.pageSize.getHeight();
            const ratio = canvas.width / canvas.height;
            let imgWidth = pdfWidth - 20;
            let imgHeight = imgWidth / ratio;
            
            if (imgHeight > pdfHeight - 20) {
                imgHeight = pdfHeight - 20;
                imgWidth = imgHeight * ratio;
            }

            const x = (pdfWidth - imgWidth) / 2;
            const y = 10;

            pdf.addImage(imgData, 'PNG', x, y, imgWidth, imgHeight);

            // === PERBAIKAN UTAMA ADA DI SINI ===
            // Membuat nama file kustom menggunakan variabel yang sudah diteruskan dari PHP
            const namaFile = `Bukti Ujian ${nim_mahasiswa} (${matkul_ujian}).pdf`;
            // Menyimpan PDF dengan nama file kustom
            pdf.save(namaFile);
            // ===================================

            tombolDownload.innerText = 'Download PDF';
            tombolDownload.disabled = false;
        }).catch(err => {
            console.error("Terjadi kesalahan saat membuat PDF: ", err);
            alert("Maaf, terjadi kesalahan saat membuat PDF. Silakan coba lagi.");
            tombolDownload.innerText = 'Download PDF';
            tombolDownload.disabled = false;
        });
    }
</script>
