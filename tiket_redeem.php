<?php
session_start();
include 'config.php';
$kode = $_GET['kode'];
$query = mysqli_query($conn, "SELECT * FROM tbredeem_request WHERE kode_unik = '$kode'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Kamu - Ai-CHA Premium</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #e63946; --accent: #ffb703; }

        body {
            margin: 0; padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; overflow: hidden;
        }

        /* 1. BACKGROUND FIXED (SAMA DENGAN DASHBOARD) */
        .bg-fixed {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: url('images/logored.jpg') no-repeat center center; 
            background-size: cover; z-index: -1;
        }
        .bg-fixed::after {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.2); /* Gelapkan sedikit agar kartu putih menonjol */
        }

        /* 2. KARTU TIKET (KONSEP GLASSMORPHISM DASHBOARD) */
        .ticket-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 90%; max-width: 400px;
            padding: 50px 30px;
            border-radius: 40px; /* Radius mewah dashboard */
            box-shadow: 0 40px 100px rgba(0,0,0,0.3);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.4);
            position: relative;
        }

        /* Efek Lubang Tiket Digital */
        .ticket-card::before, .ticket-card::after {
            content: ''; position: absolute; width: 30px; height: 30px;
            background: rgba(0,0,0,0.1); border-radius: 50%; top: 60%; transform: translateY(-50%);
        }
        .ticket-card::before { left: -15px; }
        .ticket-card::after { right: -15px; }

        h2 { font-weight: 800; color: #333; font-size: 26px; margin: 0 0 10px; letter-spacing: -1px; }
        .subtitle { color: #666; font-size: 14px; margin-bottom: 30px; }

        /* 3. BOX KODE JUMBO */
        .code-display {
            background: white;
            border: 3px dashed var(--primary);
            padding: 25px 10px;
            border-radius: 25px;
            margin: 20px 0;
            box-shadow: 0 10px 20px rgba(230, 57, 70, 0.05);
        }
        .code-display strong {
            font-size: 38px;
            color: var(--primary);
            letter-spacing: 6px;
            display: block;
            font-weight: 800;
        }

        /* INFO ITEM GAYA CARD OUTLET */
        .item-info {
            background: rgba(0,0,0,0.03);
            padding: 20px;
            border-radius: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .item-info span { display: block; color: #999; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 5px; }
        .item-info b { color: #333; font-size: 18px; font-weight: 800; }

        .status-info {
            font-size: 12px; color: var(--primary); font-weight: 700;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-bottom: 35px;
        }

        /* BUTTON BACK (GAYA LOGOUT DASHBOARD) */
        .btn-back {
            text-decoration: none; color: #bbb; font-weight: 800; font-size: 12px;
            text-transform: uppercase; letter-spacing: 2px;
            display: inline-flex; align-items: center; gap: 10px;
            transition: 0.3s;
        }
        .btn-back:hover { color: var(--primary); transform: translateX(-5px); }

        /* Animasi agar kode terlihat hidup */
        @keyframes shine {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .code-display { animation: shine 2s infinite ease-in-out; }
    </style>
</head>
<body>

    <div class="bg-fixed"></div>
<div class="ticket-card" style="text-align: center; padding: 40px 20px; background: rgba(255,255,255,0.9); border-radius: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
    
    <h2 style="font-weight: 800; color: #333; margin-bottom: 5px;">Tukarkan Menu! 🍦</h2>
    <p style="color: #666; font-size: 13px; margin-bottom: 25px;">Tunjukkan QR Code ini ke Kasir Ai-CHA</p>

    <div class="qr-box" style="background: white; padding: 20px; display: inline-block; border-radius: 30px; box-shadow: 0 10px 30px rgba(230, 57, 70, 0.1); border: 2px solid #fdf2f2; margin-bottom: 20px;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo $data['kode_unik']; ?>&color=e63946" 
             alt="QR Redeem" 
             style="display: block; width: 180px; height: 180px;">
    </div>

    <div class="code-text-box" style="margin-bottom: 30px;">
        <span style="display: block; color: #aaa; font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">Kode Manual:</span>
        <h3 style="margin: 5px 0; color: #e63946; font-size: 28px; letter-spacing: 5px; font-weight: 900;">
            <?php echo $data['kode_unik']; ?>
        </h3>
    </div>

    <div style="background: #f9f9f9; padding: 15px; border-radius: 20px; margin-bottom: 25px;">
        <p style="margin: 0; color: #888; font-size: 12px;">Menu Hadiah:</p>
        <strong style="color: #333; font-size: 18px;"><?php echo $data['nama_item']; ?></strong>
    </div>

    <p style="font-size: 11px; color: #e63946; font-weight: 700;">
        <i class="fas fa-sync-alt fa-spin"></i> Menunggu Konfirmasi Kasir...
    </p>

</div>


   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
// Variabel untuk mencegah pengecekan berulang setelah berhasil
let redeemSelesai = false;

function checkStatus() {
    if (redeemSelesai) return; // Stop jika sudah sukses

    const kodeVoucher = '<?php echo $kode; ?>';
    
    fetch('cek_status_redeem.php?kode=' + kodeVoucher)
        .then(response => response.text())
        .then(status => {
            if (status === 'selesai') {
                redeemSelesai = true; // Tandai sudah sukses

                // --- POP-UP REDEEM BERHASIL (PREMIUM) ---
                Swal.fire({
                    title: '<span style="font-family:\'Plus Jakarta Sans\',sans-serif;font-weight:800;font-size:28px;letter-spacing:-1px;color:#1a1a1a;">🎉 Redeem Berhasil!</span>',
                    html: '<p style="font-family:\'Plus Jakarta Sans\',sans-serif;font-size:16px;color:#666;line-height:1.5;margin-top:10px;">Hore! Poin kamu sudah terpotong.<br>Silakan ambil <b><?php echo $data['nama_item']; ?></b> di kasir.</p>',
                    icon: 'success',
                    iconColor: '#e63946', // Warna merah Ai-CHA
                    showConfirmButton: false, // Tanpa tombol, otomatis redirect
                    timer: 3500, // Tampil agak lama (3.5 detik) agar dinikmati visualnya
                    timerProgressBar: true, // Ada bar loading di bawah
                    
                    // Style Pop-up (Glassmorphism Putih)
                    background: 'rgba(255, 255, 255, 0.95)',
                    backdrop: `rgba(0,0,0,0.6) blur(15px)`, // Blur latar belakang sangat kuat
                    borderRadius: '40px', // Radius mewah dashboard
                    padding: '40px 20px',
                    
                    // Animasi Masuk (Meledak Pelan)
                    showClass: {
                        popup: 'animate__animated animate__zoomInDown animate__faster'
                    },
                    // Animasi Keluar (Menghilang Halus)
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                }).then(() => {
                    // --- OTOMATIS KEMBALI KE REDEEM_MENU.PHP ---
                    // Menggunakan replace agar tidak bisa di-back ke halaman tiket
                    window.location.replace('redeem_menu.php');
                });

            } else if (status === 'batal') {
                redeemSelesai = true;
                Swal.fire({
                    title: 'Redeem Dibatalkan',
                    text: 'Maaf, permintaan redeem kamu ditolak/dibatalkan admin.',
                    icon: 'error',
                    confirmButtonText: 'Kembali',
                    confirmButtonColor: '#333',
                    borderRadius: '20px'
                }).then(() => {
                    window.location.replace('redeem_menu.php');
                });
            }
        });
}

// Jalankan pengecekan setiap 2 detik (Lebih cepat responsnya)
setInterval(checkStatus, 2000);
</script>
</script>
</body>
</html>