<?php        
require 'include/db.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: index.php');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add'])) {
        $kode     = $mysqli->real_escape_string($_POST['kode']);
        $kriteria = $mysqli->real_escape_string($_POST['kriteria']);
        $atribut  = $mysqli->real_escape_string($_POST['atribut']);
        $bobot    = intval($_POST['bobot']);

        $sql = "INSERT INTO kriteria (kode, kriteria, atribut, bobot)
                VALUES ('$kode', '$kriteria', '$atribut', $bobot)";
        if (!$mysqli->query($sql)) die("SQL ERROR (INSERT): " . $mysqli->error);
        $msg = 'Kriteria berhasil ditambahkan';
    }

    if (isset($_POST['edit'])) {
        $nomor    = intval($_POST['nomor']);
        $kode     = $mysqli->real_escape_string($_POST['kode']);
        $kriteria = $mysqli->real_escape_string($_POST['kriteria']);
        $atribut  = $mysqli->real_escape_string($_POST['atribut']);
        $bobot    = intval($_POST['bobot']);

        $sql = "
            UPDATE kriteria 
            SET kode = '$kode',
                kriteria = '$kriteria',
                atribut = '$atribut',
                bobot = $bobot
            WHERE nomor = $nomor
        ";
        if (!$mysqli->query($sql)) die("SQL ERROR (UPDATE): " . $mysqli->error);
        $msg = 'Kriteria berhasil diperbarui';
    }

    if (isset($_POST['delete'])) {
        $nomor = intval($_POST['nomor']);
        $sql = "DELETE FROM kriteria WHERE nomor = $nomor";
        if (!$mysqli->query($sql)) die("SQL ERROR (DELETE): " . $mysqli->error);
        $msg = 'Kriteria berhasil dihapus';
    }
}

$totalBobotQuery = $mysqli->query("SELECT SUM(bobot) AS total FROM kriteria");
if (!$totalBobotQuery) die("SQL ERROR (Total Bobot): " . $mysqli->error);
$totalBobotRow = $totalBobotQuery->fetch_assoc();
$totalBobot = ($totalBobotRow && $totalBobotRow['total'] > 0) ? (float)$totalBobotRow['total'] : 1;


$kriteriaRes = $mysqli->query("SELECT nomor, bobot FROM kriteria");
if ($kriteriaRes) {
    while ($row = $kriteriaRes->fetch_assoc()) {
        $norm = $totalBobot > 0 ? round($row['bobot'] / $totalBobot, 5) : 0;
        $mysqli->query("UPDATE kriteria SET normalisasi = $norm WHERE nomor = {$row['nomor']}");
    }
}

include 'include/header.php';
?>
<link rel="stylesheet" href="assets/kriteria.css?v=<?= time(); ?>">

