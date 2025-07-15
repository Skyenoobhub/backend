<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "travelapps");

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, gender) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $gender);

    if ($stmt->execute()) {
        header("Location: login_form.php");
        exit();
    } else {
        $error = "Registrasi gagal: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna</title>
    <style>
        body { font-family: Arial; background: #f1f1f1; padding: 20px; }
        form { background: #fff; padding: 20px; border-radius: 10px; max-width: 400px; margin: auto; }
        input, select { width: 100%; padding: 10px; margin-bottom: 10px; }
        button { background: #28a745; color: #fff; border: none; padding: 10px; width: 100%; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Daftar Akun Baru</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="name" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="No Telepon" required>
        <select name="gender" required>
            <option value="">Pilih Jenis Kelamin</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
        <input type="password" name="password" placeholder="Kata Sandi" required>
        <button type="submit">Daftar</button>
        <p>Sudah punya akun? <a href="login_form.php">Masuk di sini</a></p>
    </form>
</body>
</html>
