<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    // kalau pakai XAMPP biasanya password dikosongkan
    private $password = "";
    private $dbname = "db_bioskop";
    private $conn;
    public function __construct() 
    {
        $this->connect();
    }

    public function connect() {
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }
}
?>