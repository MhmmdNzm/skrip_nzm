<?php    
require 'include/db.php';
require 'include/header.php';


$totalAlternatif = 0;
if ($res = $mysqli->query("SELECT COUNT(*) AS jml FROM alternatif")) {
    $row = $res->fetch_assoc();
    $totalAlternatif = (int)$row['jml'];
    $res->close();
}

$totalKriteria = 0;
if ($res = $mysqli->query("SELECT COUNT(*) AS jml FROM kriteria")) {
    $row = $res->fetch_assoc();
    $totalKriteria = (int)$row['jml'];
    $res->close();
}

$kriteria = [];
$qKriteria = $mysqli->query("SELECT * FROM kriteria ORDER BY nomor ASC");
while ($r = $qKriteria->fetch_assoc()) {
    $kriteria[] = $r;
}

$alternatif = [];
$qAlt = $mysqli->query("SELECT * FROM alternatif ORDER BY no ASC");
while ($r = $qAlt->fetch_assoc()) {
    $alternatif[] = $r;
}


$kolomKriteria = [
    'kedisiplinan (K1)',
    'Kesiapan Fisik (K2)',
    'Ketrampilan Operasional (K3)',
    'Sikap Dan Etika Kerja (K4)',
    'Pengalaman Kerja (K5)'
];

$maxmin = [];
foreach ($kolomKriteria as $kolom) {
    $sql = "SELECT MAX(`$kolom`) AS maxv, MIN(`$kolom`) AS minv FROM alternatif";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    $maxmin[$kolom] = ['max' => $row['maxv'], 'min' => $row['minv']];
}


$ranking = [];
foreach ($alternatif as $alt) {
    $total = 0;
    foreach ($kriteria as $i => $kri) {
        $atribut = strtolower($kri['atribut']);
        $bobot   = (float)$kri['normalisasi'];
        $kolom   = $kolomKriteria[$i];
        $nilai   = (float)$alt[$kolom];

        if ($atribut == 'benefit') {
            $r = $nilai / $maxmin[$kolom]['max'];
        } else {
            $r = $maxmin[$kolom]['min'] / $nilai;
        }
        $total += $r * $bobot;
    }

    $ranking[] = [
        'nama' => $alt['Nama Security'],
        'kode' => $alt['kode'],
        'nilai' => round($total, 4)
    ];
}


usort($ranking, function ($a, $b) {
    return $b['nilai'] <=> $a['nilai'];
});

$rankingTertinggi = !empty($ranking)
    ? $ranking[0]['nama'] . " (" . number_format($ranking[0]['nilai'], 4) . ")"
    : 'Belum ada data';
?>
<link rel="stylesheet" href="assets/dashboard.css">

<div class="content" id="content">

    <h3 class="welcome-text mb-4">Dashboard</h3>

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card-navy">
                <div class="stat">
                    <div>
                        <h5>Total Alternatif</h5>
                        <p class="mt-2 mb-0">
                            <strong><?= number_format($totalAlternatif); ?></strong> Alternatif Terdaftar
                        </p>
                    </div>
                    <div class="icon-wrap icon-alt">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-navy">
                <div class="stat">
                    <div>
                        <h5>Total Kriteria</h5>
                        <p class="mt-2 mb-0">
                            <strong><?= number_format($totalKriteria); ?></strong> Kriteria Aktif
                        </p>
                    </div>
                    <div class="icon-wrap icon-kri">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-navy">
                <div class="stat">
                    <div>
                        <h5>Ranking Tertinggi</h5>
                        <p class="mt-2 mb-0"><?= $rankingTertinggi; ?></p>
                    </div>
                    <div class="icon-wrap icon-rank">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card saw-info-card">
            <h3 class="fw-bold mb-2">Sistem Pendukung Keputusan Metode SAW</h3>
            <p>
                Sistem Pendukung Keputusan (SPK) dengan metode <strong>Simple Additive Weighting (SAW)</strong>
                merupakan metode penjumlahan terbobot yang digunakan untuk mencari alternatif terbaik
                berdasarkan nilai kriteria yang telah dinormalisasi. Setiap nilai normalisasi dikalikan 
                dengan bobot kriteria untuk menghasilkan nilai akhir, sehingga dapat menentukan ranking
                alternatif secara objektif.
            </p>
        </div>

    </div>
</div>

<?php require 'include/footer.php'; ?>
