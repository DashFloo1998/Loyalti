<?php
session_start();
include 'config.php'; 

$id_user = $_SESSION['user_id']; 
$query = mysqli_query($conn, "SELECT * FROM tbmember WHERE ID = '$id_user'");
$user = mysqli_fetch_assoc($query);

// --- LOGIKA BARU ---
$current_pts = isset($user['Member_point']) ? (int)$user['Member_point'] : 0;
$total_redeem = isset($user['Total_Redeem']) ? (int)$user['Total_Redeem'] : 0; // Poin yang sudah dipakai

// AMBIL DATA DULU
$total_redeem = (int)$user['Total_Redeem']; 
$pts_silver = 1500;
$pts_gold = 2500;
$pts_super = 4000;
$pts_ultimate = 8000;

// LOGIKA PENENTUAN WARNA (Gunakan >= agar pas angka 5000 langsung berubah)
if ($total_redeem >= $pts_ultimate) {
    $tier_name = "ULTIMATE MEMBER";
    $tier_icon = "fa-gem"; // Ikon Permata
    $tier_color = "#e5e4e2"; // Warna Platinum
    // Efek Chrome dengan aksen Biru, Gold, Merah
    $bg_card = "linear-gradient(135deg, #e5e4e2 0%, #ffffff 25%, #0077b6 40%, #ffb703 60%, #e63946 80%, #e5e4e2 100%)";
    $progress_percent = 100;
    $goal_text = "STATUS DEWA: Anda adalah legenda Ai-CHA!";
} elseif ($total_redeem >= $pts_super) {
    $tier_name = "SUPER MEMBER";
    $tier_icon = "fa-bolt"; 
    $tier_color = "#00b4d8"; // Biru Elektrik
    $bg_card = "linear-gradient(135deg, #03045e 0%, #0077b6 100%)";
    $pts_range = $pts_ultimate - $pts_super;
    $progress_percent = (($total_redeem - $pts_super) / $pts_range) * 100;
    $goal_text = "Tukar " . number_format($pts_ultimate - $total_redeem, 0, ',', '.') . " Pts lagi ke Ultimate.";
}
elseif ($total_redeem >= $pts_gold) {
    // --- TAMPILAN GOLD ---
    $tier_name = "GOLD MEMBER";
    $tier_color = "#ffb703";
    $bg_card = "linear-gradient(135deg, #bf953f 0%, #000000 100%)";
} 
elseif ($total_redeem >= $pts_silver) {
    // --- TAMPILAN SILVER (5000 - 14999) ---
    $tier_name = "SILVER MEMBER";
    $tier_color = "#c0c0c0";
    $bg_card = "linear-gradient(135deg, #434343 0%, #000000 100%)";
} 
else {
    // --- TAMPILAN BLACK (Dibawah 5000) ---
    $tier_name = "BLACK MEMBER";
    $tier_color = "#e63946";
    $bg_card = "linear-gradient(135deg, #1a1a1a 0%, #000000 100%)";
}

// Logika Greeting yang Lengkap
date_default_timezone_set('Asia/Jakarta');
$hour = (int)date('H');

