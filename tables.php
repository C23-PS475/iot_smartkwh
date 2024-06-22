<?php
// Mulai session
session_start();

// Periksa apakah session 'username' sudah diset
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect pengguna ke halaman login
    header("Location: login.php");
    exit(); // Pastikan keluar dari skrip
}

// Koneksi ke database
$konek = mysqli_connect("localhost", "root", "", "iot_smartkwh");

// Tentukan jumlah data yang ingin ditampilkan per halaman
$data_per_halaman = 10;

// Tentukan halaman saat ini
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_saat_ini = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
$Previous = $halaman - 1;
$next = $halaman + 1;

// Hitung offset untuk kueri SQL
$offset = ($halaman_saat_ini - 1) * $data_per_halaman;

// Inisialisasi variabel filter
$dari_tgl = '';
$sampai_tgl = '';

if (isset($_POST['filter'])) {
    $dari_tgl = $_POST['dari_tgl'];
    $sampai_tgl = $_POST['sampai_tgl'];

    $query = "SELECT s.*, g.supply, g.demand 
              FROM sensor s
              JOIN grafik g ON s.ID = g.ID
              WHERE s.tanggal BETWEEN '$dari_tgl' AND '$sampai_tgl'
              LIMIT $offset, $data_per_halaman";
    $data_report = mysqli_query($konek, $query);

    $total_data_query = "SELECT COUNT(*) AS total 
                        FROM grafik g
                        JOIN sensor s ON s.ID = g.ID
                        WHERE s.tanggal BETWEEN '$dari_tgl' AND '$sampai_tgl'";
    $total_data_result = mysqli_query($konek, $total_data_query);
    $total_data_row = mysqli_fetch_assoc($total_data_result);
    $total_data = $total_data_row['total'];
} else {
    $query = "SELECT s.*, g.supply, g.demand 
              FROM sensor s
              JOIN grafik g ON s.ID = g.ID
              LIMIT $offset, $data_per_halaman";
    $data_report = mysqli_query($konek, $query);

    // Hitung jumlah total data
    $total_data_query = "SELECT COUNT(*) AS total FROM grafik";
    $total_data_result = mysqli_query($konek, $total_data_query);
    $total_data_row = mysqli_fetch_assoc($total_data_result);
    $total_data = $total_data_row['total'];
}

// Hitung jumlah total halaman
$total_halaman = ceil($total_data / $data_per_halaman);
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

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-link.active {
            background-color: #dddddd;
            border-color: #dddddd;
            color: #333333;
        }
    </style>

</head>

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

            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Report</span></a>
            </li>

            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="kontrol.php">
                    <i class="fas fa-fw fa-plus-square"></i>
                    <span>Manajemen Listrik</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h5 class="m-0 font-weight-bold text-primary">Data Report</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post">
                                    <table>
                                        <tr>
                                            <td>Dari Tanggal dan Waktu</td>
                                            <td><input type="datetime-local" name="dari_tgl" value="<?php echo $dari_tgl; ?>" required="required"></td>
                                            <td>Sampai Tanggal dan Waktu</td>
                                            <td><input type="datetime-local" name="sampai_tgl" value="<?php echo $sampai_tgl; ?>" required="required"></td>
                                            <td><input type="submit" class="btn btn-primary" name="filter" value="Filter"></td>
                                        </tr>
                                    </table>
                                </form>
                                <br>
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Power</th>
                                            <th>Energy</th>
                                            <th>Voltage</th>
                                            <th>Current</th>
                                            <th>Frekuensi</th>
                                            <th>Supply</th>
                                            <th>Demand</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_array($data_report)) { ?>
                                            <tr>
                                                <td><?php echo $row['ID']; ?></td>
                                                <td><?php echo $row['power']; ?></td>
                                                <td><?php echo $row['energy']; ?></td>
                                                <td><?php echo $row['voltage']; ?></td>
                                                <td><?php echo $row['current']; ?></td>
                                                <td><?php echo $row['frekuensi']; ?></td>
                                                <td><?php echo $row['supply']; ?></td>
                                                <td><?php echo $row['demand']; ?></td>
                                                <td><?php echo $row['tanggal']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <div class="total-pages">Page <?php echo $halaman_saat_ini; ?> of <?php echo $total_halaman; ?></div>
                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            <?php if ($halaman_saat_ini > 1) : ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?halaman=1">First</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="?halaman=<?php echo $halaman_saat_ini - 1; ?>">
                                                        << </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php
                                            // Batasan jumlah halaman yang ditampilkan
                                            $batas_halaman = 10;

                                            // Menentukan batas awal dan akhir halaman yang ditampilkan
                                            $start = max(1, $halaman_saat_ini - floor($batas_halaman / 2));
                                            $end = min($total_halaman, $start + $batas_halaman - 1);

                                            for ($x = $start; $x <= $end; $x++) {
                                            ?>
                                                <li class="page-item <?php if ($x == $halaman_saat_ini) echo 'active'; ?>"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
                                            <?php
                                            }
                                            ?>
                                            <?php if ($halaman_saat_ini < $total_halaman) : ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?halaman=<?php echo $halaman_saat_ini + 1; ?>">>></a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="?halaman=<?php echo $total_halaman; ?>">Last</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>