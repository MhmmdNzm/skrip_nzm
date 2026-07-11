<?php     
require 'include/db.php';
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");


function getPaginationVars($name, $limit = 10) {
    $page = isset($_GET[$name]) ? (int)$_GET[$name] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;
    return [$page, $limit, $offset];
}

list($pageAlt, $limitAlt, $offsetAlt) = getPaginationVars('pageAlt');
list($pageNorm, $limitNorm, $offsetNorm) = getPaginationVars('pageNorm');
list($pageTotal, $limitTotal, $offsetTotal) = getPaginationVars('pageTotal');


$totalAltRows = $mysqli->query("SELECT COUNT(*) AS total FROM alternatif")->fetch_assoc()['total'];
$totalAltPages = ceil($totalAltRows / $limitAlt);

$sqlAlt = "
    SELECT 
        `no`, `kode`, `Nama Security` AS nama,
        `kedisiplinan (K1)` AS k1, `Kesiapan Fisik (K2)` AS k2,
        `Ketrampilan Operasional (K3)` AS k3, `Sikap Dan Etika Kerja (K4)` AS k4,
        `Pengalaman Kerja (K5)` AS k5
    FROM `alternatif`
    ORDER BY `no` ASC
    LIMIT $limitAlt OFFSET $offsetAlt
";
$resAlt = $mysqli->query($sqlAlt);
$alternatif = [];
while ($r = $resAlt->fetch_assoc()) $alternatif[] = $r;


$kriteria = [];
$qKriteria = $mysqli->query("SELECT * FROM kriteria ORDER BY nomor ASC");
while ($r = $qKriteria->fetch_assoc()) $kriteria[] = $r;

