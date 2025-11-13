<?php

session_start();
require_once "../config/db.php"; 

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil NIP dari session login
$nip = $_SESSION['user']['nip'];

// === Ambil data user dari database ===
$stmt = $conn->prepare("SELECT * FROM users WHERE nip = ?");
$stmt->bind_param("s", $nip);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    echo "Data pengguna tidak ditemukan di database.";
    exit;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna - PelindoDesk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
            background-color: #fff;
            text-align: center;
        }
        header {
            background-color: #f6ebda;
            padding: 10px 20px;
            text-align: left;
        }
        header img {
            height: 40px;
            vertical-align: middle;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
        }
        h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .photo-upload {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #eee;
            border-radius: 50%;
            width: 150px; 
            height: 150px;
            margin: 0 auto 20px auto;
            font-weight: bold;
            color: #333;
            position: relative;
            overflow: hidden;
        }
        .photo-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
        .profile-info {
            text-align: left;
            margin: 0 auto;
            max-width: 400px;
        }
        .profile-item {
            margin-bottom: 15px;
            font-size: 20px;
        }
        .profile-item span {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            color: #6d432f;
        }
        .buttons {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn-edit, .btn-kembali {
            background-color: #fff;
            color: #6d432f;
            border: 1px solid #6d432f;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        footer {
            border-top: 1px solid #ccc;
            margin-top: 40px;
            padding: 10px;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <img src="../assets/img/logo.png" alt="PelindoDesk Logo">
    </header>

    <div class="container">
        <h2>Profil Pengguna</h2>

        <div class="photo-upload">
            <?php if (!empty($userData['foto'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($userData['foto']); ?>" alt="Foto Profil">
            <?php else: ?>
                <img src="../assets/img/default-user.png" alt="Foto Default">
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <div class="profile-item"><span>Nama:</span> <?php echo htmlspecialchars($userData['nama']); ?></div>
            <div class="profile-item"><span>NIP:</span> <?php echo htmlspecialchars($userData['nip']); ?></div>
            <div class="profile-item"><span>Jabatan:</span> <?php echo htmlspecialchars($userData['jabatan']); ?></div>
            <div class="profile-item"><span>Divisi:</span> <?php echo htmlspecialchars($userData['divisi']); ?></div>
            <div class="profile-item"><span>Email:</span> <?php echo htmlspecialchars($userData['email']); ?></div>
        </div>

        <div class="buttons">
            <a href="javascript:history.back()" class="btn-kembali">🔙 Kembali</a>
        </div>
    </div>

    <footer>
        © 2025 PelindoDesk. All Rights Reserved.
    </footer>
</body>
</html>
