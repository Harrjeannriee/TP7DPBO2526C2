<?php
// manggil class yang dibutuhkan
require_once "config/database.php";
require_once "class/Peserta.php";
require_once "class/Pendaftaran.php";
require_once "class/DokumenPeserta.php";

// koneksi ke database
$database = new Database();
$db = $database->getConnection();

// tentukan halaman yang mau dibuka
// kalau belum ada halaman yang dipilih, tampilkan dashboard
$page = $_GET['page'] ?? 'dashboard';

// daftar halaman yang boleh diakses
$allowed_pages = [
    'dashboard',
    'biodata',
    'dokumen',
    'konfirmasi'
];

// kalau halaman tidak ada dalam daftar, arahkan ke dashboard
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// buat objek dari masing-masing class
$peserta = new Peserta($db);
$dokumen = new DokumenPeserta($db);
$pendaftaran = new Pendaftaran($db);

$error = null;

// 1. PROSES CREATE BIODATA
// cek apakah form simpan biodata dikirim menggunakan POST
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['simpan_biodata'])
) {

    // ambil data dari form dan masukkan ke atribut objek peserta
    $peserta->nik_peserta = $_POST['nik_peserta'];
    $peserta->nama_peserta = $_POST['nama_peserta'];
    $peserta->jenis_kelamin = $_POST['jenis_kelamin'];
    $peserta->tanggal_lahir = $_POST['tanggal_lahir'];
    $peserta->alamat = $_POST['alamat'];
    $peserta->nomor_telepon = $_POST['nomor_telepon'];
    $peserta->status_pekerjaan = $_POST['status_pekerjaan'];

    try {

        // simpan data peserta ke database
        if ($peserta->create()) {

            // kalau berhasil, kembali ke halaman biodata
            header("Location: index.php?page=biodata&status=success");
            exit;
        }

    } catch (PDOException $e) {

        // tampilkan pesan kalau NIK sudah digunakan atau data gagal disimpan
        $error = "NIK sudah terdaftar atau data tidak dapat disimpan.";
    }
}

// 2. PROSES UPDATE BIODATA
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['update_biodata'])
) {

    // ambil data yang sudah diedit dari form
    $peserta->id_peserta = $_POST['id_peserta'];
    $peserta->nik_peserta = $_POST['nik_peserta'];
    $peserta->nama_peserta = $_POST['nama_peserta'];
    $peserta->jenis_kelamin = $_POST['jenis_kelamin'];
    $peserta->tanggal_lahir = $_POST['tanggal_lahir'];
    $peserta->alamat = $_POST['alamat'];
    $peserta->nomor_telepon = $_POST['nomor_telepon'];
    $peserta->status_pekerjaan = $_POST['status_pekerjaan'];

    try {

        // simpan perubahan data peserta
        if ($peserta->update()) {

            // kembali ke halaman biodata kalau berhasil
            header("Location: index.php?page=biodata&status=updated");
            exit;
        }

    } catch (PDOException $e) {

        $error = "Data peserta gagal diperbarui.";
    }
}

// 3. MENGAMBIL DATA PESERTA UNTUK EDIT
$data_edit = null;

// cek apakah ada ID peserta yang dipilih untuk diedit
if (isset($_GET['edit'])) {

    $id_edit = $_GET['edit'];

    // ambil data peserta berdasarkan ID
    $stmt_edit = $db->prepare(
        "SELECT * FROM peserta WHERE id_peserta = :id"
    );

    $stmt_edit->bindParam(":id", $id_edit);
    $stmt_edit->execute();

    // ambil hasil query dalam bentuk array
    $data_edit = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}

// 4. PROSES DELETE BIODATA
if (isset($_GET['hapus'])) {

    // ambil ID peserta yang mau dihapus
    $peserta->id_peserta = $_GET['hapus'];

    try {

        // hapus data peserta melalui method delete()
        if ($peserta->delete()) {

            header("Location: index.php?page=biodata&status=deleted");
            exit;
        }

    } catch (PDOException $e) {

        // data tidak bisa dihapus kalau masih memiliki data yang terhubung
        $error = "Data peserta tidak dapat dihapus. Pastikan dokumen dan pendaftarannya sudah dihapus terlebih dahulu.";
    }
}

