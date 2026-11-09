<?php
// Koneksi database
require_once __DIR__ . '/../config/database.php';

$database_dashboard = new Database();
$db_dashboard = $database_dashboard->getConnection();

// Total peserta
$stmt = $db_dashboard->prepare(
    "SELECT COUNT(*) FROM peserta"
);
$stmt->execute();
$total_peserta = (int) $stmt->fetchColumn();

// Total dokumen
$stmt = $db_dashboard->prepare(
    "SELECT COUNT(*) FROM dokumen_peserta"
);
$stmt->execute();
$total_dokumen = (int) $stmt->fetchColumn();

// Total pendaftaran
$stmt = $db_dashboard->prepare(
    "SELECT COUNT(*) FROM pendaftaran"
);
$stmt->execute();
$total_pendaftaran = (int) $stmt->fetchColumn();

//MENENTUKAN PROGRES PENDAFTARAN
$progress = 0;

if ($total_peserta > 0) {
    $progress = 1;
}

if ($total_dokumen > 0) {
    $progress = 2;
}

if ($total_pendaftaran > 0) {
    $progress = 3;
}

$persentase = round(($progress / 3) * 100);
?>

<section class="dashboard-section">
    <!-- HEADER DASHBOARD -->
    <div class="page-heading">
        <p class="greeting">
            WELCOME TO BPJS MANDIRI REGISTRATION
        </p>
        <h1>Dashboard Pendaftaran</h1>
        <p>
            Kelola proses pendaftaran peserta BPJS Mandiri
            melalui tiga tahapan berikut.
        </p>
    </div>

    <!-- STATISTIK -->
    <div class="dashboard-stats">
        <!-- Jumlah peserta yang mendaftar -->
        <div class="stat-card">
            <p>Total Peserta</p>
            <h2>
                <?= $total_peserta ?>
            </h2>
        </div>

        <!-- Jumlah dokumen yang ada -->
        <div class="stat-card">
            <p>Dokumen Diunggah</p>
            <h2>
                <?= $total_dokumen ?>
            </h2>
        </div>


        <!-- jumlah pendaftar -->
        <div class="stat-card"
            <p>Pendaftaran Tercatat</p>
            <h2>
                <?= $total_pendaftaran ?>
            </h2>
        </div>
    </div>

    <!-- DIAGRAM ALUR -->
    <div class="flow-container">
        <!-- TAHAP 1: BIODATA -->
        <div class="flow-card">
            <div class="flow-number">
                <?= $progress >= 1 ? '✓' : '01' ?>
            </div>
            <h3>Biodata Peserta</h3>
            <p>
                Lengkapi identitas dan informasi
                calon peserta BPJS Mandiri.
            </p>
            <span class="flow-status">
                <?= $progress >= 1
                    ? 'Data tersedia'
                    : 'Belum dimulai' ?>
            </span>
            <a href="index.php?page=biodata">
                Buka Biodata →
            </a>
        </div>

        <div class="flow-arrow">
            ↓
        </div>

        <!-- TAHAP 2: DOKUMEN -->
        <div class="flow-card">
            <div class="flow-number">
                <?= $progress >= 2 ? '✓' : '02' ?>
            </div>

            <h3>Kelengkapan Dokumen</h3>
            <p>
                Unggah dokumen KTP dan Kartu Keluarga
                sebagai persyaratan pendaftaran.
            </p>
            <span class="flow-status">
                <?= $progress >= 2
                    ? 'Dokumen tersedia'
                    : 'Belum dimulai' ?>
            </span>
            <a href="index.php?page=dokumen">
                Buka Dokumen →
            </a>
        </div>

        <div class="flow-arrow">
            ↓
        </div>

        <!-- TAHAP 3: KONFIRMASI -->
        <div class="flow-card">
            <div class="flow-number">
                <?= $progress >= 3 ? '✓' : '03' ?>
            </div>
            <h3>Konfirmasi Pendaftaran</h3>
            <p>
                Periksa kembali informasi dan
                selesaikan proses pendaftaran.
            </p>
            <span class="flow-status">
                <?= $progress >= 3
                    ? 'Pendaftaran tercatat'
                    : 'Belum dimulai' ?>
            </span>
            <a href="index.php?page=konfirmasi">
                Buka Konfirmasi →
            </a>
        </div>
    </div>
</section>
