<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';
require_once 'action.php';

$db = new Database();
$conn = $db->getConnection();
$pel = new Pelanggan($conn);

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $pel->getById($_GET['edit']);
}

$dataPelanggan = $pel->getAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container">

        <h1>Data Pelanggan</h1>

        <!-- form update -->
        <?php if ($editData): ?>
        <form action="action.php" method="post">
            <input type="hidden" name="id_pelanggan" value="<?= htmlspecialchars($editData['id_pelanggan']) ?>">

            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><input type="text" name="nama" value="<?= htmlspecialchars($editData['nama']) ?>"></td>
                </tr>
                <tr>
                    <td>No HP</td>
                    <td>:</td>
                    <td><input type="text" name="no_hp" value="<?= htmlspecialchars($editData['no_hp']) ?>"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($editData['email']) ?>" required>
                    </td>
                </tr>
            </table>

            <input type="submit" value="Update" name="update_pelanggan" class="update">
            <a href="pelanggan.php" class="batal">Batal</a>
        </form>


        <?php else: ?>


        <!-- form tambah -->
        <form action="action.php" method="post">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><input type="text" name="nama" required></td>
                </tr>
                <tr>
                    <td>No HP</td>
                    <td>:</td>
                    <td><input type="text" name="no_hp" required pattern="[0-9]{10,13}" minlength="10" maxlength="13">
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><input type="email" name="email" required></td>
                </tr>
            </table>

            <input type="submit" value="Submit" name="submit_pelanggan" class="update">
        </form>

        <?php endif; ?>


        <!-- tabel data -->
        <h2>Daftar Pelanggan</h2>

        <table class="data-table">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>

            <?php if (!empty($dataPelanggan)): ?>
            <?php $no = 1; foreach ($dataPelanggan as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['no_hp']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td class="aksi">
                    <a href="pelanggan.php?edit=<?= $row['id_pelanggan'] ?>" class="update">Edit</a>
                    <a href="action.php?delete_pelanggan=<?= $row['id_pelanggan'] ?>" class="delete"
                        onclick="return confirm('Yakin hapus data ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php else: ?>
            <tr>
                <td colspan="5">Belum ada data pelanggan.</td>
            </tr>
            <?php endif; ?>
        </table>

    </div>

</body>

</html>