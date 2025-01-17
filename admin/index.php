<?php
session_start();
include 'functions.php';

if (!isset($_SESSION["super_userxxx"])) {
  echo "<script>
    window.location.href='login.php';
  </script>";
  exit;
}


$zona_waktu = time() + (60 * 60 * 8);
$tanggal_sekarang = gmdate('d', $zona_waktu);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'link.php'; ?>
  <style>
    th,
    td {
      text-align: center !important;
    }
  </style>
</head>

<body>

  <?php if (isset($_GET['welcome'])) : ?>

    <?php if ($_GET['welcome'] = "true") : ?>
      <script>
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true
        })

        Toast.fire({
          icon: 'success',
          title: 'Selamat Datang!'
        })
      </script>

    <?php endif; ?>
  <?php endif; ?>


  <!-- ======== sidebar-nav start =========== -->
  <?php include 'sidebar.php'; ?>
  <!-- ======== sidebar-nav end =========== -->

  <!-- ======== main-wrapper start =========== -->
  <main class="main-wrapper">
    <!-- ========== header start ========== -->
    <header class="header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-6">
            <div class="header-left d-flex align-items-center">
              <div class="menu-toggle-btn mr-20">
                <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                  <i class="lni lni-chevron-left me-2"></i> Menu
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- ========== header end ========== -->

    <!-- ========== section start ========== -->
    <section class="section">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2>Toko Motor Dashboard</h2>
                <p>Anda login menggunakan username <b class="text-info"><?= $_SESSION['username']; ?></b></p>

              </div>
            </div>
            <!-- end col -->
            <div class="col-md-6">
              <div class="breadcrumb-wrapper mb-30">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="#0">Toko Motor</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Dashboard
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->
        <div class="row">
          <div class="col-xl-6 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
              <div class="icon purple">
                <i class="lni lni-cart-full"></i>
              </div>
              <div class="content">
                <h6 class="mb-10">Total Penjualan Hari Ini</h6>
                <h3 class="text-bold mb-10">
                  <?php $hitung_penjualan = mysqli_query($conn, "SELECT SUM(jumlah) as jml FROM pesanan WHERE status IS NOT NULL AND DAY(tanggal) = '$tanggal_sekarang'"); ?>
                  <?php foreach ($hitung_penjualan as $x) {
                  } ?>
                  <?= $x['jml']; ?> item
                </h3>
              </div>
            </div>
            <!-- End Icon Cart -->
          </div>
          <!-- End Col -->
          <div class="col-xl-6 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
              <div class="icon success">
                <i class="lni lni-dollar"></i>
              </div>
              <div class="content">
                <?php $px = query("SELECT SUM(jumlah * harga) as pendapatan FROM pesanan WHERE status IS NOT NULL")[0]; ?>
                <h6 class="mb-10">Total Pemasukan</h6>
                <?php if ($px['pendapatan'] == NULL) {
                  $px['pendapatan'] = 0;
                } ?>
                <h3 class="text-bold mb-10">Rp<?= number_format($px['pendapatan'], 0, ',', '.'); ?></h3>
              </div>
            </div>
            <!-- End Icon Cart -->
          </div>
        </div>

        <div class="card px-3">
          <table class="table">
            <tr>
              <th>Whatsapp</th>
              <th>Waktu Daftar</th>
            </tr>
            <?php $user = mysqli_query($conn, "SELECT * FROM user ORDER BY waktu_daftar DESC"); ?>
            <?php foreach ($user as $key) : ?>
              <tr>
                <td><?= $key['whatsapp']; ?></td>
                <td><?= date('d F Y / H:i:s', strtotime($key['waktu_daftar'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
      <!-- end container -->
    </section>
    <!-- ========== section end ========== -->



    <!-- ========== footer start =========== -->
    <footer class="footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 order-last order-md-first">
            <div class="copyright text-center text-md-start">
              <p class="text-sm">
                Designed and Developed by
                <a rel="nofollow" target="_blank">
                  Admin
                </a>
              </p>
            </div>
          </div>
          <!-- end col-->
          <div class="col-md-6">
            <div class="
                  terms
                  d-flex
                  justify-content-center justify-content-md-end
                ">
              <a href="#0" class="text-sm">Term & Conditions</a>
              <a href="#0" class="text-sm ml-15">Privacy & Policy</a>
            </div>
          </div>
        </div>
        <!-- end row -->
      </div>
      <!-- end container -->
    </footer>
    <!-- ========== footer end =========== -->
  </main>
  <!-- ======== main-wrapper end =========== -->

  <!-- ========= All Javascript files linkup ======== -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/Chart.min.js"></script>
  <script src="assets/js/dynamic-pie-chart.js"></script>
  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/fullcalendar.js"></script>
  <script src="assets/js/jvectormap.min.js"></script>
  <script src="assets/js/world-merc.js"></script>
  <script src="assets/js/polyfill.js"></script>
  <script src="assets/js/main.js"></script>

  <?php
  $second_sekarang = time() + (60 * 60 * 8);
  $tanggal_sekarang = gmdate('Y-m-d', $second_sekarang);
  $d = gmdate('d F', $second_sekarang);


  $second_min1 = time() + (60 * 60 * 8) - 86400;
  $tanggal_min1 = gmdate('Y-m-d', $second_min1);
  $d1 = gmdate('d F', $second_min1);

  $second_min2 = time() + (60 * 60 * 8) - 172800;
  $tanggal_min2 = gmdate('Y-m-d', $second_min2);
  $d2 = gmdate('d F', $second_min2);

  $second_min3 = time() + (60 * 60 * 8) - 259200;
  $tanggal_min3 = gmdate('Y-m-d', $second_min3);
  $d3 = gmdate('d F', $second_min3);

  $second_min4 = time() + (60 * 60 * 8) - 345600;
  $tanggal_min4 = gmdate('Y-m-d', $second_min4);
  $d4 = gmdate('d F', $second_min4);

  $second_min5 = time() + (60 * 60 * 8) - 432000;
  $tanggal_min5 = gmdate('Y-m-d', $second_min5);
  $d5 = gmdate('d F', $second_min5);

  $second_min6 = time() + (60 * 60 * 8) - 518400;
  $tanggal_min6 = gmdate('Y-m-d', $second_min6);
  $d6 = gmdate('d F', $second_min6);

  $p1 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_sekarang'")[0];

  $p2 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_min1'")[0];

  $p3 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_min2'")[0];

  $p4 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_min3'")[0];

  $p5 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_min4'")[0];

  $p6 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_min5'")[0];

  $p7 = query("SELECT SUM(harga * jumlah) as total FROM pesanan WHERE tanggal = '$tanggal_min6'")[0];
  ?>

  <script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
      type: 'bar',
      data: {
        backgroundColor: 'rgb(0,181,226)',
        borderColor: 'rgb(0,181,226)',
        labels: ['<?= $d6; ?>', '<?= $d5; ?>', '<?= $d4; ?>', '<?= $d3; ?>', '<?= $d2; ?>', '<?= $d1; ?>', '<?= $d; ?>'],
        datasets: [{
          backgroundColor: 'rgb(0,181,226)',
          borderColor: 'rgb(0,100,100)',
          label: 'Grafik Pemasukan 1 Minggu Terakhir',
          data: [<?= $p7['total']; ?>, <?= $p6['total']; ?>, <?= $p5['total']; ?>, <?= $p4['total']; ?>, <?= $p3['total']; ?>, <?= $p2['total']; ?>, <?= $p1['total']; ?>],
          borderWidth: 1
        }]
      },

      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

</body>

</html>