<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'travelapps');
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

// Pagination paket
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$paketRes = $conn->query("SELECT * FROM paket LIMIT $start, $limit");
$totalPaket = $conn->query("SELECT COUNT(*) as total FROM paket")->fetch_assoc()['total'];
$totalPages = ceil($totalPaket / $limit);

// Data user dan admin
$userRes = $conn->query("SELECT id, name, email, phone, gender, created_at FROM users");
$adminRes = $conn->query("SELECT id, nama, email FROM admin");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* (CSS tidak diubah dari versi sebelumnya untuk mempertahankan struktur tampilan dashboard) */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f4f6f8;
    display: flex;
    height: 100vh;
}
.sidebar {
    width: 220px;
    background: #1565c0;
    color: #fff;
    display: flex;
    flex-direction: column;
    padding-top: 30px;
}
.sidebar h2 {
    margin: 0 0 30px;
    text-align: center;
}
.sidebar a {
    padding: 15px 20px;
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: .3s;
}
.sidebar a:hover, .sidebar a.active {
    background: #0d47a1;
}
.logout {
    margin-top: auto;
    text-align: center;
    padding: 15px;
}
.logout button {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
}
.main {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}
.section {
    display: none;
}
.section.active {
    display: block;
}
h1 {
    color: #1565c0;
    margin-bottom: 20px;
}
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.card {
    width: 300px;
    background: #fff;
    border-radius: 10px;
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-bottom: 1px solid #ccc;
}
.card .info {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.card h3 {
    font-size: 18px;
    margin: 0 0 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}
.card p {
    font-size: 14px;
    color: #666;
    flex: 1;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
    margin-bottom: 10px;
}
.price {
    font-weight: bold;
    color: #28a745;
    padding-top: 8px;
}
.action {
    margin-top: 10px;
    display: flex;
    gap: 8px;
}
.action a {
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 13px;
    color: #fff;
    text-decoration: none;
}
.edit {
    background: #17a2b8;
}
.delete {
    background: #dc3545;
}
.pagination {
    text-align: center;
    margin-top: 30px;
}
.pagination a {
    padding: 8px 14px;
    background: #007bff;
    color: #fff;
    margin: 0 4px;
    border-radius: 5px;
    text-decoration: none;
}
.pagination a:hover {
    background: #0056b3;
}
.pagination .active {
    background: #28a745;
    pointer-events: none;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: #fff;
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
    font-size: 14px;
}
th {
    background: #1565c0;
    color: #fff;
}
</style>
</head>
<body>

<div class="sidebar">
  <h2>Dashboard</h2>
  <a href="#" onclick="showSection('paket', this)" class="active"><i class="fas fa-map"></i>Paket Wisata</a>
  <a href="#" onclick="showSection('pengguna', this)"><i class="fas fa-users"></i>Pengguna</a>
  <a href="#" onclick="showSection('admin', this)"><i class="fas fa-user-shield"></i>Admin</a>
  <a href="https://dashboard.sandbox.midtrans.com/beta/transactions" target="_blank"><i class="fas fa-receipt"></i>Transaksi</a>
  <div class="logout">
    <form method="post" action="logout.php">
      <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </form>
  </div>
</div>

<div class="main">
  <div id="paket" class="section active">
    <h1><i class="fas fa-map-marked-alt"></i> Daftar Paket Wisata</h1>
    <a href="create.php" style="display:inline-block;margin-bottom:20px;background:#007bff;color:#fff;padding:10px 16px;border-radius:6px;text-decoration:none;"><i class="fas fa-plus-circle"></i> Tambah Paket</a>
    <div class="card-container">
      <?php while($row = $paketRes->fetch_assoc()): ?>
        <div class="card">
          <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto">
          <div class="info">
            <h3><?= htmlspecialchars($row['nama_paket']) ?></h3>
            <p><?= htmlspecialchars($row['deskripsi']) ?></p>
            <div class="price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
            <div class="action">
              <a href="update.php?id=<?= $row['id'] ?>" class="edit"><i class="fas fa-edit"></i></a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Hapus paket ini?')"><i class="fas fa-trash-alt"></i></a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <div class="pagination">
      <?php for($i=1;$i<=$totalPages;$i++): ?>
        <a href="?page=<?= $i ?>" class="<?= ($i==$page)?'active':'' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  </div>

  <div id="pengguna" class="section">
    <h1><i class="fas fa-users"></i> Daftar Pengguna</h1>
    <table>
      <tr><th>ID</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Jenis Kelamin</th><th>Terdaftar</th></tr>
      <?php while($u = $userRes->fetch_assoc()): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['phone']) ?></td>
          <td><?= htmlspecialchars($u['gender']) ?></td>
          <td><?= htmlspecialchars($u['created_at']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <div id="admin" class="section">
    <h1><i class="fas fa-user-shield"></i> Daftar Admin</h1>
    <table>
      <tr><th>ID</th><th>Nama</th><th>Email</th></tr>
      <?php while($a = $adminRes->fetch_assoc()): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['nama']) ?></td>
          <td><?= htmlspecialchars($a['email']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>

<script>
function showSection(id, el) {
  document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
  if (el) el.classList.add('active');
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}
</script>

</body>
</html>
