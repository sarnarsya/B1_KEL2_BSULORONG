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

// Proses penyimpanan data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_data'])) {
    $id_nasabah = $_POST['id_nasabah']; // ID nasabah yang dipilih melalui pencarian
    $tanggal = $_POST['tanggal'];
    $jenis_sampah = $_POST['jenis_sampah'];
    $berat = $_POST['berat'];
    $harga = $_POST['harga']; // Ambil harga yang dihitung

    // Validasi apakah ID Nasabah ada di tabel nasabah
    $sql_check_nasabah = "SELECT * FROM nasabah WHERE id = '$id_nasabah'";
    $result_check = $conn->query($sql_check_nasabah);

    if ($result_check->num_rows > 0) {
        // Jika valid, simpan data
        $sql = "INSERT INTO transaksi_sampah1 (id_nasabah, tanggal, jenis_sampah, berat, harga) 
                VALUES ('$id_nasabah', '$tanggal', '$jenis_sampah', '$berat', '$harga')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Data berhasil disimpan!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } 
}

// Proses pencarian data nasabah jika ada request via AJAX
if (isset($_GET['search_query'])) {
    $query = $_GET['search_query'];
    $sql = "SELECT id, Nama_nasabah FROM nasabah WHERE Nama_nasabah LIKE '%$query%' OR nomor_induk LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p onclick=\"selectNasabah('{$row['id']}', '{$row['Nama_nasabah']}')\">{$row['Nama_nasabah']}</p>";
        }
    } else {
        echo "<p>Nasabah tidak ditemukan</p>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor Sampah</title>
    <style>
        /* CSS untuk header dan body */
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
            white-space: nowrap;
        }

        .logo-right img {
            width: 100px; /* Ukuran logo lebih kecil */
            height: auto;
        }

        /* CSS untuk body dan form */
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #3290f5;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #result p {
            cursor: pointer;
            padding: 5px;
            background-color: #f0f0f0;
            margin: 0;
            border: 1px solid #ddd;
        }

        #result p:hover {
            background-color: #e0e0e0;
        }
    </style>
    <script>
        function searchNasabah() {
            const query = document.getElementById('search').value;

            if (query.length > 2) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `?search_query=${query}`, true);
                xhr.onload = function () {
                    if (this.status === 200) {
                        document.getElementById('result').innerHTML = this.responseText;
                    }
                };
                xhr.send();
            } else {
                document.getElementById('result').innerHTML = '';
            }
        }

        function selectNasabah(id, name) {
            document.getElementById('id_nasabah').value = id;
            document.getElementById('selected_nasabah').innerText = `Nasabah dipilih: ${name}`;
            document.getElementById('result').innerHTML = '';
        }

        // Fungsi untuk menghitung harga otomatis
        function calculateHarga() {
            const jenisSampah = document.getElementById('jenis_sampah').value;
            const berat = parseFloat(document.getElementById('berat').value);
            let hargaPerKg;

            // Tentukan harga per kg berdasarkan jenis sampah
            switch (jenisSampah) {
                case "Plastik":
                    hargaPerKg = 2000;
                    break;
                case "Logam":
                    hargaPerKg = 5000;
                    break;
                case "Kertas":
                    hargaPerKg = 2000;
                    break;
                case "Dos":
                    hargaPerKg = 2000;
                    break;
                case "Botol":
                    hargaPerKg = 3000;
                    break;
                default:
                    hargaPerKg = 0;
                    break;
            }

            // Jika berat valid, hitung harga
            if (!isNaN(berat) && berat > 0) {
                const totalHarga = berat * hargaPerKg;
                document.getElementById('harga').value = totalHarga;
            } else {
                document.getElementById('harga').value = '';
            }
        }
    </script>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <a href="nasabah.html" class="btn-kembali">⟵</a>
            <span class="logo-title">BSU LORONG</span>
        </div>
        <div class="logo-right">
            <img src="logo/logo putih.png" alt="Logo">
        </div>
    </div>

    <div class="container">
        <h1>Setor Sampah</h1>

        <!-- Menampilkan pesan sukses atau error -->
        <?php if (!empty($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
        <?php if (!empty($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

        <!-- Form pencarian nasabah -->
        <div class="form-group">
            <label for="search">Cari Nama/Nomor Induk:</label>
            <input type="text" id="search" placeholder="Masukkan nama atau nomor induk" onkeyup="searchNasabah()">
            <div id="result"></div>
        </div>
        <p id="selected_nasabah" style="color: green; font-weight: bold;"></p>

        <!-- Form setor sampah -->
        <form method="POST" action="">
            <input type="hidden" name="id_nasabah" id="id_nasabah" required>

            <div class="form-group">
                <label for="tanggal">Hari/Tanggal:</label>
                <input type="date" name="tanggal" required>
            </div>

            <div class="form-group">
                <label for="jenis_sampah">Jenis Sampah:</label>
                <select name="jenis_sampah" id="jenis_sampah" required onchange="calculateHarga()">
                    <option value="Plastik">Plastik</option>
                    <option value="Logam">Logam</option>
                    <option value="Kertas">Kertas</option>
                    <option value="Dos">Dos</option>
                    <option value="Botol">Botol</option>
                </select>
            </div>

            <div class="form-group">
                <label for="berat">Berat (Kg):</label>
                <input type="number" name="berat" id="berat" step="0.01" required oninput="calculateHarga()">
            </div>

            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="text" name="harga" id="harga" readonly>
            </div>

            <button type="submit" name="submit_data">Tambah</button>
        </form>
    </div>
</body>
</html>
