<?php
require 'include/db.php';
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");


$kriteria = [];
$qKriteria = $mysqli->query("SELECT * FROM kriteria ORDER BY nomor ASC");
while ($r = $qKriteria->fetch_assoc()) $kriteria[] = $r;

$sqlAlt = "
    SELECT 
        `kode`, 
        `Nama Security` AS nama,
        `kedisiplinan (K1)` AS k1, 
        `Kesiapan Fisik (K2)` AS k2,
        `Ketrampilan Operasional (K3)` AS k3, 
        `Sikap Dan Etika Kerja (K4)` AS k4,
        `Pengalaman Kerja (K5)` AS k5
    FROM alternatif
    ORDER BY `no` ASC
";
$resAlt = $mysqli->query($sqlAlt);
$alternatif = [];
while ($r = $resAlt->fetch_assoc()) {
    
    foreach ($r as $key => $val) {
        if (is_numeric($val)) {
            $r[$key] = strval($val);
        }
    }
    $alternatif[] = $r;
}


$max = $mysqli->query("
    SELECT 
        MAX(`kedisiplinan (K1)`) AS max_k1,
        MAX(`Kesiapan Fisik (K2)`) AS max_k2,
        MAX(`Ketrampilan Operasional (K3)`) AS max_k3,
        MAX(`Sikap Dan Etika Kerja (K4)`) AS max_k4,
        MAX(`Pengalaman Kerja (K5)`) AS max_k5
    FROM alternatif
")->fetch_assoc();

foreach ($max as $key => $val) {
    $max[$key] = strval($val);
}


$bobot = [];
foreach ($kriteria as $row) {
    $kode = strtolower(str_replace(['(', ')', ' '], '', $row['kode']));
    
    $bobot[$kode] = strval($row['normalisasi']);
}

$maxArr = [
    'k1' => $max['max_k1'] ?: "1",
    'k2' => $max['max_k2'] ?: "1",
    'k3' => $max['max_k3'] ?: "1",
    'k4' => $max['max_k4'] ?: "1",
    'k5' => $max['max_k5'] ?: "1"
];

$normalisasiData = [];
foreach ($alternatif as $alt) {
    $total = "0";
    for ($i = 1; $i <= 5; $i++) {
        $key = "k$i";
        $nilai = $alt[$key];
        $maxVal = $maxArr[$key];
        $bobotVal = $bobot["k$i"];

        
        if (bccomp($maxVal, "0", 15) === 0) {
            $n = "0";
        } else {
            $n = bcdiv($nilai, $maxVal, 15);
        }

        $alt["n_$key"] = $n;

        
        $total = bcadd($total, bcmul($n, $bobotVal, 15), 15);
    }

    $alt['total'] = $total;
    $normalisasiData[] = $alt;
}

usort($normalisasiData, function($a, $b) {
    return bccomp($b['total'], $a['total'], 15);
});

include 'include/header.php';
?>
<link rel="stylesheet" href="assets/alternatif.css?v=<?= time(); ?>">

<div class="page-wrapper">
    <h2 class="fw-bold mb-4">Hasil Peranking</h2>
    <div class="table-wrapper mb-4">
        <table class="table table-modern table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>RANK</th>
                    <th>KODE</th>
                    <th>NAMA SECURITY</th>
                    <th>Nilai Akhir (V<sub>i</sub>)</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach ($normalisasiData as $row): ?>
                <tr class="<?= ($rank === 1) ?  : '' ?>">
                    <td><strong><?= $rank++ ?></strong></td>
                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= number_format((float)$row['total'], 4, '.', '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'include/footer.php'; ?>
