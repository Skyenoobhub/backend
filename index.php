<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'travelapps';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

$sql = "SELECT * FROM paket LIMIT $start_from, $limit";
$result = $conn->query($sql);

// Total data
$total_result = $conn->query("SELECT COUNT(id) AS total FROM paket");
$row = $total_result->fetch_assoc();
$total_pages = ceil($row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Paket Wisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 32px;
            color: #007BFF;
        }

        .add-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .add-button i {
            margin-right: 6px;
        }

        .add-button:hover {
            background-color: #0056b3;
        }

        .card {
            display: flex;
            gap: 20px;
            padding: 20px;
            overflow-x: auto;
            margin: 0 auto;
            max-width: 1200px;
        }

        .card-item {
            width: 300px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            flex-shrink: 0;
            transition: transform 0.3s;
        }

        .card-item:hover {
            transform: translateY(-5px);
        }

        .card-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .card-item .content {
            padding: 15px;
        }

        .card-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }

        .card-item p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .price {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .action-links {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 12px;
        }

        .action-links a {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            font-size: 13px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edit {
            background-color: #17a2b8;
        }

        .edit:hover {
            background-color: #138496;
        }

        .delete {
            background-color: #dc3545;
        }

        .delete:hover {
            background-color: #c82333;
        }

        .pagination {
            text-align: center;
            margin: 30px 0;
        }

        .pagination a {
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            margin: 0 4px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .active {
            background-color: #28a745;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

    <h1><i class="fas fa-map-marked-alt"></i> Daftar Paket Wisata</h1>

    <a href="create.php" class="add-button"><i class="fas fa-plus-circle"></i> Tambah Paket Wisata</a>

    <div class="card">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card-item">
                <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Paket">
                <div class="content">
                    <h3><?php echo htmlspecialchars($row['nama_paket']); ?></h3>
                    <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                    <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <div class="action-links">
                        <a href="update.php?id=<?php echo $row['id']; ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Hapus paket ini?');"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

</body>
</html>
