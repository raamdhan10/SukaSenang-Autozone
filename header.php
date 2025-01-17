<!-- HEADER -->
<?php if (isset($_SESSION['user_akusara'])) {
  $whatsapp = $_SESSION['whatsapp'];
  $cekUser = mysqli_query($conn, "SELECT * FROM user WHERE whatsapp = '$whatsapp' AND password IS NULL");
  $hitungUser = mysqli_num_rows($cekUser);
}
?>
<?php if (isset($_SESSION['user_akusara']) and $hitungUser > 0) : ?>
  <div style="margin-top: 0;" class="p-3 bg-dark text-white">
    <div class="flexMain">
      <div class="flex2 text-center">
        <div><strong><small>Amankan akun anda dengan membuat password<button type="button" data-bs-toggle="modal" data-bs-target="#createPasswordModal" style="background-color:transparent;outline: none;border: none;" class="text-info" href="profil/"> disini.</button style="background-color:transparent;"></small></strong></div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function change() {
      var password = document.getElementById('password').type;
      if (password == 'password') {
        document.getElementById('password').type = 'text';
        document.getElementById('mybutton').innerHTML = '<i class="fas fa-eye"></i>';
      } else {
        document.getElementById('password').type = 'password';
        document.getElementById('mybutton').innerHTML = '<i class="fas fa-eye-slash"></i>';
      }
    }
  </script>




  <?php if (isset($_POST["create"])) {

    function addPwd($data)
    {
      global $conn;

      $whatsapp = htmlspecialchars($data["whatsapp"]);
      $password_sebelum = mysqli_real_escape_string($conn, $data["password"]);

      $password = password_hash($password_sebelum, PASSWORD_DEFAULT);

      $query = "UPDATE user SET 
      password = '$password'
      WHERE whatsapp = '$whatsapp' AND password IS NULL
    ";

      mysqli_query($conn, $query);
      return mysqli_affected_rows($conn);
    }


    if (addPwd($_POST) > 0) {
      $_SESSION["pwdCorrect"] = true;
      $_SESSION["whatsapp"] = $whatsapp;
      echo "<script>
              window.location.href='./';
            </script>";
      exit;
    } else {
      echo mysqli_error($conn);
    }
  } ?>


  <!-- Modal -->
  <div class="modal fade" id="createPasswordModal" tabindex="-1" aria-labelledby="createPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPasswordModalLabel">Buat Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post">
            <input type="hidden" name="whatsapp" value="<?= $_SESSION['whatsapp']; ?>" required>
            <label for="password" class="form-label">Masukkan Password Baru</label>
            <div class="mb-3 input-group flex-nowrap">
              <input type="password" class="form-control" name="password" id="password" required>
              <span style="cursor: pointer;" class="input-group-text" id="mybutton" onclick="return change()"><i class="fas fa-eye-slash"></i></span>
            </div>
            <div class="mt-2 text-white bg-warning px-2 py-1 rounded"><small><i class="fas fa-info-circle"></i> Disarankan menggunakan kombinasi antara huruf kecil, huruf besar, angka dan simbol.</small></div>

        </div>
        <div class="modal-footer">
          <button type="submit" name="create" class="btn w-100 btn-dark text-white mt-2">Kirim</button>
        </div>
        </form>
      </div>
    </div>
  </div>

<?php endif; ?>


<div id="menuHolder">
  <div role="navigation" class="sticky-top border-bottom border-top" id="mainNavigation">
    <div class="flexMain">
      <div class="flex2">
        <button class="whiteLink siteLink" style="border-right:1px solid #eaeaea" onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
      </div>
      <div class="flex3 text-center" id="siteBrand">
        TOKO MOTOR
      </div>


      <div class="flex2 text-end d-none d-md-block">

      </div>
    </div>
  </div>

  <div id="menuDrawer">
    <div class="p-4 border-bottom">
      <div class='row'>
        <div class="col text-end ">
          <i class="fas fa-times" role="btn" onclick="menuToggle()"></i>
        </div>
      </div>
    </div>
    <div>
      <a href="#" class="nav-menu-item"><i class="fas fa-home me-3"></i>Beranda</a>
      <a href="katalog/" class="nav-menu-item"><i class="fas fa-store me-3"></i>Produk</a>
      <?php if (isset($_SESSION['user_akusara'])) : ?>
        <a href="keranjang/" class="nav-menu-item"><i class="fas fa-shopping-basket me-3"></i>Keranjang
          <span class="ms-3 start-100 translate-middle badge rounded-pill bg-warning">
            <?php if (isset($_SESSION['user_akusara'])) :  ?>
              <?php
              $zona = time() + (60 * 60 * 8);
              $tanggal = gmdate('Y-m-d H:i:s', $zona);
              $time = gmdate('H:i:s', $zona);
              $waktu = strtotime($tanggal);
              ?>
              <?php $whatsapp = $_SESSION['whatsapp']; ?>
              <?php $cek = query("SELECT COUNT(*) as jumlah FROM pesanan WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'")[0]; ?>
              <?= $cek['jumlah']; ?>
            <?php endif; ?>
          </span>
        </a>
        <a href="pesanan/" class="nav-menu-item"><i class="fas fa-receipt me-3"></i>Pesanan</a>
      <?php endif; ?>
      <?php if (isset($_SESSION['user_akusara'])) : ?>
        <a href="logout.php" class="nav-menu-item"><i class="fas fa-sign-out-alt me-3"></i>Logout</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- END HEADER -->