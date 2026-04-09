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
    :root { --primary: #e63946; --accent: #ffb703; --stamp-color: #2a9d8f; }

    body {
        margin: 0; padding: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: flex; justify-content: center; 
        /* Ganti align-items: center jadi flex-start supaya di mobile gak kepotong atasnya */
        align-items: center; 
        min-height: 100vh; background: #f8f8f8;
        padding-top: 20px; padding-bottom: 20px;
    }

    .bg-fixed {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: url('images/logored.jpg') no-repeat center center; 
        background-size: cover; z-index: -1;
    }
    .bg-fixed::after {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4); }

    .main-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        width: 95%; max-width: 900px;
        /* Padding diperkecil untuk mobile */
        padding: 20px; border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        position: relative;
        display: flex; flex-direction: column; box-sizing: border-box;
    }

    .header-redeem { text-align: center; margin-bottom: 15px; }
    .header-redeem h2 { font-weight: 800; color: #333; margin: 0; font-size: 22px; }
    
    .wallet-wrapper { display: flex; justify-content: center; gap: 8px; margin-top: 10px; }
    .badge-info {
        background: white; padding: 8px 12px; border-radius: 15px;
        box-shadow: 0 5px 10px rgba(0,0,0,0.05); font-weight: 800; font-size: 12px;
    }

    .tab-container {
        display: flex; background: #eee; padding: 4px; border-radius: 15px;
        margin: 15px 0; gap: 4px;
    }
    .btn-tab {
        flex: 1; padding: 10px; border: none; border-radius: 12px;
        font-weight: 800; cursor: pointer; transition: 0.3s; font-size: 11px;
    }
    .btn-tab.active { background: white; color: var(--primary); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }

    /* Grid Menu dioptimalkan */
    .menu-grid {
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px; margin-bottom: 30px;
    }

    .menu-card {
        background: white; border-radius: 20px; padding: 12px;
        text-align: center; border: 1px solid #eee;
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .menu-card img { width: 100%; height: 110px; object-fit: contain; margin-bottom: 8px; }
    .menu-card h4 { margin: 0 0 8px; font-size: 12px; font-weight: 800; color: #333; height: 32px; overflow: hidden; line-height: 1.3; }

    .poin-box {
        color: white; padding: 8px; border-radius: 12px;
        font-weight: 800; font-size: 10px; display: flex; align-items: center; justify-content: center; gap: 4px;
    }
    .bg-pts { background: var(--primary); }
    .bg-stp { background: var(--stamp-color); }

    .bottom-nav { margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
    .btn-nav { padding: 10px 15px; border-radius: 12px; font-weight: 800; font-size: 11px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
    .btn-home { background: #333; color: white; }
    .btn-page { background: var(--primary); color: white; }

    /* --- RESPONSIVE KHUSUS MOBILE --- */
    @media (max-width: 600px) {
        body { padding-top: 10px; }
        .main-container { padding: 15px; border-radius: 25px; min-height: auto; }
        /* Paksa 2 kolom yang pas di HP */
        .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .menu-card { padding: 10px; }
        .menu-card img { height: 90px; }
        .header-redeem h2 { font-size: 20px; }
        .badge-info { font-size: 10px; padding: 6px 10px; }
        .btn-tab { padding: 8px; font-size: 10px; }
    }
    </style>
</head>
<body>
<div class="bg-fixed"></div>

<div class="main-container">
    <div class="header-redeem">
        <h2>Tukar Hadiah 🍦</h2>
        <div class="wallet-wrapper">
            <div class="badge-info" style="color:var(--accent);"><i class="fas fa-coins"></i> <?php echo number_format($user['Member_point']); ?> Pts</div>
            <div class="badge-info" style="color:var(--stamp-color);"><i class="fas fa-stamp"></i> <?php echo $user['Total_Stamp']; ?> Stamp</div>
        </div>
    </div>

    <div class="tab-container">
        <button class="btn-tab active" onclick="switchTab('poin', this)">REDEEM POIN</button>
        <button class="btn-tab" onclick="switchTab('stamp', this)">REDEEM STAMP</button>
    </div>

    <div id="tab-poin" class="tab-content">
        <div class="menu-grid">
            <?php
            $menus_poin = [
                1 => [
                    ['img' => 'supreeme.webp', 'nama' => 'Supreeme Mixed', 'pts' => 350],
                    ['img' => 'Lemonade.png', 'nama' => 'Ai-Squash Lemonade', 'pts' => 120],
                    ['img' => 'oreo.png', 'nama' => 'Sundai Choco Cookies', 'pts' => 200],
                    ['img' => 'smtoreo.png', 'nama' => 'Ai-Frappe Choco Cookies', 'pts' => 250],
                    ['img' => 'str.png', 'nama' => 'Sundai Strawberry', 'pts' => 200],
                    ['img' => 'Orangemango.png', 'nama' => 'Ai-Fresh Orange Mango', 'pts' => 400],
                    ['img' => 'smtmatcha.png', 'nama' => 'Ai-Frappe Matcha', 'pts' => 400],
                    ['img' => 'Lemonjasmine.png', 'nama' => 'Ai-Lemon Jasmine Tea', 'pts' => 400],
                ],
                2 => [
                    ['img' => 'brwnsgr.webp', 'nama' => 'Ai-MilkTea Brownsugar', 'pts' => 400],
                    ['img' => 'smtmango.png', 'nama' => 'Mango Smoothies', 'pts' => 850],
                    ['img' => 'cofeeL.png', 'nama' => 'Coffee Latte', 'pts' => 1000],
                    ['img' => 'boba.png', 'nama' => 'Sundai Boba', 'pts' => 200],
                ]
            ];
            foreach ($menus_poin[$halaman] as $item): ?>
                <div class="menu-card">
                    <img src="images/<?php echo $item['img']; ?>">
                    <h4><?php echo $item['nama']; ?></h4>
                    <form action="proses_redeem.php" method="POST">
                        <input type="hidden" name="poin_dibutuhkan" value="<?php echo $item['pts']; ?>"> 
                        <input type="hidden" name="nama_item" value="<?php echo $item['nama']; ?>"> 
                        <input type="hidden" name="jenis_redeem" value="poin"> 
                        <button type="button" onclick="konfirmasiRedeem(this, 'poin')" style="border:none; background:none; width:100%; cursor:pointer;">
                            <div class="poin-box bg-pts"><i class="fas fa-ticket-alt"></i> <?php echo $item['pts']; ?> Pts</div>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="tab-stamp" class="tab-content" style="display:none;">
        <div class="menu-grid">
            <?php
            $menus_stamp = [
                ['img' => 'ConeV.png', 'nama' => 'Free 1 Ice Cream Cone Vanilla', 'stamps' => 5],
                ['img' => 'ConeS.png', 'nama' => 'Free 1 Ice Cream Cone Seasalt', 'stamps' => 5],
                ['img' => 'ConeM.png', 'nama' => 'Free 1 Ice Cream Cone Mix', 'stamps' => 5],
                ['img' => 'oreo.png', 'nama' => 'Free 1 Sundai Choco', 'stamps' => 10],
                ['img' => 'supreeme.webp', 'nama' => 'Free 1 Supreme Mixed', 'stamps' => 10],
                ['img' => 'Blueberry.png', 'nama' => 'Free 1 Ai-Blueberry', 'stamps' => 10],
                ['img' => 'Lemonade.png', 'nama' => 'Free 1 Ai-Squash Lemonade', 'stamps' => 10],
                ['img' => 'smtoreo.png', 'nama' => 'Free 1 Ai-Frappe Choco Cookies', 'stamps' => 10],
            ];
            foreach ($menus_stamp as $item): ?>
                <div class="menu-card">
                    <img src="images/<?php echo $item['img']; ?>">
                    <h4><?php echo $item['nama']; ?></h4>
                    <form action="proses_redeem.php" method="POST">
                        <input type="hidden" name="poin_dibutuhkan" value="<?php echo $item['stamps']; ?>"> 
                        <input type="hidden" name="nama_item" value="<?php echo $item['nama']; ?>"> 
                        <input type="hidden" name="jenis_redeem" value="stamp"> 
                        <button type="button" onclick="konfirmasiRedeem(this, 'stamp')" style="border:none; background:none; width:100%; cursor:pointer;">
                            <div class="poin-box bg-stp"><i class="fas fa-stamp"></i> <?php echo $item['stamps']; ?> Stamp</div>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="dashboard_user.php" class="btn-nav btn-home"><i class="fas fa-home"></i> Dashboard</a>
        <div id="pagination-pts">
            <?php if ($halaman == 1): ?>
                <a href="redeem_menu.php?p=2" class="btn-nav btn-page">Next <i class="fas fa-arrow-right"></i></a>
            <?php else: ?>
                <a href="redeem_menu.php?p=1" class="btn-nav btn-page"><i class="fas fa-arrow-left"></i> Prev</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function switchTab(type, btn) {
    document.getElementById('tab-poin').style.display = (type === 'poin') ? 'block' : 'none';
    document.getElementById('tab-stamp').style.display = (type === 'stamp') ? 'block' : 'none';
    document.getElementById('pagination-pts').style.display = (type === 'poin') ? 'block' : 'none';
    
    document.querySelectorAll('.btn-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function konfirmasiRedeem(btn, jenis) {
    const form = btn.closest('form');
    const namaItem = form.querySelector('input[name="nama_item"]').value;
    const req = form.querySelector('input[name="poin_dibutuhkan"]').value;
    const unit = (jenis === 'poin') ? 'Poin' : 'Stamp';

    Swal.fire({
        title: 'Konfirmasi Tukar',
        text: `Tukar ${req} ${unit} kamu dengan ${namaItem}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: (jenis === 'poin') ? '#e63946' : '#2a9d8f',
        cancelButtonColor: '#333',
        confirmButtonText: 'Ya, Tukar!',
        borderRadius: '35px'
    }).then((result) => {
        if (result.isConfirmed) {
            const inputRedeem = document.createElement('input');
            inputRedeem.type = 'hidden';
            inputRedeem.name = 'redeem';
            inputRedeem.value = 'true';
            form.appendChild(inputRedeem);
            form.submit();
        }
    });
}
</script>
</body>
</html>