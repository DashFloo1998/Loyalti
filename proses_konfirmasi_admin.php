<?php
session_start();
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if (isset($_POST['cek_kode'])) {
    // Ambil kode dan bersihkan
    $kode = mysqli_real_escape_string($conn, strtoupper(trim($_POST['kode_unik'])));

    // 1. Cari kodenya di tabel request yang statusnya masih 'pending'
    $query_check = mysqli_query($conn, "SELECT * FROM tbredeem_request WHERE kode_unik = '$kode' AND status = 'pending'");
    
    if (mysqli_num_rows($query_check) > 0) {
        $data = mysqli_fetch_assoc($query_check);
        $id_user = $data['id_user'];
        $poin_biaya = (int)$data['poin_biaya'];
        $item = $data['nama_item'];

        // 2. Cek poin user di tabel member
        $user_res = mysqli_query($conn, "SELECT Member_point FROM tbmember WHERE ID = '$id_user'");
        $user_data = mysqli_fetch_assoc($user_res);

        if ($user_data['Member_point'] >= $poin_biaya) {
            
            // PROSES VALIDASI (Update Member & Update Status Request)
            // A. Potong Poin & Tambah Total Redeem
            $update_user = mysqli_query($conn, "UPDATE tbmember SET 
                                 Member_point = Member_point - $poin_biaya, 
                                 Total_Redeem = Total_Redeem + $poin_biaya 
                                 WHERE ID = '$id_user'");

            // B. Ubah status request jadi 'selesai'
            $update_status = mysqli_query($conn, "UPDATE tbredeem_request SET status = 'selesai' WHERE kode_unik = '$kode'");

            if ($update_user && $update_status) {
                echo "<script>alert('BERHASIL! Poin terpotong. Silakan berikan $item.'); window.location='dashboard_admin.php';</script>";
            } else {
                echo "<script>alert('Gagal update data ke database.'); window.location='dashboard_admin.php';</script>";
            }
        } else {
            echo "<script>alert('GAGAL! Poin user tidak mencukupi.'); window.location='dashboard_admin.php';</script>";
        }
    } else {
        echo "<script>alert('KODE TIDAK VALID atau sudah pernah diklaim!'); window.location='dashboard_admin.php';</script>";
    }
} else {
    header("Location: dashboard_admin.php");
}
?>