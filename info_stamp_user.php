<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Kerja Stamp - Ai-CHA Premium</title>
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
            background: rgba(0, 0, 0, 0.2); 
        }

        /* CONTAINER UTAMA (Konsep Dashboard) */
        .info-shell { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(20px);
            width: 90%; max-width: 450px; 
            padding: 45px 35px; border-radius: 40px; 
            box-sizing: border-box;
            box-shadow: 0 40px 80px rgba(0,0,0,0.1);
            position: relative; overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        h2 { 
            font-weight: 800; color: #333; margin: 0 0 10px; 
            font-size: 26px; text-align: center; letter-spacing: -1px;
        }

        .subtitle {
            text-align: center; color: #888; font-size: 13px; margin-bottom: 35px;
        }

        /* STEP ITEM (Gaya List Premium) */
        .step-item { 
            display: flex; gap: 20px; margin-bottom: 20px; 
            align-items: flex-start; background: rgba(255,255,255,0.5);
            padding: 22px; border-radius: 30px;
            border: 1px solid rgba(0,0,0,0.03); transition: 0.3s;
        }
        .step-item:hover {
            border-color: var(--primary); transform: translateY(-5px);
            background: white; box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        }

        .step-number { 
            background: var(--primary); color: white; 
            width: 38px; height: 38px; border-radius: 14px; 
            display: flex; align-items: center; justify-content: center; 
            flex-shrink: 0; font-weight: 800; font-size: 15px;
            box-shadow: 0 10px 20px rgba(230, 57, 70, 0.2);
        }

        .step-text b { display: block; color: #333; font-size: 15px; margin-bottom: 5px; font-weight: 800; }
        .step-text p { margin: 0; font-size: 12px; color: #777; line-height: 1.6; }

        /* BOX HADIAH (Konsep Card Outlet) */
        .reward-box { 
            background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); 
            border: 2px dashed rgba(230, 57, 70, 0.2); 
            padding: 25px; border-radius: 30px; margin-top: 30px; 
        }

        .reward-title {
            display: flex; align-items: center; gap: 10px;
            color: var(--primary); font-weight: 800; font-size: 15px; margin-bottom: 15px;
        }

        .reward-list { list-style: none; padding: 0; margin: 0; }
        .reward-list li {
            font-size: 13px; color: #444; padding: 10px 0;
            display: flex; justify-content: space-between;
            border-bottom: 1px solid rgba(230, 57, 70, 0.05);
        }
        .reward-list li:last-child { border: none; }
        .reward-list li b { color: var(--primary); font-weight: 800; }

        /* BUTTON BACK */
        .btn-back { 
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 40px; text-decoration: none; color: #bbb; 
            font-size: 12px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1px; transition: 0.3s;
        }
        .btn-back:hover { color: var(--primary); transform: translateX(-5px); }

    </style>
</head>
<body>

<div class="bg-fixed"></div>

<div class="info-shell">
    <h2>Cara Kerja Stamp 🍦</h2>
    <p class="subtitle">Kumpulkan keseruan di setiap pembelian</p>
    
    <div class="step-item">
        <div class="step-number">1</div>
        <div class="step-text">
            <b>Beli Minuman Favorit</b>
            <p>Setiap transaksi minimal Rp. 30.000 mendapatkan 1 stamp. Makin banyak jajan, makin untung!</p>
        </div>
    </div>

    <div class="step-item">
        <div class="step-number">2</div>
        <div class="step-text">
            <b>Sebutkan Nomor HP</b>
            <p>Beritahu kasir nomor membership Anda saat pembayaran. Simpel dan cepat tanpa kartu fisik.</p>
        </div>
    </div>

    <div class="step-item">
        <div class="step-number">3</div>
        <div class="step-text">
            <b>Kumpulkan & Klaim</b>
            <p>Kumpulkan stamp hingga milestone tertentu dan tukar dengan reward spesial dari Ai-CHA.</p>
        </div>
    </div>

    <div class="reward-box">
        <div class="reward-title">
            <i class="fas fa-gift"></i> Hadiah Spesial Kamu
        </div>
        <ul class="reward-list">
            <li><span>5 Stamp</span> <b>Gratis 1 Ice Cream Cone</b></li>
            <li><span>10 Stamp</span> <b>Gratis 1 Minuman All Variant</b></li>
        </ul>
    </div>

    <a href="dashboard_user.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

</body>
</html>