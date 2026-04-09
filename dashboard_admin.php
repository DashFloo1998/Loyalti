<?php
session_start();
include 'config.php';

// 1. SET ZONA WAKTU & GREETING
date_default_timezone_set('Asia/Jakarta');
$hour = (int)date('H');
if ($hour >= 5 && $hour < 11) $greetings = "Selamat Pagi ☀️";
elseif ($hour >= 11 && $hour < 15) $greetings = "Selamat Siang 🌤️";
elseif ($hour >= 15 && $hour < 18) $greetings = "Selamat Sore 🌥️";
else $greetings = "Selamat Malam 🌙";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// --- LOGIKA FILTER REKAP (TAMBAHKAN INI) ---
$filter = isset($_GET['range']) ? $_GET['range'] : 'today';
switch ($filter) {
    case 'weekly':
        $judul_history = "History Redeem Minggu Ini";
        $where_clause_history = "r.tanggal >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'monthly':
        $judul_history = "History Redeem Bulan Ini";
        $where_clause_history = "MONTH(r.tanggal) = MONTH(NOW()) AND YEAR(r.tanggal) = YEAR(NOW())";
        break;
    default:
        $judul_history = "History Redeem Hari Ini";
        $where_clause_history = "DATE(r.tanggal) = CURDATE()";
        break;
}

// --- LOGIKA UTAMA: POIN (BELANJA/1000) & STAMP (BELANJA/30rb) ---
if (isset($_POST['tambah_manual'])) {
    $no_hp = mysqli_real_escape_string($conn, trim($_POST['no_hp']));
    $total_belanja = (int)$_POST['total_belanja'];

    $cek_user = mysqli_query($conn, "SELECT Nama FROM tbmember WHERE NoHP = '$no_hp' AND role = 'customer'");
    if (mysqli_num_rows($cek_user) > 0) {
        $u = mysqli_fetch_assoc($cek_user);
        
        // KONVERSI: 20.000 / 1000 = 20 Poin
        $tambah_poin = floor($total_belanja / 1000); 
        // STAMP: 30.000 = 1 Stamp
        $tambah_stamp = floor($total_belanja / 30000);

        $sql = "UPDATE tbmember SET 
                Member_point = Member_point + $tambah_poin, 
                Total_Stamp = Total_Stamp + $tambah_stamp 
                WHERE NoHP = '$no_hp'";
        
        if (mysqli_query($conn, $sql)) {
            $notif_pesan = "✅ Sukses! " . $u['Nama'] . " dapet +$tambah_poin Poin & +$tambah_stamp Stamp.";
        }
    } else {
        $notif_pesan = "❌ Gagal! Member tidak terdaftar.";
    }
}

// --- LOGIKA TOMBOL DATA MEMBER (-1 DAN R10 BALIK LAGI) ---
if (isset($_POST['tambah_stamp_user'])) {
    $id_target = $_POST['id_user'];
    mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp + 1 WHERE ID = '$id_target'");
}
if (isset($_POST['kurangi_stamp_user'])) {
    $id_target = $_POST['id_user'];
    mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp - 1 WHERE ID = '$id_target' AND Total_Stamp > 0");
}
if (isset($_POST['tukar_5_stamp_user']) || isset($_POST['tukar_10_stamp_user'])) {
    $id_target = $_POST['id_user'];
    $jumlah = isset($_POST['tukar_5_stamp_user']) ? 5 : 10;
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Total_Stamp FROM tbmember WHERE ID = '$id_target'"));
    if ($cek['Total_Stamp'] >= $jumlah) {
        mysqli_query($conn, "UPDATE tbmember SET Total_Stamp = Total_Stamp - $jumlah WHERE ID = '$id_target'");
        $notif_pesan = "✅ Berhasil Redeem $jumlah Stamp!";
    } else {
        $notif_pesan = "❌ Stamp tidak cukup!";
    }
}

// PENCARIAN & HISTORY
$search_member = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : "";
$query_user = mysqli_query($conn, "SELECT * FROM tbmember WHERE role = 'customer' AND (Nama LIKE '%$search_member%' OR NoHP LIKE '%$search_member%') ORDER BY ID DESC LIMIT 10");

