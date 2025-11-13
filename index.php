<?php
session_start();

// Jika user sudah login, arahkan sesuai jabatan
if (isset($_SESSION['user'])) {
    $jabatan = $_SESSION['user']['jabatan'] ?? '';

    if (strtolower($jabatan) === 'manajer') {
        header("Location: manajer/daftarTugas.php");
        exit();
    } else {
        header("Location: karyawan/dashboard.php");
        exit();
    }
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: guest/login.php");
    exit();
}
?>
