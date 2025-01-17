<?php

session_start();
require '../functions.php';

function upload()
{
  $namaFile = $_FILES['file']['name'];
  $ukuranFile = $_FILES['file']['size'];
  $error = $_FILES['file']['error'];
  $tmpName = $_FILES['file']['tmp_name'];


  $ekstensifile = explode('.', $namaFile);
  $ekstensifile = strtolower(end($ekstensifile));

  // generate nama file baru
  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensifile;
  move_uploaded_file($tmpName, '../../foto/' . $namaFileBaru);

  return $namaFileBaru;
}



if (!isset($_SESSION["super_userxxx"])) {
  echo "<script>
    window.location.href='../login.php';
  </script>";
  exit;
}

function tambah_produk($data)
{
  global $conn;

  // htmlspecialchars berfungsi untuk tidak menjalankan script
  $harga = htmlspecialchars($data["harga"]);
  $nama = htmlspecialchars($data["nama"]);
  $deskripsi = htmlspecialchars($data["deskripsi"]);
  $kategori = htmlspecialchars($data["kategori"]);
  $diskon = htmlspecialchars($data["diskon"]);
  $stock = htmlspecialchars($data["stock"]);
  $foto = upload();


  mysqli_query($conn, "INSERT INTO produk VALUES(NULl, '$nama', '$deskripsi', '$kategori', '$harga', '$diskon', '$stock', '$foto')");
  return mysqli_affected_rows($conn);
}

function edit_produk($data)
{
  global $conn;

  // htmlspecialchars berfungsi untuk tidak menjalankan script
  $id = htmlspecialchars($data["id"]);
  $harga = htmlspecialchars($data["harga"]);
  $nama = htmlspecialchars($data["nama"]);
  $deskripsi = htmlspecialchars($data["deskripsi"]);
  $kategori = htmlspecialchars($data["kategori"]);
  $diskon = htmlspecialchars($data["diskon"]);
  $stock = htmlspecialchars($data["stock"]);

  // Cek image empty or not
  if (isset($_FILES['file']) && empty($_FILES['file']['name'])) {
    mysqli_query($conn, "UPDATE produk SET nama = '$nama', deskripsi = '$deskripsi', kategori = '$kategori', harga = '$harga', diskon = '$diskon', stock = '$stock' WHERE id = $id");
    return mysqli_affected_rows($conn);
  } else {
    $foto = upload();
    mysqli_query($conn, "UPDATE produk SET nama = '$nama', deskripsi = '$deskripsi', kategori = '$kategori', harga = '$harga', diskon = '$diskon', stock = '$stock', foto = '$foto' WHERE id = $id");
    return mysqli_affected_rows($conn);
  }
}

if (isset($_POST["register"])) {

  if (tambah_produk($_POST) > 0) {
    $success = true;
  } else {
    echo mysqli_error($conn);
  }
}

if (isset($_POST["edit"])) {

  if (edit_produk($_POST) > 0) {
    $successEdit = true;
  } else {
    echo mysqli_error($conn);
  }
}

function cari($keyword)
{
  $query = "SELECT * FROM produk
                WHERE
              nama LIKE '%$keyword%' OR
              kategori LIKE '%$keyword%'
            ";
  return query($query);
}

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");

if (empty($produk)) {
  $not_found = true;
}


