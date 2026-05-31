<?php 
require 'koneksi.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$msg = "";
$edit_data = null;

// KIRIM TESTIMONI BARU
if(isset($_POST['kirim'])){
  $stmt = $conn->prepare("INSERT INTO tamu (user_id,nama,email,pesan,kehadiran) VALUES (?,?,?,?,?)");
  $stmt->bind_param("issss", $uid, $_SESSION['username'], $_POST['email'], $_POST['pesan'], $_POST['kehadiran']);
  if($stmt->execute()) $msg = "<div class='msg success'>Testimoni berhasil dikirim! ❀</div>";
}

// UPDATE TESTIMONI MILIK SENDIRI
if(isset($_POST['update'])){
  $stmt = $conn->prepare("UPDATE tamu SET email=?, pesan=?, kehadiran=? WHERE id=? AND user_id=?");
  $stmt->bind_param("sssii", $_POST['email'], $_POST['pesan'], $_POST['kehadiran'], $_POST['id'], $uid);
  if($stmt->execute()) {
    $msg = "<div class='msg success'>Testimoni berhasil diupdate!</div>";
    $edit_data = null;
  }
}

// HAPUS TESTIMONI MILIK SENDIRI  
if(isset($_GET['hapus'])){
  $stmt = $conn->prepare("DELETE FROM tamu WHERE id=? AND user_id=?");
  $stmt->bind_param("ii", $_GET['hapus'], $uid);
  $stmt->execute();
  $msg = "<div class='msg success'>Testimoni berhasil dihapus.</div>";
}

// AMBIL DATA BUAT EDIT
if(isset($_GET['edit'])){
  $stmt = $conn->prepare("SELECT * FROM tamu WHERE id=? AND user_id=?");
  $stmt->bind_param("ii", $_GET['edit'], $uid);
  $stmt->execute();
  $edit_data = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Buku Tamu Digital</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <nav>Halo, <b><?= htmlspecialchars($_SESSION['username']) ?></b> | <a href="logout.php">Logout</a></nav>
  
  <h1>Buku Tamu</h1>
  
  <?php 
  $total = $conn->query("SELECT COUNT(*) as jml FROM tamu WHERE kehadiran='Hadir'")->fetch_assoc()['jml'];
  echo "<div class='counter'>💐 Jumlah Tamu Hadir: <b>$total</b> 💐</div>";
  echo $msg;
  ?>

  <h2><?= $edit_data ? 'Edit Testimoni Kamu' : 'Tulis Testimoni & Doa' ?></h2>
  <form method="POST">
    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
    <input type="email" name="email" placeholder="Email kamu" value="<?= htmlspecialchars($edit_data['email'] ?? '') ?>">
    <select name="kehadiran" required>
      <option value="Hadir" <?= ($edit_data['kehadiran']??'')=='Hadir'?'selected':'' ?>>Hadir</option>
      <option value="Tidak Hadir" <?= ($edit_data['kehadiran']??'')=='Tidak Hadir'?'selected':'' ?>>Tidak Hadir</option>
    </select>
    <textarea name="pesan" placeholder="Tulis testimoni, kesan, atau doa untuk acara ini..." rows="4" required><?= htmlspecialchars($edit_data['pesan'] ?? '') ?></textarea>
    
    <?php if($edit_data): ?>
      <button class="btn" name="update">Update Data</button>
      <a href="index.php" class="btn" style="background:#ccc">Batal</a>
    <?php else: ?>
      <button class="btn" name="kirim" style="width:100%">Kirim Testimoni</button>
    <?php endif; ?>
  </form>

  <h2>Testimoni Kamu</h2>
  <?php 
  $stmt = $conn->prepare("SELECT * FROM tamu WHERE user_id=? ORDER BY tanggal DESC");
  $stmt->bind_param("i", $uid);
  $stmt->execute();
  $data = $stmt->get_result();
  
  if($data->num_rows == 0) {
    echo "<p style='text-align:center;color:#999'>Kamu belum menulis testimoni.</p>";
  }
  
  while($row = $data->fetch_assoc()){
    echo "<div class='card'>
      <small>{$row['tanggal']} - {$row['kehadiran']}</small>
      <p>" . nl2br(htmlspecialchars($row['pesan'])) . "</p>
      <div>
        <a href='?edit={$row['id']}' class='btn' style='padding:6px 12px;font-size:13px'>Edit</a>
        <a href='?hapus={$row['id']}' onclick='return confirm(\"Yakin hapus testimoni ini?\")' class='btn btn-danger' style='padding:6px 12px;font-size:13px'>Hapus</a>
      </div>
    </div>";
  } 
  ?>

  <h2>Testimoni Semua Tamu</h2>
  <?php 
  $all = $conn->query("SELECT t.*,u.username FROM tamu t JOIN users u ON t.user_id=u.id ORDER BY tanggal DESC LIMIT 20");
  while($row = $all->fetch_assoc()){
    echo "<div class='card'>
      <b>" . htmlspecialchars($row['username']) . "</b> <small>- {$row['tanggal']} - {$row['kehadiran']}</small>
      <p>" . nl2br(htmlspecialchars($row['pesan'])) . "</p>
    </div>";
  } 
  ?>
</div>
</body>
</html>