$max = $mysqli->query("
    SELECT 
        MAX(`kedisiplinan (K1)`) AS max_k1,
        MAX(`Kesiapan Fisik (K2)`) AS max_k2,
        MAX(`Ketrampilan Operasional (K3)`) AS max_k3,
        MAX(`Sikap Dan Etika Kerja (K4)`) AS max_k4,
        MAX(`Pengalaman Kerja (K5)`) AS max_k5
    FROM alternatif
")->fetch_assoc();


$bobot = [];
foreach ($kriteria as $row) {
    $kode = strtolower(str_replace(['(', ')', ' '], '', $row['kode']));
    $bobot[$kode] = (float)$row['normalisasi'];
}

$maxArr = [
    'k1' => $max['max_k1'] ?? 1,
    'k2' => $max['max_k2'] ?? 1,
    'k3' => $max['max_k3'] ?? 1,
    'k4' => $max['max_k4'] ?? 1,
    'k5' => $max['max_k5'] ?? 1
];

$totalRows = $totalAltRows;
$totalNormPages = ceil($totalRows / $limitNorm);
$totalTotalPages = ceil($totalRows / $limitTotal);

$sqlNorm = "
    SELECT 
        `no`, `kode`, `Nama Security` AS nama,
        `kedisiplinan (K1)` AS k1, `Kesiapan Fisik (K2)` AS k2,
        `Ketrampilan Operasional (K3)` AS k3, `Sikap Dan Etika Kerja (K4)` AS k4,
        `Pengalaman Kerja (K5)` AS k5
    FROM alternatif
    ORDER BY no ASC
";
$resAll = $mysqli->query($sqlNorm);
$normalisasiData = [];
while ($alt = $resAll->fetch_assoc()) {
    $total = 0;
    for ($i = 1; $i <= 5; $i++) {
        $key = "k$i";
        $n = ($maxArr[$key] > 0) ? $alt[$key] / $maxArr[$key] : 0;
        $alt["n_$key"] = round($n, 1);
        $total += $n * ($bobot["k$i"] ?? 0);
    }
    $alt['total'] = round($total, 4);
    $normalisasiData[] = $alt;
}

$normalisasi = array_slice($normalisasiData, $offsetNorm, $limitNorm);
$totalHasil = array_slice($normalisasiData, $offsetTotal, $limitTotal);

include 'include/header.php';
?>
<link rel="stylesheet" href="assets/alternatif.css?v=<?= time(); ?>">

<style>
.fade-section {
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-section.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<div class="page-wrapper">
    <h2 class="fw-bold mb-4">Perhitungan SAW (Simple Additive Weighting)</h2>

    
    <section id="altTable" class="fade-section visible">
        <h5 class="fw-semibold text-dark mb-3">Tabel Alternatif (Data Mentah)</h5>
        <div class="table-wrapper mb-4">
            <table class="table table-modern table-bordered align-middle text-center">
                <thead><tr>
                    <th>No</th><th>Kode</th><th>Nama Security</th>
                    <th>K1</th><th>K2</th><th>K3</th><th>K4</th><th>K5</th>
                </tr></thead>
                <tbody>
                    <?php $no=$offsetAlt+1; foreach ($alternatif as $alt): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $alt['kode'] ?></td>
                        <td class="text-start"><?= $alt['nama'] ?></td>
                        <td><?= $alt['k1'] ?></td><td><?= $alt['k2'] ?></td>
                        <td><?= $alt['k3'] ?></td><td><?= $alt['k4'] ?></td><td><?= $alt['k5'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    
    <nav aria-label="Pagination Alternatif">
      <ul class="pagination justify-content-center mt-3">
        <li class="page-item <?= ($pageAlt <= 1) ? 'disabled' : '' ?>">
          <a class="page-link scroll-link" href="?pageAlt=<?= $pageAlt - 1 ?>&pageNorm=<?= $pageNorm ?>&pageTotal=<?= $pageTotal ?>#altTable">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalAltPages; $i++): ?>
          <li class="page-item <?= ($i == $pageAlt) ? 'active' : '' ?>">
            <a class="page-link scroll-link" href="?pageAlt=<?= $i ?>&pageNorm=<?= $pageNorm ?>&pageTotal=<?= $pageTotal ?>#altTable"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= ($pageAlt >= $totalAltPages) ? 'disabled' : '' ?>">
          <a class="page-link scroll-link" href="?pageAlt=<?= $pageAlt + 1 ?>&pageNorm=<?= $pageNorm ?>&pageTotal=<?= $pageTotal ?>#altTable">Next</a>
        </li>
      </ul>
    </nav>

    
    <section class="fade-section visible">
        <h5 class="fw-semibold text-dark mt-5 mb-3">Nilai Maksimum Tiap Kriteria</h5>
        <div class="table-wrapper mb-4">
            <table class="table table-modern table-bordered text-center align-middle">
                <thead><tr><th>K1</th><th>K2</th><th>K3</th><th>K4</th><th>K5</th></tr></thead>
                <tbody><tr>
                    <td><?= $maxArr['k1'] ?></td>
                    <td><?= $maxArr['k2'] ?></td>
                    <td><?= $maxArr['k3'] ?></td>
                    <td><?= $maxArr['k4'] ?></td>
                    <td><?= $maxArr['k5'] ?></td>
                </tr></tbody>
            </table>
        </div>
    </section>

    
    <section id="normTable" class="fade-section visible">
        <h5 class="fw-semibold text-dark mb-3">Tabel Normalisasi</h5>
        <div class="table-wrapper mb-4">
            <table class="table table-modern table-bordered align-middle text-center">
                <thead><tr>
                    <th>No</th><th>Kode</th><th>Nama Security</th>
                    <th>K1</th><th>K2</th><th>K3</th><th>K4</th><th>K5</th>
                </tr></thead>
                <tbody>
                    <?php $no=$offsetNorm+1; foreach ($normalisasi as $alt): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $alt['kode'] ?></td>
                        <td class="text-start"><?= $alt['nama'] ?></td>
                        <td><?= $alt['n_k1'] ?></td><td><?= $alt['n_k2'] ?></td>
                        <td><?= $alt['n_k3'] ?></td><td><?= $alt['n_k4'] ?></td><td><?= $alt['n_k5'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    
    <nav aria-label="Pagination Normalisasi">
      <ul class="pagination justify-content-center mt-3">
        <li class="page-item <?= ($pageNorm <= 1) ? 'disabled' : '' ?>">
          <a class="page-link scroll-link" href="?pageNorm=<?= $pageNorm - 1 ?>&pageAlt=<?= $pageAlt ?>&pageTotal=<?= $pageTotal ?>#normTable">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalNormPages; $i++): ?>
          <li class="page-item <?= ($i == $pageNorm) ? 'active' : '' ?>">
            <a class="page-link scroll-link" href="?pageNorm=<?= $i ?>&pageAlt=<?= $pageAlt ?>&pageTotal=<?= $pageTotal ?>#normTable"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= ($pageNorm >= $totalNormPages) ? 'disabled' : '' ?>">
          <a class="page-link scroll-link" href="?pageNorm=<?= $pageNorm + 1 ?>&pageAlt=<?= $pageAlt ?>&pageTotal=<?= $pageTotal ?>#normTable">Next</a>
        </li>
      </ul>
    </nav>

      
   <div class="rumus-section">
    <b>Rumus Normalisasi:</b>

    <div class="rumus-formula">
        R<sub>ij</sub> = X<sub>ij</sub> / Max(X<sub>ij</sub>)
    </div>

    <div class="keterangan">
        <i>Keterangan:</i>
        <ul>
            <li><b>R<sub>ij</sub></b> : <span>Nilai ternormalisasi</span></li>
            <li><b>X<sub>ij</sub></b> : <span>Nilai alternatif</span></li>
            <li><b>Max(X<sub>ij</sub>)</b> : <span>Nilai maksimum pada setiap kriteria</span></li>
        </ul>
    </div>
</div>

        
    <section id="kriteriaTable" class="fade-section visible">
        <h5 class="fw-semibold text-dark mt-5 mb-3">Tabel Kriteria</h5>
        <div class="table-wrapper mb-4">
            <table class="table table-modern table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Atribut</th>
                        <th>Bobot</th>
                        <th>Normalisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kriteria as $row): ?>
                    <tr>
                        <td><?= $row['nomor'] ?></td>
                        <td><?= htmlspecialchars($row['kode']) ?></td>
                        <td><?= htmlspecialchars($row['kriteria']) ?></td>
                        <td><?= ucfirst($row['atribut']) ?></td>
                        <td><?= $row['bobot'] ?></td>
                        <td><?= rtrim(rtrim(number_format($row['normalisasi'], 4), '0'), '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>


<section id="totalTable" class="fade-section visible">
    <h5 class="fw-semibold text-dark mb-3">Tabel Nilai Akhir (Total Per Alternatif)</h5>
    <div class="table-wrapper mb-4">
        <table class="table table-modern table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Security</th>
                    <th>Nilai Akhir (V<sub>i</sub>)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $offsetTotal + 1; foreach ($totalHasil as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= number_format($row['total'], 4, '.', '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

   
    <nav aria-label="Pagination Total">
      <ul class="pagination justify-content-center mt-3">
        <li class="page-item <?= ($pageTotal <= 1) ? 'disabled' : '' ?>">
          <a class="page-link scroll-link" href="?pageTotal=<?= $pageTotal - 1 ?>&pageAlt=<?= $pageAlt ?>&pageNorm=<?= $pageNorm ?>#totalTable">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalTotalPages; $i++): ?>
          <li class="page-item <?= ($i == $pageTotal) ? 'active' : '' ?>">
            <a class="page-link scroll-link" href="?pageTotal=<?= $i ?>&pageAlt=<?= $pageAlt ?>&pageNorm=<?= $pageNorm ?>#totalTable"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= ($pageTotal >= $totalTotalPages) ? 'disabled' : '' ?>">
          <a class="page-link scroll-link" href="?pageTotal=<?= $pageTotal + 1 ?>&pageAlt=<?= $pageAlt ?>&pageNorm=<?= $pageNorm ?>#totalTable">Next</a>
        </li>
      </ul>
    </nav>

    <div class="rumus-saw">
    <b>Rumus Perhitungan Nilai Akhir (SAW):</b>

    <div class="rumus-formula">
        V<sub>i</sub> = Σ (W<sub>j</sub> × R<sub>ij</sub>)
    </div>

    <div class="keterangan">
        <i>Keterangan:</i>
        <ul>
            <li><b>V<sub>i</sub></b> : <span>Nilai preferensi alternatif</span></li>
            <li><b>W<sub>j</sub></b> : <span>Bobot normalisasi</span></li>
            <li><b>R<sub>ij</sub></b> : <span>Nilai hasil normalisasi alternatif</span></li>
        </ul>
    </div>
</div>

</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.scroll-link').forEach(link => {
    link.addEventListener('click', () => {
      setTimeout(() => {
        const targetId = link.getAttribute('href').split('#')[1];
        const section = document.getElementById(targetId);
        if (section) {
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
          section.classList.remove('visible');
          setTimeout(() => section.classList.add('visible'), 100);
        }
      }, 400);
    });
  });
});
</script>

<?php include 'include/footer.php'; ?>
