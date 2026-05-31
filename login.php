<?php require 'koneksi.php';
$error = "";
if(isset($_POST['login'])){
  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $_POST['username']);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  if($user && password_verify($_POST['password'], $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: index.php");
    exit;
  } else {
    $error = "<div class='msg error'>Username atau password salah</div>";
  }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login - Buku Tamu</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
<h2>Login Akun</h2>
<?= $error ?>
<form method="POST">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button class="btn" name="login" style="width:100%">Login</button>
</form>
<p style="text-align:center;margin-top:20px">Belum punya akun? <a href="register.php" style="color:var(--brown)">Daftar di sini</a></p>
</div>
</body>
</html>