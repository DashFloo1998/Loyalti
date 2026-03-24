<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "Loyalti"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Aktifkan laporan error agar tidak muncul "This page isn't working" yang misterius
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>