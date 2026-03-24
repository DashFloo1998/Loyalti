<?php
session_start();
include 'config.php';

// Proteksi: Jika belum login atau bukan customer, lempar ke login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];
// Ambil data (Gunakan Nama Kolom sesuai DB: Nama, Total_Stamp)
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id'");
$user = mysqli_fetch_assoc($query);

$total_stamp = $user['Total_Stamp'];
$limit = 10;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Loyalti - <?php echo $user['Nama']; ?></title>
    <style>
        :root { --primary: #4239e6; --dark: #ff0000; --light: #f1faee; }
        body { background: url('images/background.jpg') no-repeat center center fixed; background-size: cover; font-family: 'Poppins', sans-serif; ;background-color: #f4f4f4; margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
         .main-container { background: rgba(255, 255, 255, 0.9); 
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
            gap: 10px; }
        header h1 { color: var(--dark); font-size: 22px; margin-bottom: 5px; }
        header p { color: #666; font-size: 14px; margin-top: 0; }
        
        .loyalty-card { background: var(--dark); color: white; padding: 20px; border-radius: 15px; margin: 20px 0; position: relative; overflow: hidden; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; }
        .brand-name { font-weight: bold; letter-spacing: 1px; color: #ffffff; }
        
        .stamp-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px; }
        .circle { width: 50px; height: 50px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; transition: 0.3s; }
        .circle.active { background: #ffca3a; border-style: solid; color: #333; transform: scale(1.1); box-shadow: 0 0 10px rgba(255,202,58,0.5); border-color: white; }
        .circle.free { background: var(--primary); border-style: solid; color: white; }

        .card-footer p { font-size: 14px; margin-bottom: 8px; }
        progress { width: 100%; height: 12px; border-radius: 10px; }
        progress::-webkit-progress-bar { background: #eee; border-radius: 10px; }
        progress::-webkit-progress-value { background: #ffca3a; border-radius: 10px; }

        .promo-text { background: #fff3cd; color: #856404; padding: 10px; border-radius: 8px; font-size: 13px; margin: 15px 0; }
        .btn-logout { display: block; text-decoration: none; color: var(--primary); font-weight: bold; margin-top: 20px; font-size: 14px; }
        .btn-back { display: inline-block; margin-top: 20px; text-decoration: none; color: #3956e6; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>

<div class="main-container">
    <header>
        <h1>Halo, <?php echo $user['Nama']; ?>!</h1>
        <p>Kumpulkan 5 dan 10 stamp untuk 1 minuman gratis</p>
    </header>

    <div class="loyalty-card">
        <div class="card-header">
            <span style="font-size: 15px;"><strong>LOYALTY CARD</span>
            <span class="brand-name"><strong>AI-CHA</span>
        </div>
        
       <div class="stamp-grid">
    <?php
        $logo_path = "images/logo.jpg";
    for ($i = 1; $i <= $limit; $i++) {
        $status = ($i <= $total_stamp) ? 'active' : '';
        
        // Logika baru: Cek apakah angka 5 atau 10
        $is_free_milestone = ($i == 5 || $i == 10);
        $class_free = $is_free_milestone ? 'free' : '';
        $text_display = $is_free_milestone ? 'FREE' : $i;
        
        echo "<div class='circle $status $class_free'>";
        if ($i <= $total_stamp) {
          echo "<img src='$logo_path' style='width: 90%; height: 90%; object-fit: contain; border-radius: 50%;'>";
} else {
            echo $text_display;
        }
        echo "</div>";
    }
    ?>
</div>

        <div class="card-footer">
            <p>Progres: <strong><?php echo $total_stamp; ?></strong> / 10 Stamp</p>
            <progress value="<?php echo $total_stamp; ?>" max="10"></progress>
        </div>
    </div>

    <div class="info-section">
        <p class="promo-text">Kumpulkan Stampnya dan dapatkan Minuman <strong>GRATIS!</strong></p>
        <a href="logout.php" class="btn-logout">Keluar Aplikasi</a>
    </div>

    <br><a href="dashboard_user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    
</div>

</body>
</html>