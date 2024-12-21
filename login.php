<?php
session_start();

// Data login yang valid
$valid_credentials = [
    "username" => "AdminBSULorong",
    "password" => password_hash("bsulorong1", PASSWORD_DEFAULT)
];

// Variabel untuk pesan error
$error_message = '';

// Cek apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi username dan password
    if ($username === $valid_credentials['username'] && password_verify($password, $valid_credentials['password'])) {
        // Login berhasil, set session dan redirect ke menu.html
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: menu.html");
        exit;
    } else {
        // Login gagal, set pesan error
        $error_message = "Username atau Password salah. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BSU Lorong</title>
    <style>
        /* General body styling */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #5DADE2; /* Soft blue background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main container */
        .container {
            text-align: center;
            width: 90%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Logo Section */
        .logo-section {
            margin-bottom: 15px;
        }

        .logo {
            max-width: 100%; /* Responsif ke ukuran kontainer */
            width: 350px; /* Perbesar ukuran logo */
            height: auto; /* Pastikan proporsi tetap */
        }

        .title {
            font-size: 24px;
            color: #4c95d3;
            margin-top: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Form Section */
        .form-section {
            margin-top: 20px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #666666;
            margin-bottom: 20px;
        }

        .signup-form {
            display: flex;                
            flex-direction: column;      
            gap: 15px;                    
            align-items: center;          
            justify-content: center;      
        }
          
        .form-input {
            width: 80%;                   
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;           
        }

        .btn-submit {
            width: 80%;                   
            background-color: #4c95d3;
            color: white;
            font-size: 16px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px;             
        }
        
        .btn-submit:hover {
            background-color: #3a75b0;
        }

        .btn-submit:active {
            background-color: #2e5a87;
            transform: scale(0.95);
        }

        /* Error Message Styling */
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            background-color: #ffe6e6;
            padding: 10px;
            border: 1px solid #ffcccc;
            border-radius: 5px;
            text-align: center;
        }

        /* Footer */
        .form-footer {
            margin-top: 15px;
            font-size: 14px;
            color: #666666;
        }

        .link {
            color: #4c95d3;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="logo/logo putih.png" alt="BSU Lorong Logo" class="logo">
            <h1 class="title">Silahkan Login</h1>
        </div>
        
        <!-- Form Section -->
        <div class="form-section">
            <p class="form-subtitle">Masuk Sebagai Admin BSU Lorong</p>
            
            <!-- Tampilkan pesan error jika ada -->
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <!-- Form with redirection -->
            <form action="" method="POST" class="signup-form">
                <input type="text" class="form-input" name="username" placeholder="Username" required>
                <input type="password" class="form-input" name="password" placeholder="Password" required>
                <button type="submit" class="btn-submit">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>