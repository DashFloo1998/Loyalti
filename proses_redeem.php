<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['redeem'])) {
    header("Location: dashboard_user.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$poin_dibutuhkan = (int)$_POST['poin_dibutuhkan'];
$nama_item = mysqli_real_escape_string($conn, $_POST['nama_item']);

// 1. Ambil data terbaru dari database (Mencegah manipulasi input)
$res = mysqli_query($conn, "SELECT Member_point FROM tbmember WHERE ID = '$id_user'");
$row = mysqli_fetch_assoc($res);
$poin_sekarang = (int)$row['Member_point'];

if ($poin_sekarang >= $poin_dibutuhkan) {
    // 2. Potong Poin
    $update = mysqli_query($conn, "UPDATE tbmember SET Member_point = Member_point - $poin_dibutuhkan WHERE ID = '$id_user'");
    
    // 3. Catat di History
    mysqli_query($conn, "INSERT INTO tbhistory (id_user, aksi, tanggal) VALUES ('$id_user', 'Redeem: $nama_item', NOW())");

    if ($update) {
        // Tampilkan Struk Digital
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <style>
                body { font-family: 'Poppins', sans-serif; background: #e63946; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                .receipt { background: white; padding: 30px; border-radius: 25px; text-align: center; width: 85%; max-width: 320px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
                .check-icon { font-size: 50px; color: #2ecc71; margin-bottom: 15px; }
                .btn-done { display: block; margin-top: 25px; padding: 12px; background: #e63946; color: white; text-decoration: none; border-radius: 12px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="receipt">
                <i class="fas fa-check-circle check-icon"></i>
                <h2 style="margin:0;">Berhasil!</h2>
                <p style="color:#666; font-size:14px;">Tunjukkan ini ke Kasir Ai-CHA untuk klaim:</p>
                <div style="background:#f9f9f9; padding:15px; border-radius:10px; margin:15px 0; border: 2px dashed #ddd;">
                    <strong style="font-size:18px;"><?php echo $nama_item; ?></strong>
                </div>
                <small style="color:#999;">ID: #<?php echo rand(1000, 9999); ?> | Sisa: <?php echo ($poin_sekarang - $poin_dibutuhkan); ?> Pts</small>
                <a href="dashboard_user.php" class="btn-done">Selesai</a>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
} else {
    echo "<script>alert('Poin Anda tidak cukup!'); window.location='redeem_menu.php';</script>";
}
?>