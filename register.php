<?php require 'config.php';
if(isset($_POST['daftar'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    
    $cek = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($cek->num_rows > 0){
        $error = "Email sudah terdaftar!";
    } else {
        $conn->query("INSERT INTO users (nama,email,password) VALUES ('$nama','$email','$password')");
        $sukses = "Registrasi berhasil! Silakan login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Daftar Akun</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"></head>
<body class="d-flex align-items-center" style="min-height:100vh">
<div class="container"><div class="row justify-content-center"><div class="col-md-4">
<div class="card p-4">
<h2 class="text-center mb-4">📝 Daftar Akun</h2>
<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; 
if(isset($sukses)) echo "<div class='alert alert-success'>$sukses</div>"; ?>
<form method="POST">
<div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required minlength="4"></div>
<button name="daftar" class="btn btn-primary w-100">Daftar Sekarang</button>
</form>
<p class="text-center mt-3">Udah punya akun? <a href="login.php">Login di sini</a></p>
</div></div></div></div></body></html>