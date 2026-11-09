<?php
// INISIALISASI VARIABEL
// Mencegah Undefined Variable di editor
$data_edit = $data_edit ?? null;
$error = $error ?? null;
$data_peserta = $data_peserta ?? [];
?>


<!-- FORM BIODATA PESERTA -->
<section class="form-section">
    <div class="page-heading">
        <p class="greeting">TAHAP 01 · BIODATA</p>
        <h1>
            <?= $data_edit ? 'Edit Biodata Peserta' : 'Informasi Peserta' ?>
        </h1>
        <p>
            Lengkapi identitas diri sesuai dokumen resmi.
        </p>
    </div>


    <!-- NOTIFIKASI -->
    <?php if (($_GET['status'] ?? '') === 'success'): ?>
        <p class="success-message">
            Biodata berhasil disimpan!
        </p>
    <?php endif; ?>


    <?php if (($_GET['status'] ?? '') === 'updated'): ?>
        <p class="success-message">
            Biodata berhasil diperbarui!
        </p>
    <?php endif; ?>

    <?php if (($_GET['status'] ?? '') === 'deleted'): ?>
        <p class="success-message">
            Biodata berhasil dihapus!
        </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error-message">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <!-- FORM INPUT -->
    <form action="index.php?page=biodata"
          method="POST"
          class="biodata-form">
        <?php if ($data_edit): ?>
            <input type="hidden"
                   name="id_peserta"
                   value="<?= htmlspecialchars((string)$data_edit['id_peserta']) ?>">
        <?php endif; ?>

        <!-- NIK -->
        <div class="form-group">
            <label for="nik">
                Nomor Induk Kependudukan (NIK)
            </label>
            <input
                type="text"
                id="nik"
                name="nik_peserta"
                maxlength="16"
                minlength="16"
                pattern="[0-9]{16}"
                value="<?= htmlspecialchars($data_edit['nik_peserta'] ?? '') ?>"
                placeholder="Masukkan 16 digit NIK"
                required
            >
        </div>

        <!-- NAMA -->
        <div class="form-group">
            <label for="nama">
                Nama Lengkap
            </label>
            <input
                type="text"
                id="nama"
                name="nama_peserta"
                value="<?= htmlspecialchars($data_edit['nama_peserta'] ?? '') ?>"
                placeholder="Nama sesuai KTP"
                required
            >
        </div>

        <!-- JENIS KELAMIN DAN TANGGAL LAHIR -->
        <div class="form-row">
            <div class="form-group">
                <label for="jenis_kelamin">
                    Jenis Kelamin
                </label>
                <select id="jenis_kelamin"
                        name="jenis_kelamin"
                        required>
                    <option value="">
                        Pilih jenis kelamin
                    </option>

                    <option value="Laki-laki"
                        <?= ($data_edit['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>
                        Laki-laki
                    </option>

                    <option value="Perempuan"
                        <?= ($data_edit['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>
                        Perempuan
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">
                    Tanggal Lahir
                </label>
                <input
                    type="date"
                    id="tanggal_lahir"
                    name="tanggal_lahir"
                    value="<?= htmlspecialchars($data_edit['tanggal_lahir'] ?? '') ?>"
                    required
                >
            </div>
        </div>

        <!-- ALAMAT -->
        <div class="form-group">
            <label for="alamat">
                Alamat Lengkap
            </label>

            <textarea
                id="alamat"
                name="alamat"
                placeholder="Masukkan alamat sesuai KTP"
                rows="4"
                required><?= htmlspecialchars($data_edit['alamat'] ?? '') ?></textarea>
        </div>

        <!-- NOMOR TELEPON -->
        <div class="form-group">
            <label for="telepon">
                Nomor Telepon
            </label>

            <input
                type="tel"
                id="telepon"
                name="nomor_telepon"
                value="<?= htmlspecialchars($data_edit['nomor_telepon'] ?? '') ?>"
                placeholder="08xxxxxxxxxx"
                required
            >
        </div>

        <!-- STATUS PEKERJAAN -->
        <div class="form-group">
            <label for="pekerjaan">
                Status Pekerjaan
            </label>
            <select
                id="pekerjaan"
                name="status_pekerjaan"
                required
            >

                <option value="">
                    Pilih status pekerjaan
                </option>

                <?php
                $pilihan_pekerjaan = [
                    'Mahasiswa',
                    'Pelajar',
                    'Bekerja',
                    'Tidak Bekerja',
                    'Wiraswasta'
                ];
                foreach ($pilihan_pekerjaan as $item):
                ?>

                    <option
                        value="<?= htmlspecialchars($item) ?>"
                        <?= ($data_edit['status_pekerjaan'] ?? '') === $item ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($item) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- TOMBOL FORM -->
        <div class="form-actions">
            <?php if ($data_edit): ?>
                <a href="index.php?page=biodata"
                   class="cancel-btn">
                    Batal
                </a>

                <button
                    type="submit"
                    name="update_biodata"
                    class="submit-btn"
                >
                    Simpan Perubahan
                </button>
            <?php else: ?>

                <button
                    type="submit"
                    name="simpan_biodata"
                    class="submit-btn"
                >
                    Simpan & Lanjutkan →
                </button>
            <?php endif; ?>
        </div>
    </form>
</section>

<!-- TABEL PESERTA -->
<section class="table-section">
    <div class="table-heading">
        <div>
            <h2>Data Peserta</h2>
            <p>
                Daftar peserta yang telah mengisi biodata.
            </p>
        </div>
        <span class="total-data">
            <?= count($data_peserta) ?> Peserta
        </span>
    </div>

    <div class="table-container">
        <table class="participant-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIK</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Kelamin</th>
                    <th>Nomor Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($data_peserta) > 0): ?>
                    <?php foreach ($data_peserta as $index => $row): ?>
                        <tr>
                            <td>
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nik_peserta']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_peserta']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['jenis_kelamin']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nomor_telepon']) ?>
                            </td>

                            <td>

                                <a href="index.php?page=biodata&edit=<?= urlencode((string)$row['id_peserta']) ?>">
                                    Edit
                                </a>

                                <a
                                    href="index.php?page=biodata&hapus=<?= urlencode((string)$row['id_peserta']) ?>"
                                    onclick="return confirm('Yakin ingin menghapus peserta ini?')"
                                >
                                    Hapus
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>

                    <tr>
                        <td colspan="6">
                            Belum ada data peserta.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
