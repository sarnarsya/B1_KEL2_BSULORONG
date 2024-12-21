<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root';  // Sesuaikan dengan username MySQL Anda
$password = '';      // Sesuaikan dengan password MySQL Anda
$dbname = 'bsulorong1';

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan total berat berdasarkan jenis sampah
$total_berat = [
    'Plastik' => 0,
    'Logam' => 0,
    'Kertas' => 0,
    'Dos' => 0,
    'Botol' => 0
];

// Ambil total berat per jenis sampah
foreach ($total_berat as $jenis_sampah => &$berat) {
    $berat_sql = "SELECT SUM(berat) AS total_berat FROM transaksi_sampah1 WHERE jenis_sampah = '$jenis_sampah'";
    $berat_result = $conn->query($berat_sql);
    $berat_data = $berat_result->fetch_assoc();
    $berat = $berat_data['total_berat'] ?? 0;
}

// Simpan data penjualan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_sampah = $_POST['jenis_sampah'];
    $berat = $_POST['berat'];
    $tanggal = $_POST['tanggal'];

    // Menghitung harga berdasarkan jenis sampah dan berat
    $harga_per_kg = 0;
    switch ($jenis_sampah) {
        case "Plastik":
            $harga_per_kg = 1500;
            break;
        case "Logam":
            $harga_per_kg = 4000;
            break;
        case "Kertas":
            $harga_per_kg = 2000;
            break;
        case "Dos":
            $harga_per_kg = 1900;
            break;
        case "Botol":
            $harga_per_kg = 1900;
            break;
    }

    // Hitung harga total
    $harga_total = $berat * $harga_per_kg;

    // Query untuk menyimpan data ke tabel transaksi_sampah1
    $insert_sql = "INSERT INTO transaksi_sampah1 (jenis_sampah, berat, tanggal, harga) VALUES ('$jenis_sampah', '$berat', '$tanggal', '$harga_total')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penjualan Sampah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #3290f5;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group select,
        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group input[type="submit"] {
            background-color: #3290f5;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form-group input[type="submit"]:hover {
            background-color: #2678d6;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Tambah Penjualan Sampah</h2>
    <form method="POST">
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="jenis_sampah">Jenis Sampah:</label>
            <select id="jenis_sampah" name="jenis_sampah" required onchange="updateBerat()">
                <option value="">Pilih Jenis Sampah</option>
                <option value="Plastik" data-total-berat="<?php echo $total_berat['Plastik']; ?>">Plastik</option>
                <option value="Logam" data-total-berat="<?php echo $total_berat['Logam']; ?>">Logam</option>
                <option value="Kertas" data-total-berat="<?php echo $total_berat['Kertas']; ?>">Kertas</option>
                <option value="Dos" data-total-berat="<?php echo $total_berat['Dos']; ?>">Dos</option>
                <option value="Botol" data-total-berat="<?php echo $total_berat['Botol']; ?>">Botol</option>
            </select>
        </div>

        <div class="form-group">
            <label for="berat">Berat (kg):</label>
            <input type="text" id="berat" name="berat" readonly>
        </div>

        <div class="form-group">
            <input type="submit" value="Simpan">
        </div>
    </form>
</div>

<script>
// Fungsi untuk memperbarui berat total berdasarkan jenis sampah yang dipilih
function updateBerat() {
    const jenisSampahSelect = document.getElementById('jenis_sampah');
    const beratInput = document.getElementById('berat');
    
    // Ambil total berat dari atribut data-total-berat di opsi yang dipilih
    const selectedOption = jenisSampahSelect.options[jenisSampahSelect.selectedIndex];
    const totalBerat = selectedOption.getAttribute('data-total-berat');
    
    // Tampilkan total berat di input berat
    beratInput.value = totalBerat ? totalBerat : '';
}
</script>

</body>
</html>

<?php
$conn->close();
?>
