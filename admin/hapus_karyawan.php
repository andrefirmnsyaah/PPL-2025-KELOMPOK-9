<?php
require_once __DIR__ . '/../includes/init.php';
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID karyawan tidak ditemukan!";
    header("Location: tambah_karyawan.php");
    exit;
}

$id = $_GET['id'];

// Cek apakah karyawan punya penilaian
$stmtCek = $conn->prepare("SELECT COUNT(*) FROM penilaian WHERE id_karyawan = ?");
$stmtCek->bind_param("s", $id);
$stmtCek->execute();
$stmtCek->bind_result($jumlah);
$stmtCek->fetch();
$stmtCek->close();

if ($jumlah > 0) {
    $_SESSION['error'] = "❌ Karyawan tidak bisa dihapus karena masih memiliki data penilaian.";
} else {
    $stmtDelete = $conn->prepare("DELETE FROM karyawan WHERE id = ?");
    $stmtDelete->bind_param("s", $id);
    if ($stmtDelete->execute()) {
        $_SESSION['success'] = "✅ Karyawan berhasil dihapus.";
    } else {
        $_SESSION['error'] = "❌ Gagal menghapus karyawan.";
    }
    $stmtDelete->close();
}

header("Location: tambah_karyawan.php");
exit;
