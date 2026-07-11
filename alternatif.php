<?php   
require 'include/db.php';
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");

$msg = "";


if (isset($_POST['add'])) {
    $kode  = trim($_POST['kode']);
    $nama  = trim($_POST['nama_security']);
    $k1    = (int)$_POST['k1'];
    $k2    = (int)$_POST['k2'];
    $k3    = (int)$_POST['k3'];
    $k4    = (int)$_POST['k4'];
    $k5    = (int)$_POST['k5'];

    $values = [$k1, $k2, $k3, $k4, $k5];
    foreach ($values as $v) {
        if ($v < 1 || $v > 5) {
            die("<div class='alert alert-danger text-center m-4'>Nilai kriteria harus antara 1 hingga 5.</div>");
        }
    }

    $sql = "
        INSERT INTO `alternatif`
        (`kode`, `Nama Security`, `kedisiplinan (K1)`, `Kesiapan Fisik (K2)`,
         `Ketrampilan Operasional (K3)`, `Sikap Dan Etika Kerja (K4)`, `Pengalaman Kerja (K5)`)
        VALUES (?,?,?,?,?,?,?)
    ";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) die('SQL ERROR (INSERT): '.$mysqli->error);
    $stmt->bind_param("ssiiiii", $kode, $nama, $k1, $k2, $k3, $k4, $k5);
    $stmt->execute();
    $stmt->close();
    $msg = "Data alternatif berhasil ditambahkan!";
}


if (isset($_POST['edit'])) {
    $no    = (int)$_POST['no'];
    $kode  = trim($_POST['kode']);
    $nama  = trim($_POST['nama_security']);
    $k1    = (int)$_POST['k1'];
    $k2    = (int)$_POST['k2'];
    $k3    = (int)$_POST['k3'];
    $k4    = (int)$_POST['k4'];
    $k5    = (int)$_POST['k5'];

    $sql = "
        UPDATE `alternatif`
        SET `kode`=?,
            `Nama Security`=?,
            `kedisiplinan (K1)`=?,
            `Kesiapan Fisik (K2)`=?,
            `Ketrampilan Operasional (K3)`=?,
            `Sikap Dan Etika Kerja (K4)`=?,
            `Pengalaman Kerja (K5)`=?
        WHERE `no`=?
    ";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) die('SQL ERROR (UPDATE): '.$mysqli->error);
    $stmt->bind_param("ssiiiiii", $kode, $nama, $k1, $k2, $k3, $k4, $k5, $no);
    $stmt->execute();
    $stmt->close();
    $msg = "Data alternatif berhasil diperbarui!";
}


if (isset($_POST['delete'])) {
    $no = (int)$_POST['no'];
    $stmt = $mysqli->prepare("DELETE FROM `alternatif` WHERE `no`=?");
    if (!$stmt) die("SQL ERROR (DELETE): ".$mysqli->error);
    $stmt->bind_param("i", $no);
    $stmt->execute();
    $stmt->close();
    $msg = "Data alternatif berhasil dihapus!";
}


$limit = 10; // jumlah baris per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$totalRows = $mysqli->query("SELECT COUNT(*) AS total FROM alternatif")->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);


$sqlList = "
    SELECT
      `no`,
      `kode`,
      `Nama Security` AS nama_security,
      `kedisiplinan (K1)` AS k1,
      `Kesiapan Fisik (K2)` AS k2,
      `Ketrampilan Operasional (K3)` AS k3,
      `Sikap Dan Etika Kerja (K4)` AS k4,
      `Pengalaman Kerja (K5)` AS k5
    FROM `alternatif`
    ORDER BY `no` ASC
    LIMIT $limit OFFSET $offset
";
$res = $mysqli->query($sqlList);
if (!$res) die('SQL ERROR (SELECT): '.$mysqli->error);

$sqlMax = "
    SELECT 
        MAX(`kedisiplinan (K1)`) AS max_k1,
        MAX(`Kesiapan Fisik (K2)`) AS max_k2,
        MAX(`Ketrampilan Operasional (K3)`) AS max_k3,
        MAX(`Sikap Dan Etika Kerja (K4)`) AS max_k4,
        MAX(`Pengalaman Kerja (K5)`) AS max_k5
    FROM `alternatif`
