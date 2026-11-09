-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2026 pada 12.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bpjs_registration`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_peserta`
--

CREATE TABLE `dokumen_peserta` (
  `id_dokumen` int(11) NOT NULL,
  `id_peserta` int(11) NOT NULL,
  `file_ktp` varchar(255) NOT NULL,
  `file_kk` varchar(255) NOT NULL,
  `tanggal_upload` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen_peserta`
--

INSERT INTO `dokumen_peserta` (`id_dokumen`, `id_peserta`, `file_ktp`, `file_kk`, `tanggal_upload`) VALUES
(4, 13, 'uploads/ktp_elena.png', 'uploads/kk_elena.png', '2026-11-09'),
(5, 14, 'uploads/ktp_white.png', 'uploads/kk_white.png', '2026-11-09'),
(6, 16, 'uploads/ktp_alya.png', 'uploads/kk_alya.png', '2026-11-09'),
(7, 17, 'uploads/ktp_nara.png', 'uploads/kk_nara.png', '2026-11-09'),
(9, 19, 'uploads/ktp_clara.png', 'uploads/kk_clara.png', '2026-11-09'),
(10, 20, 'uploads/ktp_dimas.png', 'uploads/kk_dimas.png', '2026-11-09'),
(11, 21, 'uploads/ktp_salsa.png', 'uploads/kk_salsa.png', '2026-11-09'),
(12, 22, 'uploads/ktp_fajar.png', 'uploads/kk_fajar.png', '2026-11-09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_pendaftaran` int(11) NOT NULL,
  `id_peserta` int(11) NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `status_pendaftaran` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id_pendaftaran`, `id_peserta`, `tanggal_daftar`, `status_pendaftaran`) VALUES
(4, 13, '2026-11-09', 'Disetujui'),
(5, 14, '2026-11-09', 'Diproses'),
(6, 16, '2026-11-09', 'Menunggu Konfirmasi'),
(7, 17, '2026-11-09', 'Diproses'),
(9, 19, '2026-11-09', 'Disetujui'),
(10, 20, '2026-11-09', 'Diproses'),
(11, 21, '2026-11-09', 'Disetujui'),
(12, 22, '2026-11-09', 'Menunggu Konfirmasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peserta`
--

CREATE TABLE `peserta` (
  `id_peserta` int(11) NOT NULL,
  `nik_peserta` varchar(16) NOT NULL,
  `nama_peserta` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `status_pekerjaan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peserta`
--

INSERT INTO `peserta` (`id_peserta`, `nik_peserta`, `nama_peserta`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `nomor_telepon`, `status_pekerjaan`) VALUES
(13, '1122334455667788', 'Elena Van de Meer', 'Perempuan', '2006-11-17', 'Bandung', '089660053203', 'Mahasiswa'),
(14, '1234567891011121', 'White Blair', 'Perempuan', '2026-09-01', 'Bandung', '089975672346', 'Pelajar'),
(16, '3200000000000001', 'Alya Putri', 'Perempuan', '2005-03-12', 'Bandung', '081234567801', 'Mahasiswa'),
(17, '3200000000000002', 'Nara Safitri', 'Perempuan', '2004-07-21', 'Cimahi', '081234567802', 'Mahasiswa'),
(19, '3200000000000004', 'Clara Maharani', 'Perempuan', '2004-02-14', 'Bandung', '081234567804', 'Mahasiswa'),
(20, '3200000000000005', 'Dimas Arya', 'Laki-laki', '2005-06-19', 'Sumedang', '081234567805', 'Pelajar'),
(21, '3200000000000006', 'Salsa Anindya', 'Perempuan', '2004-09-23', 'Cimahi', '081234567806', 'Mahasiswa'),
(22, '3200000000000007', 'Fajar Ramadhan', 'Laki-laki', '2005-12-08', 'Bandung', '081234567807', 'Karyawan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dokumen_peserta`
--
ALTER TABLE `dokumen_peserta`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD UNIQUE KEY `id_peserta` (`id_peserta`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD UNIQUE KEY `id_peserta` (`id_peserta`);

--
-- Indeks untuk tabel `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_peserta`),
  ADD UNIQUE KEY `nik_peserta` (`nik_peserta`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dokumen_peserta`
--
ALTER TABLE `dokumen_peserta`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peserta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokumen_peserta`
--
ALTER TABLE `dokumen_peserta`
  ADD CONSTRAINT `dokumen_peserta_ibfk_1` FOREIGN KEY (`id_peserta`) REFERENCES `peserta` (`id_peserta`);

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`id_peserta`) REFERENCES `peserta` (`id_peserta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