// 5. FUNGSI UPLOAD DOKUMEN
function uploadDokumen($file, $folder_upload)
{

    // cek apakah file berhasil dipilih dan diunggah
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File gagal diunggah.");
    }

    // maksimal ukuran file yang boleh diunggah adalah 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception("Ukuran file maksimal 2 MB.");
    }

    // ambil jenis file berdasarkan MIME
    $mime = mime_content_type($file['tmp_name']);

    // format file yang diperbolehkan hanya JPG dan PNG
    $format_diizinkan = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png'
    ];

    // cek apakah format file sesuai dengan ketentuan
    if (!isset($format_diizinkan[$mime])) {
        throw new Exception(
            "Format dokumen harus JPG, JPEG, atau PNG."
        );
    }

    // buat nama file unik supaya tidak bentrok dengan file lain
    $nama_file = uniqid('dokumen_', true)
        . '.' . $format_diizinkan[$mime];

    // tentukan lokasi penyimpanan file
    $tujuan = $folder_upload . $nama_file;

    // pindahkan file dari lokasi sementara ke folder uploads
    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        throw new Exception("Gagal menyimpan file.");
    }

    // kembalikan lokasi file untuk disimpan ke database
    return "uploads/" . $nama_file;
}

// 6. PROSES UPLOAD DOKUMEN
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['simpan_dokumen'])
) {

    try {

        $id_peserta = $_POST['id_peserta'];

        // cek apakah peserta yang dipilih sudah terdaftar
        $cek = $db->prepare(
            "SELECT id_peserta FROM peserta
             WHERE id_peserta = :id"
        );

        $cek->execute([
            ':id' => $id_peserta
        ]);

        // hentikan proses kalau peserta tidak ditemukan
        if (!$cek->fetch()) {
            throw new Exception("Peserta tidak ditemukan.");
        }

        // tentukan folder untuk menyimpan dokumen
        $folder_upload = __DIR__ . "/uploads/";

        // buat folder uploads kalau belum tersedia
        if (!is_dir($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        // upload file KTP dan KK
        $file_ktp = uploadDokumen(
            $_FILES['file_ktp'],
            $folder_upload
        );

        $file_kk = uploadDokumen(
            $_FILES['file_kk'],
            $folder_upload
        );

        // masukkan data dokumen ke atribut objek
        $dokumen->id_peserta = $id_peserta;
        $dokumen->file_ktp = $file_ktp;
        $dokumen->file_kk = $file_kk;
        $dokumen->tanggal_upload = date('Y-m-d');

        // simpan data dokumen ke database
        if ($dokumen->create()) {

            header("Location: index.php?page=dokumen&status=success");
            exit;
        }

    } catch (PDOException $e) {

        // peserta hanya boleh memiliki satu data dokumen
        $error = "Dokumen gagal disimpan. Pastikan peserta belum memiliki dokumen.";

    } catch (Exception $e) {

        // tampilkan pesan kalau proses upload mengalami kesalahan
        $error = $e->getMessage();
    }
}

// 7. PROSES UPDATE DOKUMEN
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['update_dokumen'])
) {

    try {

        $id_dokumen = $_POST['id_dokumen'];

        // ambil data dokumen lama berdasarkan ID
        $stmt_lama = $db->prepare(
            "SELECT * FROM dokumen_peserta
             WHERE id_dokumen = :id"
        );

        $stmt_lama->execute([':id' => $id_dokumen]);
        $data_lama = $stmt_lama->fetch(PDO::FETCH_ASSOC);

        // cek apakah data dokumen ditemukan
        if (!$data_lama) {
            throw new Exception("Dokumen tidak ditemukan.");
        }

        $folder_upload = __DIR__ . "/uploads/";

        // buat folder uploads kalau belum tersedia
        if (!is_dir($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        // kalau tidak ada file baru, tetap gunakan file lama
        $file_ktp_baru = $data_lama['file_ktp'];
        $file_kk_baru = $data_lama['file_kk'];

        // cek apakah ada file KTP baru yang diunggah
        if (
            isset($_FILES['file_ktp']) &&
            $_FILES['file_ktp']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            $file_ktp_baru = uploadDokumen(
                $_FILES['file_ktp'],
                $folder_upload
            );
        }

        // cek apakah ada file KK baru yang diunggah
        if (
            isset($_FILES['file_kk']) &&
            $_FILES['file_kk']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            $file_kk_baru = uploadDokumen(
                $_FILES['file_kk'],
                $folder_upload
            );
        }

        // masukkan data dokumen yang baru ke atribut objek
        $dokumen->id_dokumen = $id_dokumen;
        $dokumen->id_peserta = $data_lama['id_peserta'];
        $dokumen->file_ktp = $file_ktp_baru;
        $dokumen->file_kk = $file_kk_baru;
        $dokumen->tanggal_upload = date('Y-m-d');

        // simpan perubahan data dokumen
        if ($dokumen->update()) {

            // hapus file KTP lama kalau sudah diganti
            if ($file_ktp_baru !== $data_lama['file_ktp']) {

                $path_lama = __DIR__ . '/' . $data_lama['file_ktp'];

                if (is_file($path_lama)) {
                    unlink($path_lama);
                }
            }

            // hapus file KK lama kalau sudah diganti
            if ($file_kk_baru !== $data_lama['file_kk']) {

                $path_lama = __DIR__ . '/' . $data_lama['file_kk'];

                if (is_file($path_lama)) {
                    unlink($path_lama);
                }
            }

            header("Location: index.php?page=dokumen&status=updated");
            exit;
        }

        throw new Exception("Dokumen gagal diperbarui.");

    } catch (PDOException $e) {

        $error = "Dokumen gagal diperbarui: " . $e->getMessage();

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}

// 8. PROSES DELETE DOKUMEN
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['hapus_dokumen'])
) {

    try {

        $id_dokumen = $_POST['id_dokumen'];

        // ambil lokasi file KTP dan KK sebelum data dihapus
        $stmt_file = $db->prepare(
            "SELECT file_ktp, file_kk
             FROM dokumen_peserta
             WHERE id_dokumen = :id"
        );

        $stmt_file->execute([':id' => $id_dokumen]);
        $data_file = $stmt_file->fetch(PDO::FETCH_ASSOC);

        // cek apakah dokumen ditemukan
        if (!$data_file) {
            throw new Exception("Dokumen tidak ditemukan.");
        }

        // masukkan ID dokumen ke objek
        $dokumen->id_dokumen = $id_dokumen;

        // hapus data dokumen dari database
        if ($dokumen->delete()) {

            // ambil lokasi folder uploads
            $folder_upload = realpath(__DIR__ . '/uploads');

            // cek file KTP dan KK satu per satu
            foreach (['file_ktp', 'file_kk'] as $kolom) {

                if ($folder_upload !== false) {

                    $path_file = __DIR__ . '/' . $data_file[$kolom];
                    $real_file = realpath($path_file);

                    // pastikan file memang berada di folder uploads
                    if (
                        $real_file !== false &&
                        strpos($real_file, $folder_upload . DIRECTORY_SEPARATOR) === 0 &&
                        is_file($real_file)
                    ) {

                        // hapus file fisik dari folder uploads
                        unlink($real_file);
                    }
                }
            }

            header("Location: index.php?page=dokumen&status=deleted");
            exit;

        } else {

            throw new Exception("Dokumen gagal dihapus.");
        }

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}

// 9. MENGAMBIL DATA DOKUMEN UNTUK EDIT
$data_dokumen_edit = null;

// cek apakah ada dokumen yang dipilih untuk diedit
if (isset($_GET['edit_dokumen'])) {

    // ambil data dokumen berdasarkan ID
    $stmt_edit_dokumen = $db->prepare(
        "SELECT * FROM dokumen_peserta
         WHERE id_dokumen = :id"
    );

    $stmt_edit_dokumen->execute([
        ':id' => $_GET['edit_dokumen']
    ]);

    $data_dokumen_edit = $stmt_edit_dokumen->fetch(PDO::FETCH_ASSOC);
}

// 10. PROSES CREATE PENDAFTARAN
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['simpan_pendaftaran'])
) {

    try {

        $id_peserta = $_POST['id_peserta'];

        // cek apakah peserta sudah memiliki biodata dan dokumen
        $cek = $db->prepare(
            "SELECT p.id_peserta
             FROM peserta p
             INNER JOIN dokumen_peserta d
                ON p.id_peserta = d.id_peserta
             WHERE p.id_peserta = :id"
        );

        $cek->execute([':id' => $id_peserta]);

        // pendaftaran hanya bisa dilakukan kalau data peserta dan dokumen tersedia
        if (!$cek->fetch()) {
            throw new Exception(
                "Peserta belum memiliki biodata atau dokumen lengkap."
            );
        }

        // isi data pendaftaran
        $pendaftaran->id_peserta = $id_peserta;
        $pendaftaran->tanggal_daftar = date('Y-m-d');
        $pendaftaran->status_pendaftaran = "Menunggu Konfirmasi";

        // simpan data pendaftaran ke database
        if ($pendaftaran->create()) {

            header("Location: index.php?page=konfirmasi&status=success");
            exit;
        }

    } catch (PDOException $e) {

        // peserta tidak bisa mendaftar dua kali
        $error = "Pendaftaran gagal. Peserta mungkin sudah terdaftar.";

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}

// 11. PROSES UPDATE PENDAFTARAN
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['update_pendaftaran'])
) {

    try {

        // ambil data pendaftaran yang sudah diedit
        $pendaftaran->id_pendaftaran = $_POST['id_pendaftaran'];
        $pendaftaran->tanggal_daftar = $_POST['tanggal_daftar'];
        $pendaftaran->status_pendaftaran = $_POST['status_pendaftaran'];

        // simpan perubahan data pendaftaran
        if ($pendaftaran->update()) {

            header("Location: index.php?page=konfirmasi&status=updated");
            exit;
        }

        throw new Exception("Pendaftaran gagal diperbarui.");

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}

// 12. PROSES DELETE PENDAFTARAN
if (isset($_GET['hapus_pendaftaran'])) {

    // ambil ID pendaftaran yang mau dihapus
    $pendaftaran->id_pendaftaran = $_GET['hapus_pendaftaran'];

    try {

        // hapus data pendaftaran dari database
        if ($pendaftaran->delete()) {

            header("Location: index.php?page=konfirmasi&status=deleted");
            exit;
        }

        $error = "Pendaftaran gagal dihapus.";

    } catch (PDOException $e) {

        $error = "Pendaftaran gagal dihapus.";
    }
}

// 13. MENGAMBIL DATA PENDAFTARAN UNTUK EDIT
$data_pendaftaran_edit = null;

// cek apakah ada pendaftaran yang dipilih untuk diedit
if (isset($_GET['edit_pendaftaran'])) {

    // ambil data pendaftaran berdasarkan ID
    $stmt_edit = $db->prepare(
        "SELECT * FROM pendaftaran
         WHERE id_pendaftaran = :id"
    );

    $stmt_edit->execute([
        ':id' => $_GET['edit_pendaftaran']
    ]);

    $data_pendaftaran_edit = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}

// 14. READ DATA PESERTA
// ambil seluruh data peserta dari database
$stmt_peserta = $peserta->read();

// ubah hasil query menjadi array agar bisa digunakan di halaman
$data_peserta = $stmt_peserta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BPJS Mandiri Registration</title>

    <!-- manggil file CSS untuk mengatur tampilan website -->
    <link rel="stylesheet" href="style/style.css">

</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div class="logo">

            <h2>BPJS</h2>

            <p>Mandiri Registration</p>

        </div>

        <!-- menu navigasi untuk berpindah halaman -->
        <nav class="navigation">

            <a href="index.php?page=dashboard"
                class="<?= $page == 'dashboard' ? 'active' : '' ?>">
                Dashboard
            </a>

            <a href="index.php?page=biodata"
                class="<?= $page == 'biodata' ? 'active' : '' ?>">
                Biodata Peserta
            </a>

            <a href="index.php?page=dokumen"
                class="<?= $page == 'dokumen' ? 'active' : '' ?>">
                Kelengkapan Dokumen
            </a>

            <a href="index.php?page=konfirmasi"
                class="<?= $page == 'konfirmasi' ? 'active' : '' ?>">
                Pendaftaran
            </a>

        </nav>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <header class="topbar">

            <!-- tombol untuk mengecilkan dan membuka sidebar -->
            <button id="toggleSidebar" class="toggle-btn">
                ☰
            </button>

            <p>BPJS Mandiri Registration</p>

        </header>


        <!-- PEMANGGILAN HALAMAN -->

        <?php

        // tampilkan halaman sesuai menu yang dipilih
        if ($page == 'dashboard') {

            include 'view/dashboard.php';

        } elseif ($page == 'biodata') {

            include 'view/biodata.php';

        } elseif ($page == 'dokumen') {

            include 'view/dokumen.php';

        } elseif ($page == 'konfirmasi') {

            include 'view/konfirmasi.php';

        }

        ?>

    </main>

    <!-- manggil JavaScript untuk mengatur interaksi sidebar -->
    <script src="style/script.js"></script>

</body>

</html>