";
$maxRes = $mysqli->query($sqlMax);
$maxRow = $maxRes->fetch_assoc();

include 'include/header.php';
?>
<link rel="stylesheet" href="assets/alternatif.css?v=<?= time(); ?>">

<div class="page-wrapper">
    <h2 class="fw-bold mb-4">Data Alternatif</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success text-center fw-semibold shadow-sm"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-2 mb-4 box-form">
        <div class="col-md-2"><input name="kode" class="form-control" placeholder="Kode" required></div>
        <div class="col-md-3"><input name="nama_security" class="form-control" placeholder="Nama Security" required></div>
        <?php for ($i=1; $i<=5; $i++): ?>
            <div class="col-md-1">
                <input type="number" name="k<?= $i ?>" min="1" max="5" class="form-control" placeholder="K<?= $i ?>" required>
            </div>
        <?php endfor; ?>
        <div class="col-md-2"><button class="btn btn-navy w-100 fw-bold" name="add">Tambah</button></div>
    </form>

    <div class="table-wrapper mb-4">
        <table class="table table-modern table-bordered align-middle mb-0 text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Security</th>
                    <th>K1</th><th>K2</th><th>K3</th><th>K4</th><th>K5</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['no'] ?></td>
                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td class="nama-security text-start"><?= htmlspecialchars($row['nama_security']) ?></td>
                    <td><?= $row['k1'] ?></td>
                    <td><?= $row['k2'] ?></td>
                    <td><?= $row['k3'] ?></td>
                    <td><?= $row['k4'] ?></td>
                    <td><?= $row['k5'] ?></td>
                    <td>
                        <div class="aksi-wrapper">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?= $row['no'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?');">
                                <input type="hidden" name="no" value="<?= $row['no'] ?>">
                                <button name="delete" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="edit<?= $row['no'] ?>" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <form method="post">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Edit Alternatif</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="no" value="<?= $row['no'] ?>">
                                <label class="form-label">Kode</label>
                                <input name="kode" class="form-control mb-2" value="<?= htmlspecialchars($row['kode']) ?>" required>
                                <label class="form-label">Nama Security</label>
                                <input name="nama_security" class="form-control mb-2" value="<?= htmlspecialchars($row['nama_security']) ?>" required>
                                <?php for ($i=1; $i<=5; $i++): ?>
                                    <label class="form-label fw-semibold">K<?= $i ?></label>
                                    <input type="number" name="k<?= $i ?>" min="1" max="5" class="form-control mb-2" value="<?= $row['k'.$i] ?>" required>
                                <?php endfor; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div></div>
                </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center mt-3">
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
        </li>
      </ul>
    </nav>

    <h4 class="mt-5 fw-bold text-dark">Nilai Maksimum Tiap Kriteria (Untuk Perhitungan SAW)</h4>
    <div class="table-wrapper mb-3">
        <table class="table table-modern table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>K1<br><small>(Kedisiplinan)</small></th>
                    <th>K2<br><small>(Kesiapan Fisik)</small></th>
                    <th>K3<br><small>(Ketrampilan Operasional)</small></th>
                    <th>K4<br><small>(Sikap & Etika Kerja)</small></th>
                    <th>K5<br><small>(Pengalaman Kerja)</small></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?= $maxRow['max_k1'] ?? 0 ?></strong></td>
                    <td><strong><?= $maxRow['max_k2'] ?? 0 ?></strong></td>
                    <td><strong><?= $maxRow['max_k3'] ?? 0 ?></strong></td>
                    <td><strong><?= $maxRow['max_k4'] ?? 0 ?></strong></td>
                    <td><strong><?= $maxRow['max_k5'] ?? 0 ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="rumus-section">
        <b>Mencari Nilai MAX :</b>
        <div class="rumus-formula"> Max(X<sub>ij</sub>)</div>
    </div>

    <div class="keterangan-section mt-2">
        <i>Keterangan:</i>
        <ul>
            <b><li>Max(X<sub>ij</sub>)</b> : Nilai maksimum pada setiap kriteria</li>
        </ul>
    </div>
</div>

<?php include 'include/footer.php'; ?>
