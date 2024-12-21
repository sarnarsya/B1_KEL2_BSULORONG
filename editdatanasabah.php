<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bsulorong1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    die("ID tidak valid.");
}

// Query untuk mengambil data berdasarkan ID
$sql = "SELECT * FROM nasabah WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$data = $result->fetch_assoc();

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string($_POST['Nama_nasabah']);
    $nomor_induk = $conn->real_escape_string($_POST['nomor_induk']);
    $alamat = $conn->real_escape_string($_POST['alamat']);

    $update_sql = "UPDATE nasabah SET Nama_nasabah = '$nama', nomor_induk = '$nomor_induk', alamat = '$alamat' WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: datanasabah.php"); // Ganti 'index.php' dengan halaman utama Anda
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nasabah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            width: 100%;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #3290f5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data Nasabah</h1>
        <form method="POST">
            <div class="form-group">
                <label for="Nama_nasabah">Nama Nasabah</label>
                <input type="text" id="Nama_nasabah" name="Nama_nasabah" value="<?= htmlspecialchars($data['Nama_nasabah']) ?>" required>
            </div>
            <div class="form-group">
                <label for="nomor_induk">Nomor Induk</label>
                <input type="text" id="nomor_induk" name="nomor_induk" value="<?= htmlspecialchars($data['nomor_induk']) ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="4" required><?= htmlspecialchars($data['alamat']) ?></textarea>
            </div>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
