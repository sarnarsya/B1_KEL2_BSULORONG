<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "bsulorong1";

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses data jika form disubmit
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_nasabah = $_POST['nama_nasabah'];
    $nomor_induk = $_POST['nomor_induk'];
    $alamat = $_POST['alamat'];

    // Query insert
    $sql = "INSERT INTO nasabah (Nama_nasabah, nomor_induk, alamat) VALUES ('$nama_nasabah', '$nomor_induk', '$alamat')";

    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil ditambahkan!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Nasabah</title>
    <style>
        /* CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #3290f5;
            color: white;
            padding: 0px 10px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px; /* Jarak antara tombol kembali dan tulisan */
        }

        .btn-kembali {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 5px 10px;
            border: 2px solid white;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-kembali:hover {
            background-color: white;
            color: #007bff;
        }

        .logo-title {
            font-size: 18px;
            font-weight: bolder;
            white-space: nowrap; /* Agar teks tidak terpotong */
        }

        .logo-right img {
            width: 100px; /* Ukuran logo lebih kecil */
            height: auto;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 500px; /* Ukuran form diperbesar */
            margin: 50px auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px; /* Judul form diperbesar */
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #666;
        }

        input {
            width: 100%;
            padding: 12px; /* Input lebih besar */
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn-tambah {
            background-color: #3290f5;
            color: #fff;
            border: none;
            padding: 12px 20px; /* Tombol lebih besar */
            border-radius: 5px;
            cursor: pointr;
            width: 100%;
            font-size: 16px;
        }

        .btn-tambah:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            font-size: 16px;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <!-- Grup kiri: Tombol kembali + Tulisan -->
        <div class="header-left">
            <a href="nasabah.html" class="btn-kembali">⟵</a>
            <span class="logo-title">BSU LORONG</span>
        </div>
        <!-- Logo kanan atas -->
        <div class="logo-right">
            <img src="logo/logo putih.png" alt="Logo">
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Tambah Nasabah</h1>
        <form method="POST">
            <label for="nama">Nama Nasabah:</label>
            <input type="text" id="nama" name="nama_nasabah" required>

            <label for="nomor_induk">Nomor Induk:</label>
            <input type="text" id="nomor_induk" name="nomor_induk" required>

            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" required>

            <button type="submit" class="btn-tambah">Tambah</button>
        </form>
        <?php
        if (!empty($message)) {
            echo "<div class='message'>$message</div>";
        }
        ?>
    </div>
</body>
</html>
