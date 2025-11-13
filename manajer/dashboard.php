<?php

session_start();
require_once "../config/db.php"; // pastikan path-nya benar

// Jika user belum login, arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil email user dari session (biasanya disimpan saat login)
$email = $_SESSION['user']['email'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT nama, jabatan, divisi FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $username = $userData['nama'];
    $jabatan = $userData['jabatan'];
    $divisi = $userData['divisi'];
} else {
    // Jika data user tidak ditemukan, logout otomatis
    session_destroy();
    header("Location: login.php");
    exit();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - PelindoDesk</title>
  <link rel="stylesheet" href="../assets/css/styleHeaderFooter.css">
  <link rel="stylesheet" href="../assets/css/styleDashboard.css">
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="logo">
      <img src="../assets/img/logo.png" alt="PelindoDesk Logo">
    </div>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="tentangPelindoDesk.php">Tentang PelindoDesk</a>
      <a href="kontak.php">Kontak</a>
      <a href="detailProfil.php" class="icon-link"><span class="icon">👤</span></a>
    </nav>
  </header>

  <!-- HERO -->
  <section class="hero">
    <h1>Dashboard</h1>
    <p>Hai, <?= htmlspecialchars($username) ?>! Jabatan kamu: <?= htmlspecialchars($jabatan) ?> - <?= htmlspecialchars($divisi) ?>.</p>
    <p>Yuk, semangat kerjain tugasmu 💪</p>
  </section>

  <!-- AKTIVITAS -->
  <section class="aktivitas">
    <h2>Hari ini kamu mau ngapain nih?</h2>
    <div class="aktivitas-grid">
      <?php if (strtolower($jabatan) !== 'staf'): ?>
        <a href="tambahTugasKaryawan.php" class="aktivitas-card">
          <img src="../assets/img/add-task.jpg" alt="Tambah Tugas">
          <p>Tambah Tugas Karyawan</p>
        </a>
      <?php endif; ?>
      <a href="daftarTugasKaryawan.php" class="aktivitas-card">
        <img src="../assets/img/list-task.jpg" alt="Daftar Tugas">
        <p>Daftar Tugas Karyawan</p>
      </a>
      <a href="rekapTugasPribadi.php" class="aktivitas-card">
        <img src="../assets/img/rekap.jpg" alt="Rekap Tugas">
        <p>Rekap Tugas Divisi</p>
      </a>
    </div>
  </section>

  <!-- TIPS -->
  <section class="tips">
    <div class="tips-card">
      <img src="../assets/img/businessman.png" alt="Businessman">
      <div class="tips-text">
        <h2>Tips untuk <span>Memaksimalkan</span> Kinerja Tugas:</h2>
        <ul>
          <li>1. Urutkan tugas berdasarkan tingkat urgensi</li>
          <li>2. Buat batas waktu penyelesaian tugas</li>
          <li>3. Jalin komunikasi yang jelas dan rutin</li>
          <li>4. Fokus pada satu tugas hingga selesai dulu</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-top">
      <div class="footer-col">
        <h3>Butuh Bantuan?</h3>
        <p>Kami siap membantu! Jika ada pertanyaan atau butuh informasi lebih lanjut, hubungi tim kami untuk solusi terbaik.</p>
        <div class="subscribe">
          <input type="email" placeholder="Email">
          <button>→</button>
        </div>
      </div>
      <div class="footer-col">
        <h3>Halaman</h3>
        <a href="logout.php">Log Out</a>
        <a href="tentangPelindoDesk.php">Tentang Kami</a>
        <a href="kontak.php">FAQ</a>
      </div>
      <div class="footer-col">
        <h3>Dukungan</h3>
        <a href="kontak.php">Hubungi Kami</a>
        <a href="#">Pusat Dukungan</a>
        <a href="#">Privasi dan Kebijakan</a>
      </div>
      <div class="footer-col">
        <h3>Mitra</h3>
        <a href="#">Mitra Kami</a>
        <a href="#">Komunitas</a>
        <a href="#">Investor</a>
      </div>
    </div>

    <div class="footer-bottom">
      <img src="assets/img/logo.png" alt="PelindoDesk Logo">
      <p>© 2025 PelindoDesk. All Rights Reserved.</p>
      <div class="socials">
        <a href="#">📘</a>
        <a href="#">🐦</a>
        <a href="#">📸</a>
        <a href="#">💬</a>
      </div>
    </div>
  </footer>
</body>
</html>
