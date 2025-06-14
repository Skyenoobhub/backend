<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'travelapps';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM paket WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_paket = $_POST['nama_paket'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $fasilitas = $_POST['fasilitas'];
    $rincian = $_POST['rincian'];

    // Update query
    $update_sql = "UPDATE paket SET nama_paket = ?, harga = ?, deskripsi = ?, fasilitas = ?, rincian = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssi", $nama_paket, $harga, $deskripsi, $fasilitas, $rincian, $id);
    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect back to the main page after successful update
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket Wisata</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 32px;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: inline-block;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        input:focus, textarea:focus {
            border-color: #007BFF;
            outline: none;
        }
        textarea {
            resize: vertical;
            height: 150px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 20px;
            display: block;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group:last-child {
            margin-bottom: 0;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
            color: #007BFF;
            text-decoration: none;
        }
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Paket Wisata</h1>

        <form action="update.php?id=<?php echo $row['id']; ?>" method="POST">
            
            <div class="form-group">
                <label for="nama_paket">Nama Paket:</label>
                <input type="text" id="nama_paket" name="nama_paket" value="<?php echo htmlspecialchars($row['nama_paket']); ?>" required>
            </div>

            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="text" id="harga" name="harga" value="<?php echo htmlspecialchars($row['harga']); ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" required><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="fasilitas">Fasilitas:</label>
                <textarea id="fasilitas" name="fasilitas" required><?php echo htmlspecialchars($row['fasilitas']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="rincian">Rincian:</label>
                <textarea id="rincian" name="rincian" required><?php echo htmlspecialchars($row['rincian']); ?></textarea>
            </div>

            <button type="submit">Update Paket</button>

        </form>

        <a href="index.php" class="back-btn">Kembali ke Daftar Paket</a>
    </div>

</body>
</html>