if ($hour >= 5 && $hour < 11) {
    $greetings = "Selamat Pagi ☀️";
} elseif ($hour >= 11 && $hour < 15) {
    $greetings = "Selamat Siang 🌤️";
} elseif ($hour >= 15 && $hour < 18) {
    // TAMBAHKAN BARIS INI BIAR JAM 16:00 GAK JADI MALAM
    $greetings = "Selamat Sore 🌥️";
} else {
    $greetings = "Selamat Malam 🌙";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Ai-CHA Dashboard Premium</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #e63946; --accent: #ffb703; }
        
        body { 
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            }
        /* 2. LAYER BACKGROUND (Anti-Zoom) */
        .bg-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Ganti 'background.jpg' dengan path gambar Mas */
            background: url('images/logored.jpg') no-repeat center center; 
            background-size: cover;
            z-index: -1; /* Kirim ke paling belakang */
            transform: scale(1); /* Mengunci rasio agar tidak nge-zoom */
        }

        /* Tambahkan overlay sedikit biar teks lebih terbaca (opsional) */
        .bg-fixed::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.1); /* Hitam transparan 10% */
        }
        
        /* --- CONTAINER UTAMA (ADAPTIVE) --- */
        .apex-shell { 
            background: white; 
            width: 100%; 
            max-width: 950px; /* Diperlebar untuk Laptop */
            padding: 30px; 
            box-sizing: border-box; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh;
            box-shadow: 0 0 50px rgba(0,0,0,0.05);
        }

        /* --- LAYOUT GRID UNTUK LAPTOP --- */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* HEADER */
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        header h2 { margin: 0; font-weight: 800; font-size: clamp(18px, 3vw, 24px); }

        /* KARTU MEMBER (GAGAH) */
        .glossy-card {
            background: <?php echo $bg_card; ?>; 
            
            /* --- KUNCI ANIMASI WARNA --- */
            background-size: 400% 400% !important; /* Diperbesar agar pergeseran warnanya halus */
            animation: gradient-move 8s ease infinite !important; /* Panggil nama animasinya di sini */
            
            padding: 40px; 
            border-radius: 35px; 
            color: white; 
            position: relative; 
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3); 
            margin-bottom: 30px; 
            border: 1px solid <?php echo $tier_color; ?>40;
            transition: all 0.5s ease;
        }

        /* --- LOGIKA PERGERAKAN GRADASI --- */
        @keyframes gradient-move {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        /* EFEK NEON PADA ANGKA POIN */
        .pts-val { 
    font-size: clamp(45px, 5vw, 65px); 
    font-weight: 800; 
    line-height: 1; 
    margin: 15px 0; 
    color: <?php echo $tier_color; ?>; 
    
    /* Neon Glow */
    text-shadow: 
        0 0 10px <?php echo $tier_color; ?>80,
        0 0 20px <?php echo $tier_color; ?>40;
}

    /* Khusus Ultimate, kita kasih teks gradasi biar chrome-nya berasa */
    <?php if($tier_name == "ULTIMATE MEMBER"): ?>
    .pts-val {
        background: linear-gradient(to bottom, #ffffff 0%, #e5e4e2 50%, #abb2b9 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 10px rgba(255,255,255,0.5));
    }
    <?php endif; ?>

    /* EFEK KILATAN CAHAYA (SHINE SWEEP) */
    .glossy-card::before {
        content: ''; 
        position: absolute; 
        top: 0; 
        left: -150%; /* Mulai dari luar kiri */
        width: 50%; 
        height: 100%;
        background: linear-gradient(
            to right, 
            transparent, 
            rgba(255, 255, 255, 0.2), /* Cahaya putih transparan */
            transparent
        );
        transform: skewX(-25deg); /* Miring biar keren */
        animation: shine-sweep 4s infinite; /* Jalan terus tiap 4 detik */
        z-index: 1;
    }

    @keyframes shine-sweep {
        0% { left: -150%; }
        20% { left: 150%; } /* Kilatannya lewat cepat */
        100% { left: 150%; } /* Sisa waktu buat nunggu perulangan */
    }

    /* Pastikan konten di dalam kartu nggk ketutup cahaya */
    .glossy-card > div, 
    .glossy-card > i, 
    .glossy-card > .pts-val {
        position: relative;
        z-index: 2;
    }

        /* Kalau mau efek kedip-kedip halus, aktifkan baris di bawah ini di class .pts-val */
        /* animation: neon-flicker 3s infinite alternate; */

        /* NAVIGASI */
        .nav-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 15px; 
        }
        .nav-item { 
            background: #fff; padding: 25px 10px; border-radius: 25px; text-align: center; 
            text-decoration: none; color: #333; border: 1px solid #eee; transition: 0.3s;
        }
        .nav-item i { font-size: 26px; color: var(--primary); display: block; margin-bottom: 10px; }

        /* OUTLETS */
        .slider-track { display: flex; gap: 20px; overflow-x: auto; padding-bottom: 15px; scrollbar-width: none; }
        .store-card { min-width: 280px; background: #fdfdfd; padding: 25px; border-radius: 30px; border: 1px solid #eee; flex-shrink: 0; }

        /* RESPONSIF KHUSUS LAPTOP (Desktop View) */
        @media (min-width: 850px) {
            .apex-shell { margin: 40px 0; border-radius: 40px; min-height: auto; }
            .desktop-row {
                display: grid;
                grid-template-columns: 1.2fr 1fr; /* Kiri kartu, kanan menu */
                gap: 30px;
                align-items: start;
            }
        }

        /* RESPONSIF HP */
        @media (max-width: 500px) {
            .apex-shell { padding: 20px; max-width: 100%; }
            .nav-grid { grid-template-columns: repeat(2, 1fr); }
            .glossy-card { padding: 25px; }
            .pts-val { font-size: 45px; }
        }
    </style>
</head>
<body>
<div class="bg-fixed"></div>
<div class="apex-shell">
    <header>
        <div>
            <h2><?php echo $greetings; ?> <br><?php echo isset($user['Nama']) ? $user['Nama'] : 'Member'; ?></h2>
            <br><p style="margin: 0; font-size: 11px; color: #888;">Loyalty Program Ai-CHA</p>
        </div>
        <img src="images/logotp.png" style="width: 80px;" alt="Logo">
    </header>

    <div class="glossy-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="text-align: left;">
                <span style="font-size: 10px; font-weight: 800; color: <?php echo $tier_color; ?>; letter-spacing: 2px;"><?php echo $tier_name; ?></span>
                <p style="margin: 0; font-size: 11px; opacity: 0.6; font-weight: 600;">points balance</p>
            </div>
            <i class="fas <?php echo $tier_icon; ?>" style="font-size: 20px; color: <?php echo $tier_color; ?>;"></i>
        </div>

        <div class="pts-val">
            <?php echo number_format($current_pts, 0, ',', '.'); ?> 
            <small style="font-size: 18px; color:  text-shadow: none; font-weight: 400; opacity: 0.6;">Points</small>
        </div>
        
        <div class="neon-container">
            <div class="neon-fill"></div>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-top: 15px; font-size: 9px; font-weight: 700;">
            <span style="color: rgba(255,255,255,0.2);">
                 ID: #<?php echo str_pad((string)$user['ID'], 6, '0', STR_PAD_LEFT); ?></span>
        </div>
    </div>

    <div class="nav-grid">
        <a href="kartu_loyalti.php" class="nav-item"><i class="fas fa-qrcode"></i><span>Loyalty</span></a>
        <a href="redeem_menu.php" class="nav-item"><i class="fas fa-gift"></i><span>Redeem</span></a>
        <a href="info_member_user.php" class="nav-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
        <a href="info_stamp_user.php" class="nav-item"><i class="fas fa-question-circle"></i><span>Support</span></a>
    </div>

    <h3 style="margin: 0 0 12px; font-size: 15px; font-weight: 800;">Our Outlets 📍</h3>
    <div class="slider-track" id="dragSlider">
        <div class="store-card"><h4>Ai-CHA Pluit Village</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Pluit Village Mall Lt. GF, Jakut</p><a href="https://www.google.com/maps/place/Ai-Cha+Ice+Cream+%26+Tea/@-6.1163998,106.7860462,17z/data=!3m1!4b1!4m6!3m5!1s0x2e6a1d72ccfd3b09:0x799afd9cb6a8d7b7!8m2!3d-6.1163998!4d106.7886211!16s%2Fg%2F11v0tmcx85?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA PIK</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Golf Island Rukan Beach, Jakut</p><a href="https://www.google.com/maps/place/AI-CHA+ice+cream+shop/@-6.0926169,106.7373906,17z/data=!3m1!4b1!4m6!3m5!1s0x2e6a1da02350883f:0x8eae3f690621df81!8m2!3d-6.0926222!4d106.7399709!16s%2Fg%2F11txr5_sc2?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Lokasari</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Lokasari Square Lantai Dasar, Jakbar</p><a href="https://www.google.com/maps/place/Ai-Cha+Lokasari/@-6.1473116,106.8210384,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f5006d0a3819:0xa2cea8be41b89570!8m2!3d-6.1473169!4d106.8236187!16s%2Fg%2F11w1qntscw?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Pasar Baru</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Pasar Baru Dalam, Jakpus</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+-+Pasar+Baru/@-6.1624919,106.8306624,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f5815c45dde9:0xa155bb86ed419858!8m2!3d-6.1624972!4d106.8332427!16s%2Fg%2F11kc0wvc77?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Blok M</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Blok M Plaza Lantai LG, Jaksel</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+-+Blok+M+Plaza/@-6.2439223,106.79498,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f1e844122e55:0x67741ba29ffd9c66!8m2!3d-6.2439276!4d106.7975603!16s%2Fg%2F11k99lht2b?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Tanjung Duren</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Tanjung Duren Raya, Jakbar</p><a href="hhttps://www.google.com/maps/place/Ai-CHA+Tanjung+Duren/@-6.1739494,106.7830207,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f7c0368e24fd:0xd5524c5cd45318f8!8m2!3d-6.1739548!4d106.787897!16s%2Fg%2F11twcfjv08?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Citra Raya</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Ruko Palma Cikupa, Tangerang</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+X+Zhengda+-+Citra+Raya/@-6.2376197,106.5214804,17z/data=!3m1!4b1!4m6!3m5!1s0x2e4207a700f30bef:0x6424e55f9fb88b9d!8m2!3d-6.237625!4d106.5240607!16s%2Fg%2F11sdkcc9yy?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Pamulang</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Pamulang Blok SH21 No 15, Tangsel</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+-+Pamulang/@-6.342566,106.7254082,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69ef2dbc77aba3:0x9e7cd6dacddb2114!8m2!3d-6.3425713!4d106.7279885!16s%2Fg%2F11sb6rnzjl?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Condet</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Zamzam Square Condet, Jaktim</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+-+Condet/@-6.2934509,106.8531499,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f35e3c7de855:0xc91301af33e57bf9!8m2!3d-6.2934562!4d106.8557302!16s%2Fg%2F11tp9vb65l?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Kelapa Gading</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Boulevard Raya no 4, Jakut</p><a href="https://www.google.com/maps/place/Ai-Cha+Boulevard+Raya/@-6.1657349,106.8975798,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f500640e73d1:0xc5dffa595a4f9a36!8m2!3d-6.1657403!4d106.9024561!16s%2Fg%2F11lp044lhf?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Bekasi</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Summarecon Bekasi, Bekasi</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+-+Summarecon+Bekasi/@-6.2220149,107.0104308,17z/data=!3m1!4b1!4m6!3m5!1s0x2e698feefc61d59d:0x5e18aad6bed019e4!8m2!3d-6.2220202!4d107.0130111!16s%2Fg%2F11k3njrwk6?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
        <div class="store-card"><h4>Ai-CHA Bogor</h4><span style="font-size:10px; color:#28a745; font-weight:800;">🟢 OPEN</span><p style="font-size:11px; color:#666; margin:8px 0;">Pandu Raya no 147, Bogor</p><a href="https://www.google.com/maps/place/Ai-CHA+Ice+Cream+%26+Tea+X+Zhengda+-+Pandu+Raya/@-6.5883572,106.8140744,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69c527bac463c9:0xe3117ce13272b696!8m2!3d-6.5883625!4d106.8166547!16s%2Fg%2F11scqqx5wm?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="color:var(--primary); text-decoration:none; font-size:11px; font-weight:800;">OPEN MAPS <i class="fas fa-arrow-right"></i></a></div>
    </div>

    <div class="logout-area" style="margin-top: auto; padding: 30px 0; text-align: center; border-top: 1px solid #f5f5f5;">
    
    <a href="logout.php" style="
        text-decoration: none; 
        color: #e63946; 
        font-weight: 600; 
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: 0.3s ease;
    " onmouseover="this.style.transform='translateX(-5px)'; this.style.color='#c12a36';" 
       onmouseout="this.style.transform='translateX(0)'; this.style.color='#e63946';">
        
        <i class="fas fa-sign-out-alt"></i> Logout Session
    </a>

    <p style="font-size: 9px; color: #bbb; margin-top: 15px; letter-spacing: 1px;">
        Ai-CHA v2.3.0 Premium Edition
    </p>
</div>
</div>

<script>
    const slider = document.getElementById('dragSlider');
    let isDown = false, startX, scrollLeft;
    slider.addEventListener('mousedown', (e) => { isDown = true; startX = e.pageX - slider.offsetLeft; scrollLeft = slider.scrollLeft; });
    slider.addEventListener('mouseleave', () => { isDown = false; });
    slider.addEventListener('mouseup', () => { isDown = false; });
    slider.addEventListener('mousemove', (e) => { if(!isDown) return; e.preventDefault(); const x = e.pageX - slider.offsetLeft; const walk = (x - startX) * 2; slider.scrollLeft = scrollLeft - walk; });
</script>

</body>
</html>