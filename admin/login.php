<?php

session_start();
require 'functions.php';


if (isset($_POST["login"])) {

  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $password = mysqli_real_escape_string($conn, $_POST["password"]);

  $user = query("SELECT * FROM super_user WHERE username = '$username' AND level = 'Admin'");
  foreach ($user as $a) {
  }

  ini_set("display_errors", 0);

  if ($username == $a['username']) {

    $result = mysqli_query($conn, "SELECT * FROM super_user WHERE username = '$username' AND level = 'Admin'");


    if (mysqli_num_rows($result) === 1) {


      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {

        $_SESSION["login"] = true;
        $_SESSION["super_userxxx"] = true;
        $_SESSION["username"] = $username;

        header("Location: index.php?welcome=true");
        exit;
      }
    }
  }


  $error = true;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'link.php'; ?>
</head>

<body>

  <?php if (isset($success)) : ?>
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
        title: 'Registrasi Akun Berhasil'
      })
    </script>
  <?php endif; ?>

  <?php if (isset($_GET['duplicate'])) : ?>

    <?php if ($_GET['duplicate'] = "true") : ?>
      <script>
        Swal.fire({
          icon: 'error',
          showConfirmButton: false,
          text: 'Username telah digunakan sebelumnya, Gunakan username yang lain untuk mendaftar!',
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

    <!-- ========== signin-section start ========== -->
    <section class="signin-section">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2>Login Admin</h2>
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
                      Login
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

        <div class="row g-0 auth-row">
          <div class="col-lg-6">
            <div class="auth-cover-wrapper bg-primary-100">
              <div class="auth-cover">
                <div class="title text-center">
                  <h1 class="text-primary mb-10">Selamat Datang!</h1>
                </div>
                <div class="cover-image">
                  <img src="assets/images/auth/signin-image.svg" alt="" />
                </div>
                <div class="shape-image">
                  <img src="assets/images/auth/shape.svg" alt="" />
                </div>
              </div>
            </div>
          </div>
          <!-- end col -->
          <div class="col-lg-6">
            <div class="signup-wrapper">
              <div class="form-wrapper">
                <h6 class="mb-15 text-center">Form Login</h6>
                <hr>
                <form action="" method="post">
                  <div class="row">
                    <div class="col-12">
                      <div class="input-style-1">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Contoh: eko" required />
                      </div>
                    </div>
                    <!-- end col -->
                    <div class="col-12">
                      <div class="input-style-1">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Password" required />
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="
                            button-group
                            d-flex
                            justify-content-center
                            flex-wrap
                          ">
                        <button type="submit" name="login" class="
                              main-btn
                              primary-btn
                              btn-hover
                              w-100
                              text-center
                            ">
                          Submit
                        </button>
                      </div>
                    </div>
                  </div>
                  <!-- end row -->
                </form>
              </div>
            </div>
          </div>
          <!-- end col -->
        </div>
        <!-- end row -->
      </div>
    </section>
    <!-- ========== signin-section end ========== -->

    <!-- ========== footer start =========== -->
    <footer class="footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 order-last order-md-first">
            <div class="copyright text-center text-md-start">
              <p class="text-sm">
                Designed and Developed by
                <a href="https://plainadmin.com" rel="nofollow" target="_blank">
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
</body>

</html>