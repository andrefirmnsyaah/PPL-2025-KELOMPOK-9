<?php
require_once __DIR__ . '/../includes/init.php';
session_start(); 

$tanggal     = $_POST['tanggal'];
$id_karyawan = $_POST['id_karyawan'];
$attitude    = $_POST['attitude'];
$kehadiran   = $_POST['kehadiran'];
$daily       = $_POST['daily_report'];
$logbook     = $_POST['logbook'];
$gangguan    = $_POST['gangguan'];

$success = true;
$duplikat = [];

for ($i = 0; $i < count($id_karyawan); $i++) {
    // Ambil nilai
    $id   = mysqli_real_escape_string($conn, $id_karyawan[$i]);
    $att  = trim($attitude[$i]);
    $keh  = trim($kehadiran[$i]);
    $dr   = trim($daily[$i]);
    $log  = trim($logbook[$i]);
    $gang = trim($gangguan[$i]);

    // Lewati jika semua nilai kosong (baris kosong)
    if ($att === '' && $keh === '' && $dr === '' && $log === '' && $gang === '') {
        continue;
    }

    // Lewati jika ada nilai yang belum lengkap
    if ($att === '' || $keh === '' || $dr === '' || $log === '' || $gang === '') {
        continue; // atau bisa simpan sebagai error parsial kalau mau
    }

    // Cek duplikat
    $cek = mysqli_query($conn, "SELECT id FROM penilaian WHERE id_karyawan = '$id' AND tanggal = '$tanggal'");
    if (mysqli_num_rows($cek) > 0) {
        $duplikat[] = $id;
        continue;
    }

    $query = "INSERT INTO penilaian 
        (id_karyawan, tanggal, attitude, kehadiran, daily_report, logbook, gangguan) 
        VALUES 
        ('$id', '$tanggal', '$att', '$keh', '$dr', '$log', '$gang')";

    if (!mysqli_query($conn, $query)) {
        $success = false;
        break;
    }
}

if ($success && empty($duplikat)) {
    $_SESSION['success'] = "✅ Semua penilaian berhasil disimpan!";
} elseif ($success && !empty($duplikat)) {
    $_SESSION['success'] = "⚠️ Penilaian sebagian disimpan. Karyawan dengan ID: " . implode(', ', $duplikat) . " sudah pernah dinilai hari ini.";
} else {
    $_SESSION['error'] = "❌ Gagal menyimpan: " . mysqli_error($conn);
}

header("Location: ../admin/input.php");
exit;

