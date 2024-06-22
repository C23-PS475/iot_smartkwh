<?php
// Ambil nilai dari AJAX
$btnId = $_POST['btnId'];
$value = $_POST['value'];

// Lakukan koneksi ke database MySQL
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

// Update nilai di database
if ($btnId == "btnKontrol1") {
    $sql = "UPDATE kontrol SET kontrol_satu=$value";
} elseif ($btnId == "btnKontrol2") {
    $sql = "UPDATE kontrol SET kontrol_dua=$value";
} else {
    echo "ID tombol tidak valid.";
}

if ($conn->query($sql) === TRUE) {
    echo "Nilai berhasil diperbarui di database.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
