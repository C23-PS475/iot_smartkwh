<?php
// Mulai session
session_start();

// Periksa apakah session 'username' sudah diset
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect pengguna ke halaman login
    header("Location: login.php");
    exit(); // Pastikan keluar dari skrip
}
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
    <link rel="stylesheet" href="text/css" href="assets/css/bootstrap.min.css">
    <script type="text/javascript" src="assets/js/jquery-3.4.0.min.js"></script>
    <script type="text/javascript" src="assets/js/mdb.min.js"></script>
    <script type="text/javascript" src="jquery-latest.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>

</head>

<style type="text/css">
    div.testArea {
        display: inline-block;
        width: 12em;
        height: 10em;
        position: relative;
        box-sizing: border-box;
    }

    div.testName {
        position: absolute;
        top: -0.3em;
        left: 35%;
        width: auto;
        font-size: 1.4em;
        z-index: 9;
        white-space: nowrap;
    }

    div.testName2 {
        position: absolute;
        top: -0.3em;
        left: 30%;
        width: auto;
        font-size: 1.4em;
        z-index: 9;
        white-space: nowrap;
    }

    div.testName3 {
        position: absolute;
        top: -0.3em;
        left: 25%;
        width: auto;
        font-size: 1.4em;
        z-index: 9;
        white-space: nowrap;
    }

    div.meterText {
        position: absolute;
        bottom: 1.5em;
        width: auto;
        font-size: 2em;
        z-index: 9;
        color: black;
        left: 35%;
        white-space: nowrap;
    }

    div.meterText:empty:before {
        content: "";
    }

    div.unit {
        position: absolute;
        bottom: 1em;
        left: 40%;
        width: auto;
        z-index: 9;
        white-space: nowrap;
    }

    div.unit2 {
        position: absolute;
        bottom: 1em;
        left: 45%;
        width: auto;
        z-index: 9;
        white-space: nowrap;
    }

    div.nilai {
        position: absolute;
        bottom: 0.3em;
        left: 15%;
        width: auto;
        z-index: 9;
        white-space: nowrap;
    }

    div.nilai2 {
        position: absolute;
        bottom: 0.3em;
        left: 70%;
        width: auto;
        z-index: 9;
        white-space: nowrap;
    }

    div.testArea canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    div.testGroup {
        display: inline-block;
    }

    .card-body div#beban,
    .card-body div#beban2 {
        clear: both;
        /* Bersihkan float */
        font-weight: bold;
        /* Membuat tulisan tebal */
        color: white;
        /* Membuat tulisan berwarna putih */
        border-radius: 0.25rem;
        /* Atur border-radius sesuai kebutuhan */
    }

    /* Style untuk ketika relay ON */
    .card-body div#beban span,
    .card-body div#beban2 span {
        background-color: green;
        /* Warna latar belakang untuk relay ON */
        padding: 0.25rem 0.5rem;
        /* Atur padding sesuai kebutuhan */
        border-radius: 0.25rem;
        /* Atur border-radius sesuai kebutuhan */
    }

    /* Style untuk ketika relay OFF */
    .card-body div#beban span.off,
    .card-body div#beban2 span.off {
        background-color: red;
        /* Warna latar belakang untuk relay OFF */
        padding: 0.25rem 0.5rem;
        /* Atur padding sesuai kebutuhan */
        border-radius: 0.25rem;
        /* Atur border-radius sesuai kebutuhan */
    }

    @media all and (max-width:65em) {
        body {
            font-size: 1.5vw;
        }

        div.testName {
            top: -0.3em;
            left: 35%;
        }

        div.testName2 {
            top: -0.3em;
            left: 30%;
        }

        div.testName3 {
            top: -0.3em;
            left: 25%;
        }
    }

    @media all and (max-width:40em) {
        body {
            font-size: 0.8em;
        }

        div.testGroup {
            display: block;
            margin: 0 auto;
        }
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $("#dlText").load('ceksensor.php #power');
            $("#ulText").load('ceksensor.php #energy');
            $("#pingText").load('ceksensor.php #voltage');
            $("#jitText").load('ceksensor.php #current');
            $("#frekText").load('ceksensor.php #frekuensi');
            $("#grafik").load('data.php');
            $("#beban").load('ceksensor.php #relay1', function(response, status, xhr) {
                // Menampilkan tulisan ON atau OFF berdasarkan nilai relay1
                var relay1Value = parseInt($(this).text().trim());
                if (relay1Value === 0) {
                    $(this).html('<span style="background-color: green;">ON</span>');
                } else {
                    $(this).html('<span style="background-color: red;">OFF</span>');
                }
            });
            $("#beban2").load('ceksensor.php #relay2', function(response, status, xhr) {
                // Menampilkan tulisan ON atau OFF berdasarkan nilai relay2
                var relay2Value = parseInt($(this).text().trim());
                if (relay2Value === 0) {
                    $(this).html('<span style="background-color: green;">ON</span>');
                } else {
                    $(this).html('<span style="background-color: red;">OFF</span>');
                }
            });
            $.get("ceksensor.php", function(data) {
                // Extracting the value of money (#uang) directly from the response
                var uang = $(data).filter("#uang").text();
                $("#uang").text(uang);
            });

        }, 1000);


    });

    function I(id) {
        return document.getElementById(id);
    }
    var meterBk = "#E0E0E0";
    var dlColor = "#6060AA",
        ulColor = "#309030",
        pingColor = "#AA6060",
        jitColor = "#FFD700";
    frekColor = "#2980B9"
    var progColor = "#EEEEEE";
    var parameters = { //custom test parameters. See doc.md for a complete list
        time_dl: 10, //download test lasts 10 seconds
        time_ul: 10, //upload test lasts 10 seconds
        count_ping: 50, //ping+jitter test does 20 pings
        getIp_ispInfo: false //will only get IP address without ISP info
    };

    //CODE FOR GAUGES
    function drawMeter(c, amount, maxAmount, bk, fg, prog) {
        var ctx = c.getContext("2d");
        var dp = window.devicePixelRatio || 1;
        var cw = c.clientWidth * dp,
            ch = c.clientHeight * dp;
        var sizScale = ch * 0.0055;
        if (c.width == cw && c.height == ch) {
            ctx.clearRect(0, 0, cw, ch);
        } else {
            c.width = cw;
            c.height = ch;
        }

        // Hitung persentase dari amount terhadap maksimum
        var percentage = amount / maxAmount;

        // Gambar busur berdasarkan persentase
        ctx.beginPath();
        ctx.strokeStyle = bk;
        ctx.lineWidth = 16 * sizScale;
        ctx.arc(c.width / 2, c.height - 58 * sizScale, c.height / 1.8 - ctx.lineWidth, -Math.PI * 1.1, Math.PI * 0.1);
        ctx.stroke();
        ctx.beginPath();
        ctx.strokeStyle = fg;
        ctx.lineWidth = 16 * sizScale;
        ctx.arc(c.width / 2, c.height - 58 * sizScale, c.height / 1.8 - ctx.lineWidth, -Math.PI * 1.1, percentage * Math.PI * 1.2 - Math.PI * 1.1);
        ctx.stroke();
        if (typeof prog !== "undefined") {
            ctx.fillStyle = prog;
            ctx.fillRect(c.width * 0.3, c.height - 16 * sizScale, c.width * 0.4 * percentage, 4 * sizScale);
        }
    }

    function updateUI(forced) {
        // Ambil nilai dlText dari elemen #dlText
        var dlTextValue = parseFloat($('#dlText').text());
        var ulTextValue = parseFloat($('#ulText').text());
        var pingTextValue = parseInt($('#pingText').text());
        var jitTextValue = parseFloat($('#jitText').text());
        var frekTextValue = parseFloat($('#frekText').text());

        // Tentukan warna meter berdasarkan nilai dlText
        var dlMeterColor;
        if (dlTextValue >= 0 && dlTextValue <= 1000) {
            dlMeterColor = dlColor;
        } else {
            dlMeterColor = progColor;
        }

        var ulMeterColor;
        if (ulTextValue >= 0 && ulTextValue <= 600) {
            ulMeterColor = ulColor;
        } else {
            ulMeterColor = progColor;
        }

        var pingMeterColor;
        if (pingTextValue >= 0 && pingTextValue <= 600) {
            pingMeterColor = pingColor;
        } else {
            pingMeterColor = pingColor;
        }

        var jitMeterColor;
        if (jitTextValue >= 0 && jitTextValue <= 100) {
            jitMeterColor = jitColor;
        } else {
            jitMeterColor = jitColor;
        }

        var frekMeterColor;
        if (frekTextValue >= 0 && frekTextValue <= 100) {
            frekMeterColor = frekColor;
        } else {
            frekMeterColor = frekColor;
        }


        // Update teks dlText
        $('#dlText').text(dlTextValue);
        $('#ulText').text(ulTextValue);
        $('#pingText').text(pingTextValue);
        $('#jitText').text(jitTextValue);
        $('#frekText').text(frekTextValue);


        // Menggambar meter dengan warna yang sesuai
        drawMeter(I("dlMeter"), dlTextValue, 1000, meterBk, dlMeterColor);
        drawMeter(I("ulMeter"), ulTextValue, 600, meterBk, ulMeterColor);
        drawMeter(I("pingMeter"), pingTextValue, 600, meterBk, pingMeterColor);
        drawMeter(I("jitMeter"), jitTextValue, 100, meterBk, jitMeterColor);
        drawMeter(I("frekMeter"), frekTextValue, 100, meterBk, frekMeterColor);
    }

    //update the UI every frame
    window.requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || (function(callback, element) {
        setTimeout(callback, 1000 / 60);
    });

    function frame() {
        requestAnimationFrame(frame);
        updateUI();
    }
    frame(); //start frame loop
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
            <li class="nav-item active">
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

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
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
                                    <h5 class="m-0 font-weight-bold text-primary">Monitoring</h5>
                                    <div class="dropdown no-arrow">
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="testGroup">
                                        <div class="testArea">
                                            <div class="testName2">Demand</div>
                                            <canvas id="dlMeter" class="meter"></canvas>
                                            <div id="dlText" class="meterText"></div>
                                            <div class="unit">KWH</div>
                                            <div class="nilai">0</div>
                                            <div class="nilai2">1000</div>
                                        </div>
                                        <div class="testArea">
                                            <div class="testName2">Supply</div>
                                            <canvas id="ulMeter" class="meter"></canvas>
                                            <div id="ulText" class="meterText"></div>
                                            <div class="unit">KWH</div>
                                            <div class="nilai">0</div>
                                            <div class="nilai2">600</div>
                                        </div>
                                        <div class="testArea">
                                            <div class="testName2">Voltage</div>
                                            <canvas id="pingMeter" class="meter"></canvas>
                                            <div id="pingText" class="meterText"></div>
                                            <div class="unit2">V</div>
                                            <div class="nilai">0</div>
                                            <div class="nilai2">600</div>
                                        </div>
                                        <div class="testArea">
                                            <div class="testName2">Current</div>
                                            <canvas id="jitMeter" class="meter"></canvas>
                                            <div id="jitText" class="meterText"></div>
                                            <div class="unit2">A</div>
                                            <div class="nilai">0</div>
                                            <div class="nilai2">100</div>
                                        </div>
                                        <div class="testArea">
                                            <div class="testName3">Frekuensi</div>
                                            <canvas id="frekMeter" class="meter"></canvas>
                                            <div id="frekText" class="meterText"></div>
                                            <div class="unit2">Hz</div>
                                            <div class="nilai">0</div>
                                            <div class="nilai2">100</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-9 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class="m-0 font-weight-bold text-primary">Grafik Energy</h5>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div id="grafik">

                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class=" col-xl-3 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class="m-0 font-weight-bold text-primary">Status</h5>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h6>Relay Stop Kontak</h6>
                                            <div id="beban"></div>
                                        </div>
                                        <div class="col">
                                            <h6>Relay Lampu</h6>
                                            <div id="beban2"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class="m-0 font-weight-bold text-primary">Jumlah</h5>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <i class="fas fa-money-bill-wave" style="color: green; font-size: 30px" ;></i>
                                            <span id="uang" style="font-size: 30px; color: black;"></span>

                                        </div>
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
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>


</body>

</html>