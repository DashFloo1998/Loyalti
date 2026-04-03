<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$id_user = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id_user'");
$user = mysqli_fetch_assoc($query);

$halaman = isset($_GET['p']) ? (int)$_GET['p'] : 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Menu - Ai-CHA Premium</title>
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
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.2); }

        .main-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            width: 95%; max-width: 1000px;
            padding: 40px; border-radius: 40px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.1);
            position: relative; min-height: 85vh;
            display: flex; flex-direction: column; box-sizing: border-box;
        }

        .header-redeem { text-align: center; margin-bottom: 30px; }
        .header-redeem h2 { font-weight: 800; color: #333; margin: 0; font-size: 28px; }
        .points-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; padding: 10px 20px; border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-top: 10px;
            color: var(--accent); font-weight: 800; font-size: 16px;
        }

        /* MENU GRID ADAPTIVE */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 80px;
        }

        .menu-card {
            background: white; border-radius: 30px; padding: 20px;
            text-align: center; transition: 0.3s;
            border: 1px solid #f0f0f0; position: relative;
        }
        .menu-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: var(--primary); }

        .menu-card img {
            width: 100%; height: 180px; object-fit: contain;
            margin-bottom: 15px; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.1));
        }

        .menu-card h4 { margin: 0 0 15px; font-size: 14px; font-weight: 800; color: #333; height: 40px; display: flex; align-items: center; justify-content: center; }

        .poin-box {
            background: var(--primary); color: white; padding: 12px;
            border-radius: 18px; font-weight: 800; font-size: 12px;
            transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 5px;
        }
        .menu-card:hover .poin-box { background: #333; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

        /* NAVIGASI BAWAH */
        .bottom-nav {
            margin-top: auto; display: flex; justify-content: space-between; align-items: center;
        }

        .btn-nav {
            padding: 12px 25px; border-radius: 20px; font-weight: 800; font-size: 12px;
            text-decoration: none; display: flex; align-items: center; gap: 10px; transition: 0.3s;
        }
        .btn-home { background: #333; color: white; }
        .btn-page { background: var(--primary); color: white; box-shadow: 0 10px 20px rgba(230, 57, 70, 0.2); }
        .btn-nav:hover { opacity: 0.9; transform: scale(1.05); }

        @media (max-width: 600px) {
            .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .main-container { padding: 25px 15px; }
            .menu-card { padding: 15px; border-radius: 25px; }
            .menu-card img { height: 120px; }
            .menu-card h4 { font-size: 11px; }
        }

        .plus-jakarta-sans-title {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 800 !important;
        }
        .premium-popup {
            border-radius: 35px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        </style>
</head>
<body>

<div class="bg-fixed"></div>

<div class="main-container">
    <div class="header-redeem">
        <h2>Tukar Hadiah 🍦</h2>
        <div class="points-badge">
            <i class="fas fa-coins"></i> <?php echo number_format($user['Member_point']); ?> Pts
        </div>
    </div>

    <div class="menu-grid">
        <?php
        // Data menu disederhanakan agar kodingan bersih
        $menus = [
            1 => [
                ['img' => 'supreeme.webp', 'nama' => 'Supreeme Mixed', 'pts' => 300],
                ['img' => 'Lemonade.png', 'nama' => 'Ai-Squash Lemonade', 'pts' => 100],
                ['img' => 'oreo.png', 'nama' => 'Sundai Choco Cookies', 'pts' => 120],
                ['img' => 'smtoreo.png', 'nama' => 'Ai-Frappe Choco Cookies', 'pts' => 120],
                ['img' => 'str.png', 'nama' => 'Sundai Strawberry', 'pts' => 120],
                ['img' => 'smtmatcha.png', 'nama' => 'Ai-Frappe Matcha', 'pts' => 350],
                ['img' => 'brwnsgr.webp', 'nama' => 'Ai-MilkTea Brownsugar', 'pts' => 5200],
            ],
            2 => [
                ['img' => 'smtmango.png', 'nama' => 'Mango Smoothies', 'pts' => 5200],
                ['img' => 'cofeeL.png', 'nama' => 'Coffee Latte', 'pts' => 5200],
            ]
        ];

        foreach ($menus[$halaman] as $item): ?>
            <div class="menu-card">
                <img src="images/<?php echo $item['img']; ?>" alt="<?php echo $item['nama']; ?>">
                <h4><?php echo $item['nama']; ?></h4>
               <form action="proses_redeem.php" method="POST">
    <input type="hidden" name="poin_dibutuhkan" value="<?php echo $item['pts']; ?>"> 
    <input type="hidden" name="nama_item" value="<?php echo $item['nama']; ?>"> 
    
    <button type="button" onclick="konfirmasiRedeem(this)" style="border: none; background: none; padding: 0; cursor: pointer; width: 100%;">
        <div class="poin-box">
            <i class="fas fa-ticket-alt"></i> <?php echo number_format($item['pts']); ?> Pts
        </div>
    </button>
</form>
                            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-bottom: 20px; font-size: 11px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: 2px;">
        Halaman <?php echo $halaman; ?> / 2
    </div>

    <div class="bottom-nav">
        <a href="dashboard_user.php" class="btn-nav btn-home">
            <i class="fas fa-home"></i> <span class="hide-mobile">Menu Utama</span>
        </a>

        <?php if ($halaman == 1): ?>
            <a href="redeem_menu.php?p=2" class="btn-nav btn-page">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </a>
        <?php else: ?>
            <a href="redeem_menu.php?p=1" class="btn-nav btn-page">
                <i class="fas fa-arrow-left"></i> Sebelumnya
            </a>
        <?php endif; ?>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiRedeem(btn) {
    const form = btn.closest('form');
    const namaItem = form.querySelector('input[name="nama_item"]').value;
    const poinReq = form.querySelector('input[name="poin_dibutuhkan"]').value;

    Swal.fire({
        title: 'Konfirmasi Tukar',
        text: `Tukar ${poinReq} Poin kamu dengan ${namaItem}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#333',
        confirmButtonText: 'Ya, Tukar!',
        cancelButtonText: 'Batal',
        borderRadius: '35px'
    }).then((result) => {
        if (result.isConfirmed) {
            // BUAT INPUT HIDDEN DARURAT AGAR PHP MENGENALI $_POST['redeem']
            const inputRedeem = document.createElement('input');
            inputRedeem.type = 'hidden';
            inputRedeem.name = 'redeem'; // Ini yang dicari oleh proses_redeem.php
            inputRedeem.value = 'true';
            form.appendChild(inputRedeem);

            // SEKARANG KIRIM FORM
            form.submit();
        }
    });
}
</script>
</body>
</html>