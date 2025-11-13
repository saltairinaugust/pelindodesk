<?php
session_start();
require_once "../config/db.php"; // pastikan path-nya benar

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user']['nama'];

// ======== FILTER UNIT ========
$unitDipilih = isset($_GET['unit']) ? $_GET['unit'] : 'Semua';

// ======== QUERY UNTUK HITUNG STATUS ========
if ($unitDipilih === 'Semua' || $unitDipilih === '') {
    $sql = "SELECT status, COUNT(*) AS jumlah FROM tugas_karyawan GROUP BY status";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT status, COUNT(*) AS jumlah FROM tugas_karyawan WHERE unit = ? GROUP BY status";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $unitDipilih);
}

$stmt->execute();
$result = $stmt->get_result();

// ======== AMBIL DATA KE DALAM ARRAY ========
$statusCount = [];
while ($row = $result->fetch_assoc()) {
    $statusCount[$row['status']] = (int)$row['jumlah'];
}

// ======== WARNA UNTUK SETIAP STATUS ========
$statusColors = [
    'Selesai' => '#58D68D',              // hijau
    'Revisi' => '#5DADE2',               // biru
    'Ditunda' => '#E74C3C',              // merah
    'Sedang Dikerjakan' => '#F4D03F',    // kuning
    'Menunggu Persetujuan' => '#C9C9C9', // abu-abu
    'Belum Dikerjakan' => '#E67E22'      // oranye
];

$chartColors = [];
foreach (array_keys($statusCount) as $status) {
    $chartColors[] = $statusColors[$status] ?? '#BDC3C7';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Tugas Berdasarkan Status - PelindoDesk</title>
    <link rel="stylesheet" href="../assets/css/style4.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="../assets/img/logo.png" alt="PelindoDesk Logo" class="logo-img">
        <span class="logo-text">PelindoDesk</span>
    </div>
</header>

<div class="container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="profile">
            <span class="username">
                <a href="../includes/detailProfil.php" class="username-link">👤 <?= htmlspecialchars($username) ?></a>
            </span>
        </div>
        <ul class="menu">
                <li><a href="../includes/tentangPelindoDesk.php"><span class="icon">ℹ️</span> Tentang PelindoDesk</a></li>
                <li><a href="daftarTugas.php"><span class="icon">👥</span> Daftar Tugas Karyawan</a></li>
                <li class="active"><a href="rekapDivisi.php"><span class="icon">📊</span> Rekap Tugas Divisi</a></li>
            </ul>
            <button class="logout" onclick="window.location.href='../guest/login.php'">Keluar</button>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <h1>Rekap Jumlah Tugas Berdasarkan Status</h1>

        <!-- FILTER DIVISI -->
        <div class="filter">
            <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px;">
                <select name="unit" required>
                    <option value="Semua" <?= ($unitDipilih == 'Semua' || $unitDipilih == '') ? 'selected' : '' ?>>Semua Divisi</option>
                    <option value="Pelayanan Kapal" <?= ($unitDipilih == 'Pelayanan Kapal') ? 'selected' : '' ?>>Pelayanan Kapal</option>
                    <option value="Keuangan" <?= ($unitDipilih == 'Keuangan') ? 'selected' : '' ?>>Keuangan</option>
                    <option value="Operasional" <?= ($unitDipilih == 'Operasional') ? 'selected' : '' ?>>Operasional</option>
                </select>

                <button type="submit" style="padding: 6px 12px; border: 1px solid #946D43; background: #fff; border-radius: 6px; cursor: pointer; color: #946D43; font-weight: bold;">
                    Tampilkan
                </button>

                <button type="button" onclick="window.location.href='rekapTugasPribadi.php'" 
                        style="padding: 6px 12px; border: 1px solid #da0505; background: #fff; border-radius: 6px; cursor: pointer; color: #da0505; font-weight: bold;">
                    Reset
                </button>
            </form>
        </div>

        <?php if (!empty($statusCount)): ?>
            <h2><?= ($unitDipilih === 'Semua' || $unitDipilih === '') ? 'Semua Divisi' : 'Divisi ' . htmlspecialchars($unitDipilih) ?></h2>
            
            <div class="chart-container" style="max-width: 600px; margin: 0 auto;">
                <canvas id="statusChart"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('statusChart').getContext('2d');
                const statusData = {
                    labels: <?= json_encode(array_keys($statusCount)) ?>,
                    datasets: [{
                        label: 'Jumlah Tugas',
                        data: <?= json_encode(array_values($statusCount)) ?>,
                        backgroundColor: <?= json_encode($chartColors) ?>,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                };

                new Chart(ctx, {
                    type: 'pie',
                    data: statusData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Distribusi Status Tugas'
                            }
                        }
                    }
                });
            </script>
        <?php else: ?>
            <p style="margin-top: 20px;">Tidak ada data tugas untuk divisi ini.</p>
        <?php endif; ?>
    </main>
</div>

<footer class="footer">
    © 2025 PelindoDesk. All Rights Reserved.
</footer>
</body>
</html>
