<?php
include 'config.php';

if (isset($_POST['register'])) {
    $nama_input = mysqli_real_escape_string($conn, $_POST['nama']);
    $nohp_input = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $pass_input = $_POST['password'];
    $password_hash = password_hash($pass_input, PASSWORD_DEFAULT);

    // Query insert (pastikan tabel tbmember punya kolom role)
    $query = "INSERT INTO tbmember (Nama, NoHP, Password, Total_Stamp, role) 
              VALUES ('$nama_input', '$nohp_input', '$password_hash', 0, 'customer')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
    } else {
        $error = "Gagal mendaftar: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Ai-CHA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Mengambil style yang sama persis dari login.php */
        body {
            background: url('images/logored.jpg') no-repeat center center fixed; 
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
        }

        h2 { 
            color: #e63946; 
            font-weight: 700; 
            margin-bottom: 25px;
            font-size: 22px;
        }

        .error-msg {
            background: #ffe5e5;
            color: #d63031;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 15px;
            border: 1px solid #ffb3b3;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #e63946;
        }

        input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border-radius: 15px;
            border: 1px solid #ddd;
            outline: none;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            transition: 0.3s;
        }

        input:focus {
            border-color: #e63946;
            box-shadow: 0 0 8px rgba(230, 57, 70, 0.2);
        }

        .btn-regis {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #e63946, #ff4d6d);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 8px 15px rgba(230, 57, 70, 0.3);
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-regis:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(230, 57, 70, 0.4);
        }

        .login-area {
            margin-top: 25px;
            font-size: 13px;
            color: #666;
        }

        .login-area a {
            color: #e63946;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="https://arest.web.id/sites/default/files/styles/foto_company_singlepost/public/logo-ai-cha-ice-cream-and-tea.png?itok=gmWdhx1o" class="logo" alt="Ai-CHA Logo">
    
    <h2>Registrasi Member</h2>

    <?php if(isset($error)): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
        </div>

        <div class="input-group">
            <i class="fas fa-phone"></i>
            <input type="text" name="no_hp" placeholder="Nomor HP" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Buat Password" required>
        </div>

        <button type="submit" name="register" class="btn-regis">
            DAFTAR SEKARANG <i class="fas fa-user-plus"></i>
        </button>
    </form>

    <div class="login-area">
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</div>

</body>
</html>