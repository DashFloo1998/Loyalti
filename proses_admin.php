<?php
session_start();
include 'config.php';

if (isset($_POST['tambah_manual'])) {
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $total_belanja = (int)$_POST['total_belanja'];

    // LOGIKA OTOMATIS KELIPATAN 30rb
    // floor digunakan agar jika belanja 50rb, tetap dapat 1 stamp (bukan 1.6)
    $tambah_stamp = floor($total_belanja / 30000); 
    
    // Poin tetap pakai rumus Rp 1.000 = 1 Point (Jadi 30.000 = 30 Point)
    $tambah_point = floor($total_belanja / 1000);

    if ($tambah_stamp > 0) {
        // Cek apakah member ada
        $cek_member = mysqli_query($conn, "SELECT * FROM tbmember WHERE NoHP = '$no_hp'");
        
        if (mysqli_num_rows($cek_member) > 0) {
            // UPDATE STAMP DAN POINT SEKALIGUS
            $sql = "UPDATE tbmember SET 
                    Total_Stamp = Total_Stamp + $tambah_stamp, 
                    Member_point = Member_point + $tambah_point 
                    WHERE NoHP = '$no_hp'";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Berhasil! Member dapat $tambah_stamp Stamp & $tambah_point Point.'); window.location='dashboard_admin.php';</script>";
            }
        } else {
            echo "<script>alert('Nomor HP tidak terdaftar sebagai member!'); window.location='dashboard_admin.php';</script>";
        }
    } else {
        echo "<script>alert('Belanja di bawah 30rb tidak mendapatkan Stamp.'); window.location='dashboard_admin.php';</script>";
    }
}
?>