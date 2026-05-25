<?php 
include 'header.php'; 

if(!isset($_SESSION['username'])){
    header("Location: auth/login.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

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

// Query data dengan search + pagination
$where = $search ? "WHERE nama LIKE '%$search%' OR keperluan LIKE '%$search%'" : "";
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tamu $where");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$data = mysqli_query($conn, "SELECT * FROM tamu $where ORDER BY waktu DESC LIMIT $limit OFFSET $offset");

// Statistik
$total_tamu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tamu"))['total'];
$tamu_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tamu WHERE DATE(waktu) = CURDATE()"))['total'];

// Data chart
$chart_data = mysqli_query($conn, "SELECT DATE(waktu) as tanggal, COUNT(*) as jumlah FROM tamu GROUP BY DATE(waktu) ORDER BY tanggal DESC LIMIT 7");
$labels = [];
$values = [];
while($row = mysqli_fetch_assoc($chart_data)){
    $labels[] = date('d-M', strtotime($row['tanggal']));
    $values[] = $row['jumlah'];
}
$labels = array_reverse($labels);
$values = array_reverse($values);
?>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card p-3" style="background:#e8dff5;">
            <h5>Total Tamu</h5>
            <h2><?= $total_tamu ?></h2>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3" style="background:#c9f0ff;">
            <h5>Tamu Hari Ini</h5>
            <h2><?= $tamu_hari_ini ?></h2>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="card p-4 mb-4">
    <h4>Statistik 7 Hari Terakhir</h4>
    <canvas id="tamuChart"></canvas>
</div>

<!-- Search & Export -->
<div class="row mb-3">
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari nama/keperluan..." value="<?= $search ?>">
            <button class="btn btn-primary">Cari</button>
            <?php if($search): ?>
                <a href="index.php" class="btn btn-secondary ms-2">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <a href="export.php?type=excel" class="btn" style="background:#b7f7c8;">Export Excel</a>
    </div>
</div>

<div class="row">
    <!-- Form Tambah/Edit -->
    <div class="col-md-5">
        <div class="card p-4">
            <h4 class="mb-3"><?= $edit_data['id'] ? "Edit Data Tamu" : "Isi Buku Tamu" ?></h4>
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

    <!-- Tabel Data -->
    <div class="col-md-7">
        <div class="card p-4">
            <h4 class="mb-3">Data Tamu <small class="text-muted">(Total: <?= $total_data ?>)</small></h4>
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
                        <?php $no=$offset+1; while($row = mysqli_fetch_assoc($data)): ?>
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

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for($i=1; $i<=$total_pages; $i++): ?>
                    <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>