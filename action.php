<?php
require_once 'koneksi.php';

#Class Film

class Film {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM film");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM film WHERE id_film = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function insert($judul, $genre, $durasi, $harga_tiket) {
        $stmt = $this->conn->prepare("INSERT INTO film (judul, genre, durasi, harga_tiket) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $judul, $genre, $durasi, $harga_tiket);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update($id, $judul, $genre, $durasi, $harga_tiket) {
        $stmt = $this->conn->prepare("UPDATE film SET judul=?, genre=?, durasi=?, harga_tiket=? WHERE id_film=?");
        $stmt->bind_param("ssssi", $judul, $genre, $durasi, $harga_tiket, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM film WHERE id_film=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

# class pelanggan

class Pelanggan {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM pelanggan");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pelanggan WHERE id_pelanggan=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function insert($nama, $no_hp, $email) {
        $stmt = $this->conn->prepare("INSERT INTO pelanggan (nama, no_hp, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $no_hp, $email);
        return $stmt->execute();
    }

    public function update($id, $nama, $no_hp, $email) {
        $stmt = $this->conn->prepare("UPDATE pelanggan SET nama=?, no_hp=?, email=? WHERE id_pelanggan=?");
        $stmt->bind_param("sssi", $nama, $no_hp, $email, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


# objek
$db = new Database();
$conn = $db->getConnection();

$film = new Film($conn);
$pel  = new Pelanggan($conn);

# FILM
# insert film 
if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $genre = $_POST['genre'];
    $durasi = $_POST['durasi'];
    $harga_tiket = $_POST['harga_tiket'];

    if (empty($judul) || empty($genre) || empty($durasi) || empty($harga_tiket)) {
        echo "Semua field harus diisi! <a href='index.php'>Kembali</a>";
    } 
    else {
        if ($film->insert($judul, $genre, $durasi, $harga_tiket)) {
            echo "Data berhasil disimpan, <a href='index.php'>Kembali</a>";
        } else {
            echo "Gagal menyimpan data. <a href='index.php'>Kembali</a>";
        }
    }
}

# update film 
if (isset($_POST['edit'])) {
    $id = $_POST['id_film'];
    $judul = $_POST['judul'];
    $genre = $_POST['genre'];
    $durasi = $_POST['durasi'];
    $harga_tiket = $_POST['harga_tiket'];

    if ($film->update($id, $judul, $genre, $durasi, $harga_tiket)) {
        echo "Data berhasil diubah, <a href='index.php'>Kembali</a>";
    } else {
        echo "Gagal mengubah data. <a href='index.php'>Kembali</a>";
    }
}

# Delete film
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if ($film->delete($id)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menghapus data. <a href='index.php'>Kembali</a>";
    }
}

#PELANGGAN
# Insert pelanggan 
if (isset($_POST['submit_pelanggan'])) {
    $nama  = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];

    // cek field kosong
    if (empty($nama) || empty($no_hp) || empty($email)) {
        echo "Semua field harus diisi! <a href='pelanggan.php'>Kembali</a>";
        exit;
    }

    // cek format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Format email tidak valid! <a href='pelanggan.php'>Kembali</a>";
        exit;
    }

    if ($pel->insert($nama, $no_hp, $email)) {
        echo "Data berhasil disimpan, <a href='pelanggan.php'>Kembali</a>";   
    } else {
        echo "Gagal menyimpan data. <a href='pelanggan.php'>Kembali</a>";     
    }
}

# Update pelanggan 
if (isset($_POST['update_pelanggan'])) {
    $id    = $_POST['id_pelanggan'];
    $nama  = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];

    if (empty($nama) || empty($no_hp) || empty($email)) {
        echo "Semua field harus diisi! <a href='pelanggan.php'>Kembali</a>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Format email tidak valid! <a href='pelanggan.php'>Kembali</a>";
        exit;
    }

    if ($pel->update($id, $nama, $no_hp, $email)) {
        echo "Data berhasil diubah, <a href='pelanggan.php'>Kembali</a>";     
    } else {
        echo "Gagal mengubah data. <a href='pelanggan.php'>Kembali</a>";      
    }
}

# Delete pelanggan 
if (isset($_GET['delete_pelanggan'])) {
    $id = $_GET['delete_pelanggan'];

    if ($pel->delete($id)) {
        echo "Data berhasil dihapus, <a href='pelanggan.php'>Kembali</a>";    
    } else {
        echo "Gagal menghapus data. <a href='pelanggan.php'>Kembali</a>";     
    }
}


#class user

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && $user['password'] === md5($password)) {
            return $user;
        }
        return false;
    }
}
?>