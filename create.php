<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'travelapps';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$uploadSuccess = false;
$uploadError = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_paket = htmlspecialchars($_POST['nama_paket']);
    $harga = htmlspecialchars($_POST['harga']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $fasilitas = htmlspecialchars($_POST['fasilitas']);
    $rincian = htmlspecialchars($_POST['rincian']);

    $foto = uniqid() . '-' . basename($_FILES['foto']['name']);
    $target = __DIR__ . "/uploads/" . $foto;

    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $uploadError = true;
    }

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $fileType = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
    if (!in_array($fileType, $allowedTypes)) {
        $uploadError = true;
    }

    if (!$uploadError && move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        $uploadSuccess = true;

        $stmt = $conn->prepare("INSERT INTO paket (nama_paket, harga, deskripsi, fasilitas, foto, rincian) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nama_paket, $harga, $deskripsi, $fasilitas, $foto, $rincian);

        if (!$stmt->execute()) {
            $uploadError = true;
        }

        $stmt->close();
    } else {
        $uploadError = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Paket Wisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-ZT2KWDV2TTOnQ2uvUCq8b3Vj5X3vn4Rx1LKo3QefF+bB5MfyzsHzJbW+ipR16rpNsvwl3mOAnOtxW5Cr6a3x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            color: #007BFF;
            font-size: 28px;
            margin-bottom: 30px;
            position: relative;
        }

        h2::after {
            content: '';
            width: 60px;
            height: 3px;
            background-color: #007BFF;
            display: block;
            margin: 10px auto 0;
            border-radius: 5px;
        }

        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
            color: #444;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #007BFF;
        }

        input, textarea, button {
            width: 100%;
            padding: 10px 10px 10px 35px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: #f8f9fa;
            transition: 0.3s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #007BFF;
            background-color: #fff;
        }

        textarea {
            resize: vertical;
        }

        input[type="file"] {
            padding-left: 10px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            margin-top: 20px;
            padding: 12px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 6px;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .divider {
            margin: 25px 0;
            height: 1px;
            background-color: #ddd;
        }

        .success-message, .error-message {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            font-weight: bold;
            border-radius: 6px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .display-data {
            margin-top: 20px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .display-data img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }

        .display-data p {
            margin: 8px 0;
            line-height: 1.5;
        }

        .display-data strong {
            color: #007BFF;
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 22px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-plane-departure"></i> Tambah Paket Wisata</h2>

        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nama_paket"><i class="fas fa-tag"></i> Nama Paket</label>
                <input type="text" name="nama_paket" id="nama_paket" required>
            </div>

            <div class="input-group">
                <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga</label>
                <input type="number" name="harga" id="harga" required>
            </div>

            <div class="input-group">
                <label for="deskripsi"><i class="fas fa-align-left"></i> Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" required></textarea>
            </div>

            <div class="input-group">
                <label for="fasilitas"><i class="fas fa-concierge-bell"></i> Fasilitas</label>
                <textarea name="fasilitas" id="fasilitas" required></textarea>
            </div>

            <div class="input-group">
                <label for="foto"><i class="fas fa-image"></i> Foto</label>
                <input type="file" name="foto" id="foto" required>
            </div>

            <div class="input-group">
                <label for="rincian"><i class="fas fa-info-circle"></i> Rincian</label>
                <textarea name="rincian" id="rincian" required></textarea>
            </div>

            <button type="submit"><i class="fas fa-plus-circle"></i> Tambah Paket</button>
        </form>

        <?php if($uploadSuccess): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Paket berhasil ditambahkan!
            </div>
            <div class="divider"></div>
            <div class="display-data">
                <p><strong>Nama Paket:</strong> <?= $nama_paket ?></p>
                <p><strong>Harga:</strong> Rp <?= number_format($harga, 0, ',', '.') ?></p>
                <p><strong>Deskripsi:</strong> <?= $deskripsi ?></p>
                <p><strong>Fasilitas:</strong> <?= $fasilitas ?></p>
                <p><strong>Rincian:</strong> <?= $rincian ?></p>
                <p><strong>Foto:</strong></p>
                <img src="uploads/<?= $foto ?>" alt="Foto Paket">
            </div>
        <?php elseif ($uploadError): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> Terjadi kesalahan saat upload data.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
