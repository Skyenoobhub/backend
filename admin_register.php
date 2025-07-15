<?php
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = new mysqli("localhost", "root", "", "travelapps");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek apakah email sudah ada
    $check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedPassword);
        if ($stmt->execute()) {
            $success = "Akun berhasil dibuat. Silakan login.";
        } else {
            $error = "Gagal menyimpan akun: " . $conn->error;
        }
    }

    $check->close();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Admin</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .register-box {
      background: #fff;
      padding: 40px 50px;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
      width: 420px;
    }
    .register-box h2 {
      margin-bottom: 24px;
      color: #1565c0;
      font-weight: bold;
    }
    .input-group {
      position: relative;
      margin-bottom: 18px;
    }
    .input-group i {
      position: absolute;
      top: 12px;
      left: 12px;
      color: #888;
      font-size: 14px;
    }
    .input-group input {
      width: 100%;
      padding: 10px 12px 10px 38px;
      border: 1px solid #ccc;
      border-radius: 6px;
      outline: none;
      font-size: 14px;
    }
    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 6px;
      background: #1565c0;
      color: white;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      margin-top: 10px;
    }
    .error {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
    }
    .success {
      color: green;
      font-size: 14px;
      margin-bottom: 10px;
    }
    a {
      display: block;
      margin-top: 16px;
      color: #1565c0;
      text-decoration: none;
      font-size: 14px;
    }
    a strong {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="register-box">
    <h2><i class="fas fa-user-plus"></i> Daftar Admin</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit">Daftar</button>
    </form>

    <a href="admin_login.php"><i class="fas fa-sign-in-alt"></i> Sudah punya akun? <strong>Login</strong></a>
  </div>

</body>
</html>
