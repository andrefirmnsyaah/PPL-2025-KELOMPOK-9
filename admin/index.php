<?php
require_once __DIR__ . '/../includes/init.php';

// Proteksi halaman: hanya bisa diakses jika sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Validasi parameter bulan
$bulan_ini = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan_ini)) {
    $bulan_ini = date('Y-m');
}

// Query aman menggunakan prepared statement
$query = "
SELECT k.id, k.nama, 
       SUM(p.attitude) AS attitude, 
       SUM(p.kehadiran) AS kehadiran,
       SUM(p.daily_report) AS daily_report,
       SUM(p.logbook) AS logbook,
       SUM(p.gangguan) AS gangguan,
       COUNT(p.id) AS jumlah_hari
FROM penilaian p
JOIN karyawan k ON k.id = p.id_karyawan
WHERE DATE_FORMAT(p.tanggal, '%Y-%m') = ?
GROUP BY k.id
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $bulan_ini);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-radius: 0.35rem;
            margin: 0.2rem 0;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            text-align: center;
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
        }
        
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            height: 4.375rem;
        }
        
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.35rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.25rem;
            margin: 0;
            border-radius: calc(0.35rem - 1px) calc(0.35rem - 1px) 0 0;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .text-primary {
            color: #4e73df !important;
        }
        
        .table thead th {
            border-bottom: 2px solid #e3e6f0;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.8rem;
            color: #5a5c69;
        }
        
        .badge-success {
            background-color: #1cc88a;
        }
        
        .badge-warning {
            background-color: #f6c23e;
        }
        
        .badge-danger {
            background-color: #e74a3b;
        }
        
        .progress {
            background-color: #eaecf4;
            border-radius: 10rem;
        }
        
        .content-wrapper {
            background-color: #f8f9fc;
            min-height: 100vh;
        }
        
        .dropdown-toggle::after {
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                z-index: 1050;
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .content-wrapper {
                margin-left: 0;
            }
        }
        
        @media (min-width: 769px) {
            .content-wrapper {
                margin-left: 250px;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                z-index: 1000;
            }
        }
    </style>

