<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">📖 Buku Tamu</a>
        <div>
            <?php if(isset($_SESSION['username'])): ?>
                <span class="me-3">Halo, <?= $_SESSION['username']; ?></span>
                <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="btn btn-primary btn-sm">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container mt-5">