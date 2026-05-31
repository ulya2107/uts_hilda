<?php require 'koneksi.php'; 
$msg = "";
if(isset($_POST['register'])){
  $username = trim($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO users (username,password) VALUES (?,?)");
  $stmt->bind_param("ss", $username, $password);
  if($stmt->execute()) {
    $msg = "<div class='msg success'>Registrasi berhasil! Silakan login.</div>";
  } else {
    $msg = "<div class='msg error'>Username sudah dipakai.</div>";
  }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register - Buku Tamu</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
<h2>Daftar Akun Baru</h2>
<?= $msg ?>
<form method="POST">
  <input type="text" name="username" placeholder="Username" required minlength="4">
  <input type="password" name="password" placeholder="Password minimal 6 karakter" required minlength="6">
  <button class="btn" name="register" style="width:100%">Daftar Sekarang</button>
</form>
<p style="text-align:center;margin-top:20px">Udah punya akun? <a href="login.php" style="color:var(--brown)">Login di sini</a></p>
</div>
</body>
</html>