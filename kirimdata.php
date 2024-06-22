<?php

date_default_timezone_set('Asia/Jakarta');
$konek = mysqli_connect("localhost", "root", "", "iot_smartkwh");

// Mendapatkan data dari GET dan memeriksa apakah nilai valid
$power = isset($_GET["power"]) && is_numeric($_GET["power"]) ? $_GET["power"] : 0;
$energy = isset($_GET["energy"]) && is_numeric($_GET["energy"]) ? $_GET["energy"] : 0;
$voltage = isset($_GET["voltage"]) && is_numeric($_GET["voltage"]) ? $_GET["voltage"] : 0;
$current = isset($_GET["current"]) && is_numeric($_GET["current"]) ? $_GET["current"] : 0;
$frekuensi = isset($_GET["frekuensi"]) && is_numeric($_GET["frekuensi"]) ? $_GET["frekuensi"] : 0;
$supply = isset($_GET["supply"]) && is_numeric($_GET["supply"]) ? $_GET["supply"] : 0;
$demand = isset($_GET["demand"]) && is_numeric($_GET["demand"]) ? $_GET["demand"] : 0;
$status1 = isset($_GET["status1"]) ? $_GET["status1"] : NULL;
$status2 = isset($_GET["status2"]) ? $_GET["status2"] : NULL;
$tanggal = date('Y-m-d H:i:s');

// Reset AUTO_INCREMENT tabel sensor dan grafik
mysqli_query($konek, "ALTER TABLE sensor AUTO_INCREMENT=1");
mysqli_query($konek, "INSERT INTO sensor (power, energy, voltage, current, frekuensi, tanggal) VALUES ('$power', '$energy', '$voltage', '$current', '$frekuensi', '$tanggal')");
mysqli_query($konek, "ALTER TABLE grafik AUTO_INCREMENT=1");
$simpan_grafik = mysqli_query($konek, "INSERT INTO grafik (supply, demand) VALUES ('$supply', '$demand')");

// Memperbarui tabel beban hanya jika nilai status diberikan
if ($status1 !== NULL) {
    mysqli_query($konek, "UPDATE beban SET relay_satu='$status1'");
}

if ($status2 !== NULL) {
    mysqli_query($konek, "UPDATE beban SET relay_dua='$status2'");
}

// Menampilkan pesan sesuai dengan hasil penyimpanan
if ($simpan_grafik) {
    echo "Berhasil disimpan";
} else {
    echo "Gagal disimpan";
}

?>