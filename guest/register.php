<?php
require_once "../config/db.php";
$error = "";

$upload_dir = "../uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $nip = trim($_POST['nip']);
    $jabatan = trim($_POST['jabatan']);
    $divisi = trim($_POST['divisi']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password !== $konfirmasi) {
        $error = "Kata sandi tidak cocok!";
    } else {
        $foto_nama = "";
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto_nama = uniqid('foto_') . '.' . strtolower($ext);
            $target = $upload_dir . $foto_nama;

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($ext), $allowed)) {
                $error = "Format foto tidak valid (harus JPG, PNG, atau GIF)";
            } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $error = "Ukuran foto terlalu besar (maks 2MB)";
            } else {
                move_uploaded_file($_FILES['foto']['tmp_name'], $target);
            }
        }

        if (empty($error)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $cek = $conn->prepare("SELECT nip FROM users WHERE nip = ?");
            $cek->bind_param("s", $nip);
            $cek->execute();
            $result = $cek->get_result();

            if ($result->num_rows > 0) {
                $error = "NIP sudah terdaftar. Gunakan NIP lain.";
            } else {
                $sql = "INSERT INTO users (nama, nip, jabatan, divisi, email, password, foto)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $nama, $nip, $jabatan, $divisi, $email, $hashed_password, $foto_nama);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit;
                } else {
                    $error = "Gagal menyimpan ke database: " . $conn->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PelindoDesk - Registrasi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fff; text-align: center; }
        header { background-color: #f6ebda; padding: 10px 20px; text-align: left; }
        header img { height: 40px; vertical-align: middle; }
        .container { max-width: 600px; margin: 30px auto; padding: 20px; }
        h2 { font-size: 22px; font-weight: bold; margin-bottom: 25px; }
        .photo-upload {
            display: flex; align-items: center; justify-content: center;
            flex-direction: column; background-color: #eee;
            border-radius: 50%; width: 120px; height: 120px;
            margin: 0 auto 20px auto; font-weight: bold; color: #333;
            cursor: pointer; overflow: hidden; position: relative;
        }
        .photo-upload img { width: 100%; height: 100%; object-fit: cover; display: none; border-radius: 50%; }
        .photo-upload input[type="file"] { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .form-group { display: flex; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        .form-group input, .form-group select {
            width: 100%; padding: 12px;
            border: 1px solid #a7774f; border-radius: 8px; font-size: 14px;
        }
        .form-group-single { margin-bottom: 20px; }
        .form-group-single input, .form-group-single select {
            width: 96%; padding: 12px;
            border: 1px solid #a7774f; border-radius: 8px; font-size: 14px;
        }
        .buttons { margin-top: 25px; display: flex; justify-content: center; gap: 15px; }
        .btn-cancel, .btn-save {
            background-color: #fff; padding: 10px 20px; border-radius: 8px;
            cursor: pointer; font-size: 14px;
        }
        .btn-cancel { color: red; border: 1px solid red; }
        .btn-save { color: #6d432f; border: 1px solid #6d432f; }
        footer { border-top: 1px solid #ccc; margin-top: 40px; padding: 10px; font-size: 13px; text-align: center; }
    </style>
</head>
<body>
    <header>
        <img src="../assets/img/logo.png" alt="PelindoDesk Logo">
    </header>

    <div class="container">
        <h2>Yuk, lengkapi data dirimu</h2>

        <form method="post" enctype="multipart/form-data">
            <div class="photo-upload" id="photo-container">
                <img id="preview" src="#" alt="Preview Foto">
                <span id="upload-text">+<br>Add<br>Photo</span>
                <input type="file" name="foto" id="foto" accept="image/*" required>
            </div>

            <div class="form-group">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
                <input type="text" name="nip" placeholder="NIP" required>
            </div>

            <div class="form-group">
                <!-- Dropdown Jabatan -->
                <select name="jabatan" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Manajer">Manajer</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Koordinator">Koordinator</option>
                    <option value="Staf">Staf</option>
                </select>

                <!-- Dropdown Divisi -->
                <select name="divisi" required>
                    <option value="">-- Pilih Divisi --</option>
                    <option value="Keuangan">Keuangan</option>
                    <option value="Operasional">Operasional</option>
                    <option value="Pelayanan Kapal">Pelayanan Kapal</option>
                </select>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Kata Sandi" required>
                <input type="password" name="konfirmasi" placeholder="Konfirmasi Kata Sandi" required>
            </div>

            <div class="form-group-single">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="buttons">
                <button type="button" class="btn-cancel" onclick="history.back()">✖ Batal</button>
                <button type="submit" class="btn-save">💾 Simpan</button>
            </div>
        </form>

        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>

    <footer>© 2025 PelindoDesk. All Rights Reserved.</footer>

    <script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        const uploadText = document.getElementById('upload-text');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                uploadText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "#";
            preview.style.display = 'none';
            uploadText.style.display = 'block';
        }
    });
    </script>
</body>
</html>