$query_history = mysqli_query($conn, "SELECT r.*, m.Nama 
    FROM tbredeem_request r 
    JOIN tbmember m ON r.id_user = m.ID 
    WHERE r.status = 'selesai' AND $where_clause_history 
    ORDER BY r.tanggal DESC");
$count_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbredeem_request WHERE status='selesai' AND DATE(tanggal) = CURDATE()"))['total'];
$count_member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbmember WHERE role='customer'"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ai-CHA POS Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode"></script> 
     <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f7f6; margin: 0; padding: 15px; color: #2d3436; min-height: 100vh; }
        .container { max-width: 1000px; margin: auto; display: flex; flex-direction: column; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; background: white; padding: 20px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        
        /* Menu Grid */
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin-bottom: 20px; }
        .menu-card { background: white; padding: 20px 10px; border-radius: 20px; text-align: center; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; gap: 10px; border: 1px solid #eee; }
        .menu-card:active { transform: scale(0.95); }
        .menu-card i { font-size: 28px; }
        .menu-card span { font-weight: 700; font-size: 12px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .stat-box { padding: 20px; border-radius: 25px; color: white; text-align: center; }
        
        .content-box { background: white; padding: 20px; border-radius: 25px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: none; border: 1px solid #eee; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Form & Table */
        input, select { padding: 12px; border: 1px solid #dfe6e9; border-radius: 12px; width: 100%; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        td { padding: 12px; border-bottom: 1px solid #f1f2f6; }
        .btn-action { padding: 8px 12px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; font-size: 11px; }

        @media (max-width: 768px) {
            .menu-grid { grid-template-columns: 1fr 1fr; }
            .header h2 { font-size: 18px; }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if(isset($notif_pesan)): ?>
        <div id="notif-alert" style="background: white; border: 2px solid #e63946; color: #1a1a1a; padding: 15px; border-radius: 20px; margin-bottom: 20px; font-weight: 800; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: 0.5s;">
            <?php echo $notif_pesan; ?>
        </div>
    <?php endif; ?>

    <div class="header">
        <div>
            <h2><?php echo $greetings; ?></h2>
            <p style="margin:0; color:#b2bec3; font-size: 12px;">Admin Summarecon Bekasi</p>
        </div>
        <a href="logout.php" style="color:#e63946; text-decoration:none; font-weight:800; font-size: 13px;">LOGOUT</a>
    </div>

    <div class="menu-grid">
        <div class="menu-card" onclick="startScanner()"><i class="fas fa-qrcode" style="color:#e63946;"></i><span>SCAN</span></div>
        <div class="menu-card" onclick="toggleSection('box-transaksi')"><i class="fas fa-cash-register" style="color:#f4a261;"></i><span>POIN</span></div>
        <div class="menu-card" onclick="toggleSection('box-history')"><i class="fas fa-history" style="color:#2a9d8f;"></i><span>REKAP</span></div>
        <div class="menu-card" onclick="toggleSection('box-member')"><i class="fas fa-users" style="color:#457b9d;"></i><span>DATA</span></div>
    </div>

    <div id="box-stats" class="stats-grid">
        <div class="stat-box" style="background: linear-gradient(135deg, #e63946, #d62828);">
            <small style="opacity:0.8; font-size:10px; font-weight:800;">REDEEM HARI INI</small>
            <div style="font-size:28px; font-weight:800;"><?php echo $count_today; ?></div>
        </div>
        <div class="stat-box" style="background: linear-gradient(135deg, #2d3436, #000);">
            <small style="opacity:0.8; font-size:10px; font-weight:800;">TOTAL MEMBER</small>
            <div style="font-size:28px; font-weight:800;"><?php echo $count_member; ?></div>
        </div>
    </div>

    <div id="welcome-banner" style="background: #fff; border: 2px dashed #ddd; padding: 40px 20px; border-radius: 25px; text-align: center;">
        <i class="fas fa-store" style="font-size: 40px; color: #eee; margin-bottom: 10px;"></i>
        <p style="color: #999; font-size: 13px; margin: 0;">Silakan pilih menu untuk memulai operasional.</p>
    </div>

   <div id="box-scanner" class="content-box">
    <h4 style="margin:0 0 15px 0;"><i class="fas fa-qrcode"></i> Validator Scan</h4>
    
    <div id="reader-container"></div>

    <form id="form-scan-manual">
        <input type="text" name="kode_unik" id="kode_unik" placeholder="KODE AIC-XXXXX" required 
               style="text-align:center; font-weight:800; border:2px dashed #e63946; color:#e63946; text-transform:uppercase;">
        
        <div id="area-aksi-scan" style="margin-top:15px; display:none;">
            <p id="info-customer-scan" style="font-size:12px; font-weight:800; color:#2d3436; margin-bottom:10px;"></p>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                <button type="button" onclick="prosesRedeem()" style="padding:15px; background:#2a9d8f; color:white; border-radius:15px; border:none; font-weight:800; cursor:pointer;">✅ TERIMA REDEEM</button>
                <button type="button" onclick="stopScanner(); toggleSection('box-scanner')" style="padding:15px; background:#e63946; color:white; border-radius:15px; border:none; font-weight:800; cursor:pointer;">❌ BATAL</button>
            </div>
        </div>

        <button type="button" id="btn-cek-manual" onclick="cekKodeManual()" style="margin-top:10px; width:100%; padding:15px; background:#1a1a1a; color:white; border-radius:15px; border:none; font-weight:800;">CEK KODE</button>
    </form>
</div>

    <div id="box-transaksi" class="content-box">
        <h4 style="margin:0 0 15px 0;"><i class="fas fa-plus-circle"></i> Input Transaksi</h4>
        <form action="" method="POST"> 
            <input type="text" name="no_hp" placeholder="Nomor HP Member" required><br><br>
            <input type="number" name="total_belanja" placeholder="Total Belanja (Rp)" required>
            <button type="submit" name="tambah_manual" style="width:100%; background:#f4a261; color:white; padding:15px; border-radius:15px; border:none; font-weight:800;">PROSES TRANSAKSI</button>
        </form>
    </div>

   <div id="box-history" class="content-box">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <h4 style="margin:0;"><?php echo $judul_history; ?></h4>
        
        <form method="GET" action="">
            <select name="range" onchange="this.form.submit()" 
                    style="padding:5px 10px; border-radius:10px; border:1px solid #eee; font-family:'Plus Jakarta Sans'; font-size:11px; font-weight:800; cursor:pointer; background:#f9f9f9;">
                <option value="today" <?php echo $filter == 'today' ? 'selected' : ''; ?>>Hari Ini</option>
                <option value="weekly" <?php echo $filter == 'weekly' ? 'selected' : ''; ?>>Weekly</option>
                <option value="monthly" <?php echo $filter == 'monthly' ? 'selected' : ''; ?>>Monthly</option>
            </select>
        </form>
    </div>

    <div style="overflow-y:auto; max-height:250px;">
        <table style="width:100%; border-collapse:collapse;">
            <?php if(mysqli_num_rows($query_history) > 0) : ?>
                <?php while($h = mysqli_fetch_assoc($query_history)) : ?>
                <tr style="border-bottom:1px solid #f5f5f5;">
                    <td style="padding:10px 5px;"><small style="color:#888;"><?php echo date('d/m H:i', strtotime($h['tanggal'])); ?></small></td>
                    <td style="padding:10px 5px;"><strong><?php echo htmlspecialchars($h['Nama']); ?></strong></td>
                    <td style="padding:10px 5px; color:#e63946; font-weight:700; text-align:right; font-size:12px;">
                        <?php echo htmlspecialchars($h['nama_item']); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3" style="text-align:center; padding:20px; color:#ccc; font-size:12px;">Belum ada history transaksi.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

    <div id="box-member" class="content-box">
        <h4 style="margin:0 0 15px 0;">Data Member</h4>
        <form method="GET" style="display:flex; gap:5px;">
           <input type="text" name="search" placeholder="Cari Nama/HP..." value="<?php echo htmlspecialchars($search_member); ?>">
            <button type="submit" style="width:80px; background:#457b9d; color:white; border:none; border-radius:12px; margin-bottom:10px;">CARI</button>
        </form>
        <div style="overflow-x:auto;">
            <table>
                <thead><tr style="text-align:left;"><th>Nama/HP</th><th>Stamp</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query_user)) : ?>
                    <tr>
                        <td><strong><?php echo $row['Nama']; ?></strong><br><small><?php echo $row['NoHP']; ?></small></td>
                        <td><span style="background:#fff3cd; padding:5px 10px; border-radius:8px; font-weight:800;"><?php echo $row['Total_Stamp']; ?></span></td>
                        <td>
                            <form method="POST" style="display:flex; gap:4px;">
                                <input type="hidden" name="id_user" value="<?php echo $row['ID']; ?>">
                                <button type="submit" name="tambah_stamp_user" class="btn-action" style="background:#2a9d8f; color:white;">+1</button>
                                <button type="submit" name="kurangi_stamp_user" class="btn-action" style="background:#f4a261; color:white;">-1</button>
                                <button type="submit" name="tukar_5_stamp_user" class="btn-action" style="background:#e63946; color:white;">R5</button>
                                <button type="submit" name="tukar_10_stamp_user" class="btn-action" style="background:#2d3436; color:white;">R10</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let html5QrcodeScanner = null;

function toggleSection(id) {
    const boxes = document.querySelectorAll('.content-box');
    const banner = document.getElementById('welcome-banner');
    const stats = document.getElementById('box-stats');
    let anyOpen = false;

    boxes.forEach(box => {
        if(box.id === id) {
            box.style.display = (box.style.display === 'block') ? 'none' : 'block';
            if(box.style.display === 'block') anyOpen = true;
        } else {
            box.style.display = 'none';
        }
    });

    banner.style.display = anyOpen ? 'none' : 'block';
    if(id !== 'box-scanner') stopScanner();
}

function startScanner() {
    toggleSection('box-scanner');
    const container = document.getElementById("reader-container");
    container.innerHTML = '<div id="reader"></div>';
    
    // Reset tampilan button
    document.getElementById("area-aksi-scan").style.display = "none";
    document.getElementById("btn-cek-manual").style.display = "block";

    html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 20, qrbox: 250 });
    html5QrcodeScanner.render((txt) => {
        document.getElementById("kode_unik").value = txt;
        stopScanner();
        // Langsung munculin button redeem setelah scan sukses
        tampilkanButtonRedeem(txt);
    });
}

function cekKodeManual() {
    const kode = document.getElementById("kode_unik").value;
    if(kode.length > 5) {
        tampilkanButtonRedeem(kode);
    } else {
        alert("Masukkan kode yang valid!");
    }
}

function tampilkanButtonRedeem(kode) {
    // Sembunyikan tombol cek, munculkan tombol Redeem/Batal
    document.getElementById("btn-cek-manual").style.display = "none";
    document.getElementById("area-aksi-scan").style.display = "block";
    document.getElementById("info-customer-scan").innerText = "Konfirmasi Redeem untuk Kode: " + kode;
}

function prosesRedeem() {
    const kode = document.getElementById("kode_unik").value;
    // Buat form bayangan untuk kirim POST ke proses_konfirmasi_admin.php
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'proses_konfirmasi_admin.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'kode_unik';
    input.value = kode;
    
    const btn = document.createElement('input');
    btn.type = 'hidden';
    btn.name = 'cek_kode';
    btn.value = '1';

    form.appendChild(input);
    form.appendChild(btn);
    document.body.appendChild(form);
    form.submit();
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear().then(_ => {
            document.getElementById("reader-container").innerHTML = "";
            html5QrcodeScanner = null;
        });
    }
}

// SCRIPT PENGHILANG NOTIF OTOMATIS (Mendeteksi perubahan box atau load)
window.addEventListener('load', function() {
    const alertBox = document.getElementById('notif-alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.opacity = '0';
            setTimeout(() => { alertBox.style.display = 'none'; }, 500);
        }, 3000); // Muncul 3 detik
    }
});
</script>
</body>
</html>