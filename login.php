<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $nohp_input = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $pass_input = $_POST['password'];

    $query = "SELECT * FROM tbmember WHERE NoHP = '$nohp_input'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($pass_input, $user['Password'])) {
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['nama']    = $user['Nama'];
        $_SESSION['role']    = $user['role']; 

        if ($user['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit();
    } else {
        $error = "Nomor HP atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member - Ai-CHA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: url('images/background.jpg') no-repeat center center fixed; 
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(230, 57, 70, 0.4);
        }

        .register-area {
            margin-top: 25px;
            font-size: 13px;
            color: #666;
        }

        .register-area a {
            color: #e63946;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="https://arest.web.id/sites/default/files/styles/foto_company_singlepost/public/logo-ai-cha-ice-cream-and-tea.png?itok=gmWdhx1o" class="logo" alt="Ai-CHA Logo">
    
    <h2>Login Loyalti</h2>

    <?php if(isset($error)): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <i class="fas fa-phone"></i>
            <input type="text" name="no_hp" placeholder="Nomor HP" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="login" class="btn-login">
            MASUK <i class="fas fa-sign-in-alt"></i>
        </button>
    </form>

    <div class="register-area">
        <p>Belum punya member? <a href="register.php">Daftar Sekarang</a></p>
    </div>
</div>

</body>
</html>