<?php 

$konek = mysqli_connect("localhost", "root", "", "iot_smartkwh");
$sql = mysqli_query($konek, "SELECT * FROM sensor ORDER BY id DESC LIMIT 1");
$sql2 = mysqli_query($konek,"SELECT * FROM beban");
$data = mysqli_fetch_array($sql);
$data2 = mysqli_fetch_array($sql2);
$power = $data["power"];
$energy = $data["energy"];
$voltage = $data["voltage"];
$current = $data["current"];
$frekuensi = $data["frekuensi"];
$relay1 = $data2["relay_satu"];
$relay2 = $data2["relay_dua"];


echo '<span id="power">' . $power . '</span>';
echo '<span id="energy">' . $energy  . '</span>';
echo '<span id="voltage">'. $voltage . '</span>';
echo '<span id="current">'. $current . '</span>';
echo '<span id="frekuensi">'. $frekuensi . '</span>';

echo '<span id="relay1">'. $relay1 . '</span>';
echo '<span id="relay2">'. $relay2 . '</span>';

// Konversi energi menjadi nilai uang (rupiah) dengan tarif listrik 1500 rupiah per KWh
$tarifPerKwh = 1500;
$uang = $energy * $tarifPerKwh;

// Menampilkan nilai uang dalam format mata uang rupiah
echo '<span id="uang">' . number_format($uang, 0, ',', '.') . '</span>';

?>
