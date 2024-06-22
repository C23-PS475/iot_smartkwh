<?php
$servername = "localhost"; // Ganti dengan hostname atau IP server MySQL Anda
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "iot_smartkwh"; 

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil nilai dari tabel kontrol
$sql_kontrol = "SELECT kontrol_satu, kontrol_dua FROM kontrol";
$result_kontrol = $conn->query($sql_kontrol);

// Jika hasil ditemukan, kirimkan nilai sebagai respons
if ($result_kontrol->num_rows > 0) {
    $row_kontrol = $result_kontrol->fetch_assoc();
    $kontrol_satu = $row_kontrol["kontrol_satu"];
    $kontrol_dua = $row_kontrol["kontrol_dua"];
} else {
    $kontrol_satu = "Data tidak ditemukan";
    $kontrol_dua = "Data tidak ditemukan";
}

// Query untuk mengambil nilai dari tabel batasan
$sql_batas = "SELECT batasnilai, waktumulai, waktuselesai, status FROM batasan";
$result_batas = $conn->query($sql_batas);

// Jika hasil ditemukan, kirimkan nilai sebagai respons
if ($result_batas->num_rows > 0) {
    $row_batas = $result_batas->fetch_assoc();
    $batasnilai = $row_batas["batasnilai"];
    $waktumulai = $row_batas["waktumulai"];
    $waktuselesai = $row_batas["waktuselesai"];
    $status = $row_batas["status"];
} else {
    $batasnilai = "Data tidak ditemukan";   
    $waktumulai = "Data tidak ditemukan";
    $waktuselesai = "Data tidak ditemukan";
    $status = "Data tidak ditemukan";
}

// Gabungkan nilai-nilai sebagai respons
$response = "$kontrol_satu,$kontrol_dua,$batasnilai,$waktumulai,$waktuselesai,$status";
echo $response;

$conn->close();
?>
