<?php
require_once __DIR__ . '/../includes/init.php';

$success = '';
$error = '';
$search = trim($_GET['search'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nama = trim($_POST['nama'] ?? '');

    if ($id === '' || $nama === '') {
        $error = "ID dan Nama tidak boleh kosong!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM karyawan WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "ID karyawan sudah terdaftar!";
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO karyawan (id, nama) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $id, $nama);
            if ($stmt_insert->execute()) {
                $success = "Karyawan berhasil ditambahkan!";
            } else {
                $error = "Terjadi kesalahan saat menambahkan karyawan.";
            }
            $stmt_insert->close();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            z-index: 1000;
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
    </style>
</head>
<body>
    <nav class="sidebar d-flex flex-column p-0">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Penilaian</div>
        </a>
        <ul class="nav flex-column px-3">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="input.php">
                    <i class="fas fa-fw fa-plus me-2"></i>
                    <span>Input Penilaian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="list_penilaian.php">
                    <i class="fas fa-fw fa-edit me-2"></i>
                    <span>Edit/Hapus Penilaian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="tambah_karyawan.php">
                    <i class="fas fa-fw fa-user-plus me-2"></i>
                    <span>Tambah Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">
                    <i class="fas fa-fw fa-sign-out-alt me-2"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="content-wrapper" style="margin-left:250px; padding:2rem; background-color: #f8f9fc; min-height: 100vh;">
        <h4 class="mb-4 text-primary">Tambah Karyawan</h4>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ID Karyawan</label>
                            <input type="text" name="id" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="my-4">
<h5>Import Data Karyawan (Excel .xlsx)</h5>
<form method="POST" action="import_karyawan.php" enctype="multipart/form-data">
    <div class="row g-3">
        <div class="col-md-9">
            <input type="file" name="file_excel" accept=".xlsx" class="form-control" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" type="submit">
                <i class="fas fa-file-import me-1"></i> Import Excel
            </button>
        </div>
    </div>
</form>

        <div class="card shadow">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span>Daftar Karyawan</span>
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari nama..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-sm btn-outline-primary" type="submit">Cari</button>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        $no = 1;
                        $searchQuery = $search ? "WHERE nama LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'" : "";
                        $data = mysqli_query($conn, "SELECT * FROM karyawan $searchQuery ORDER BY nama ASC");
                        while ($row = mysqli_fetch_assoc($data)) {
                            echo "<tr>";
                            echo "<td>{$no}</td>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>
                                    <a href='hapus_karyawan.php?id={$row['id']}' 
                                       onclick=\"return confirm('Yakin ingin hapus karyawan ini?')\" 
                                       class='btn btn-sm btn-danger'>
                                        <i class='fas fa-trash'></i> Hapus
                                    </a>
                                  </td>";
                            echo "</tr>";
                            $no++;
                        }
                        if ($no === 1) {
                            echo "<tr><td colspan='4' class='text-center'>Data tidak ditemukan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


