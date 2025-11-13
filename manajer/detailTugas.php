<?php
require_once "../config/db.php"; // koneksi database
session_start();

// Jika user belum login, arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil id_tugas dari URL (misal: detailTugasKaryawan.php?id_tugas=5)
$id_tugas = $_GET['id_tugas'] ?? null;

// Jika tidak ada id_tugas di URL
if (!$id_tugas) {
    echo "<h2>ID Tugas tidak ditemukan!</h2>";
    exit;
}

// Query ambil data tugas berdasarkan ID
$sql = "SELECT * FROM tugas_karyawan WHERE id_tugas = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tugas);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_assoc();

// Jika tidak ditemukan
if (!$detail) {
    echo "<h2>Data tugas tidak ditemukan!</h2>";
    exit;
}

// Siapkan data tugas dengan label
$tugas = [
    "Judul Tugas" => $detail['judul'] ?? '-',
    "Link Drive" => $detail['link_drive'] ?? '-',
    "Deskripsi Singkat" => $detail['deskripsi'] ?? '-',
    "Tingkat Prioritas" => $detail['prioritas'] ?? '-',
    "Status Tugas" => $detail['status'] ?? '-',
    "Tenggat" => $detail['tenggat'] ?? '-',
    "Divisi Terkait" => $detail['unit'] ?? '-',
    "Komentar (opsional)" => $detail['komentar'] ?? '-'
];

$nama = $detail['nama'] ?? 'Tidak diketahui';
$nip = $detail['nip'] ?? 'Tidak diketahui';
$jabatan = $detail['jabatan'] ?? 'Tidak diketahui';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tugas Karyawan - PelindoDesk</title>
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
                <li><a href="tambahTugas.php"><span class="icon">📥</span> Tambah Tugas</a></li>
                <li class="active"><a href="daftarTugas.php"><span class="icon">👥</span> Daftar Tugas Karyawan</a></li>
                <li><a href="rekapDivisi.php"><span class="icon">📊</span> Rekap Tugas Divisi</a></li>
            </ul>
            <button class="logout" onclick="window.location.href='../guest/login.php'">Keluar</button>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content">
            <h1>Detail Tugas Karyawan</h1>
            <h3><?= htmlspecialchars($nama) ?> <span class="tag">#Penanggung Jawab</span></h3>
            <h3><?= htmlspecialchars($jabatan) ?> <span class="tag">#Jabatan</span></h3>
            <h2><?= htmlspecialchars($nip) ?> <span class="tag">#NIP</span></h2>

            <div class="task-detail">
                <?php foreach ($tugas as $label => $isi): ?>
                    <div class="row">
                        <div class="label"><?= htmlspecialchars($label) ?>:</div>
                        <div class="value <?= ($label === 'Link Drive') ? 'link-drive' : '' ?>">
                            <?php if ($label === 'Link Drive' && $isi !== '-' && $isi !== ''): ?>
                                <a href="<?= htmlspecialchars($isi) ?>" target="_blank">Klik Di Sini</a>
                            <?php else: ?>
                                <?= htmlspecialchars($isi) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="btn edit" onclick="window.location.href='editDetailTugas.php?id_tugas=<?= urlencode($id_tugas) ?>'">
                ✏️ Edit Detail Tugas
            </button>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        © 2025 PelindoDesk. All Rights Reserved.
    </footer>
</body>
</html>
