<?php
include 'config/db.php';

if(!isset($_SESSION['username'])){
    header("Location: auth/login.php");
    exit;
}

$type = $_GET['type'];
$data = mysqli_query($conn, "SELECT * FROM tamu ORDER BY waktu DESC");

if($type == 'excel'){
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=data_tamu.xls");
    
    echo "<table border='1'>";
    echo "<tr><th>No</th><th>Nama</th><th>Alamat</th><th>Keperluan</th><th>Waktu</th></tr>";
    $no=1;
    while($row = mysqli_fetch_assoc($data)){
        echo "<tr>
            <td>".$no++."</td>
            <td>".$row['nama']."</td>
            <td>".$row['alamat']."</td>
            <td>".$row['keperluan']."</td>
            <td>".$row['waktu']."</td>
        </tr>";
    }
    echo "</table>";
}

if($type == 'pdf'){
    // Simple HTML to PDF via browser print
    echo "<script>window.print();</script>";
    echo "<h3>Data Tamu</h3>";
    echo "<table border='1' width='100%' style='border-collapse:collapse;'>";
    echo "<tr><th>No</th><th>Nama</th><th>Alamat</th><th>Keperluan</th><th>Waktu</th></tr>";
    $no=1;
    while($row = mysqli_fetch_assoc($data)){
        echo "<tr>
            <td>".$no++."</td>
            <td>".$row['nama']."</td>
            <td>".$row['alamat']."</td>
            <td>".$row['keperluan']."</td>
            <td>".$row['waktu']."</td>
        </tr>";
    }
    echo "</table>";
}
?>