<?php
require_once __DIR__ . '/../includes/init.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: list_penilaian.php");
    exit;
}

$id = $_GET['id'];

if (mysqli_query($conn, "DELETE FROM penilaian WHERE id = '$id'")) {
    $_SESSION['success'] = "✅ Data penilaian berhasil dihapus.";
} else {
    $_SESSION['error'] = "❌ Gagal menghapus data: " . mysqli_error($conn);
}

header("Location: list_penilaian.php");
exit;
?>