// jika tombol cari di tekan
if (isset($_POST["cari"])) {
  $produk = cari($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'link.php'; ?>
  <script>
    $(document).ready(function() {

      $("#form").hide();

      $("#btn-show").click(function() {
        $("#form").show();
      })

      $("#btn-hide").click(function() {
        $("#form").hide();
      })

    });
  </script>
  <style>
    td,
    th {
      padding: 20px !important;
    }
  </style>
</head>

<body>

  <?php if (isset($_GET['edit'])) : ?>

    <script type='text/javascript'>
      $(document).ready(function() {
        $('#edit<?= $_GET["id"]; ?>').modal('show');
      });
    </script>

  <?php endif; ?>


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
        title: 'Berhasil Menambah Produk'
      })
    </script>
  <?php endif; ?>

  <?php if (isset($successEdit)) : ?>
    <script>
      window.location.href = "index.php?editSuccess=true";
    </script>
  <?php endif; ?>

  <?php if (isset($_GET['editSuccess'])) : ?>
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
        title: 'Berhasil Edit Produk'
      })
    </script>
  <?php endif; ?>

  <?php if (isset($_GET['success_hapus'])) : ?>
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
        title: 'Berhasil Hapus Produk'
      })
    </script>
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

    <!-- ========== tab components start ========== -->
    <section class="tab-components">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2>Kelola Produk</h2>
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
                      Kelola Produk
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->
          <a id="btn-show" class="btn btn-info text-white mb-3"><i class="fas fa-plus-square"></i> Tambah Produk</a>
        </div>

        <div class="my-4">
          <form action="" method="post" class="d-flex">
            <input type="search" name="keyword" id="keyword" style="width:50%;margin-right: 20px;" autocomplete="off" placeholder="Cari produk disini..." class="form-control">
            <button type="submit" name="cari" class="badge bg-info px-3" style="outline:0;border:0;">Cari</button>
          </form>
        </div>
        <!-- ========== title-wrapper end ========== -->

        <!-- ========== form-elements-wrapper start ========== -->
        <div class="form-elements-wrapper">
          <div class="row">
            <div class="col">
              <!-- input style start -->
              <form id="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-style mb-30">
                  <p align="right">
                    <a href="#" id="btn-hide" class="btn btn-close"></a>
                  </p>
                  <div class="input-style-1">
                    <div class="input-style-1">
                      <label>Tipe Produk</label>
                      <input type="text" name="kategori" required />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-style-1">
                        <label>Nama Produk</label>
                        <input type="text" name="nama" required />
                      </div>
                    </div>
                    <div class="col">
                      <div class="input-style-1">
                        <label>Deskripsi</label>
                        <input type="text" name="deskripsi" required />
                      </div>
                    </div>
                    <div class="input-style-1">
                      <label>Harga Jual</label>
                      <input type="number" name="harga" required />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-style-1">
                        <label>Stock</label>
                        <input type="number" name="stock" required />
                      </div>
                    </div>
                    <div class="col">
                      <div class="input-style-1">
                        <label>Diskon (%)</label>
                        <input type="number" name="diskon" required />
                      </div>
                    </div>
                  </div>
                  <div class="input-style-1">
                    <label>Gambar produk</label>
                    <input type="file" name="file" required />
                  </div>
                  <button type="submit" name="register" class="
                        btn
                        primary-btn
                        btn-hover
                        w-100
                        text-center
                      ">
                    Tambah
                  </button>
              </form>
            </div>
            <!-- end card -->
            <!-- ======= input style end ======= -->



          </div>
          <!-- end col -->
        </div>
        <!-- end row -->
      </div>
      <!-- ========== form-elements-wrapper end ========== -->

      <div class="card p-3 table-responsive ">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Aksi</th>
              <th colspan="2" style="text-align: center;">Produk</th>
              <th>Deskripsi</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($produk as $m) : ?>
              <tr>
                <td>
                  <button type="button" class="badge bg-warning text-white" style="border: 0;outline: 0;" data-bs-toggle="modal" data-bs-target="#edit<?= $m['id']; ?>">
                    <i class="fas fa-edit"></i> Edit
                  </button>
                  <button type="button" class="badge bg-danger" style="border: 0;outline: 0;" data-bs-toggle="modal" data-bs-target="#del<?= $m['id']; ?>">
                    <i class="fas fa-trash-alt"></i> Delete
                  </button>
                </td>
                <td>
                  <img src="../../foto/<?= $m['foto']; ?>" width="150">
                </td>
                <td>
                  <?= $m['kategori']; ?> - <?= $m['nama']; ?>
                </td>
                <td><?= $m['deskripsi']; ?></td>
                <td>Rp<?= number_format($m['harga'], 0, ',', '.'); ?></td>
                <td><?= $m['diskon']; ?>%</td>
                <td><?= $m['stock']; ?></td>

              </tr>
          </tbody>

          <!-- Edit Modal -->
          <div class="modal fade" id="edit<?= $m['id']; ?>" tabindex="-1" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="edit">Edit produk</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="card-style mb-30">
                    <form action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?= $m['id']; ?>">
                      <div class="input-style-1">
                        <label>Kategori Produk</label>
                        <select name="kategori" class="form-control" required>
                          <option value="<?= $m['kategori']; ?>"><?= $m['kategori']; ?></option>
                          <option value="Single Origin">Single Origin</option>
                          <option value="Espresso Coffee">Espresso Coffee</option>
                          <option value="Coffee Tools">Coffee Tools</option>
                        </select>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-style-1">
                            <label>Nama Produk</label>
                            <input type="text" name="nama" value="<?= $m['nama']; ?>" placeholder="Contoh: Tenda Pavillo 4P" required />
                          </div>
                        </div>
                        <div class="col">
                          <div class="input-style-1">
                            <label>Deskripsi</label>
                            <input type="text" name="deskripsi" value="<?= $m['deskripsi']; ?>" required />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-style-1">
                            <label>Harga Jual</label>
                            <input type="number" name="harga" value="<?= $m['harga']; ?>" required />
                          </div>
                        </div>
                        <div class="col">
                          <div class="input-style-1">
                            <label>Stock</label>
                            <input type="number" name="stock" value="<?= $m['stock']; ?>" required />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-style-1">
                            <label>Diskon (%)</label>
                            <input type="number" name="diskon" value="<?= $m['diskon']; ?>" required />
                          </div>
                        </div>
                      </div>
                      <div class="input-style-1">
                        <label>Ubah Gambar Produk <small><b>*Hiraukan jika tidak ingin merubah</b></small></label>
                        <input type="file" name="file" />
                      </div>
                      <button type="submit" name="edit" class="
                                  btn
                                  primary-btn
                                  btn-hover
                                  w-100
                                  text-center
                                ">
                        Edit
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Delete Modal -->
          <div class="modal fade" id="del<?= $m['id']; ?>" tabindex="-1" aria-labelledby="del" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="del<?= $m['id']; ?>">Konfirmasi Delete Produk</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                  <center>
                    <p>Yakin menghapus produk secara permanen?</p>
                    <br>
                  </center>

                  <form action="" method="post" enctype="multipart/form-data">

                    <a href="hapus-produk.php?id=<?= $m['id']; ?>" class="btn btn-danger w-100 text-white">Ya, Hapus Permanen</a>
                  </form>
                </div>
              </div>
            </div>
          </div>

        <?php endforeach; ?>


        </table>
      </div>



      </div>
      <!-- end container -->
    </section>
    <!-- ========== tab components end ========== -->




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
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/Chart.min.js"></script>
  <script src="../assets/js/dynamic-pie-chart.js"></script>
  <script src="../assets/js/moment.min.js"></script>
  <script src="../assets/js/fullcalendar.js"></script>
  <script src="../assets/js/jvectormap.min.js"></script>
  <script src="../assets/js/world-merc.js"></script>
  <script src="../assets/js/polyfill.js"></script>
  <script src="../assets/js/main.js"></script>
</body>

</html>