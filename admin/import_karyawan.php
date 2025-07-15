<?php
require_once __DIR__ . '/../includes/init.php';
require '../vendor/autoload.php'; // pastikan path composer kamu benar

use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_excel'])) {
    $file = $_FILES['file_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $successCount = 0;
        $duplicateCount = 0;

        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // lewati header

            $id = trim($row[0]);
            $nama = trim($row[1]);

            if ($id === '' || $nama === '') continue;

            // cek jika ID sudah ada
            $stmt = $conn->prepare("SELECT id FROM karyawan WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                $insert = $conn->prepare("INSERT INTO karyawan (id, nama) VALUES (?, ?)");
                $insert->bind_param("ss", $id, $nama);
                $insert->execute();
                $successCount++;
                $insert->close();
            } else {
                $duplicateCount++;
            }
            $stmt->close();
        }

        $_SESSION['success'] = "✅ Berhasil import $successCount karyawan. $duplicateCount duplikat diabaikan.";
    } catch (Exception $e) {
        $_SESSION['error'] = "❌ Gagal membaca file: " . $e->getMessage();
    }
}

header("Location: tambah_karyawan.php");
exit;
