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

// Proses delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM nasabah WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Inisialisasi variabel pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data dari tabel nasabah berdasarkan pencarian
$sql = "SELECT id, Nama_nasabah, nomor_induk, alamat FROM nasabah";
if (!empty($search)) {
    $sql .= " WHERE Nama_nasabah LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR nomor_induk LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$result = $conn->query($sql);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal: " . $conn->error);
}

// Array untuk menyimpan data
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSU Lorong - Nasabah</title>
    <style>
        /* Gaya umum */
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

        /* Header */
        .header {
            background-color: #3290f5;
            color: white;
            padding: 10px 20px;
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
        }

        .logo-right img {
            width: 100px;
            height: auto;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            border: 1px solid #3290f5;
            border-radius: 4px;
            width: 200px;
        }

        .search-bar button {
            padding: 8px 12px;
            background-color: #3290f5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 8px;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3290f5;
            color: white;
        }

        td button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        td .edit {
            background-color: #28a745;
            color: white;
            border-radius: 5px;
        }

        td .delete {
            background-color: #dc3545;
            color: white;
        }

        td .edit:hover {
            background-color: #218838;
        }

        td .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <a href="nasabah.html" class="btn-kembali">⟵</a>
            <span class="logo-title">DATA NASABAH BSU LORONG</span>
        </div>
        <div class="logo-right">
            <img src="logo/logo putih.png" alt="Logo">
        </div>
    </div>

    <div class="container">
        <h1>Nasabah</h1>
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari nama atau nomor induk..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nomor Induk</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $index => $nasabah): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($nasabah['Nama_nasabah']) ?></td>
                            <td><?= htmlspecialchars($nasabah['nomor_induk']) ?></td>
                            <td><?= htmlspecialchars($nasabah['alamat']) ?></td>
                            <td>
                                <a href="?delete=<?= $nasabah['id'] ?>" class="delete">Delete</a>
                                <a href="editdatanasabah.php?id=<?= $nasabah['id'] ?>" class="edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
