<?php

// database.php digunakan untuk membuat dan menyediakan koneksi ke MySQL
// buat class bernama database
class Database {
    // atribut koneksi
    private $host = "localhost";
    private $db_name = "bpjs_registration";
    private $username = "root";
    private $password = "";

    public $conn;

    // method untuk membuat koneksi mengguanakn PDO
    public function getConnection() {
        // try catch itu buat menangani kemungkinan kegagalan koneksi
        try {
            // buat koneksi ke MYSQL dengan konfigurasi host, nama database, username, dan passwoard
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            // mengatur PDO agar menampilkan execption ketika terjadi kesalahan dalam proses database
            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            // mengembalikan objek koneksi supaya bisa digunakan oleh class lainnya
            return $this->conn;

            // menampilkan pesan kalau koneksi database gagal
        } catch (PDOException $exception) {
            echo "Koneksi gagal: " . $exception->getMessage();
        }
    }
}