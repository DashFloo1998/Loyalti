<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id'");
$user = mysqli_fetch_assoc($query);

$total_stamp = (int)$user['Total_Stamp'];
$limit = 10;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loyalty Card - Ai-CHA Premium</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #e63946; --accent: #26f234; --bg-dark: #1a1a1a; }
        
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
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.2); 
        }

        .main-container { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(20px);
            width: 90%; max-width: 420px; 
            padding: 40px 30px; border-radius: 40px; 
            box-shadow: 0 40px 80px rgba(0,0,0,0.1);
            text-align: center; border: 1px solid rgba(255, 255, 255, 0.5);
            box-sizing: border-box;
        }

        header h1 { color: #333; font-weight: 800; font-size: 24px; margin-bottom: 5px; letter-spacing: -1px; }
        header p { color: #888; font-size: 12px; margin-top: 0; font-weight: 600; }
        
        /* GAYA KARTU LOYALTY PREMIUM */
        .loyalty-card { 
            background: linear-gradient(135deg, #222 0%, #000 100%); 
            color: white; padding: 25px; border-radius: 30px; 
            margin: 25px 0; position: relative; overflow: hidden; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        /* Efek Kilau Kartu */
        .loyalty-card::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        }

        .card-header { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); 
            padding-bottom: 15px; position: relative; z-index: 2;
        }
        .card-title { font-weight: 800; font-size: 11px; letter-spacing: 2px; color: #ffffff; }
        .brand-name { font-weight: 800; color: white; font-size: 14px; }
        
        /* GRID STAMP GAYA BARU */
        /* --- FIX JARAK STAMP (Gap Diperlebar) --- */
        .stamp-grid { 
            display: grid; 
            grid-template-columns: repeat(5, 1fr); 
            gap: 15px; /* Sebelumnya mungkin 10px, kita naikkan jadi 15px */
            margin-bottom: 25px; 
            position: relative; 
            z-index: 2; 
        }

        /* --- FIX BULATAN STAMP (Padding Ditambah) --- */
        .circle { 
            width: 100%; 
            aspect-ratio: 1/1; 
            background: rgba(255,255,255,0.05); 
            border: 2px dashed rgba(255,255,255,0.2); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 800; 
            font-size: 11px; 
            color: rgba(255,255,255,0.3);
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            
            /* Tambahkan Padding di sini agar logo tidak dempet ke pinggir */
            padding: 8px; /* Memberi ruang napas untuk logo pinguin */
            box-sizing: border-box; /* Pastikan padding tidak merusak ukuran bulatan */
        }

        /* Saat Stamp Terisi */
        .circle.active { 
            background:red; border: 2px solid white; 
            transform: scale(1.1); 
            box-shadow: 0 0 20px rgba(255,255,255,0.4); 
        }
        
        /* Bulatan Milestone (5 & 10) */
        .circle.free { border-color: var(--accent); color: var(--accent); }
        .circle.active.free { background: var(--accent); border-color: var(--accent); box-shadow: 0 0 20px rgba(255,255,255,0.4);  }

        .card-footer { position: relative; z-index: 2; }
        .card-footer p { font-size: 12px; font-weight: 700; margin-bottom: 10px; color: #ccc; }
        
        /* PROGRESS BAR NEON */
        .progress-container { width: 100%; height: 8px; background: #333; border-radius: 10px; overflow: hidden; }
        .progress-bar { 
            height: 100%; background: var(--primary); 
            box-shadow: 0 0 15px var(--primary); 
            transition: 1s ease-out; 
        }

        .promo-text { 
            background: #fff5f5; color: var(--primary); 
            padding: 15px; border-radius: 20px; font-size: 12px; 
            margin: 20px 0; font-weight: 700; border: 1px solid #ffebeb;
        }

        .btn-back { 
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 25px; text-decoration: none; color: #bbb; 
            font-weight: 800; font-size: 12px; text-transform: uppercase;
            letter-spacing: 1px; transition: 0.3s;
        }
        .btn-back:hover { color: var(--primary); transform: translateX(-5px); }
    </style>
</head>
<body>

<div class="bg-fixed"></div>

<div class="main-container">
    <header>
        <h1>Halo <?php echo htmlspecialchars($user['Nama']); ?></h1>
        <p>Kumpulkan stamp dan nikmati ice cream & minuman gratis</p>
    </header>

    <div class="loyalty-card">
        <div class="card-header">
            <span class="card-title">LOYALTY PROGRAM</span>
            <span class="brand-name">Ai-CHA</span>
        </div>
        
        <div class="stamp-grid">
            <?php
            $logo_path = "images/logotp1.png"; // Gunakan logo PNG transparan Mas
            for ($i = 1; $i <= $limit; $i++) {
                $status = ($i <= $total_stamp) ? 'active' : '';
                $is_free_milestone = ($i == 5 || $i == 10);
                $class_free = $is_free_milestone ? 'free' : '';
                $text_display = $is_free_milestone ? 'FREE' : $i;
                
                echo "<div class='circle $status $class_free'>";
                if ($i <= $total_stamp) {
                    // Gunakan width/height 100% dan object-fit: contain
                    echo "<img src='$logo_path' style='width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));' onerror=\"this.style.display='none'; this.parentNode.innerText='✓'\">";
                } else {
                    echo $text_display;
                }
                echo "</div>"; }?>
        </div>

        <div class="card-footer">
            <p>Progres: <?php echo $total_stamp; ?> / 10 Stamp</p>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo ($total_stamp / 10) * 100; ?>%;"></div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="promo-text">
            <i class="fas fa-info-circle"></i> Setiap 5 & 10 stamp bisa ditukar ice cream dan minuman GRATIS!
        </div>
    </div>

    <a href="dashboard_user.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

</body>
</html>