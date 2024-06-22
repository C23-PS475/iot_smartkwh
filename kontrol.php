<?php
// Mulai session
session_start();

// Periksa apakah session 'username' sudah diset
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect pengguna ke halaman login
    header("Location: login.php");
    exit(); // Pastikan keluar dari skrip
}
$servername = "localhost"; // Ganti dengan hostname atau IP server MySQL Anda
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "iot_smartkwh";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
$query = "SELECT batasnilai, waktumulai, waktuselesai, status FROM batasan";
// Cek koneksi
$result = mysqli_query($conn, $query);

// Query untuk mengambil data dari tabel kontrol
$query_kontrol = "SELECT kontrol_satu, kontrol_dua FROM kontrol";
$result_kontrol = mysqli_query($conn, $query_kontrol);

// Periksa apakah query berhasil dieksekusi
if (!$result_kontrol) {
    die("Query gagal dieksekusi: " . mysqli_error($conn));
}

// Ambil baris pertama hasil query (asumsikan hanya ada satu baris data)
$row_kontrol = mysqli_fetch_assoc($result_kontrol);

// Ambil nilai-nilai dari baris hasil query
$kontrolSatu = $row_kontrol['kontrol_satu'];
$kontrolDua = $row_kontrol['kontrol_dua'];


// Periksa apakah query berhasil dieksekusi
if (!$result) {
    die("Query gagal dieksekusi: " . mysqli_error($koneksi));
}



// Ambil baris pertama hasil query (asumsikan hanya ada satu baris data)
$row = mysqli_fetch_assoc($result);

// Ambil nilai-nilai dari baris hasil query
$batasNilai = $row['batasnilai'];
$waktuMulai = $row['waktumulai'];
$waktuSelesai = $row['waktuselesai'];
$status = $row['status'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Smart KwH</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js/jquery-3.4.0.min.js"></script>
    <script type="text/javascript" src="assets/js/mdb.min.js"></script>
    <script type="text/javascript" src="jquery-latest.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .button-cover,
        .knobs,
        .layer {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .button {
            position: relative;
            top: 50%;
            width: 75px;
            height: 36px;
            margin: 3px;
            overflow: hidden;
        }

        .button.r,
        .button.r .layer {
            border-radius: 100px;
        }

        .button.b2 {
            border-radius: 2px;
        }

        .checkbox {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .knobs {
            z-index: 2;
        }

        .layer {
            width: 100%;
            background-color: #e1f7eb;
            transition: 0.3s ease all;
            z-index: 1;
        }

        #button-3 .knobs:before {
            content: "ON";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 31px;
            height: 28px;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            line-height: 1;
            padding: 9px 4px;
            background-color: #1cc88a;
            border-radius: 50%;
            transition: 0.3s ease all, left 0.3s cubic-bezier(0.18, 0.89, 0.35, 1.15);
        }

        #button-3 .checkbox:active+.knobs:before {
            width: 46px;
            border-radius: 100px;
        }

        #button-3 .checkbox:checked:active+.knobs:before {
            margin-left: -26px;
        }

        #button-3 .checkbox:checked+.knobs:before {
            content: "OFF";
            left: 42px;
            background-color: #f44336;
        }

        #button-3 .checkbox:checked~.layer {
            background-color: #fcebeb;
        }
    </style>

</head>
<script>
    function toggleRelay(btnId) {
        var button = document.getElementById(btnId);
        var value = button.textContent === "OFF" ? 1 : 0;

        // Toggle value
        value = value === 1 ? 0 : 1;

        // Update button text and class
        if (value === 1) {
            button.textContent = "OFF";
            button.classList.remove("btn-success");
            button.classList.add("btn-danger");
        } else {
            button.textContent = "ON";
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
        }

        // Kirim nilai ke MySQL menggunakan AJAX
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Nilai berhasil dikirim ke MySQL.");
            }
        };
        xhttp.open("POST", "update_kontrol.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("btnId=" + btnId + "&value=" + value);
    }
</script>



