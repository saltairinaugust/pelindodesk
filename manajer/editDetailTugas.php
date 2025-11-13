<?php
require_once "../config/db.php"; // koneksi database
session_start();

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil id_tugas dari URL
$id_tugas = $_GET['id_tugas'] ?? null;
if (!$id_tugas) {
    echo "<h2>ID Tugas tidak ditemukan!</h2>";
    exit;
}

// Jika form disubmit → proses update ke database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $link_drive = $_POST['link'];
    $deskripsi = $_POST['deskripsi'];
    $prioritas = $_POST['prioritas'];
    $status = $_POST['status'];
    $tenggat = $_POST['tenggat'];
    $unit = $_POST['unit'];
    $komentar = $_POST['komentar'];

    $sql = "UPDATE tugas_karyawan 
            SET judul = ?, link_drive = ?, deskripsi = ?, prioritas = ?, status = ?, tenggat = ?, unit = ?, komentar = ?
            WHERE id_tugas = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $judul, $link_drive, $deskripsi, $prioritas, $status, $tenggat, $unit, $komentar, $id_tugas);

    if ($stmt->execute()) {
        header("Location: detailTugas.php?id_tugas=$id_tugas&update=1");
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan perubahan!');</script>";
    }
}

// Ambil data tugas berdasarkan ID
$sql = "SELECT * FROM tugas_karyawan WHERE id_tugas = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tugas);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_assoc();

if (!$detail) {
    echo "<h2>Data tugas tidak ditemukan!</h2>";
    exit;
}

$nama = $detail['nama'];
$nip = $detail['nip'];
$jabatan = $detail['jabatan'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas Karyawan - PelindoDesk</title>
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
                <span class="username">
                    <a href="../includes/detailProfil.php" class="username-link">
                        👤 <?= htmlspecialchars($_SESSION['user']['nama']) ?>
                    </a>
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
            <h1>Edit Detail Tugas</h1>
            <h3><?= htmlspecialchars($nama) ?> <span class="tag">#Penanggung Jawab</span></h3>
            <h3><?= htmlspecialchars($jabatan) ?> <span class="tag">#Jabatan</span></h3>
            <h2><?= htmlspecialchars($nip) ?> <span class="tag">#NIP</span></h2>

            <!-- FORM EDIT -->
            <form method="POST" class="detail-tugas">
                <div class="row">
                    <label class="label">Judul Tugas</label>
                    <div class="value"><input type="text" name="judul" value="<?= htmlspecialchars($detail['judul']) ?>" required></div>

                    <label class="label">Link Drive</label>
                    <div class="value"><input type="url" name="link" value="<?= htmlspecialchars($detail['link_drive']) ?>"></div>

                    <label class="label">Deskripsi Singkat</label>
                    <div class="value"><textarea name="deskripsi" required><?= htmlspecialchars($detail['deskripsi']) ?></textarea></div>
                </div>

                <div class="row">
                    <label class="label">Tingkat Prioritas</label>
                    <div class="prioritas-container">
                        <label><input type="radio" name="prioritas" value="Tinggi" <?= ($detail['prioritas'] == 'Tinggi') ? 'checked' : '' ?>> Tinggi</label>
                        <label><input type="radio" name="prioritas" value="Sedang" <?= ($detail['prioritas'] == 'Sedang') ? 'checked' : '' ?>> Sedang</label>
                        <label><input type="radio" name="prioritas" value="Rendah" <?= ($detail['prioritas'] == 'Rendah') ? 'checked' : '' ?>> Rendah</label>
                    </div>

                    <label class="label">Status Tugas</label>
                    <div class="value">
                        <select name="status" required>
                            <option value="Belum Dikerjakan" <?= ($detail['status']=='Belum Dikerjakan')?'selected':'' ?>>Belum Dikerjakan</option>
                            <option value="Sedang Dikerjakan" <?= ($detail['status']=='Sedang Dikerjakan')?'selected':'' ?>>Sedang Dikerjakan</option>
                            <option value="Revisi" <?= ($detail['status']=='Revisi')?'selected':'' ?>>Revisi</option>
                            <option value="Ditunda" <?= ($detail['status']=='Ditunda')?'selected':'' ?>>Ditunda</option>
                            <option value="Menunggu Persetujuan" <?= ($detail['status']=='Menunggu Persetujuan')?'selected':'' ?>>Menunggu Persetujuan</option>
                            <option value="Selesai" <?= ($detail['status']=='Selesai')?'selected':'' ?>>Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label class="label">Tenggat</label>
                    <div class="value"><input type="date" name="tenggat" value="<?= htmlspecialchars($detail['tenggat']) ?>" required></div>

                    <label class="label">Divisi Terkait</label>
                    <div class="value">
                        <select name="unit" required>
                            <option value="Keuangan" <?= ($detail['unit']=='Keuangan')?'selected':'' ?>>Keuangan</option>
                            <option value="Operasional" <?= ($detail['unit']=='Operasional')?'selected':'' ?>>Operasional</option>
                            <option value="Pelayanan Kapal" <?= ($detail['unit']=='Pelayanan Kapal')?'selected':'' ?>>Pelayanan Kapal</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label class="label">Komentar (Opsional)</label>
                    <div class="value"><textarea name="komentar"><?= htmlspecialchars($detail['komentar']) ?></textarea></div>
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <button type="button" class="btn batal" onclick="javascript:history.back()">❌ Batal</button>
                    <button type="submit" class="btn simpan">💾 Simpan Perubahan</button>
                </div>
            </form>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        © 2025 PelindoDesk. All Rights Reserved.
    </footer>
</body>
</html>
