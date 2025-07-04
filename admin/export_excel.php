<?php
require_once __DIR__ . '/../includes/init.php';

// Proteksi akses hanya untuk admin
if (!isset($_SESSION['admin'])) {
    die("Akses ditolak.");
}

// Ambil dan validasi parameter bulan
$bulan_ini = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan_ini)) {
    die("Parameter bulan tidak valid.");
}

// Header untuk Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=penilaian_karyawan_" . $bulan_ini . ".xls");

// Mulai output tabel
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>Nama Karyawan</th>
        <th>Attitude (%)</th>
        <th>Kehadiran (%)</th>
        <th>Daily Report (%)</th>
        <th>Logbook (%)</th>
        <th>Gangguan (%)</th>
        <th>Total (%)</th>
      </tr>";

// Query penilaian
$query = "
SELECT k.nama, 
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

$no = 1;
while ($row = $result->fetch_assoc()) {
    // Hitung persentase per kategori
    $att = round($row['attitude'] / ($row['jumlah_hari'] * 25) * 25, 2);
    $keh = round($row['kehadiran'] / ($row['jumlah_hari'] * 20) * 20, 2);
    $daily = round($row['daily_report'] / ($row['jumlah_hari'] * 15) * 15, 2);
    $log = round($row['logbook'] / ($row['jumlah_hari'] * 15) * 15, 2);
    $gang = round($row['gangguan'] / ($row['jumlah_hari'] * 25) * 25, 2);
    $total = $att + $keh + $daily + $log + $gang;

    // Output baris
    echo "<tr>
            <td>" . htmlspecialchars($no) . "</td>
            <td>" . htmlspecialchars($row['nama']) . "</td>
            <td>" . htmlspecialchars($att) . "</td>
            <td>" . htmlspecialchars($keh) . "</td>
            <td>" . htmlspecialchars($daily) . "</td>
            <td>" . htmlspecialchars($log) . "</td>
            <td>" . htmlspecialchars($gang) . "</td>
            <td>" . htmlspecialchars($total) . "</td>
          </tr>";
    $no++;
}

echo "</table>";
?>