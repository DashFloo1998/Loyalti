<?php
include 'config.php';
$kode = $_GET['kode'];

$query = mysqli_query($conn, "SELECT status FROM tbredeem_request WHERE kode_unik = '$kode'");
$data = mysqli_fetch_assoc($query);

// Kirim statusnya saja ke JavaScript
echo $data['status'];
?>