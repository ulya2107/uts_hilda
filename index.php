<?php 
include 'header.php'; 

if(!isset($_SESSION['username'])){
    header("Location: auth/login.php");
    exit;
}

// Proses tambah & edit
if(isset($_POST['simpan'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $keperluan = mysqli_real_escape_string($conn, $_POST['keperluan']);
    $id = $_POST['id'];
    
    if($id == ""){
        mysqli_query($conn, "INSERT INTO tamu (nama, alamat, keperluan) VALUES ('$nama', '$alamat', '$keperluan')");
        $success = "Data berhasil ditambahkan!";
    } else {
        mysqli_query($conn, "UPDATE tamu SET nama='$nama', alamat='$alamat', keperluan='$keperluan' WHERE id='$id'");
        $success = "Data berhasil diupdate!";
    }
}

// Proses hapus
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tamu WHERE id='$id'");
    header("Location: index.php");
    exit;
}

// Ambil data untuk edit
$edit_data = ['id'=>'', 'nama'=>'', 'alamat'=>'', 'keperluan'=>''];
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM tamu WHERE id='$id'");
    $edit_data = mysqli_fetch_assoc($q);
}

$data = mysqli_query($conn, "SELECT * FROM tamu ORDER BY waktu DESC");
?>

<div class="row">
    <div class="col-md-5">
        <div class="card p-4">
            <h4 class="mb-3"><?= $edit_data['id'] ? "Edit Data Tamu" : "Tambah Data Tamu" ?></h4>
            <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <div class="mb-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?= $edit_data['nama'] ?>" required>
                </div>
                <div class="mb-3">
                    <textarea name="alamat" class="form-control" placeholder="Alamat" rows="2" required><?= $edit_data['alamat'] ?></textarea>
                </div>
                <div class="mb-3">
                    <input type="text" name="keperluan" class="form-control" placeholder="Keperluan" value="<?= $edit_data['keperluan'] ?>" required>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary w-100">Simpan</button>
                <?php if($edit_data['id']): ?>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card p-4">
            <h4 class="mb-3">Data Tamu</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Keperluan</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['alamat']; ?></td>
                            <td><?= $row['keperluan']; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['waktu'])); ?></td>
                            <td>
                                <a href="index.php?edit=<?= $row['id'] ?>" class="btn btn-sm" style="background:#ffeaa7;">Edit</a>
                                <a href="index.php?hapus=<?= $row['id'] ?>" class="btn btn-sm" style="background:#fab1a0;" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>