<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Monitoring PLTH <sup>Matrik</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Report</span></a>
            </li>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="kontrol.php">
                    <i class="fas fa-fw fa-plus-square"></i>
                    <span>Manajemen Listrik</span></a>
            </li>

            <hr class="sidebar-divider">


        </ul>


        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['username']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="login.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">

                        <div class="col-xl-12 col-lg-5">
                            <div class="card shadow mb-4">

                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class="m-0 font-weight-bold text-primary">Manajemen Listrik</h5>
                                    <div class="dropdown no-arrow">
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="batasnilai">Batas Nilai Energy</label>
                                            <!-- Mengganti elemen select dengan input teks -->
                                            <input type="text" class="form-control" id="batasnilai" placeholder="Masukkan Batasan Nilai" value="<?php echo $batasNilai; ?>" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="waktumulai">Batas Waktu Mulai</label>
                                            <input type="time" class="form-control" id="waktumulai" placeholder="Masukkan hari ke" value="<?php echo date('H:i', strtotime($waktuMulai)); ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="waktuselesai">Batas Waktu Selesai</label>
                                            <input type="time" class="form-control" id="waktuselesai" placeholder="Masukkan hari ke" value="<?php echo date('H:i', strtotime($waktuSelesai)); ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="status">Status</label>
                                            <div class="toggle-button-cover">
                                                <div class="button-cover">
                                                    <div class="button r" id="button-3">
                                                        <input type="checkbox" class="checkbox" id="status" <?php echo $status == 0 ? 'checked' : ''; ?> />
                                                        <div class="knobs"></div>
                                                        <div class="layer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-1 pt-3">
                                            <button type="button" id="btnMulai" class="btn btn-primary">OK</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class="m-0 font-weight-bold text-primary">Kontrol Manual</h5>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                        <h4>Relay Stop Kontak</h4>
                                    <button id="btnKontrol1" class="btn <?php echo $kontrolSatu == 1 ? 'btn-danger' : 'btn-success'; ?>" onclick="toggleRelay('btnKontrol1')">
                                        <?php echo $kontrolSatu == 1 ? 'OFF' : 'ON'; ?>
                                    </button>
                                </div>
                                <div class="col">
                                    <h4>Relay Lampu</h4>
                                    <button id="btnKontrol2" class="btn <?php echo $kontrolDua == 1 ? 'btn-danger' : 'btn-success'; ?>" onclick="toggleRelay('btnKontrol2')">
                                        <?php echo $kontrolDua == 1 ? 'OFF' : 'ON'; ?>
                                    </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>




                </div>
                <!-- End of Main Content -->

                <!-- Footer -->

                <footer class="sticky-footer bg-white" style="margin-top : 200px">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Dipersembahkan &copy; Politeknik Negeri Malang</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById("btnMulai").addEventListener("click", kirimNilai);

            function kirimNilai() {
                // Mendapatkan nilai dari input batasnilai
                var batasNilai = document.getElementById("batasnilai").value;

                // Mendapatkan nilai dari input waktumulai
                var waktuMulai = document.getElementById("waktumulai").value;

                // Mendapatkan nilai dari input waktuSelesai
                var waktuSelesai = document.getElementById("waktuselesai").value;

                // Mendapatkan status checkbox
                var status = document.getElementById("status").checked ? 0 : 1;

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Tampilkan respons yang diterima
                        console.log(this.responseText);

                        // Tampilkan notifikasi SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: 'Nilai berhasil diperbarui ke dalam database.'
                        });
                        // Setelah berhasil, kosongkan nilai input
                        document.getElementById("batasnilai").value = batasNilai;
                        document.getElementById("waktumulai").value = waktuMulai;
                        document.getElementById("waktuselesai").value = waktuSelesai;
                        document.getElementById("status").checked = status === 0; // Reset the checkbox
                    }
                };
                xhttp.open("POST", "update_kontrol2.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("batasnilai=" + batasNilai + "&waktumulai=" + waktuMulai + "&waktuselesai=" + waktuSelesai + "&status=" + status);
            }
        </script>


        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
</body>

</html>