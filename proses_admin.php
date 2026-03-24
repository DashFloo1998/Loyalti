<?php
session_start();
include 'config.php';

if (isset($_POST['tambah_manual'])) {
    $nohp_input = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $total_belanja = (int)$_POST['total_belanja'];
    
    // Logika: Belanja 10.000 dapat 100 Poin (1% dari belanja)
    $tambah_point = $total_belanja * 0.01; 

    $cek_user = mysqli_query($conn, "SELECT ID, Nama, Member_point FROM tbmember WHERE NoHP = '$nohp_input'");
    $data = mysqli_fetch_assoc($cek_user);

    if ($data) {
        $id_user = $data['ID'];
        // UPDATE STAMP +1 DAN TAMBAH POINT
        // PENTING: Cek penulisan 'Member_Point' harus sama dengan di phpMyAdmin
        $sql = "UPDATE tbmember SET 
                Total_Stamp = Total_Stamp + 1, 
                Member_point = Member_point + $tambah_point 
                WHERE ID = '$id_user'";
        
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn, "INSERT INTO tbhistory (id_user, aksi) VALUES ('$id_user', 'Admin: +1 Stamp & +$tambah_point Pts')");
            echo "<script>alert('Berhasil! " . $data['Nama'] . " dapat " . $tambah_point . " Poin'); window.location='dashboard_admin.php';</script>";
        }
    } else {
        echo "<script>alert('Nomor HP tidak ditemukan!'); window.location='dashboard_admin.php';</script>";
    }
}
?>