<?php require 'config.php';
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
    if($result->num_rows == 1){
        $_SESSION['user'] = $result->fetch_assoc();
        header("Location: index.php");
    } else $error = "Email atau password salah!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"></head>
<body class="d-flex align-items-center" style="min-height:100vh">
<div class="container"><div class="row justify-content-center"><div class="col-md-4">
<div class="card p-4">
<h2 class="text-center mb-4">📖 Buku Tamu</h2>
<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="POST">
<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
<button name="login" class="btn btn-primary w-100">Login</button>
</form>
<p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div></div></div></div></body></html>