<?php require 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$sukses = '';
if(isset($_POST['simpan'])){
    $user_id = $_SESSION['user']['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $keperluan = $_POST['keperluan'];
    $conn->query("INSERT INTO buku_tamu (user_id,nama,alamat,keperluan) VALUES ('$user_id','$nama','$alamat','$keperluan')");
    $sukses = "Data berhasil disimpan!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Data</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar navbar-expand-lg mb-4"><div class="container">
<a class="navbar-brand" href="index.php">📖 Buku Tamu</a></div></nav>

<div class="container"><div class="row justify-content-center"><div class="col-md-6">
<div class="card p-4">
<h4 class="mb-3">Isi Buku Tamu</h4>
<?php if($sukses): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
✓ <?= $sukses ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<script>setTimeout(()=>{window.location='index.php'}, 2000);</script>
<?php endif; ?>
<form method="POST">
<div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
<div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2" required></textarea></div>
<div class="mb-3"><label>Keperluan</label><input type="text" name="keperluan" class="form-control" placeholder="Contoh: Bertemu Pak RT" required></div>
<button name="simpan" class="btn btn-primary w-100">Simpan</button>
</form>
<p class="text-center mt-3"><a href="index.php">Lihat Data Saya</a></p>
</div></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>