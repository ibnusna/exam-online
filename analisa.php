    <script>
        // Chart untuk visualisasi skor
        <?php if ($mode === 'ringkasan'): ?>
        const ctx = document.getElementById('scoreChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Benar', 'Salah'],
                datasets: [{
                    data: [<?php echo $jumlahBenar; ?>, <?php echo $jumlahSalah; ?>],
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        <?php endif; ?>

        // Add smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.bg-white');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Print function enhancement
        function printResult() {
            window.print();
        }

        // Tab switching smooth animation
        document.querySelectorAll('a[href*="mode="]').forEach(link => {
            link.addEventListener('click', function(e) {
                // Add loading animation if needed
                document.body.style.opacity = '0.8';
                setTimeout(() => {
                    document.body.style.opacity = '1';
                }, 500);
            });
        });

        // Auto save functionality (if needed for future features)
        function autoSave() {
            // Implementation for auto-saving results or bookmarks
            console.log('Auto save triggered');
        }

        // Export functionality
        function exportResults(format) {
            if (format === 'pdf') {
                window.print();
            } else if (format === 'json') {
                const resultData = {
                    nama: '<?php echo addslashes($nama); ?>',
                    nim: '<?php echo $nim; ?>',
                    mataKuliah: '<?php echo addslashes($mataKuliah); ?>',
                    skor: <?php echo $persentase; ?>,
                    grade: '<?php echo $skorHuruf; ?>',
                    jumlahBenar: <?php echo $jumlahBenar; ?>,
                    jumlahSalah: <?php echo $jumlahSalah; ?>,
                    totalSoal: <?php echo $totalSoal; ?>,
                    status: '<?php echo $statusLulus ? 'LULUS' : 'TIDAK LULUS'; ?>',
                    waktuPengerjaan: '<?php echo $waktuPengerjaanMenit; ?> menit <?php echo $waktuPengerjaanDetik; ?> detik',
                    tanggal: '<?php echo date('Y-m-d H:i:s'); ?>'
                };
                
                const dataStr = JSON.stringify(resultData, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                
                const exportFileDefaultName = 'hasil_ujian_' + '<?php echo $nim; ?>' + '_' + '<?php echo date('Y-m-d'); ?>' + '.json';
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'p':
                        e.preventDefault();
                        window.print();
                        break;
                    case '1':
                        e.preventDefault();
                        window.location.href = '?mode=ringkasan';
                        break;
                    case '2':
                        e.preventDefault();
                        window.location.href = '?mode=analisis';
                        break;
                    case '3':
                        e.preventDefault();
                        window.location.href = '?mode=evaluasi';
                        break;
                }
            }
        });

        // Scroll to top functionality
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show scroll to top button when scrolled down
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollTopBtn');
            if (scrollBtn) {
                if (window.pageYOffset > 300) {
                    scrollBtn.style.display = 'block';
                } else {
                    scrollBtn.style.display = 'none';
                }
            }
        });

        // Performance metrics tracking (optional)
        function trackPerformance() {
            const performanceData = {
                skor: <?php echo $persentase; ?>,
                waktuPengerjaan: <?php echo $waktuPengerjaan; ?>,
                efisiensi: Math.round((<?php echo $jumlahBenar; ?> / <?php echo $waktuPengerjaanMenit; ?>) * 100) / 100
            };
            
            // This could be sent to analytics or stored locally
            localStorage.setItem('lastExamPerformance', JSON.stringify(performanceData));
        }

        // Initialize performance tracking
        trackPerformance();

        // Tooltip functionality for better UX
        function initTooltips() {
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'absolute bg-gray-800 text-white text-xs rounded py-1 px-2 z-50';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    tooltip.style.bottom = '100%';
                    tooltip.style.left = '50%';
                    tooltip.style.transform = 'translateX(-50%)';
                    tooltip.style.marginBottom = '5px';
                    this.style.position = 'relative';
                    this.appendChild(tooltip);
                });
                
                element.addEventListener('mouseleave', function() {
                    const tooltip = this.querySelector('.absolute');
                    if (tooltip) {
                        tooltip.remove();
                    }
                });
            });
        }

        // Initialize tooltips
        initTooltips();

        // Share results functionality (future feature)
        function shareResults() {
            if (navigator.share) {
                navigator.share({
                    title: 'Hasil Ujian - <?php echo $mataKuliah; ?>',
                    text: 'Saya mendapat skor <?php echo $persentase; ?>% dengan grade <?php echo $skorHuruf; ?> pada ujian <?php echo $mataKuliah; ?>',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                const textArea = document.createElement('textarea');
                textArea.value = `Hasil Ujian ${<?php echo json_encode($mataKuliah); ?>}: Skor ${<?php echo $persentase; ?>}% (Grade ${<?php echo json_encode($skorHuruf); ?>})`;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Hasil ujian telah disalin ke clipboard!');
            }
        }

        // Add floating scroll to top button
        const scrollTopBtn = document.createElement('button');
        scrollTopBtn.id = 'scrollTopBtn';
        scrollTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollTopBtn.className = 'fixed bottom-4 right-4 bg-blue-500 text-white p-3 rounded-full shadow-lg hover:bg-blue-600 transition-colors z-50 no-print';
        scrollTopBtn.style.display = 'none';
        scrollTopBtn.onclick = scrollToTop;
        document.body.appendChild(scrollTopBtn);
    </script>