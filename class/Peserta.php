<?php

// Peserta.php digunakan untuk mengelola data peserta melalui operasi CRUD

class Peserta
{
    // atribut untuk menyimpan koneksi database
    private $conn;
    // menyimpan nama tabel yang akan dikelola 
    private $table_name = "peserta";

    // atribut
    public $id_peserta;
    public $nik_peserta;
    public $nama_peserta;
    public $jenis_kelamin;
    public $tanggal_lahir;
    public $alamat;
    public $nomor_telepon;
    public $status_pekerjaan;

    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create()
    {
        // kita akan memasukkan data baru ke dalam tabel yang sudah dideklarasikan
        $query = "INSERT INTO " . $this->table_name . "
        (nik_peserta, nama_peserta, jenis_kelamin, tanggal_lahir, alamat, nomor_telepon,
        status_pekerjaan)
        VALUES
        (:nik, :nama, :jenis_kelamin, :tanggal_lahir, :alamat,
        :nomor_telepon, :status_pekerjaan)";

        // prepare (mempersiapkan query)
        // note buat saya: prepare digunakan untuk mencegah SQL injection
        // memisahkan intruksi SQL dari data pengguna
        $stmt = $this->conn->prepare($query);

        // menghubungkan data (atribut objek) dengan placeholder
        $stmt->bindParam(":nik", $this->nik_peserta);
        $stmt->bindParam(":nama", $this->nama_peserta);
        $stmt->bindParam(":jenis_kelamin", $this->jenis_kelamin);
        $stmt->bindParam(":tanggal_lahir", $this->tanggal_lahir);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":nomor_telepon", $this->nomor_telepon);
        $stmt->bindParam(":status_pekerjaan", $this->status_pekerjaan);

        // mengembalikan hasil, true kalau berhasil, false kalau gagal
        // execute = menjalankan query
        return $stmt->execute();
    }

    // READ
    public function read()
    {
        // pilih tabel untuk dibaca
        $query = "SELECT * FROM " . $this->table_name;

        // persiapkan query
        $stmt = $this->conn->prepare($query);

        // jalankan query
        $stmt->execute();
        // mengembalikkan objek statement yang berisi hasil query
        return $stmt;
    }

    // UPDATE
    public function update()
    {

        $query = "UPDATE " . $this->table_name . "
              SET
              nik_peserta = :nik,
              nama_peserta = :nama,
              jenis_kelamin = :jenis_kelamin,
              tanggal_lahir = :tanggal_lahir,
              alamat = :alamat,
              nomor_telepon = :nomor_telepon,
              status_pekerjaan = :status_pekerjaan
              WHERE id_peserta = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nik", $this->nik_peserta);
        $stmt->bindParam(":nama", $this->nama_peserta);
        $stmt->bindParam(":jenis_kelamin", $this->jenis_kelamin);
        $stmt->bindParam(":tanggal_lahir", $this->tanggal_lahir);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":nomor_telepon", $this->nomor_telepon);
        $stmt->bindParam(":status_pekerjaan", $this->status_pekerjaan);
        $stmt->bindParam(":id", $this->id_peserta);

        return $stmt->execute();
    }

    // DELETE
    public function delete()
    {

        $query = "DELETE FROM " . $this->table_name . "
              WHERE id_peserta = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id_peserta);

        return $stmt->execute();
    }
}