<div class="page-wrapper">
    <h2 class="mb-4">Data Kriteria</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success text-center fw-semibold shadow-sm" id="notifMsg">
            <?= htmlspecialchars($msg) ?>
        </div>

        <script>
            
            setTimeout(() => {
                const notif = document.getElementById("notifMsg");
                if (notif) {
                    notif.style.transition = "opacity 0.5s ease";
                    notif.style.opacity = "0";
                    setTimeout(() => notif.remove(), 500);
                }
            }, 2000);
        </script>
    <?php endif; ?>

    <div class="kriteria-form mb-4">
        <form method="post" class="row g-3">
            <div class="col-md-2">
                <input name="kode" class="form-control" placeholder="Kode Kriteria" required>
            </div>
            <div class="col-md-3">
                <input name="kriteria" class="form-control" placeholder="Nama Kriteria" required>
            </div>
            <div class="col-md-2">
                <select name="atribut" class="form-select" required>
                    <option value="">-- Pilih Atribut --</option>
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
            </div>
            <div class="col-md-2">
                <input name="bobot" type="number" min="1" step="1" class="form-control" placeholder="Bobot" required>
            </div>
            <div class="col-md-3 d-grid">
                <button name="add" class="btn btn-primary">Tambah Kriteria</button>
            </div>
        </form>
    </div>

    <div class="table-wrapper mt-4">
        <h4 class="mb-3">Daftar Kriteria dan Hasil Normalisasi Bobot</h4>

        <table class="table table-modern table-bordered align-middle mb-0 text-center">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Kode Kriteria</th>
                    <th>Kriteria</th>
                    <th>Atribut</th>
                    <th>Bobot</th>
                    <th>Normalisasi Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $mysqli->query("SELECT * FROM kriteria ORDER BY nomor ASC");
                $totalBobotTampil = 0;
                $totalNormalisasi = 0;

                if (!$res) {
                    echo '<tr><td colspan="7" class="text-danger">SQL ERROR: ' . $mysqli->error . '</td></tr>';
                } elseif ($res->num_rows === 0) {
                    echo '<tr><td colspan="7" class="text-muted">Belum ada data kriteria.</td></tr>';
                } else {
                    while ($r = $res->fetch_assoc()):
                        $totalBobotTampil += $r['bobot'];
                        $totalNormalisasi += $r['normalisasi'];
                ?>
                <tr>
                    <td><?= $r['nomor'] ?></td>
                    <td><?= htmlspecialchars($r['kode']) ?></td>
                    <td><?= htmlspecialchars($r['kriteria']) ?></td>
                    <td><?= ucfirst($r['atribut']) ?></td>
                    <td><?= $r['bobot'] ?></td>

                    <td><?= number_format(round($r['normalisasi'], 5), 4, '.', '') ?></td>

                    <td>
                        <div class="aksi-wrapper d-flex justify-content-center gap-2">
                        
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#edit<?= $r['nomor'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                        
                            <form method="post" onsubmit="return confirm('Hapus kriteria ini?');">
                                <input type="hidden" name="nomor" value="<?= $r['nomor'] ?>">
                                <button name="delete" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="edit<?= $r['nomor'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Edit Kriteria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <input type="hidden" name="nomor" value="<?= $r['nomor'] ?>">

                                    <label class="form-label">Kode Kriteria</label>
                                    <input name="kode" class="form-control mb-2" value="<?= htmlspecialchars($r['kode']) ?>" required>

                                    <label class="form-label">Nama Kriteria</label>
                                    <input name="kriteria" class="form-control mb-2" value="<?= htmlspecialchars($r['kriteria']) ?>" required>

                                    <label class="form-label">Atribut</label>
                                    <select name="atribut" class="form-select mb-2" required>
                                        <option value="benefit" <?= ($r['atribut'] === 'benefit') ? 'selected' : '' ?>>Benefit</option>
                                        <option value="cost" <?= ($r['atribut'] === 'cost') ? 'selected' : '' ?>>Cost</option>
                                    </select>

                                    <label class="form-label">Bobot</label>
                                    <input type="number" name="bobot" min="1" step="1" class="form-control" value="<?= $r['bobot'] ?>" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button name="edit" class="btn btn-success">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>

                
                <?php 
                    if (abs($totalNormalisasi - 1) < 0.0001) {
                        $totalNormalisasi = 1;
                    }
                ?>

                <tr class="fw-bold bg-light">
                    <td colspan="4" class="text-end">TOTAL :</td>
                    <td><?= $totalBobotTampil ?></td>

                    <td><?= number_format(round($totalNormalisasi, 5), 2, '.', '') ?></td>

                    <td></td>
                </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-start">
        <b>Rumus Normalisasi Bobot:</b>
        <div>
            W<sub>j</sub> = w<sub>j</sub> / Σ w<sub>j</sub>
        </div>
    </div>

    <div class="mt-3 text-start">
        <i>Keterangan:</i>
        <ul class="mt-2 mb-0">
            <li><b>W<sub>j</sub></b> : Bobot hasil normalisasi</li>
            <li><b>w<sub>j</sub></b> : Bobot awal tiap kriteria</li>
            <li><b>Σ w<sub>j</sub></b> : Total keseluruhan bobot</li>
            <li><b>n</b> : Jumlah kriteria</li>
        </ul>
    </div>

</div>

<?php include 'include/footer.php'; ?>
