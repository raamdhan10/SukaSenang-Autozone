<?php
session_start();
include 'functions.php';

if (!isset($_SESSION["super_userxxx"])) {
  echo "<script>
    window.location.href='login.php';
  </script>";
  exit;
}


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
                <h2>Pengelolaan Data Pesanan</h2>

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
                      Pesanan
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

        <!-- <div class="my-4">
            <form action="" method="post">
              <label for="">Cari berdasarkan tanggal</label>
              <input type="date" name="tanggalx" id="tanggalx" class="form-control">
            </form>
          </div> -->

        <div id="ajaxPesanan">

          <div class="row">
            <div class="col">
              <?php $pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE status != 'Menunggu Pembayaran'"); ?>
              <?php foreach ($pesanan as $p) : ?>
                <div class="card-style mb-30">
                  <div class="
                    title
                    d-flex
                    flex-wrap
                    align-items-center
                    justify-content-between
                  ">
                    <div class="left">
                      <h6 class="text-medium mb-30">
                        <span class="badge bg-info fs-6">Kode Pesanan : <u><?= $p['kode_pesanan']; ?></u></span>
                      </h6>
                    </div>
                    <div class="right">
                      <div class="select-style-1">

                      </div>
                      <!-- end select -->
                    </div>
                  </div>
                  <!-- End Title -->
                  <div class="table-responsive">
                    <table class="table top-selling-table">
                      <thead>
                        <tr>
                          <th>
                            <h6 class="text-sm text-medium">Whatsapp</h6>
                          </th>
                          <th class="min-width">
                            <h6 class="text-sm text-medium">
                              Produk
                            </h6>
                          </th>
                          <th>
                            <h6 class="text-sm text-medium">
                              Tanggal Pemesanan
                            </h6>
                          </th>
                          <th>
                            <h6 class="text-sm text-medium">
                              Bukti Transfer
                            </h6>
                          </th>
                          <th class="min-width">
                            <h6 class="text-sm text-medium">
                              Jumlah
                            </h6>
                          </th>
                          <th class="min-width">
                            <h6 class="text-sm text-medium">
                              Harga
                            </h6>
                          </th>
                          <th>
                            <h6 class="text-sm text-medium">
                              Status
                            </h6>
                          </th>
                        </tr>
                      </thead>
                      <?php $kode_pesanan = $p['kode_pesanan']; ?>
                      <?php $detail_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE status != 'Menunggu Pembayaran' AND kode_pesanan = '$kode_pesanan'"); ?>
                      <?php foreach ($detail_pesanan as $detail) : ?>
                        <tbody>
                          <tr>
                            <td>
                              <p class="text-sm"><?= $detail['whatsapp']; ?></p>
                            </td>
                            <td>
                              <div class="product">
                                <?php $id = $detail['id_produk'] ?>
                                <?php $produk = query("SELECT * FROM produk WHERE id = $id")[0]; ?>
                                <p class="text-sm"><?= $produk['nama']; ?></p>
                              </div>
                            </td>
                            <td>
                              <p class="text-sm"><?= date("d F Y", strtotime($detail['tanggal'])); ?></p>
                            </td>
                            <td>
                              <a href="../bukti_transfer/<?= $detail['bukti_transfer']; ?>" class="text-sm"><?= $detail['bukti_transfer']; ?></a>
                            </td>
                            <td>
                              <p class="text-sm">@<?= $detail['jumlah']; ?></p>
                            </td>
                            <td>
                              <p class="text-sm">Rp<?= number_format($detail['harga'], 0, ',', '.'); ?></p>
                            </td>
                            <td>
                              <span class="status-btn success-btn"><?= $detail['status']; ?></span>
                            </td>
                          </tr>
                        </tbody>
                      <?php endforeach; ?>
                    </table>
                    <!-- End Table -->
                  </div>
                </div>
              <?php endforeach; ?>

            </div>
            <!-- End Col -->
          </div>
          <!-- End Row -->

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
                <a href="https://plainadmin.com" rel="nofollow" target="_blank">
                  PlainAdmin
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

  <script src="ajax/index2.js"></script>

</body>

</html>