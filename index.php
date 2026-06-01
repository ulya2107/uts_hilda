<?php require 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");
$user_id = $_SESSION['user']['id'];

$pesan = '';
if(isset($_GET['status'])){
    if($_GET['status'] == 'hapus') $pesan = "Data berhasil dihapus!";
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM buku_tamu WHERE id=$id AND user_id=$user_id");
    header("Location: index.php?status=hapus");
}

$tgl_hari_ini = date('Y-m-d');
$jumlah = $conn->query("SELECT COUNT(*) as total FROM buku_tamu WHERE tanggal='$tgl_hari_ini' AND user_id=$user_id")->fetch_assoc()['total'];
$data = $conn->query("SELECT * FROM buku_tamu WHERE user_id=$user_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar navbar-expand-lg mb-4"><div class="container">
<a class="navbar-brand" href="#">📖 Buku Tamu </a>
<div class="ms-auto"><span class="text-white me-3">Halo, <?= $_SESSION['user']['nama'] ?></span>
<a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a></div></div></nav>

<div class="container">
<?php if($pesan): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
✓ <?= $pesan ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php 
$tgl_hari_ini = date('Y-m-d');
$jumlah_hari_ini = $conn->query("SELECT COUNT(*) as total FROM buku_tamu WHERE tanggal='$tgl_hari_ini' AND user_id=$user_id")->fetch_assoc()['total'];

$total_semua = $conn->query("SELECT COUNT(*) as total FROM buku_tamu WHERE user_id=$user_id")->fetch_assoc()['total'];
?>

<div class="row mb-4">
<div class="col-md-4"><div class="card p-3 text-center" style="background: linear-gradient(135deg, #10B981, #047857); color:white;">
<h5>Kunjungan Hari Ini</h5><h2><?= $jumlah_hari_ini ?> Kali</h2></div></div>

<div class="col-md-4"><div class="card p-3 text-center" style="background: linear-gradient(135deg, #10B981, #047857); color:white;">
<h5>Total Semua Kunjungan</h5><h2><?= $total_semua ?> Kali</h2></div></div>

<div class="col-md-4 text-end d-flex align-items-center justify-content-end">
<a href="tambah.php" class="btn btn-success me-2">+ Tambah Data</a>
<a href="export_excel.php" class="btn btn-primary">Export Excel</a>
</div></div>

<div class="card p-3">
<h5 class="mb-3">Riwayat Kunjungan</h5>
<table class="table table-hover"><thead><tr>
<th>No</th><th>Nama</th><th>Alamat</th><th>Keperluan</th><th>Jam</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
<?php $no=1; while($row = $data->fetch_assoc()): ?>
<tr><td><?= $no++ ?></td><td><?= $row['nama'] ?></td><td><?= $row['alamat'] ?></td>
<td><?= $row['keperluan'] ?></td><td><?= date('H:i', strtotime($row['jam'])) ?> WIB</td>
<td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
<td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
<a href="index.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm">Hapus</a></td></tr>
<?php endwhile; ?></tbody></table>
<?php if($data->num_rows == 0) echo "<p class='text-center text-muted'>Belum ada data kunjungan</p>"; ?>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>