</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-0" id="sidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Penilaian</div>
        </a>
        
        <hr class="sidebar-divider my-0" style="border-color: rgba(255,255,255,0.15);">
        
        <ul class="nav flex-column px-3">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Input Penilaian -->
            <li class="nav-item">
                <a class="nav-link" href="input.php">
                    <i class="fas fa-fw fa-plus me-2"></i>
                    <span>Input Penilaian</span>
                </a>
            </li>
            
            <!-- Edit/Hapus Penilaian -->
            <li class="nav-item">
                <a class="nav-link" href="list_penilaian.php">
                    <i class="fas fa-fw fa-edit me-2"></i>
                    <span>Edit/Hapus Penilaian</span>
                </a>
            </li>
            
            <!-- Tambah Karyawan -->
            <li class="nav-item">
                <a class="nav-link" href="tambah_karyawan.php">
                    <i class="fas fa-fw fa-user-plus me-2"></i>
                    <span>Tambah Karyawan</span>
                </a>
            </li>
            
            <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.15);">
            
            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">
                    <i class="fas fa-fw fa-sign-out-alt me-2"></i>
                    <span>Logout</span>
                </a>
            </li>
            
        </ul>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar -->
        <nav class="topbar navbar navbar-expand navbar-light bg-white mb-4 static-top shadow">
            <!-- Sidebar Toggle (Mobile) -->
            <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggle">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <span class="me-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                        <i class="fas fa-user-circle fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow">
                        <!-- <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="setting.php">
                            <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                            Settings
                        </a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../auth/logout.php">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid">
            
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-4 text-primary">Dashboard Penilaian Bulan: <?= date('F Y', strtotime($bulan_ini . '-01')) ?></h4>
                <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    <script>
                        document.write(new Date().toLocaleDateString('id-ID', { 
                            year: 'numeric', 
                            month: 'long' 
                        }));
                    </script>
                </small>
            </div>

            <?php
            // Buat array bulan yang tersedia (bisa juga ambil dari DB jika dinamis)
            $bulan_tersedia = [];
            $tahun_mulai = 2023;
            $tahun_skrg = date('Y');
            $bulan_skrg = date('n');

            // Generate bulan-tahun dari 2023 sampai sekarang
            for ($tahun = $tahun_mulai; $tahun <= $tahun_skrg; $tahun++) {
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    if ($tahun == $tahun_skrg && $bulan > $bulan_skrg) break;
                    $value = sprintf('%04d-%02d', $tahun, $bulan);
                    $label = date('F Y', strtotime($value . '-01'));
                    $bulan_tersedia[$value] = $label;
                }
            }

            // Ambil bulan aktif dari URL atau default sekarang
            $bulan_ini = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
            ?>

            <!-- Pilih Bulan -->
            <form method="GET" class="mb-3 d-flex align-items-center">
                <label for="bulan" class="me-2">Pilih Bulan:</label>
                <select name="bulan" id="bulan" class="form-select me-2" style="max-width: 250px;">
                    <?php
                    // Buat array bulan
                    $bulan_tersedia = [];
                    $tahun_mulai = 2023;
                    $tahun_skrg = date('Y');
                    $bulan_skrg = date('n');
                    for ($tahun = $tahun_mulai; $tahun <= $tahun_skrg; $tahun++) {
                        for ($bulan = 1; $bulan <= 12; $bulan++) {
                            if ($tahun == $tahun_skrg && $bulan > $bulan_skrg) break;
                            $val = sprintf('%04d-%02d', $tahun, $bulan);
                            $label = date('F Y', strtotime($val . '-01'));
                            $selected = ($bulan_ini === $val) ? 'selected' : '';
                            echo "<option value=\"$val\" $selected>$label</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Lihat</button>
            </form>

            <!-- DataTables Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table me-1"></i>
                        Data Penilaian Karyawan
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow">
                            <div class="dropdown-header">Aksi:</div>
                            <a class="dropdown-item" href="input.php">Input Penilaian Baru</a>
                            <a class="dropdown-item" href="export_excel.php?bulan=<?= urlencode($bulan_ini) ?>">Export ke Excel</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        
                        <?php if ($result->num_rows === 0): ?>
                            <div class="alert alert-warning">Tidak ada data penilaian untuk bulan ini.</div>
                        <?php endif; ?>

                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>Attitude</th>
                                    <th>Kehadiran</th>
                                    <th>Daily Report</th>
                                    <th>Logbook</th>
                                    <th>Gangguan</th>
                                    <th>Total Score</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) {
                                    $att = round($row['attitude'] / ($row['jumlah_hari'] * 25) * 25, 2);
                                    $keh = round($row['kehadiran'] / ($row['jumlah_hari'] * 20) * 20, 2);
                                    $daily = round($row['daily_report'] / ($row['jumlah_hari'] * 15) * 15, 2);
                                    $log = round($row['logbook'] / ($row['jumlah_hari'] * 15) * 15, 2);
                                    $gang = round($row['gangguan'] / ($row['jumlah_hari'] * 25) * 25, 2);
                                    $total = $att + $keh + $daily + $log + $gang;

                                    if ($total >= 85) {
                                        $status = 'Excellent';
                                        $statusClass = 'badge-success';
                                        $totalColor = 'text-success';
                                    } elseif ($total >= 70) {
                                        $status = 'Good';
                                        $statusClass = 'badge-warning';
                                        $totalColor = 'text-warning';
                                    } else {
                                        $status = 'Needs Improvement';
                                        $statusClass = 'badge-danger';
                                        $totalColor = 'text-danger';
                                    }
                                ?>

                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($row['nama']) ?></div>
                                                <div class="small text-muted">NIK: <?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="progress mb-1" style="height: 8px;">
                                            <div class="progress-bar <?= $att >= 20 ? 'bg-success' : ($att >= 15 ? 'bg-warning' : 'bg-danger') ?>" role="progressbar" style="width: <?= $att ?>%"></div>
                                        </div>
                                        <small><?= $att ?>%</small>
                                    </td>

                                    <td>
                                        <div class="progress mb-1" style="height: 8px;">
                                            <div class="progress-bar <?= $keh >= 16 ? 'bg-success' : ($keh >= 12 ? 'bg-warning' : 'bg-danger') ?>" role="progressbar" style="width: <?= $keh * 5 ?>%"></div>
                                        </div>
                                        <small><?= $keh ?>%</small>
                                    </td>

                                    <td>
                                        <div class="progress mb-1" style="height: 8px;">
                                            <div class="progress-bar <?= $daily >= 12 ? 'bg-success' : ($daily >= 9 ? 'bg-warning' : 'bg-danger') ?>" role="progressbar" style="width: <?= $daily * 6.67 ?>%"></div>
                                        </div>
                                        <small><?= $daily ?>%</small>
                                    </td>

                                    <td>
                                        <div class="progress mb-1" style="height: 8px;">
                                            <div class="progress-bar <?= $log >= 12 ? 'bg-success' : ($log >= 9 ? 'bg-warning' : 'bg-danger') ?>" role="progressbar" style="width: <?= $log * 6.67 ?>%"></div>
                                        </div>
                                        <small><?= $log ?>%</small>
                                    </td>

                                    <td>
                                        <div class="progress mb-1" style="height: 8px;">
                                            <div class="progress-bar <?= $gang >= 20 ? 'bg-success' : ($gang >= 15 ? 'bg-warning' : 'bg-danger') ?>" role="progressbar" style="width: <?= $gang ?>%"></div>
                                        </div>
                                        <small><?= $gang ?>%</small>
                                    </td>

                                    <td>
                                        <div class="font-weight-bold <?= $totalColor ?>"><?= $total ?>%</div>
                                    </td>

                                    <td>
                                        <span class="badge <?= $statusClass ?> rounded-pill"><?= $status ?></span>
                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
</body>
</html>