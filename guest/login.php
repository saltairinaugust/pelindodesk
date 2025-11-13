<?php
session_start();
require_once "../config/db.php"; // pastikan path benar

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip = trim($_POST['nip']);
    $password = trim($_POST['password']);

    if (empty($nip) || empty($password)) {
        $error = "Harap isi semua kolom!";
    } else {
        // Ambil data user berdasarkan NIP
        $stmt = $conn->prepare("SELECT * FROM users WHERE nip = ?");
        $stmt->bind_param("s", $nip);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {

                // Simpan data user ke session
                $_SESSION['user'] = [
                    'nama' => $user['nama'],
                    'nip' => $user['nip'],
                    'jabatan' => $user['jabatan'],
                    'divisi' => $user['divisi'],
                    'email' => $user['email'],
                    'foto' => $user['foto']
                ];

                // === CEK JABATAN ===
                if (strtolower($user['jabatan']) === "manajer") {
                    header("Location: ../manajer/daftarTugas.php");
                } else {
                    header("Location: ../karyawan/dashboard.php");
                }
                exit();
            } else {
                $error = "Kata sandi salah!";
            }
        } else {
            $error = "NIP tidak ditemukan!";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | PelindoDesk</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Kolom Gambar -->
        <div class="image-side">
            <img src="../assets/img/ilustrasi.png" alt="Ilustrasi Login">
        </div>

        <!-- Kolom Form -->
        <div class="form-side">
            <div class="logo">
                <img src="../assets/img/logo.png" alt="PelindoDesk">
            </div>
            <h2>Halo,<br><span>Selamat Datang Kembali!</span></h2>
            <p>Masukkan NIP dan kata sandi untuk melanjutkan akses.</p>

            <form method="POST" action="">
                <div class="input-group">
                    <input type="text" id="nip" name="nip" placeholder="NIP" required>
                </div>

                <div class="input-group password-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="toggle-password" onclick="togglePassword()"></span>
                </div>

                <div class="extra-container">
                    <div class="extra-register">
                        <a href="register.php">Belum Punya Akun?</a>
                    </div>
                    <div class="extra">
                        <a href="#">Ubah Kata Sandi?</a>
                    </div>
                </div>

                <?php if (!empty($error)) { ?>
                    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
                <?php } ?>

                <button type="submit">➔ Masuk</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById("password");
            pwd.type = pwd.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
