<?php
session_start();

// Jika sudah login, redirect ke index.php
if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi.";
    } else {
        $conn = new mysqli("localhost", "root", "", "travelapps");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin'] = $email;
                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Email tidak ditemukan.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
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
    .login-box {
      background: #fff;
      padding: 40px 50px;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
      width: 420px;
    }
    .login-box h2 {
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

  <div class="login-box">
    <h2><i class="fas fa-sign-in-alt"></i> Login Admin</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
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
      <button type="submit">Masuk</button>
    </form>

    <a href="admin_register.php"><i class="fas fa-user-plus"></i> Belum punya akun? <strong>Daftar</strong></a>
  </div>

</body>
</html>
