<?php
// index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang PelindoDesk</title>
    <link rel="stylesheet" href="../assets/css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../assets/css/styleTentangPelindoDesk.css">
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="logo">
            <img src="../assets/img/logo.png" alt="PelindoDesk Logo">
        </div>
        <nav>
            <a href="../karyawan/dashboard.php">Dashboard</a>
            <a href="tentangPelindoDesk.php">Tentang PelindoDesk</a>
            <a href="detailProfil.php" class="icon-link"><span class="icon">👤</span> </a>
        </nav>
    </header>

    <!-- SECTION 1 -->
    <section class="intro">
        <div class="intro-text">
            <h1>Tugas Anda Sangat Penting</h1>
            <p>
                Kami menyediakan layanan monitoring tugas karyawan yang efisien untuk membantu Anda memantau tugas pribadi, divisi, maupun unit.
            </p>
        </div>
        <div class="intro-img">
            <img src="../assets/img/monitoring1.jpg" alt="Ilustrasi Monitoring">
        </div>

        <!-- Wave Decoration -->
    </section>

    <!-- SECTION 2 -->
    <section class="about">
        <div class="about-text">
            <h1>Apa itu PelindoDesk?</h1>
            <p>
                PelindoDesk adalah aplikasi monitoring tugas karyawan berbasis website dan mobile 
                yang dikembangkan untuk mendukung kegiatan operasional PT Pelindo Jasa Maritim Wilayah Tiga. 
                Aplikasi ini berfungsi sebagai media pengelolaan, pelacakan, dan pengawasan tugas karyawan 
                secara terintegrasi.
            </p>
        </div>
        <div class="about-img">
            <img src="../assets/img/monitoring2.png" alt="Ilustrasi Pengawasan">
        </div>
    </section>

    <!-- VISI MISI -->
    <section class="visi-misi">
    <div class="card">
        <div class="icon">
            <img src="../assets/img/vision.png" alt="Icon Visi">
        </div>
        <h2>VISI</h2>
        <p>
            Menjadi aplikasi digital yang handal dalam mendukung pengawasan dan pengelolaan tugas karyawan 
            secara efektif, efisien, dan transparan di lingkungan PT Pelindo Jasa Maritim Wilayah Tiga.
        </p>
    </div>
    <div class="card">
        <div class="icon">
            <img src="../assets/img/mission.png" alt="Icon Misi">
        </div>
        <h2>MISI</h2>
        <p>
            Menyediakan platform monitoring digital yang terintegrasi antara website dan mobile 
            untuk mempermudah akses bagi pimpinan maupun karyawan.
        </p>
    </div>
</section>

    <!-- SECTION 3 -->
    <section class="why">
    <div class="why-card">
        <div class="why-img">
            <img src="../assets/img/woman.png" alt="Karyawan">
        </div>
        <div class="why-text">
            <h1>Kenapa harus PelindoDesk?</h1>
            <p>
                Dengan adanya pengelolaan tugas yang jelas, setiap karyawan bisa mengetahui prioritas kerja, 
                tenggat waktu, dan status penyelesaian. Hal ini membantu meningkatkan produktivitas 
                dan mengurangi risiko pekerjaan terlewat.
            </p>
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
                <a href="dashboard.php">Dashboard</a>
                <a href="tentangPelindoDesk.php">Tentang Kami</a>
                <a href="kontak.php">FAQ</a>
            </div>
            <div class="footer-col">
                <h3>Dukungan</h3>
                <a href="#">Hubungi Kami</a>
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
            <img src="../assets/img/logo.png" alt="PelindoDesk Logo">
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
