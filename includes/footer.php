</main>

</div>

<footer class="footer mt-auto px-4 py-3">

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">

        <!-- LEFT -->
        <div class="d-flex align-items-center">

            <div class="status-dot me-2"></div>

            <span class="text-muted small">

                Sistem berjalan dengan baik

            </span>

        </div>

        <!-- CENTER -->
        <div class="text-center">

            <small class="text-muted">

                © <?= date('Y') ?>

                <span class="fw-semibold" style="color: var(--primary-color);">

                    Inventory App

                </span>

                • Developed by

                <span class="fw-semibold text-dark">

                    Novia Dwi Urmila

                </span>

            </small>

        </div>

        <!-- RIGHT -->
        <div>

            <small class="text-muted">

                <?= date('d F Y') ?>

            </small>

        </div>

    </div>

</footer>

<style>
    .footer {

        background: rgba(255, 255, 255, 0.75);

        backdrop-filter: blur(14px);

        border-top: 1px solid #f3e8ff;

        margin-left: var(--sidebar-width);

        transition: margin-left .3s ease;

    }

    body.sidebar-toggled .footer {

        margin-left: var(--sidebar-collapsed-width);

    }

    .status-dot {

        width: 10px;

        height: 10px;

        border-radius: 50%;

        background: #22c55e;

        box-shadow:
            0 0 10px rgba(34, 197, 94, .5);

        animation: pulse 1.5s infinite;

    }

    @keyframes pulse {

        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.3);
            opacity: .6;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }

    }

    @media(max-width:768px) {

        .footer {

            margin-left: 0 !important;

        }

    }
</style>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DATATABLE -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // =========================================
    // SIDEBAR TOGGLE
    // =========================================

    $(document).ready(function() {

        function toggleSidebar() {

            $('body').toggleClass('sidebar-toggled');

        }

        $('#sidebarToggleDesktop').on('click', function(e) {

            e.preventDefault();

            toggleSidebar();

        });

        $('#sidebarToggleMobile').on('click', function(e) {

            e.preventDefault();

            toggleSidebar();

        });

    });

    // =========================================
    // AUTO CLOSE ALERT
    // =========================================

    setTimeout(function() {

        let alert = document.querySelector('.alert');

        if (alert) {

            let bsAlert = new bootstrap.Alert(alert);

            bsAlert.close();

        }

    }, 3000);
</script>

</body>

</html>
