<?php
session_start();

// pengecekan login, melindungi index
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';
require_once 'action.php';

// membuat objek dan mengambil data
$db = new Database();
$conn = $db->getConnection();
$FILM = new Film($conn);

// mengecek jika ada edit
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $FILM->getById($_GET['edit']);
}

// ambil semua data film
$dataFilm = $FILM->getAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Film Bioskop</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- NAVBAR -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Data Film Bioskop</h1>

        <?php if ($editData): ?>
        <!-- Form edit -->
        <form action="action.php" method="post">
            <input type="hidden" name="id_film" value="<?= htmlspecialchars($editData['id_film']) ?>">

            <table>
                <tr>
                    <td>Judul</td>
                    <td>:</td>
                    <td><input type="text" name="judul" required value="<?= htmlspecialchars($editData['judul']) ?>">
                    </td>
                </tr>

                <tr>
                    <td>Genre</td>
                    <td>:</td>
                    <td>
                        <select name="genre" required>
                            <option value="Komedi" <?= $editData['genre']=='Komedi'?'selected':'' ?>>Komedi</option>
                            <option value="Horor" <?= $editData['genre']=='Horor'?'selected':'' ?>>Horor</option>
                            <option value="Romansa" <?= $editData['genre']=='Romansa'?'selected':'' ?>>Romansa</option>
                            <option value="Animasi" <?= $editData['genre']=='Animasi'?'selected':'' ?>>Animasi</option>
                            <option value="Petualangan" <?= $editData['genre']=='Petualangan'?'selected':'' ?>>
                                Petualangan</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Durasi</td>
                    <td>:</td>
                    <td><input type="text" name="durasi" required value="<?= htmlspecialchars($editData['durasi']) ?>">
                    </td>
                </tr>

                <tr>
                    <td>Harga Tiket</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="harga_tiket" value="<?= htmlspecialchars($editData['harga_tiket']) ?>">
                    </td>
                </tr>
            </table>

            <input type="submit" value="Update" name="edit">
            <a href="index.php" class="batal">Batal</a>
        </form>

        <?php else: ?>

        <!-- Form tambah data -->
        <form action="action.php" method="post">
            <table>
                <tr>
                    <td>Judul</td>
                    <td>:</td>
                    <td><input type="text" name="judul" required></td>
                </tr>

                <tr>
                    <td>Genre</td>
                    <td>:</td>
                    <td>
                        <select name="genre" required>
                            <option value="">--Pilih--</option>
                            <option value="Komedi">Komedi</option>
                            <option value="Horor">Horor</option>
                            <option value="Romansa">Romansa</option>
                            <option value="Animasi">Animasi</option>
                            <option value="Petualangan">Petualangan</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Durasi</td>
                    <td>:</td>
                    <td><input type="text" name="durasi" required></td>
                </tr>

                <tr>
                    <td>Harga Tiket</td>
                    <td>:</td>
                    <td><input type="text" name="harga_tiket" required></td>
                </tr>
            </table>

            <input type="submit" value="Submit" name="submit">
        </form>

        <?php endif; ?>

        <h2>Daftar Film</h2>

        <!-- tabel data film -->
        <table class="data-table">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Genre</th>
                <th>Durasi</th>
                <th>Harga Tiket</th>
                <th>Aksi</th>
            </tr>

            <?php if (!empty($dataFilm)): ?>
            <?php $no = 1; foreach ($dataFilm as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['genre']) ?></td>
                <td><?= htmlspecialchars($row['durasi']) ?></td>
                <td><?= htmlspecialchars($row['harga_tiket']) ?></td>
                <td class="aksi">
                    <a href="action.php?delete=<?= $row['id_film'] ?>" class="delete"
                        onclick="return confirm('Yakin hapus data ini?')">Delete</a>

                    <a href="index.php?edit=<?= $row['id_film'] ?>" class="update">Update</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php else: ?>
            <tr>
                <td colspan="6">Belum ada data film.</td>
            </tr>
            <?php endif; ?>
        </table>

    </div>

</body>

</html>