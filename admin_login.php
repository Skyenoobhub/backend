<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = isset($_GET['success']) ? "Akun berhasil dibuat. Silakan login." : '';

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
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f2f5f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    .form-container h2 {
      color: #1565c0;
      margin-bottom: 25px;
    }
    .input-group {
      position: relative;
      margin-bottom: 18px;
      text-align: left;
    }
    .input-group i {
      position: absolute;
      top: 12px;
      left: 12px;
      color: #aaa;
    }
    .input-group input {
      width: 100%;
      padding: 10px 12px 10px 38px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      outline: none;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #1565c0;
      border: none;
      color: white;
      font-weight: bold;
      font-size: 15px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }
    .form-footer {
      margin-top: 18px;
      font-size: 14px;
    }
    .form-footer a {
      color: #1565c0;
      text-decoration: none;
      font-weight: bold;
    }
    .error {
      color: red;
      font-size: 13px;
      margin-bottom: 10px;
    }
    .success {
      color: green;
      font-size: 13px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2><i class="fas fa-sign-in-alt"></i> Login Admin</h2>

    <?php if (!empty($success)) echo '<div class="success">' . htmlspecialchars($success) . '</div>'; ?>
    <?php if (!empty($error)) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>

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

    <div class="form-footer">
      <i class="fas fa-user-plus"></i> Belum punya akun? <a href="admin_register.php">Daftar</a>
    </div>
  </div>

</body>
</html>
