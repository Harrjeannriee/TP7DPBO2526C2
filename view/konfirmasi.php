<?php
/** @var PDO $db */
$error = $error ?? null;
$data_peserta = $data_peserta ?? [];
$data_pendaftaran_edit = $data_pendaftaran_edit ?? null;

// Mengambil daftar pendaftaran beserta nama peserta
$stmt_daftar = $db->prepare(
    "SELECT
        pd.id_pendaftaran,
        pd.id_peserta,
        pd.tanggal_daftar,
        pd.status_pendaftaran,
        p.nama_peserta,
        p.nik_peserta
     FROM pendaftaran pd
     INNER JOIN peserta p
        ON pd.id_peserta = p.id_peserta
     ORDER BY pd.id_pendaftaran DESC"
);

$stmt_daftar->execute();
$data_pendaftaran = $stmt_daftar->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="form-section">
    <div class="page-heading">
        <p class="greeting">TAHAP 03 · PENDAFTARAN</p>
        <h1>Konfirmasi Pendaftaran</h1>
        <p>Periksa dan selesaikan proses pendaftaran peserta BPJS Mandiri.</p>
    </div>

    <?php if (!empty($error)): ?>
        <p class="error-message">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php if (($_GET['status'] ?? '') === 'success'): ?>
        <p class="success-message">Pendaftaran berhasil disimpan!</p>
    <?php elseif (($_GET['status'] ?? '') === 'updated'): ?>
        <p class="success-message">Pendaftaran berhasil diperbarui!</p>
    <?php elseif (($_GET['status'] ?? '') === 'deleted'): ?>
        <p class="success-message">Pendaftaran berhasil dihapus!</p>
    <?php endif; ?>

    <form action="index.php?page=konfirmasi"
          method="POST"
          class="biodata-form">

        <?php if ($data_pendaftaran_edit): ?>

            <input type="hidden"
                   name="id_pendaftaran"
                   value="<?= htmlspecialchars($data_pendaftaran_edit['id_pendaftaran']) ?>">

            <input type="hidden"
                   name="update_pendaftaran"
                   value="1">

            <div class="form-group">
                <label>Peserta</label>
                <input type="text"
                       value="<?php
                       foreach ($data_peserta as $p) {
                           if ($p['id_peserta'] == $data_pendaftaran_edit['id_peserta']) {
                               echo htmlspecialchars($p['nama_peserta']);
                               break;
                           }
                       }
                       ?>"
                       disabled>
            </div>

            <div class="form-group">
                <label for="tanggal_daftar">Tanggal Pendaftaran</label>
                <input type="date"
                       id="tanggal_daftar"
                       name="tanggal_daftar"
                       value="<?= htmlspecialchars($data_pendaftaran_edit['tanggal_daftar']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="status_pendaftaran">Status Pendaftaran</label>
                <select name="status_pendaftaran"
                        id="status_pendaftaran"
                        required>
                    <?php
                    $status = [
                        'Menunggu Konfirmasi',
                        'Diproses',
                        'Disetujui',
                        'Ditolak'
                    ];
                    ?>

                    <?php foreach ($status as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>"
                            <?= $data_pendaftaran_edit['status_pendaftaran'] === $s ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="index.php?page=konfirmasi" class="cancel-btn">
                    Batal
                </a>

                <button type="submit" class="submit-btn">
                    Simpan Perubahan →
                </button>
            </div>

        <?php else: ?>

            <div class="form-group">
                <label for="id_peserta">Pilih Peserta</label>

                <select name="id_peserta" id="id_peserta" required>
                    <option value="">-- Pilih Peserta --</option>

                    <?php foreach ($data_peserta as $p): ?>
                        <option value="<?= htmlspecialchars($p['id_peserta']) ?>">
                            <?= htmlspecialchars($p['nama_peserta']) ?>
                            (NIK: <?= htmlspecialchars($p['nik_peserta']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Pendaftaran</label>
                <input type="date"
                       value="<?= date('Y-m-d') ?>"
                       disabled>
                <small>Tanggal pendaftaran diatur otomatis oleh sistem.</small>
            </div>

            <div class="form-group">
                <label>Status Pendaftaran</label>
                <input type="text"
                       value="Menunggu Konfirmasi"
                       disabled>
                <small>Status awal akan ditentukan otomatis oleh sistem.</small>
            </div>

            <div class="form-actions">
                <a href="index.php?page=dokumen" class="cancel-btn">
                    ← Kembali
                </a>

                <button type="submit"
                        name="simpan_pendaftaran"
                        class="submit-btn">
                    Konfirmasi Pendaftaran →
                </button>
            </div>

        <?php endif; ?>

    </form>

    <div class="table-section">
        <h2>Data Pendaftaran Peserta</h2>

        <div class="table-container">
            <table class="participant-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Peserta</th>
                        <th>NIK</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($data_pendaftaran) > 0): ?>

                        <?php foreach ($data_pendaftaran as $i => $d): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($d['nama_peserta']) ?></td>
                                <td><?= htmlspecialchars($d['nik_peserta']) ?></td>
                                <td><?= htmlspecialchars($d['tanggal_daftar']) ?></td>
                                <td><?= htmlspecialchars($d['status_pendaftaran']) ?></td>

                                <td>
                                    <a href="index.php?page=konfirmasi&edit_pendaftaran=<?= $d['id_pendaftaran'] ?>">
                                        Edit
                                    </a>

                                    <a href="index.php?page=konfirmasi&hapus_pendaftaran=<?= $d['id_pendaftaran'] ?>"
                                       onclick="return confirm('Yakin ingin menghapus pendaftaran ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                Belum ada data pendaftaran.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
