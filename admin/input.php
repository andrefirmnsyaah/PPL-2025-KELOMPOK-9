<?php
require_once __DIR__ . '/../includes/init.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Penilaian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- CSS -->
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
        <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <span class="me-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                    <i class="fas fa-user-circle fa-fw"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow">
                    <!-- <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Settings
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../auth/logout.php">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <h4 class="mb-4 text-primary">Input Penilaian Karyawan</h4>
            

        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="simpan.php">
    <div class="mb-3">
        <label class="form-label">Tanggal Penilaian:</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Attitude (0–25)</th>
                    <th>Kehadiran (0–20)</th>
                    <th>Daily Report (0–15)</th>
                    <th>Logbook (0–15)</th>
                    <th>Gangguan (0–25)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = mysqli_query($conn, "SELECT * FROM karyawan");
                while ($row = mysqli_fetch_assoc($data)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nama']) . '
                            <input type="hidden" name="id_karyawan[]" value="' . $row['id'] . '">
                          </td>';
                    echo '<td><input type="number" name="attitude[]" class="form-control" min="0" max="25"></td>';
                    echo '<td><input type="number" name="kehadiran[]" class="form-control" min="0" max="20"></td>';
                    echo '<td><input type="number" name="daily_report[]" class="form-control" min="0" max="15"></td>';
                    echo '<td><input type="number" name="logbook[]" class="form-control" min="0" max="15"></td>';
                    echo '<td><input type="number" name="gangguan[]" class="form-control" min="0" max="25"></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-save me-1"></i> Simpan Semua Penilaian
        </button>
    </div>
</form>
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
