<?php
include 'config.php';
session_start();

// Pastikan pengecekan session sesuai dengan file login Mas Rian
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user_id']; 
// Ambil data terbaru dari database
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id_user'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Member - Ai-CHA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            /* Background Gambar sesuai keinginan Mas Rian */
            background: url('images/background.jpg') no-repeat center center fixed; 
            background-size: cover;
            
            /* Mengetengahkan Kartu */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Kartu Member dengan Efek Kaca (Glassmorphism) */
        .card-member { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px); /* Support Safari */
            width: 100%;
            max-width: 400px; /* Diperkecil sedikit agar lebih proporsional */
            padding: 40px 30px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Foto Profil Gaya Modern */
        .profile-pic { 
            width: 90px; 
            height: 90px; 
            background: linear-gradient(135deg, #e63946, #ff4d6d);
            color: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 40px; 
            margin: 0 auto 15px;
            box-shadow: 0 10px 20px rgba(230, 57, 70, 0.3);
            border: 4px solid white;
        }

        h3 { 
            margin: 10px 0 5px; 
            color: #333; 
            font-weight: 700;
        }

        .status-badge { 
            background: #e8f5e9; 
            color: #2e7d32; 
            padding: 6px 16px; 
            border-radius: 20px; 
            font-weight: bold; 
            font-size: 12px;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* List Informasi */
        .info-container {
            text-align: left;
            margin-top: 10px;
        }

        .info-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 5px; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
            font-size: 14px; 
        }

        .info-row span {
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row strong {
            color: #333;
        }

        /* Tombol Kembali */
        .btn-back { 
            margin-top: 30px; 
            text-decoration: none; 
            color: #e63946; 
            font-weight: 600; 
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.3s ease;
        }

        .btn-back:hover {
            transform: translateX(-5px);
            color: #c12a36;
        }

        .pts-value {
            color: #ffc107 !important;
        }
    </style>
</head>
<body>

<div class="card-member">
    <div class="profile-pic">
        <i class="fas fa-user"></i>
    </div>
    
    <h3><?php echo htmlspecialchars($user['Nama']); ?></h3>
    
    <div>
        <span class="status-badge">
            <i class="fas fa-check-circle"></i> Member Aktif
        </span>
    </div>
    
    <div class="info-container">
        <div class="info-row">
            <span><i class="fas fa-phone-alt"></i> Nomor HP</span>
            <strong><?php echo htmlspecialchars($user['NoHP']); ?></strong>
        </div>
        <div class="info-row">
            <span><i class="fas fa-stamp"></i> Total Stamp</span>
            <strong><?php echo $user['Total_Stamp']; ?> / 10 Stamp</strong>
        </div>
       <div class="info-row">
            <span><i class="fas fa-coins" style="color: #f1c40f; margin-right: 8px;"></i> Member Point</span>
            <span class="value" style="color: #f1c40f; font-weight: bold;">
            <?php echo number_format($user['Member_point']); ?> Pts
            </span>
        </div>
    </div>

    <a href="dashboard_user.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

</body>
</html>