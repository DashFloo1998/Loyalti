<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user_id']; 
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id_user'");
$user = mysqli_fetch_assoc($query);

// Tentukan warna poin berdasarkan total redeem agar sinkron dengan Dashboard
$total_redeem = (int)$user['Total_Redeem'];
$tier_color = "#e63946"; // Default Black
if ($total_redeem >= 50000) $tier_color = "#00b4d8"; // Platinum Chrome (pakai aksen biru)
elseif ($total_redeem >= 30000) $tier_color = "#00b4d8"; // Super
elseif ($total_redeem >= 15000) $tier_color = "#ffb703"; // Gold
elseif ($total_redeem >= 5000) $tier_color = "#c0c0c0"; // Silver
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Member - Ai-CHA Premium</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root { --primary: #e63946; --accent: #ffb703; }

        body {
            margin: 0; padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; overflow-x: hidden;
        }

        /* LAYER BACKGROUND ANTI-ZOOM */
        .bg-fixed {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: url('images/logored.jpg') no-repeat center center; 
            background-size: cover; z-index: -1;
        }
        .bg-fixed::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.1); /* Hitam transparan 10% */
            
        }

        /* KARTU MEMBER PREMIUM */
        .card-member { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 90%; max-width: 420px;
            padding: 50px 35px;
            border-radius: 40px; /* Radius mewah dashboard */
            box-shadow: 0 40px 80px rgba(0,0,0,0.1);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-sizing: border-box;
        }

        /* FOTO PROFIL GAYA DASHBOARD */
        .profile-pic { 
            width: 100px; height: 100px; 
            background: linear-gradient(135deg, var(--primary), #ff4d6d);
            color: white; border-radius: 35px; /* Kotak bulat modern */
            display: flex; align-items: center; justify-content: center; 
            font-size: 45px; margin: 0 auto 20px;
            box-shadow: 0 15px 30px rgba(230, 57, 70, 0.2);
            border: 5px solid white;
        }

        h3 { margin: 0; color: #333; font-weight: 800; font-size: 24px; letter-spacing: -0.5px; }

        .status-badge { 
            background: #e8f5e9; color: #2e7d32; padding: 6px 18px; 
            border-radius: 20px; font-weight: 800; font-size: 10px;
            display: inline-block; margin: 15px 0 30px;
            text-transform: uppercase; letter-spacing: 1px;
        }

        /* INFO LIST GAYA CLEAN */
        .info-container { text-align: left; background: rgba(0,0,0,0.02); padding: 25px; border-radius: 30px; }
        .info-row { 
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 0; border-bottom: 1px solid rgba(0,0,0,0.05); 
        }
        .info-row:last-child { border: none; }
        .info-row span { color: #888; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .info-row strong { color: #333; font-size: 14px; font-weight: 700; }
        .info-row i { color: var(--primary); width: 20px; text-align: center; }

        /* TOMBOL KEMBALI GAYA DASHBOARD */
        .btn-back { 
            margin-top: 35px; text-decoration: none; color: #bbb; 
            font-weight: 800; font-size: 12px; text-transform: uppercase;
            display: flex; align-items: center; justify-content: center;
            gap: 10px; transition: 0.3s ease; letter-spacing: 1px;
        }
        .btn-back:hover { transform: translateX(-5px); color: var(--primary); }

        .pts-value { color: <?php echo $tier_color; ?> !important; font-weight: 800 !important; }
    </style>
</head>
<body>

<div class="bg-fixed"></div>

<div class="card-member">
    <div class="profile-pic">
        <i class="fas fa-user-astronaut"></i>
    </div>
    
    <h3><?php echo htmlspecialchars($user['Nama']); ?></h3>
    
    <span class="status-badge">
        <i class="fas fa-check-circle"></i> Verified Member
    </span>
    
    <div class="info-container">
        <div class="info-row">
            <span><i class="fas fa-phone-alt"></i> Nomor HP</span>
            <strong><?php echo htmlspecialchars($user['NoHP']); ?></strong>
        </div>
        <div class="info-row">
            <span><i class="fas fa-id-card"></i> Member ID</span>
            <strong>#<?php echo str_pad($user['ID'], 6, '0', STR_PAD_LEFT); ?></strong>
        </div>
        <div class="info-row">
            <span><i class="fas fa-stamp"></i> Total Stamp</span>
            <strong><?php echo $user['Total_Stamp']; ?> / 10</strong>
        </div>
       <div class="info-row">
            <span><i class="fas fa-coins"></i> Member Point</span>
            <strong class="pts-value"><?php echo number_format($user['Member_point']); ?> Pts</strong>
        </div>
    </div>

    <a href="dashboard_user.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

</body>
</html>