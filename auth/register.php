<?php 
include '../header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($check) > 0){
        $error = "Username sudah dipakai";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if($query){
            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<div class="row">
    <div class="col-md-5 mx-auto">
        <div class="card p-4">
            <h3 class="card-title text-center mb-4">Register</h3>
            <?php 
            if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; 
            if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; 
            ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>