<?php
// fungsi-fungsi bantu untuk SAW
function get_kriteria($mysqli) {
$arr = [];
$res = $mysqli->query("SELECT * FROM kriteria ORDER BY id");
while ($r = $res->fetch_assoc()) $arr[] = $r;
return $arr;
}
function get_alternatif($mysqli) {
$arr = [];
$res = $mysqli->query("SELECT * FROM alternatif ORDER BY id");
while ($r = $res->fetch_assoc()) $arr[] = $r;
return $arr;
}
function get_penilaian($mysqli) {
$arr = [];
$res = $mysqli->query("SELECT * FROM penilaian");
while ($r = $res->fetch_assoc()) $arr[] = $r;
return $arr;
}