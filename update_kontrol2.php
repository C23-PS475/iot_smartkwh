<?php
// Mulai session
session_start();

// Periksa apakah session 'username' sudah diset
if (!isset($_SESSION['username'])) {
    // Jika tidak, kirim respons error atau redirect pengguna ke halaman login
    http_response_code(401); // Unauthorized
    exit(); // Pastikan keluar dari skrip
}

// Ambil nilai dari AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah data 'batasnilai' dan 'waktumulai' diterima
    if (isset($_POST['batasnilai']) && isset($_POST['waktumulai'])) {
        // Tangkap data dari permintaan POST
        $batasnilai = $_POST['batasnilai'];
        $waktumulai = $_POST['waktumulai'];
        $waktuselesai = $_POST['waktuselesai'];
        $status = $_POST['status'];

        // Lakukan koneksi ke database MySQL
        $servername = "147.139.214.76"; // Ganti dengan hostname atau IP server MySQL Anda
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "iot_smartkwh"; 

        // Buat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Ubah format waktu menjadi format yang sesuai dengan format datetime MySQL
        $waktumulai_mysql = date('H:i', strtotime($waktumulai));
        $waktuselesai_mysql = date('H:i', strtotime($waktuselesai));

        // Query untuk melakukan insert nilai dalam tabel batasan
        $sql = "UPDATE batasan SET batasnilai='$batasnilai', waktumulai='$waktumulai_mysql', waktuselesai='$waktuselesai_mysql', status='$status'";


        // Eksekusi query
        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil diperbarui di dalam database.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Tutup koneksi
        $conn->close();
    } else {
        // Jika data 'batasnilai' dan 'waktumulai' tidak diterima, kirim respons error
        http_response_code(400); // Bad Request
        echo "Data tidak diterima.";
    }
} else {
    // Jika bukan metode POST, kirim respons method not allowed
    http_response_code(405); // Method Not Allowed
    echo "Metode tidak diizinkan.";
}
