<div class="main-container">
    <header>
        <h1>Halo, <?php echo $user['Nama']; ?>!</h1>
        <p>Selamat datang di Ai-CHA Loyalty</p>
    </header>

    <div class="content-layout" style="display: flex; gap: 15px; margin-top: 10px;">
        
        <div class="main-content" style="flex: 2;">
            <div class="promo-banner">
                <h3 style="margin: 0;">Promo Minggu Ini 🍦</h3>
                <p style="font-size: 14px;">Beli 1 Sundae Gratis 1 Tea! Khusus Member.</p>
            </div>

            <div class="store-section">
                <h4 style="color: #333; margin-bottom: 10px;">Lokasi Store Utama</h4>
                
                <div class="store-card">
                    <strong style="color: #e63946;">Ai-CHA Pluit Village Mall</strong>
                    <p style="font-size: 12px; color: #28a745; font-weight: bold; margin: 5px 0;">🟢 Buka (09.00 - 22.00)</p>
                    <a href="https://maps.google.com/?q=Ai-CHA+Pluit+Village" target="_blank" style="text-decoration: none;">
                        <p style="font-size: 11px; color: #2a00fc;">📍 Pluit Village Mall Lt. GF, Jakarta Utara <i class="fas fa-external-link-alt"></i></p>
                    </a>
                </div>

                <div class="store-card" style="margin-top: 15px;">
                    <strong style="color: #e63946;">Ai-CHA Grand Indonesia</strong>
                    <p style="font-size: 12px; color: #28a745; font-weight: bold; margin: 5px 0;">🟢 Buka (09.00 - 22.00)</p>
                    <a href="https://maps.google.com/?q=Ai-CHA+Grand+Indonesia" target="_blank" style="text-decoration: none;">
                        <p style="font-size: 11px; color: #2a00fc;">📍 Grand Indonesia, West Mall Lt. LG <i class="fas fa-external-link-alt"></i></p>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-buttons">
            <a href="kartu_loyalti.php" class="circle-btn btn-loyalty">
                <i class="fas fa-th"></i>
                <span>Kartu<br>Loyalti</span>
            </a>

            <a href="info_member_user.php" class="circle-btn btn-member">
                <i class="fas fa-id-card"></i>
                <span>Info<br>Membership</span>
            </a>

            <a href="redeem_menu.php" class="circle-btn btn-redeem-gold">
                <i class="fas fa-gift"></i>
                <span>Tukar<br>Point</span>
            </a>

            <a href="info_stamp_user.php" class="circle-btn btn-info">
                <i class="fas fa-info-circle"></i>
                <span>Ketentuan</span>
            </a>

            <a href="logout.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </div>
    </div>
</div>

<style> 
body {
    background: url('images/background.jpg') no-repeat center center fixed; 
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    /* Kunci agar konten di tengah layar */
    display: flex;
    justify-content: center; /* Center Horizontal */
    align-items: center;     /* Center Vertikal */
    min-height: 100vh;       /* Tinggi minimal 100% layar */

}

.main-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    width: 90%;             /* Agar tidak mentok di HP */
    max-width: 1000px;       /* Ukuran ideal yang Mas mau */
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    
    /* Hapus margin: 15px auto karena sudah diatur Flexbox body */
    position: relative;
    min-height: 80vh; 
    display: flex;
    flex-direction: column;
}

.promo-banner {
    background: linear-gradient(135deg, #e63946, #ff4d6d);
    color: white;
    padding: 20px;
    border-radius: 15px;
}

.store-card {
    background: white;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #eee;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.sidebar-buttons {
    flex: 0.5;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.circle-btn {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    color: white;
    font-weight: bold;
    font-size: 10px;
    text-align: center;
    border: 3px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.circle-btn:hover { transform: scale(1.1); }

/* Warna Tombol */
.btn-loyalty { background: linear-gradient(135deg, #e63946, #b30000); }
.btn-member { background: linear-gradient(135deg, #4d79ff, #002db3); }
.btn-redeem-gold { background: linear-gradient(135deg, #f1c40f, #d4ac0d); color: #333 !important; }
.btn-info { background: linear-gradient(135deg, #95a5a6, #7f8c8d); }

.logout-link {
    color: #e63946;
    font-size: 12px;
    margin-top: 10px;
    font-weight: bold;
    text-decoration: none;
}
</style>