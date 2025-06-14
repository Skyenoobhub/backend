<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'travelapps';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil paket yang sudah difavoritkan
$sql = "SELECT p.id, p.nama_paket, p.harga, p.foto 
        FROM favorites f 
        JOIN paket p ON f.package_id = p.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket Favorit</title>
    <style>
        /* Style seperti sebelumnya untuk desain yang konsisten */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        h1 {
            text-align: center;
            margin-top: 50px;
            font-size: 36px;
            color: #333;
        }

        .card {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .card-item {
            width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .card-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-item .content {
            padding: 15px;
        }

        .card-item h3 {
            font-size: 18px;
            color: #333;
        }

        .card-item .price {
            font-size: 16px;
            color: #4CAF50;
        }
    </style>
</head>
<body>

    <h1>Paket Favorit</h1>

    <div class="card">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card-item">
                <img src="uploads/<?php echo $row['foto']; ?>" alt="Foto Paket">
                <div class="content">
                    <h3><?php echo $row['nama_paket']; ?></h3>
                    <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
