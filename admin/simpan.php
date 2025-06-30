<?php
require_once __DIR__ . '/../includes/init.php';

session_start(); 

$id_karyawan = $_POST['id_karyawan'];
$tanggal     = $_POST['tanggal'];
$attitude    = $_POST['attitude'];
$kehadiran   = $_POST['kehadiran'];
$daily       = $_POST['daily_report'];
$logbook     = $_POST['logbook'];
$gangguan    = $_POST['gangguan'];

$query = "INSERT INTO penilaian 
(id_karyawan, tanggal, attitude, kehadiran, daily_report, logbook, gangguan) 
VALUES 
('$id_karyawan', '$tanggal', '$attitude', '$kehadiran', '$daily', '$logbook', '$gangguan')";

if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = "✅ Penilaian berhasil disimpan!";
    header("Location: ../admin/input.php");
    exit;
} else {
    $_SESSION['error'] = "❌ Gagal menyimpan: " . mysqli_error($conn);
    header("Location: ../admin/input.php");
    exit;
}
?>
