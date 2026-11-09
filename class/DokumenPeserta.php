<?php

class DokumenPeserta
{

    private $conn;
    private $table_name = "dokumen_peserta";

    // atribut
    public $id_dokumen;
    public $id_peserta;
    public $file_ktp;
    public $file_kk;
    public $tanggal_upload;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create()
    {
        // kita akan memasukkan data baru ke dalam tabel yang sudah dideklarasikan
        $query = "INSERT INTO " . $this->table_name . "
        (id_peserta, file_ktp, file_kk, tanggal_upload)
        VALUES
        (:id_peserta, :file_ktp, :file_kk, :tanggal_upload)";

        // prepare (mempersiapkan query)
        // note buat saya: prepare digunakan untuk mencegah SQL injection
        // memisahkan intruksi SQL dari data pengguna
        $stmt = $this->conn->prepare($query);

        // menghubungkan data (atribut objek) dengan placeholder
        $stmt->bindParam(":id_peserta", $this->id_peserta);
        $stmt->bindParam(":file_ktp", $this->file_ktp);
        $stmt->bindParam(":file_kk", $this->file_kk);
        $stmt->bindParam(":tanggal_upload", $this->tanggal_upload);

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
              file_ktp = :file_ktp,
              file_kk = :file_kk,
              tanggal_upload = :tanggal_upload
              WHERE id_dokumen = :id_dokumen";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":file_ktp", $this->file_ktp);
        $stmt->bindParam(":file_kk", $this->file_kk);
        $stmt->bindParam(":tanggal_upload", $this->tanggal_upload);
        $stmt->bindParam(":id_dokumen", $this->id_dokumen);

        return $stmt->execute();
    }

    // DELETE
    public function delete()
    {

        $query = "DELETE FROM " . $this->table_name . "
              WHERE id_dokumen = :id_dokumen";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_dokumen", $this->id_dokumen);

        return $stmt->execute();
    }
}
