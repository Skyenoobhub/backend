<?php
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (empty($nama) || empty($email) || empty($password) || empty($password2)) {
        $error = "Semua field wajib diisi.";
    } elseif ($password !== $password2) {
        $error = "Password tidak cocok.";
    } else {
        $conn = new mysqli("localhost", "root", "", "travelapps");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO admin (nama, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: admin_login.php?success=1");
                exit;
            } else {
                $error = "Terjadi kesalahan. Gagal mendaftar.";
            }
        }

        $check->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi Admin</title>
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
  </style>
</head>
<body>

  <div class="form-container">
    <h2><i class="fas fa-user-plus"></i> Registrasi Admin</h2>

    <?php if (!empty($error)) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>

    <form method="POST">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
      </div>
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password2" placeholder="Ulangi Password" required>
      </div>
      <button type="submit">Daftar</button>
    </form>

    <div class="form-footer">
      <i class="fas fa-sign-in-alt"></i> Sudah punya akun? <a href="admin_login.php">Login</a>
    </div>
  </div>

</body>
</html>
