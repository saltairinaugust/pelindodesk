<?php
session_start();
require_once "../config/db.php"; // pastikan path benar (ubah sesuai struktur folder)

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user']['nama'];

// === FILTERING DATA ===
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$unit = isset($_GET['unit']) ? trim($_GET['unit']) : '';

// === QUERY DASAR ===
$query = "SELECT * FROM tugas_karyawan WHERE 1=1";

// === TAMBAHKAN FILTER ===
if ($search !== '') {
    $searchParam = "%{$search}%";
    $query .= " AND (nama LIKE ? OR judul LIKE ?)";
}
if ($status !== '') {
    $query .= " AND status = ?";
}
if ($unit !== '') {
    $query .= " AND unit = ?";
}

// === PERSIAPAN STATEMENT ===
$stmt = $conn->prepare($query);

// === BIND PARAM SESUAI FILTER ===
$params = [];
$types = "";

if ($search !== '') {
    $types .= "ss";
    $params[] = &$searchParam;
    $params[] = &$searchParam;
}
if ($status !== '') {
    $types .= "s";
    $params[] = &$status;
}
if ($unit !== '') {
    $types .= "s";
    $params[] = &$unit;
}

if (!empty($params)) {
    array_unshift($params, $types);
    call_user_func_array([$stmt, 'bind_param'], $params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas Karyawan - PelindoDesk</title>
    <link rel="stylesheet" href="../assets/css/style4.css">
</head>
<body>
    <!-- HEADER -->
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
                <?php
                    $username = $_SESSION['user']['nama'];
                ?>
                <span class="username">
                    <a href="../includes/detailProfil.php" class="username-link">👤 <?= htmlspecialchars($username) ?></a>
                </span>
            </div>
            <ul class="menu">
                <li><a href="../includes/tentangPelindoDesk.php"><span class="icon">ℹ️</span> Tentang PelindoDesk</a></li>
                <li class="active"><a href="daftarTugas.php"><span class="icon">👥</span> Daftar Tugas Karyawan</a></li>
                <li><a href="rekapDivisi.php"><span class="icon">📊</span> Rekap Tugas Divisi</a></li>
            </ul>
            <button class="logout" onclick="window.location.href='../guest/login.php'">Keluar</button>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content">
            <h1>Daftar Tugas Karyawan</h1>

            <!-- FILTER DAN PENCARIAN -->
            <div class="filter">
                <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="🔍 Cari PJ atau Tugas.." 
                        value="<?= htmlspecialchars($search) ?>" 
                        style="padding: 6px 10px; border: 1px solid #d9b48f; border-radius: 6px; background: #f4e3c5; font-weight: bold;"
                    >

                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        <option value="Revisi" <?= $status == 'Revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="Ditunda" <?= $status == 'Ditunda' ? 'selected' : '' ?>>Ditunda</option>
                        <option value="Sedang Dikerjakan" <?= $status == 'Sedang Dikerjakan' ? 'selected' : '' ?>>Dikerjakan</option>
                        <option value="Belum Dikerjakan" <?= $status == 'Belum Dikerjakan' ? 'selected' : '' ?>>Belum Dikerjakan</option>
                    </select>

                    <select name="unit">
                        <option value="">Semua Unit</option>
                        <option value="Pelayanan Kapal" <?= $unit == 'Pelayanan Kapal' ? 'selected' : '' ?>>Pelayanan Kapal</option>
                        <option value="Keuangan" <?= $unit == 'Keuangan' ? 'selected' : '' ?>>Keuangan</option>
                        <option value="Operasional" <?= $unit == 'Operasional' ? 'selected' : '' ?>>Operasional</option>
                    </select>

                    <button type="submit" style="padding: 6px 12px; border: 1px solid #946D43; background: #fff; border-radius: 6px; cursor: pointer; color: #946D43; font-weight: bold;">
                        Tampilkan
                    </button>

                    <button type="button" onclick="window.location.href='daftarTugasKaryawan.php'" 
                        style="padding: 6px 12px; border: 1px solid #da0505; background: #fff; border-radius: 6px; cursor: pointer; color: #da0505; font-weight: bold;">
                        Reset Filter
                    </button>
                </form>
            </div>

            <!-- TABEL TUGAS -->
            <table class="tabel-tugas">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIP</th>
                        <th>Nama PJ</th>
                        <th>Tenggat</th>
                        <th>Judul Tugas</th>
                        <th>Status</th>
                        <th>Divisi Terkait</th>
                        <th>Lihat Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($result->num_rows === 0): ?>
                        <tr><td colspan="8">Tidak ada data ditemukan.</td></tr>
                    <?php else: 
                        while ($t = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($t["nip"]); ?></td>
                            <td><?= htmlspecialchars($t["nama"]); ?></td>
                            <td><?= htmlspecialchars($t["tenggat"]); ?></td>
                            <td><?= htmlspecialchars($t["judul"]); ?></td>
                            <td>
                                <span class="status <?= strtolower(str_replace(' ', '-', $t["status"])); ?>">
                                    <?= htmlspecialchars($t["status"]); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($t["unit"]); ?></td>
                            <td class="Lihat Detail">
                                <button class="btn info" onclick="window.location.href='detailTugas.php?id_tugas=<?= $t['id_tugas'] ?>'">ℹ️</button>

                            </td>
                        </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </main>
    </div>

    <footer class="footer">
        © 2025 PelindoDesk. All Rights Reserved.
    </footer>
</body>
</html>
