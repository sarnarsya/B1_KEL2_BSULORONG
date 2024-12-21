<?php
// Konfigurasi koneksi database
$host = 'localhost';
$username = 'root';  // Sesuaikan dengan username MySQL Anda
$password = '';      // Sesuaikan dengan password MySQL Anda
$dbname = 'bsulorong1';

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel jenis laporan
$jenis_laporan = "Riwayat Sampah Nasabah"; // Default pilihan

// Cek jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_laporan = $_POST['jenis_laporan'];
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Sampah - BSU Lorong</title>
    <style>
         .header {
            background-color: #3290f5;
            color: white;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
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
            width: 100px;
            height: auto;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #3290f5;
            margin-bottom: 20px;
            font-size: 32px;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 10px;
        }

        label {
            font-size: 16px;
            margin-right: 10px;
            color: #555;
        }

        input[type="text"], input[type="submit"], select {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        input[type="submit"] {
            background-color: #3290f5;
            color: white;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3290f5;
            color: white;
            font-weight: bold;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #3290f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <a href="menu.html" class="btn-kembali">⟵</a>
            <span class="logo-title">BSU LORONG</span>
        </div>
        <div class="logo-right">
            <img src="logo/logo putih.png" alt="Logo">
        </div>
    </div>

    <div class="container">
        <h1>Riwayat Sampah</h1>
        <form method="POST">
            <select name="jenis_laporan">
                <option value="Riwayat Sampah Nasabah" <?php echo $jenis_laporan == 'Riwayat Sampah Nasabah' ? 'selected' : ''; ?>>Riwayat Sampah Nasabah</option>
                <option value="Total Berat Sampah" <?php echo $jenis_laporan == 'Total Berat Sampah' ? 'selected' : ''; ?>>Total Berat Sampah</option>
            </select>
            <input type="submit" value="Tampilkan">
        </form>

        <?php if ($jenis_laporan == "Riwayat Sampah Nasabah"): ?>
            <?php
            // Query untuk menampilkan riwayat sampah nasabah
            $sql = "SELECT 
                        transaksi_sampah1.jenis_sampah, 
                        transaksi_sampah1.berat, 
                        transaksi_sampah1.tanggal, 
                        nasabah.Nama_nasabah
                    FROM 
                        transaksi_sampah1
                    INNER JOIN 
                        nasabah ON transaksi_sampah1.id_nasabah = nasabah.id";

            $result = $conn->query($sql);
            ?>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>Jenis Sampah</th>
                            <th>Berat Sampah (kg)</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['Nama_nasabah']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis_sampah']); ?></td>
                                <td><?php echo htmlspecialchars($row['berat']); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada data riwayat sampah.</p>
            <?php endif; ?>

        <?php elseif ($jenis_laporan == "Total Berat Sampah"): ?>
            <?php
            // Query untuk total berat berdasarkan jenis sampah
            $sql_total_per_jenis = "SELECT 
                                        jenis_sampah, 
                                        SUM(berat) AS total_berat
                                    FROM 
                                        transaksi_sampah1
                                    GROUP BY 
                                        jenis_sampah";

            $result_total_per_jenis = $conn->query($sql_total_per_jenis);

            // Query untuk total semua berat sampah
            $sql_total_semua = "SELECT 
                                    SUM(berat) AS total_berat_semua
                                FROM 
                                    transaksi_sampah1";

            $result_total_semua = $conn->query($sql_total_semua);
            $total_berat_semua = $result_total_semua->fetch_assoc()['total_berat_semua'];
            ?>
            <h2>Total Berat Berdasarkan Jenis Sampah</h2>
            <table>
                <thead>
                    <tr>
                        <th>Jenis Sampah</th>
                        <th>Total Berat (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row_total = $result_total_per_jenis->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row_total['jenis_sampah']); ?></td>
                            <td><?php echo htmlspecialchars($row_total['total_berat']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="total">
                Total Semua Berat Sampah: <?php echo $total_berat_semua; ?> kg
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
