<?php
// TAMPILKAN ERROR JIKA ADA YANG SALAH
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: Session tidak ditemukan. Silakan login ulang.");
}

if (isset($_POST['poin_dibutuhkan'])) {
    $id_user = $_SESSION['user_id'];
    $poin_req = (int)$_POST['poin_dibutuhkan'];
    $nama_item = mysqli_real_escape_string($conn, $_POST['nama_item']);

    // 1. Ambil data user
    $res = mysqli_query($conn, "SELECT Member_point FROM tbmember WHERE ID = '$id_user'");
    if (!$res) { die("Database Error (tbmember): " . mysqli_error($conn)); }
    
    $user = mysqli_fetch_assoc($res);

    // 2. Cek Poin
    if ($user['Member_point'] < $poin_req) {
        echo "<script>alert('Poin Anda tidak cukup!'); window.location='redeem_menu.php';</script>";
        exit();
    }

    // 3. Buat Kode Random
    $kode_acak = 'AIC-' . strtoupper(substr(md5(time()), 0, 5));

    // 4. INSERT KE DATABASE (PASTIKAN TABELNYA SUDAH ADA)
    // Cek dulu apakah tabel ada, kalau error dia akan muncul pesan di layar
    $sql = "INSERT INTO tbredeem_request (id_user, nama_item, poin_biaya, kode_unik, status) 
            VALUES ('$id_user', '$nama_item', '$poin_req', '$kode_acak', 'pending')";

    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, lempar ke tiket_redeem.php
        header("Location: tiket_redeem.php?kode=$kode_acak");
        exit();
    } else {
        // JIKA ERROR DISINI, DIA AKAN KASIH TAHU KENAPA
        echo "<h3>Waduh, Gagal Kirim Request!</h3>";
        echo "Pesan Error: " . mysqli_error($conn);
        echo "<br><br><b>Pesan Mas Rian:</b> Cek apakah tabel 'tbredeem_request' sudah dibuat di phpMyAdmin?";
    }
} else {
    echo "Tidak ada data yang diterima.";
}
?>