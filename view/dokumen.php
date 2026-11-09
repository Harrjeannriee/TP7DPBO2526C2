<?php
/** @var PDO $db */

$error = $error ?? null;
$data_peserta = $data_peserta ?? [];
$data_dokumen_edit = $data_dokumen_edit ?? null;

// Mengambil seluruh data dokumen beserta nama peserta
$stmt_dokumen = $db->prepare(
    "SELECT
        d.id_dokumen,
        d.id_peserta,
        d.file_ktp,
        d.file_kk,
        d.tanggal_upload,
        p.nama_peserta
     FROM dokumen_peserta d
     INNER JOIN peserta p ON d.id_peserta = p.id_peserta
     ORDER BY d.id_dokumen DESC"
);

$stmt_dokumen->execute();
$data_dokumen = $stmt_dokumen->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="form-section">

    <div class="page-heading">
        <p class="greeting">TAHAP 02 · DOKUMEN</p>
        <h1>Kelengkapan Dokumen</h1>
        <p>Unggah KTP dan Kartu Keluarga untuk melengkapi pendaftaran.</p>
    </div>

    <?php if (!empty($error)): ?>
        <p class="error-message">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php if (($_GET['status'] ?? '') === 'success'): ?>
        <p class="success-message">Dokumen berhasil disimpan!</p>

    <?php elseif (($_GET['status'] ?? '') === 'updated'): ?>
        <p class="success-message">Dokumen berhasil diperbarui!</p>

    <?php elseif (($_GET['status'] ?? '') === 'deleted'): ?>
        <p class="success-message">Dokumen berhasil dihapus!</p>
    <?php endif; ?>

    <form action="index.php?page=dokumen"
        method="POST"
        enctype="multipart/form-data"
        class="biodata-form">

        <?php if ($data_dokumen_edit): ?>

            <input type="hidden"
                name="id_dokumen"
                value="<?= htmlspecialchars($data_dokumen_edit['id_dokumen']) ?>">

            <input type="hidden"
                name="update_dokumen"
                value="1">

            <div class="form-group">
                <label>Peserta</label>
                <input type="text"
                    value="<?php
                            foreach ($data_peserta as $p) {
                                if ($p['id_peserta'] == $data_dokumen_edit['id_peserta']) {
                                    echo htmlspecialchars($p['nama_peserta']);
                                    break;
                                }
                            }
                            ?>"
                    disabled>
            </div>

            <div class="form-group">
                <label>Dokumen KTP Saat Ini</label>
                <p>
                    <a href="<?= htmlspecialchars($data_dokumen_edit['file_ktp']) ?>"
                        target="_blank">Lihat KTP</a>
                </p>
                <label for="file_ktp">Ganti KTP (opsional)</label>
                <input type="file"
                    id="file_ktp"
                    name="file_ktp"
                    accept=".jpg,.jpeg,.png">
            </div>

            <div class="form-group">
                <label>Dokumen KK Saat Ini</label>
                <p>
                    <a href="<?= htmlspecialchars($data_dokumen_edit['file_kk']) ?>"
                        target="_blank">Lihat Kartu Keluarga</a>
                </p>
                <label for="file_kk">Ganti Kartu Keluarga (opsional)</label>
                <input type="file"
                    id="file_kk"
                    name="file_kk"
                    accept=".jpg,.jpeg,.png">
            </div>

            <div class="form-actions">
                <a href="index.php?page=dokumen" class="cancel-btn">
                    Batal
                </a>

                <button type="submit" class="submit-btn">
                    Simpan Perubahan →
                </button>
            </div>

        <?php else: ?>

            <input type="hidden"
                name="simpan_dokumen"
                value="1">

            <div class="form-group">
                <label for="id_peserta">Pilih Peserta</label>

                <select name="id_peserta" id="id_peserta" required>
                    <option value="">-- Pilih Peserta --</option>

                    <?php foreach ($data_peserta as $p): ?>
                        <option value="<?= htmlspecialchars($p['id_peserta']) ?>">
                            <?= htmlspecialchars($p['nama_peserta']) ?>
                            (ID: <?= htmlspecialchars($p['id_peserta']) ?>)
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="file_ktp">Upload KTP</label>
                <input type="file"
                    id="file_ktp"
                    name="file_ktp"
                    accept=".jpg,.jpeg,.png"
                    required>
            </div>

            <div class="form-group">
                <label for="file_kk">Upload Kartu Keluarga</label>
                <input type="file"
                    id="file_kk"
                    name="file_kk"
                    accept=".jpg,.jpeg,.png"
                    required>
            </div>

            <div class="form-actions">
                <a href="index.php?page=biodata" class="cancel-btn">
                    ← Kembali
                </a>

                <button type="submit" class="submit-btn">
                    Simpan Dokumen →
                </button>
            </div>

        <?php endif; ?>

    </form>

    <div class="table-section">
        <h2>Data Dokumen Peserta</h2>

        <div class="table-container">
            <table class="participant-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Peserta</th>
                        <th>KTP</th>
                        <th>Kartu Keluarga</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($data_dokumen) > 0): ?>

                        <?php foreach ($data_dokumen as $i => $d): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>

                                <td>
                                    <?= htmlspecialchars($d['nama_peserta']) ?>
                                </td>

                                <td>
                                    <a href="<?= htmlspecialchars($d['file_ktp']) ?>"
                                        target="_blank">Lihat KTP</a>
                                </td>

                                <td>
                                    <a href="<?= htmlspecialchars($d['file_kk']) ?>"
                                        target="_blank">Lihat KK</a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($d['tanggal_upload']) ?>
                                </td>

                                <td>
                                    <a href="index.php?page=dokumen&edit_dokumen=<?= $d['id_dokumen'] ?>">
                                        Edit
                                    </a>

                                    <form action="index.php?page=dokumen"
                                        method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus dokumen peserta ini?')">

                                        <input type="hidden"
                                            name="id_dokumen"
                                            value="<?= htmlspecialchars($d['id_dokumen']) ?>">

                                        <button type="submit"
                                            name="hapus_dokumen"
                                            class="delete-btn">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6">Belum ada dokumen yang diunggah.</td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>