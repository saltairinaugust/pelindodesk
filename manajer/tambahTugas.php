<?php
session_start();
require_once "../config/db.php"; // pastikan path sesuai struktur folder kamu
$error = "";

// Jika user belum login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// ====== PROSES PENYIMPANAN DATA KE DATABASE ======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['namaPJ'];
    $jabatan = $_POST['jabatan'];
    $nip = $_POST['nip'];
    $link = $_POST['link'];
    $judul = $_POST['judulTugas'];
    $deskripsi = $_POST['deskripsi'];
    $prioritas = $_POST['prioritas'];
    $status = $_POST['status'];
    $tenggat = $_POST['tenggat'];
    $unit = $_POST['timTerkait'];
    $komentar = $_POST['komentar'];

    // Simpan ke tabel tugas_karyawan
    $stmt = $conn->prepare("INSERT INTO tugas_karyawan (nama, jabatan, nip, link_drive, judul, deskripsi, prioritas, status, tenggat, unit, komentar)VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", 
        $nama, $jabatan, $nip, $link, $judul, $deskripsi, $prioritas, $status, $tenggat, $unit, $komentar);

    if ($stmt->execute()) {
        header("Location: daftarTugasKaryawan.php?sukses=1");
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $stmt->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PelindoDesk - Tambah Tugas Karyawan</title>
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
                <li class="active"><a href="tambahTugas.php"><span class="icon">📥</span> Tambah Tugas</a></li>
                <li><a href="daftarTugas.php"><span class="icon">👥</span> Daftar Tugas Karyawan</a></li>
                <li><a href="rekapDivisi.php"><span class="icon">📊</span> Rekap Tugas Divisi</a></li>
            </ul>
            <button class="logout" onclick="window.location.href='../guest/login.php'">Keluar</button>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content">
            <h1 style="text-align: center; font-size: 26px; margin-bottom: 25px;">Isi Detail Tugas Dulu Ya</h1>

            <!-- FORM TAMBAH TUGAS -->
            <form method="POST" action="" class="detail-tugas">
                <div class="row">
                    <label class="label">Link Drive</label>
                    <div class="value"><input type="text" name="link" placeholder="Link Drive" required></div>

                    <label class="label">Nama PJ</label>
                    <div class="value"><input type="text" name="namaPJ" placeholder="Nama Penanggung Jawab" required></div>

                    <label class="label">Jabatan</label>
                    <div class="value"><input type="text" name="jabatan" placeholder="Jabatan" required></div>
                    
                    <label class="label">NIP</label>
                    <div class="value"><input type="text" name="nip" placeholder="NIP" required></div>

                    <label class="label">Judul Tugas</label>
                    <div class="value"><input type="text" name="judulTugas" placeholder="Judul Tugas" required></div>

                    <label class="label">Deskripsi Singkat</label>
                    <div class="value"><input type="text" name="deskripsi" placeholder="Deskripsi Singkat"></div>
                </div>

                <div class="row">
                    <label class="label">Tingkat Prioritas</label>
                    <div class="prioritas-container">
                        <label><input type="radio" name="prioritas" value="Tinggi" required> Tinggi</label>
                        <label><input type="radio" name="prioritas" value="Sedang"> Sedang</label>
                        <label><input type="radio" name="prioritas" value="Rendah"> Rendah</label>
                    </div>

                    <label class="label">Status Tugas</label>
                    <div class="value">
                        <select name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                            <option value="Revisi">Revisi</option>
                            <option value="Ditunda">Ditunda</option>
                            <option value="Menunggu Persetujuan">Menunggu Persetujuan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label class="label">Tenggat</label>
                    <div class="value"><input type="date" name="tenggat" required></div>

                    <label class="label">Divisi Terkait</label>
                    <div class="value">
                        <select name="timTerkait" required>
                            <option value="">Pilih Divisi</option>
                            <option value="Keuangan">Keuangan</option>
                            <option value="Operasional">Operasional</option>
                            <option value="Pelayanan Kapal">Pelayanan Kapal</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label class="label">Komentar (Opsional)</label>
                    <div class="value"><textarea name="komentar" placeholder="Tambahkan catatan atau komentar..."></textarea></div>
                </div>

                <!-- TOMBOL AKSI -->
                <div style="justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn batal" onclick="window.location.href='daftarTugasKaryawan.php'">❌ Batal</button>
                    <button type="submit" class="btn simpan">💾 Simpan</button>
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
