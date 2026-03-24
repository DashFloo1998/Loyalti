<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$id_user = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id_user'");
$user = mysqli_fetch_assoc($query);

// Logika Perpindahan Halaman (Halaman 1 atau 2)
$halaman = isset($_GET['p']) ? (int)$_GET['p'] : 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Menu Ai-CHA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       body {
    background: url('images/background.jpg') no-repeat center center fixed; 
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    
    /* Kunci agar konten di tengah layar */
    display: flex;
    justify-content: center; /* Center Horizontal */
    align-items: center;     /* Center Vertikal */
    min-height: 100vh;       /* Tinggi minimal 100% layar */
}

.main-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    width: 90%;             /* Agar tidak mentok di HP */
    max-width: 800px;       /* Ukuran ideal yang Mas mau */
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    
    /* Hapus margin: 15px auto karena sudah diatur Flexbox body */
    position: relative;
    min-height: 80vh; 
    display: flex;
    flex-direction: column;
}

       /* GRID 3 KOLOM */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 3 Baris kesamping */
            gap: 10px; /* Jarak antar menu dipersempit */
            margin-top: 10px;
        }
        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .menu-card img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
        }

        .btn-redeem {
            background: #e63946;
            color: white;
            border: none;
            padding: 6px;
            border-radius: 8px;
            width: 100%;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-redeem:disabled { background: #ccc; }

        /* Tombol Navigasi Halaman (Pojok Kanan Bawah) */
        .nav-next {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: #e63946;
            color: white;
            padding: 10px 15px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(230, 57, 70, 0.3);
        }

        /* Tombol Kembali Mini (Pojok Kiri Bawah) */
        .nav-back {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
        }

        .page-indicator {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="main-container">
    <div style="text-align: center; margin-bottom: 15px;">
        <h3 style="color: #e63946; margin: 0;">Tukar Hadiah</h3>
        <span style="font-size: 13px; font-weight: bold; color: #f1c40f;">
            <i class="fas fa-coins"></i> <?php echo number_format($user['Member_point']); ?> Pts
        </span>
    </div>

    <div class="menu-grid">
        <?php if ($halaman == 1): ?>
            <div class="menu-card">
                <img src="images/menu1.jpg">
                <h6 style="margin:5px 0;">Milk Tea L</h6>
                <button class="btn-redeem">500 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu2.jpg">
                <h6 style="margin:5px 0;">Ai-Squash Lemonade</h6>
                <button class="btn-redeem">300 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu3.jpg">
                <h6 style="margin:5px 0;">Sundai Choco Cookies</h6>
                <button class="btn-redeem">450 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu4.jpg">
                <h6 style="margin:5px 0;">Ai-Frappe Choco Cookies</h6>
                <button class="btn-redeem">400 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu4.jpg">
                <h6 style="margin:5px 0;">Sundai Strawberry</h6>
                <button class="btn-redeem">400 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu4.jpg">
                <h6 style="margin:5px 0;">Ai-Frappe Matcha</h6>
                <button class="btn-redeem">400 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu4.jpg">
                <h6 style="margin:5px 0;">Ai-MilkTea Brownsugar Pearl</h6>
                <button class="btn-redeem">400 Pts</button>
            </div>

        <?php else: ?>
            <div class="menu-card">
                <img src="images/menu5.jpg">
                <h6 style="margin:5px 0;">Mango Smoothies</h6>
                <button class="btn-redeem">600 Pts</button>
            </div>
            <div class="menu-card">
                <img src="images/menu6.jpg">
                <h6 style="margin:5px 0;">Coffee Jelly</h6>
                <button class="btn-redeem">550 Pts</button>
            </div>
        <?php endif; ?>
    </div>

    <div class="page-indicator">Halaman <?php echo $halaman; ?> dari 2</div>

    <a href="dashboard_user.php" class="nav-back"><i class="fas fa-home"></i> Menu Utama</a>

    <?php if ($halaman == 1): ?>
        <a href="redeem_menu.php?p=2" class="nav-next">Selanjutnya <i class="fas fa-arrow-right"></i></a>
    <?php else: ?>
        <a href="redeem_menu.php?p=1" class="nav-next"><i class="fas fa-arrow-left"></i> Sebelumnya</a>
    <?php endif; ?>
</div>

</body>
</html>