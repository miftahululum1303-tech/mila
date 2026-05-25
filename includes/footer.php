</main>
</div>
<footer class="footer mt-auto px-4 d-flex align-items-center justify-content-between text-muted small">
    <div>
        <span class="badge bg-success me-2"><i class="fa-solid fa-circle-check me-1"></i>Connected</span>
        Database: <strong class="text-dark">mila</strong>
    </div>
    <div class="d-none d-sm-block">
        Hari ini: <strong class="text-dark"><?php echo date('d F Y'); ?></strong>
    </div>
    <div>
        Developed by <span class="fw-bold" style="color: var(--primary-color);">Nama Anda</span> &copy; <?php echo date('Y'); ?>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Logic Handler untuk Toggle Sidebar (Desktop & Mobile)
        function toggleSidebar() {
            $('body').toggleClass('sidebar-toggled');

            // Menangani transisi penutupan submenu jika sidebar dicolcollapse
            if ($('body').hasClass('sidebar-toggled')) {
                $('.submenu').removeClass('show');
                $('.nav-link-custom[data-bs-toggle="collapse"]').attr('aria-expanded', 'false');
            }
        }

        $('#sidebarToggleDesktop').on('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });

        $('#sidebarToggleMobile').on('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });

        // Inisialisasi DataTables Responsive dengan Bootstrap 5 Styling
        if ($('#logTable').length) {
            $('#logTable').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' // Lokalisasi Bahasa Indonesia
                }
            });
        }

        // Inisialisasi Chart.js untuk Dashboard (Grafik Tren Stok)
        if ($('#dashboardChart').length) {
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Barang Masuk',
                        data: [45, 60, 25, 70, 40, 15, 30],
                        borderColor: '#3e7ccb',
                        backgroundColor: 'rgba(62, 124, 203, 0.1)',
                        fill: true,
                        tension: 0.3
                    }, {
                        label: 'Barang Keluar',
                        data: [20, 35, 40, 25, 55, 10, 5],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
</body>

</html>