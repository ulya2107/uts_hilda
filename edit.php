<?php require 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$user_id = $_SESSION['user']['id'];
$id = $_GET['id'];
$sukses = '';

$cek = $conn->query("SELECT * FROM buku_tamu WHERE id=$id AND user_id=$user_id");
if($cek->num_rows != 1) die("<script>alert('Data tidak ditemukan!');window.location='index.php';</script>");
$data = $cek->fetch_assoc();

if(isset($_POST['update'])){
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $keperluan = $_POST['keperluan'];
    $conn->query("UPDATE buku_tamu SET nama='$nama', alamat='$alamat', keperluan='$keperluan' WHERE id=$id AND user_id=$user_id");
    $sukses = "Data berhasil diupdate!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Data</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar navbar-expand-lg mb-4"><div class="container">
<a class="navbar-brand" href="index.php">📖 Buku Tamu Saya</a></div></nav>

<div class="container"><div class="row justify-content-center"><div class="col-md-6">
<div class="card p-4">
<h4 class="mb-3">Edit Data Tamu</h4>
<?php if($sukses): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
✓ <?= $sukses ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<script>setTimeout(()=>{window.location='index.php'}, 2000);</script>
<?php endif; ?>
<form method="POST">
<div class="mb-3"><label>Nama</label><input type="text" name="nama" value="<?= $data['nama'] ?>" class="form-control" required></div>
<div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2" required><?= $data['alamat'] ?></textarea></div>
<div class="mb-3"><label>Keperluan</label><input type="text" name="keperluan" value="<?= $data['keperluan'] ?>" class="form-control" required></div>
<button name="update" class="btn btn-primary">Update</button>
<a href="index.php" class="btn btn-secondary">Batal</a>
</form>
</div></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>