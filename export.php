<?php
require 'config.php';
if(!isset($_SESSION['user'])) die("Login dulu");
$user_id = $_SESSION['user']['id'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Saya_".date('Y-m-d').".xls");

$data = $conn->query("SELECT * FROM buku_tamu WHERE user_id=$user_id ORDER BY tanggal DESC, jam DESC");
echo "No\tNama\tAlamat\tKeperluan\tJam\tTanggal\n";
$no=1;
while($row = $data->fetch_assoc()){
    echo $no++."\t".$row['nama']."\t".$row['alamat']."\t".$row['keperluan']."\t".$row['jam']."\t".$row['tanggal']."\n";
}
?>