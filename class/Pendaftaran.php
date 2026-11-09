<?php

class Pendaftaran
{
    private $conn;
    private $table_name = "pendaftaran";

    // atribut
    public $id_pendaftaran;
    public $id_peserta;
    public $tanggal_daftar;
    public $status_pendaftaran;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create()
    {
        // kita akan memasukkan data baru ke dalam tabel yang sudah dideklarasikan
        $query = "INSERT INTO " . $this->table_name . "
        (id_peserta, tanggal_daftar, status_pendaftaran)
        VALUES
        (:id_peserta, :tanggal_daftar, :status_pendaftaran)";

        // prepare (mempersiapkan query)
        // note buat saya: prepare digunakan untuk mencegah SQL injection
        // memisahkan intruksi SQL dari data pengguna
        $stmt = $this->conn->prepare($query);

        // menghubungkan data (atribut objek) dengan placeholder
        $stmt->bindParam(":id_peserta", $this->id_peserta);
        $stmt->bindParam(":tanggal_daftar", $this->tanggal_daftar);
        $stmt->bindParam(":status_pendaftaran", $this->status_pendaftaran);

        // mengembalikan hasil, true kalau berhasil, false kalau gagal
        // execute = menjalankan query
        return $stmt->execute();
    }

    // READ
    public function read()
    {

        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // UPDATE
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
              SET
              tanggal_daftar = :tanggal_daftar,
              status_pendaftaran = :status_pendaftaran
              WHERE id_pendaftaran = :id_pendaftaran";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":tanggal_daftar", $this->tanggal_daftar);
        $stmt->bindParam(":status_pendaftaran", $this->status_pendaftaran);
        $stmt->bindParam(":id_pendaftaran", $this->id_pendaftaran);

        return $stmt->execute();
    }

    // DELETE
    public function delete()
    {

        $query = "DELETE FROM " . $this->table_name . "
              WHERE id_pendaftaran = :id_pendaftaran";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_pendaftaran", $this->id_pendaftaran);

        return $stmt->execute();
    }
}