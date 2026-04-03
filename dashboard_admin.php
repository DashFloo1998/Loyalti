<?php
session_start();
include 'config.php';

// Proteksi: Hanya Admin yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// LOGIKA TAMBAH STAMP UNTUK USER LAIN
if (isset($_POST['tambah_stamp_user'])) {
    $id_target = $_POST['id_user'];
    mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp + 1 WHERE ID = '$id_target'");
    header("Location: dashboard_admin.php?pesan=berhasil_tambah");
    exit();
}

// LOGIKA KURANGI STAMP UNTUK USER LAIN
if (isset($_POST['kurangi_stamp_user'])) {
    $id_target = $_POST['id_user'];
    mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp - 1 WHERE ID = '$id_target' AND Total_Stamp > 0");
    header("Location: dashboard_admin.php?pesan=berhasil_dikurangi");
    exit();
}

// LOGIKA TUKAR STAMP UNTUK USER LAIN (Potong 5)
if (isset($_POST['tukar_5_stamp_user'])) {
    $id_target = $_POST['id_user'];
    $cek = mysqli_query($conn, "SELECT Total_Stamp FROM tbmember WHERE ID = '$id_target'");
    $data = mysqli_fetch_assoc($cek);
    
    if ($data['Total_Stamp'] >= 5) {
        mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp - 5 WHERE ID = '$id_target'");
        header("Location: dashboard_admin.php?pesan=berhasil_tukar");
    } else {
        header("Location: dashboard_admin.php?pesan=gagal_stamps_kurang");
    }
    exit();
}
// LOGIKA TUKAR STAMP UNTUK USER LAIN (Potong 10)
if (isset($_POST['tukar_10_stamp_user'])) {
    $id_target = $_POST['id_user'];
    $cek = mysqli_query($conn, "SELECT Total_Stamp FROM tbmember WHERE ID = '$id_target'");
    $data = mysqli_fetch_assoc($cek);
    
    if ($data['Total_Stamp'] >= 10) {
        mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp - 10 WHERE ID = '$id_target'");
        header("Location: dashboard_admin.php?pesan=berhasil_tukar");
    } else {
        header("Location: dashboard_admin.php?pesan=gagal_stamps_kurang");
    }
    exit();
}

// LOGIKA PENCARIAN
$search = "";
if (isset($_GET['cari'])) {
    $search = $_GET['cari'];
    $query_user = mysqli_query($conn, "SELECT * FROM tbmember WHERE role = 'customer' AND (Nama LIKE '%$search%' OR NoHP LIKE '%$search%')");
} else {
    $query_user = mysqli_query($conn, "SELECT * FROM tbmember WHERE role = 'customer' ORDER BY ID DESC");
}

// ALUR LOGIKA ADMIN (Sisi Belakang):
// 1. Admin input 'AIC-77XQ'
// 2. Sistem cek di 'tb_redeem_request'
// 3. Jika ADA dan STATUS 'pending':
//    a. UPDATE tbmember SET Member_point = Member_point - poin_biaya, Total_Redeem = Total_Redeem + poin_biaya WHERE ID = id_user
//    b. UPDATE tb_redeem_request SET status = 'selesai' WHERE kode_unik = 'AIC-77XQ'
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel Admin - Kelola Stamp Pelanggan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1d3557; color: white; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; color: white; font-weight: bold; font-size: 12px; }
        .btn-add { background: #2a9d8f; }
        .btn-remove { background: #2a9d8f; }
        .btn-redeem { background: #e76f51; }
        .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .badge { padding: 4px 8px; background: #ffca3a; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Panel Admin Ai-CHA</h2>
        <a href="logout.php" style="color: red; text-decoration: none; font-weight: bold;">Keluar</a>
    </div>

    <div style="margin-bottom: 20px;">
    <form method="GET" action="dashboard_admin.php" style="display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Cari Nama atau Nomor HP Pelanggan..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
        <button type="submit" style="background: #2c3e50; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">Cari</button>
    </form>
</div>

<div style="background: #f9f9f9; padding: 20px; border-radius: 20px; border: 1px solid #eee;">
    
    <h4 style="margin: 0 0 10px 0;">Input Point & Stamp Ai-CHA</h4>
    <form method="POST" action="proses_admin.php" style="display: flex; gap: 10px; margin-bottom: 25px;">
        <input type="text" name="no_hp" placeholder="Nomor HP Member" required style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 200px;">
        <input type="number" name="total_belanja" placeholder="Total Belanja (Rp)" required style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 200px;">
        <button type="submit" name="tambah_manual" style="background: white; border: 1px solid #333; padding: 10px 15px; border-radius: 8px; font-weight: bold; cursor: pointer;">PROSES TRANSAKSI</button>
    </form> <hr style="border: 0; border-top: 1px solid #ddd; margin: 20px 0;">

    <h4 style="margin: 0 0 10px 0;">Validasi Voucher</h4>
    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Masukkan kode unik dari customer untuk memotong poin.</p>
    
    <form action="proses_konfirmasi_admin.php" method="POST" style="display: flex; gap: 10px; align-items: center;">
        <input type="text" name="kode_unik" placeholder="AIC-XXXXX" required style="text-transform: uppercase; padding: 15px; border: 1px solid #ddd; border-radius: 15px; width: 200px; font-weight: bold; font-size: 16px;">
        <button type="submit" name="cek_kode" style="background: #e63946; color: white; border: none; padding: 12px 30px; border-radius: 15px; font-weight: bold; cursor: pointer;">PROSES</button>
    </form> </div>
    
    <h3>Daftar Pelanggan</h3>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>No HP</th>
                <th>Total Stamp</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($query_user)) : ?>
            <tr>
                <td><?php echo $row['Nama']; ?></td>
                <td><?php echo $row['NoHP']; ?></td>
                <td><span class="badge"><?php echo $row['Total_Stamp']; ?></span></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id_user" value="<?php echo $row['ID']; ?>">
                        <button type="submit" name="tambah_stamp_user" class="btn btn-add">+ Stamp</button>
                        <button type="submit" name="kurangi_stamp_user" class="btn btn-remove">- Stamp</button>
                        <button type="submit" name="tukar_5_stamp_user" class="btn btn-redeem" onclick="return confirm('Tukar 5 stamp dengan minuman gratis?')">Redeem 5</button>
                        <button type="submit" name="tukar_10_stamp_user" class="btn btn-redeem" onclick="return confirm('Tukar 10 stamp dengan minuman gratis?')">Redeem 10</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if(mysqli_num_rows($query_user) == 0) echo "<tr><td colspan='4'>Data tidak ditemukan.</td></tr>"; ?>
        </tbody>
    </table>
</div>

</body>
</html>