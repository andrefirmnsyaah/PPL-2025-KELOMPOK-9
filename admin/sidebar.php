<!-- sidebar.php -->
<nav class="sidebar d-flex flex-column p-0" id="sidebar" style="min-height:100vh; background: linear-gradient(180deg, #4e73df 10%, #224abe 100%); box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); width:250px; position:fixed;">
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4 text-white text-decoration-none" href="index.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-chart-line fa-lg me-2"></i>
        </div>
        <div class="sidebar-brand-text fw-bold">Penilaian</div>
    </a>

    <hr class="sidebar-divider my-0" style="border-color: rgba(255,255,255,0.15);">

    <ul class="nav flex-column px-3">
        <li class="nav-item">
            <a class="nav-link text-white" href="index.php">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="input.php">
                <i class="fas fa-plus me-2"></i> Input Penilaian
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="list_penilaian.php">
                <i class="fas fa-edit me-2"></i> Edit Penilaian
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="tambah_karyawan.php">
                <i class="fas fa-user-plus me-2"></i> Tambah Karyawan
            </a>
        </li>

        <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.15);">

        <li class="nav-item">
            <a class="nav-link text-white" href="../auth/logout.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</nav>