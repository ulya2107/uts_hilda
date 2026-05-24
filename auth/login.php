<?php 
include '../header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $username = mysqli_real_escape_string($conn, $username);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Username atau password salah";
    }
}
?>

<div class="row">
    <div class="col-md-5 mx-auto">
        <div class="card p-4">
            <h3 class="card-title text-center mb-4">Login</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Register</a></p>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>