# TEMA WEBSITE
Bertema-kan asuransi jaminan kesehatan yang terinspirasi dari BPJS. Serta bernamakan BPJS Mandiri Registration yang merupakan sebuah sistem berbasis web yang dibuat untuk mensimulasikan proses penddaftaran data calon peserta, mulai dari pengisian biodata, pengunggahan dokumen persyaratan, sampai proses pendaftaran dan konfirmasi status.

# PENJELASAN DATABASE
Database yang digunakan bernama bpjs_registration dan terdiri dari tiga tabel utama:
1. Tabel peserta, digunakan untuk menyimpan biodata peserta, meliputi ID peserta, NIK, nama, jenis kelamin, tanggal lahir, alamat, nomor telepon, dan sttaus pekerjaan.
2. Tabel dokumen_peserta, untuk menyimpan informasi dokumen persyaratan peserta, yaitu file KTP, file KK, tanggal upload, dan ID peserta sebagai foreign key.
3. Tabel pendaftaran, digunakan untuk menyimpan informasi pendaftaran peserta, meliputi ID pendaftaran, ID peserta sebagai foreign key, tanggal daftar, dan status pendaftaran

ketiga tabel saling terhubung melalui atribut id_peserta. Tabel peserta menjadi tabel utama yang berelasi dengan tabel dokumen_peserta dan pendaftaran. Relasi menggunakan primary key dan foreign key untuk menjaga keterhubungan integritas. Sementara UNIQUe digunakan untuk mencegah nilai yang sama pada kolom tertentu. Pada tabel peserta, NIK harus unik. Sementara itu, id_peserta pada tabel dokumen_peserta dan pendaftaran juga bersifat unik sehingga satu peserta hanya dapat memiliki satu data dokumen dan satu data pendaftaran.

# PENJELASAN ALUR PROGRAM
## a. config/database.php
Berisi class Database yang digunakan untuk membuat koneksi ke database MySQL menggunakan PDO.

## b. Folder class
1. Peserta.php untuk mengelola data biodata peserta.
2. DokumenPeserta.php untuk mengelola data dokumen peserta.
3. Pendaftaran.php untuk mengelola data pendaftaran.

Setiap class memiliki atribut dan method untuk menjalankan operasi CRUD (Create, Read, Update, Delete) menggunakan Prepared Statements.

## c. index.php
Berfungsi sebagai file utama yang mengatur jalannya webiste. File ini memanggil koneksi database dan seluruh class, membuat objek, memproses operasi CRUD, menangani upload dokumen, serta mengatur navigasi halaman.

## d. Folder view
Berisi file tampilan untuk masing-masing halaman, yaitu dashboard.php, biodata.php, dokumen.php, dan konfirmasi.php.

## e. Folder style
Berisi style.css untuk mengatur tampilan website dan script.js untuk mengatur interaksi sidebar.

Alur penggunaan aplikasi:
1. Pengguna mengisi biodata peserta melalui halaman Biodata Peserta.
2. Data biodata disimpan ke tabel peserta.
3. Pengguna mengunggah dokumen KTP dan KK melalui halaman Kelengkapan Dokumen.
4. Informasi dokumen disimpan ke tabel dokumen_peserta, sedangkan file disimpan di folder uploads.
5. Setelah biodata dan dokumen tersedia, pengguna dapat melakukan pendaftaran.
6. Data pendaftaran disimpan ke tabel pendaftaran.
7. Pengguna dapat melihat dan memperbarui status pendaftaran melalui halaman Pendaftaran.

## Alur programnya
1. Pemanggilan Class, file index.php memanggil database.php, Peserta.php, DokumenPeserta.php, dan Pendaftaran.php menggunakan require_once.
2. Koneksi Database, program membuat objek dari class Database, kemudian memanggil method getConnection() untuk menghubungkan website dengan database menggunakan PDO.
3. Pembuatan Objek, program membuat objek dari masing-masing class, yaitu Peserta, DokumenPeserta, dan Pendaftaran dengan menggunakan koneksi database yang sudah dibuat.
4. Proses CRUD, program memeriksa request yang diterima, kemudian menjalankan method yang sesuai untuk proses Create, Read, Update, dan Delete. Seluruh query database menggunakan Prepared Statements.
5. Pengaturan Halaman, program membaca parameter page dari URL untuk menentukan halaman yang akan ditampilkan. Jika halaman tidak sesuai dengan daftar yang tersedia, program akan mengarahkan pengguna ke Dashboard.
6. Pemanggilan View, berdasarkan halaman yang dipilih, index.php memanggil file tampilan dari folder view, yaitu dashboard.php, biodata.php, dokumen.php, atau konfirmasi.php.
7. Pengaturan Tampilan dan Interaksi, file style.css mengatur tampilan website, sedangkan script.js mengatur interaksi sidebar menggunakan JavaScript.

# DOKUMENTASI
[![Video Demonstrasi TP8](https://img.youtube.com/vi/zaw5hpzsecw/hqdefault.jpg)](https://youtu.be/zaw5hpzsecw)
