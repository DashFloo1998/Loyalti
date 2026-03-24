<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Stamp - Ai-CHA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: url('images/background.jpg') no-repeat center center fixed; background-size: cover; font-family: 'Poppins', sans-serif; ;background-color: #f4f4f4; margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .info-container { background: white; border-radius: 20px; padding: 25px; max-width: 400px; margin: auto; backdrop-filter: blur(12px); box-shadow: 0 20px 40px rgba(0,0,0,0.3);}
        h2 { color: #e63946; text-align: center; }
        .step-item { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-start; }
        .step-number { background: #e63946; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold; }
        .step-text b { display: block; color: #333; margin-bottom: 3px; }
        .step-text p { margin: 0; font-size: 13px; color: #666; line-height: 1.4; }
        .reward-box { background: #fff4f4; border: 1px dashed #e63946; padding: 15px; border-radius: 12px; margin-top: 20px; }
        .btn-back { display: block; text-align: center; margin-top: 25px; text-decoration: none; color: #666; font-size: 14px; }
    </style>
</head>
<body>

<div class="info-container">
    <h2>Cara Kerja Stamp 🍦</h2>
    
    <div class="step-item">
        <div class="step-number">1</div>
        <div class="step-text">
            <b>Beli Minuman</b>
            <p>Setiap pembelian minimal Rp.20.000 mendapatkan 1 stamp, berlaku kelipatan</p>
        </div>
    </div>

    <div class="step-item">
        <div class="step-number">2</div>
        <div class="step-text">
            <b>Cukup Dengan Nomor Handphone</b>
            <p>Registrasi dengan nomor handphone, dan beri tahu kasir nomor membership anda</p>
        </div>
    </div>

    <div class="step-item">
        <div class="step-number">3</div>
        <div class="step-text">
            <b>Kumpulkan & Tukar</b>
            <p>Kumpulkan hingga 5 atau 10 stamp untuk menikmati hadiah gratis.</p>
        </div>
    </div>

    <div class="reward-box">
        <strong style="color: #e63946; font-size: 14px;">🎁 Hadiah Kamu:</strong>
        <ul style="font-size: 13px; color: #444; margin-top: 8px; padding-left: 20px;">
            <li><b>5 Stamp:</b> Gratis 1 Ice Cream Cone</li>
            <li><b>10 Stamp:</b> Gratis 1 Minuman All Varian</li>
        </ul>
    </div>

    <a href="dashboard_user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

</body>
</html>