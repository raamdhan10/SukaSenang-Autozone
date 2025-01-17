<?php
session_start();

include 'functions.php';

$zona_waktu = time() + (60 * 60 * 8);
$tanggal_sekarang = gmdate('Y-m-d H:i:s', $zona_waktu);
$time_sekarang = gmdate('H:i:s', $zona_waktu);
$waktu_sekarang = strtotime($tanggal_sekarang);

if (!isset($_GET['id'])) {
	echo "<script>
         window.location.replace('../');
       </script>";
	exit;
}

$id = $_GET['id'];
$produk = query("SELECT * FROM produk WHERE id = $id")[0];

function addKeranjang($data)
{
	global $conn;
	global $produk;

	$zona = time() + (60 * 60 * 8);
	$tanggal = gmdate('Y-m-d H:i:s', $zona);
	$time = gmdate('H:i:s', $zona);
	$waktu = strtotime($tanggal);

	$awal = $waktu;
	$akhir = $waktu + 1800;



	// htmlspecialchars berfungsi untuk tidak menjalankan script
	$kode = htmlspecialchars($data["kode_pesanan"]);
	$jumlah = htmlspecialchars($data["jumlah"]);
	$harga = htmlspecialchars($data["harga"]);
	$whatsapp = htmlspecialchars($data["whatsapp"]);
	$id_produk = htmlspecialchars($data["id_produk"]);
	$status = htmlspecialchars($data["status"]);
	$stock_produk = $produk['stock'];

	$cek_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE  id_produk = '$id_produk' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'");

	$cek_pesanan2 = mysqli_query($conn, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'");
	foreach ($cek_pesanan2 as $key2) {
		// code...
	}
	$hasil_cek2 = mysqli_num_rows($cek_pesanan2);


	$hasil_cek = mysqli_num_rows($cek_pesanan);
	if ($hasil_cek > 0) {
		$hitung_pesanan = query("SELECT SUM(jumlah) as jumlah FROM pesanan WHERE id_produk = '$id_produk' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'")[0];
		$stock = $stock_produk - $hitung_pesanan['jumlah'];
	} else {
		$stock = $stock_produk;
	}

	if ($hasil_cek2 > 0) {
		$kode_pesanan = $key2['kode_pesanan'];
	} else {
		$kode_pesanan = $kode;
	}


	if ($stock < $jumlah) {
		echo "<script>
					    window.location.href='index.php?kurang&id=" . $id_produk . "'
					    </script>";
		exit;
	}

	if ($jumlah < 1) {
		echo "<script>
				    alert('Minimal Pemesanan 1pcs');
				    window.location.href='index.php?id=" . $id_produk . "'
				    </script>";
		exit;
	}


	mysqli_query($conn, "INSERT INTO pesanan VALUES(NULL, '$kode_pesanan', '$whatsapp', '$id_produk', '$jumlah', '$harga', NULL, NULL, '$awal', '$akhir', NULL, '$status')");
	return mysqli_affected_rows($conn);
}

if (isset($_POST["add"])) {

	if (addKeranjang($_POST) > 0) {
		echo "<script>window.location.href='../keranjang';</script>";
		exit;
	} else {
		echo mysqli_error($conn);
	}
}

if (isset($_POST["login"])) {

	$whatsapp = mysqli_real_escape_string($conn, $_POST["whatsapp"]);

	$result = mysqli_query($conn, "SELECT * FROM user WHERE whatsapp = '$whatsapp'");

	if (mysqli_num_rows($result) > 0) {

		$cekuser2 = "SELECT * FROM user where whatsapp = '$whatsapp' AND password IS NOT NULL";
		$prosescek2 = mysqli_query($conn, $cekuser2);

		if (mysqli_num_rows($prosescek2) > 0) {
			$password = mysqli_real_escape_string($conn, $_POST["password"]);

			$row = mysqli_fetch_assoc($prosescek2);

			if (password_verify($password, $row["password"])) {
				$_SESSION["user_akusara"] = true;
				$_SESSION["whatsapp"] = $whatsapp;
				echo "<script>
              	window.location.href='?id=" . $id . "';
              </script>";
				exit;
			} else {
				$pwdError = true;
			}
		} else {

			$_SESSION["user_akusara"] = true;
			$_SESSION["whatsapp"] = $whatsapp;

			echo "<script>
        	window.location.href='?id=" . $id . "';
        </script>";
			exit;
		}
	} else {

		function registrasi($data)
		{
			global $conn;

			$whatsapp = mysqli_real_escape_string($conn, $data["whatsapp"]);

			// Masukkan Data ke Database
			mysqli_query($conn, "INSERT INTO user VALUES('$whatsapp', NULL, NULL)");
			return mysqli_affected_rows($conn);
		}


		if (registrasi($_POST) > 0) {
			$_SESSION["user_akusara"] = true;
			$_SESSION["whatsapp"] = $whatsapp;
			echo "<script>
                	window.location.href='?id=" . $id . "';
                </script>";
			exit;
		} else {
			echo mysqli_error($conn);
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>

	<?php if (isset($_GET['kurang'])) : ?>

		<script>
			Swal.fire({
				icon: 'warning',
				title: 'Wahh, Maaf',
				text: 'stock tidak mencukupi. kurangi jumlah pesanan nya yaa',
				showConfirmButton: true,
				timer: 5000
			});
		</script>

	<?php endif; ?>

	<?php if (isset($pwdError)) : ?>

		<script>
			Swal.fire({
				icon: 'warning',
				title: 'Maaf',
				text: 'Password yang anda masukkan salah..',
				showConfirmButton: true,
				timer: 5000
			});
		</script>

	<?php endif; ?>

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
				<a href="../" class="nav-menu-item"><i class="fas fa-home me-3"></i>Beranda</a>
				<a href="../katalog" class="nav-menu-item"><i class="fas fa-store me-3"></i>Produk</a>
				<a href="../keranjang/" class="nav-menu-item"><i class="fas fa-shopping-basket me-3"></i>Keranjang
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
				<a href="../pesanan/" class="nav-menu-item"><i class="fas fa-receipt me-3"></i>Pesanan</a>
				<?php if (isset($_SESSION['user_akusara'])) : ?>
					<a href="../logout.php" class="nav-menu-item"><i class="fas fa-sign-out-alt me-3"></i>Logout</a>
				<?php endif ?>
			</div>
		</div>
	</div>
	<!-- END HEADER -->


	<!-- SECTION -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<!-- Product main img -->
				<div class="col-md-6">
					<div id="product-main-img">
						<div class="product-preview" style="cursor: pointer;">
							<img src="../foto/<?= $produk['foto']; ?>">
						</div>
					</div>
				</div>
				<!-- /Product main img -->


				<!-- Product details -->
				<div class="col-md-6">
					<div class="product-details">
						<h2 class="product-name"><?= $produk['nama']; ?>
							<?php $diskon = $produk['harga'] * $produk['diskon'] / 100; ?>
							<?php $harga = $produk['harga'] - $diskon; ?>
						</h2>
						<div>
							<h3 class="product-price">Rp<?= number_format($harga, 0, ',', '.'); ?>
								<del class="product-old-price">Rp<?= number_format($produk['harga'], 0, ',', '.'); ?></del>
							</h3>
						</div>
						<p><?= $produk['deskripsi']; ?></p>
						<p>
							<?php $stock_produk = $produk['stock']; ?>
							<?php $cek_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_produk = '$id' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu_sekarang'"); ?>
							<?php $cek2 = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_produk = '$id' AND status = 'Pembayaran Sukses'"); ?>
							<?php $hasil_cek = mysqli_num_rows($cek_pesanan); ?>
							<?php $hasil_cek2 = mysqli_num_rows($cek2); ?>
							<?php if ($hasil_cek > 0 or $hasil_cek2 > 0) {
								$hitung_pesanan = query("SELECT SUM(jumlah) as jumlah FROM pesanan WHERE id_produk = '$id' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu_sekarang'")[0];
								$hitung_pesanan2 = query("SELECT SUM(jumlah) as jumlah FROM pesanan WHERE id_produk = '$id' AND status = 'Pembayaran Sukses'")[0];
								$stock = $stock_produk - $hitung_pesanan['jumlah'];
							} else {
								$stock = $stock_produk;
							}
							?>
							<?php if ($stock > 0) : ?>
								<u>Stock</u> : <span class="text-success">
									<?= $stock; ?> item
								<?php else : ?>
									<span class="badge bg-danger text-white">MAAF, STOCK PRODUK HABIS.</span>
								<?php endif; ?>
								</span>
						</p>

						<?php if ($stock > 0 and isset($_SESSION['whatsapp'])) : ?>

							<div class="add-to-cart">
								<form action="" method="post">
									<?php
									function getRandomString($n)
									{
										$characters = 'abcdefghijklmnopqrstuvwxyz';
										$randomString = '';

										for ($i = 0; $i < $n; $i++) {
											$index = rand(0, strlen($characters) - 1);
											$randomString .= $characters[$index];
										}

										return $randomString;
									}
									$whatsapp = $_SESSION['whatsapp'];
									$id_unik = $whatsapp - rand(1, 10000);
									$n = rand(5, 10);

									$kode_pesanan = $id_unik . getRandomString($n);
									?>
									<input type="hidden" name="kode_pesanan" value="<?= $kode_pesanan; ?>" required>
									<input type="hidden" name="whatsapp" value="<?= $whatsapp; ?>" required>
									<input type="hidden" name="id_produk" value="<?= $id; ?>" required>
									<input type="hidden" name="harga" value="<?= $harga; ?>" required>
									<input type="hidden" name="status" value="Menunggu Pembayaran" required>



									<div class="qty-label mb-3">
										<div class="input-number">
											<input type="number" name="jumlah" value="1" required>
											<span class="qty-up">+</span>
											<span class="qty-down">-</span>
										</div>
									</div>
									<?php if (isset($_SESSION['whatsapp'])) : ?>

										<button type="submit" name="add" class="btn-info btn text-white"><i class="fas fa-cart-plus"></i> Keranjang</button>

								</form>

							<?php endif; ?>
							</div>

						<?php endif; ?>


						<ul class="product-links">
							<li>Tipe :</li>
							<li><a href="../katalog/?kategori=<?= $produk['kategori']; ?>"><?= $produk['kategori']; ?></a></li>
						</ul>

					</div>
				</div>
				<!-- /Product details -->

			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /SECTION -->



	<?php if (!isset($_SESSION['whatsapp'])) : ?>
		<script type="text/javascript">
			$(window).on('load', function() {
				$('#loginModal').modal('show');
			});
		</script>

		<!-- Modal -->
		<div class="modal fade" data-bs-backdrop="static" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-fullscreen-sm-down modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="loginModalLabel">Kenalan dulu yuk.. <i class="far fa-grin-wink"></i></h5>
					</div>
					<div class="modal-body">
						<form name="loginForm" action="" method="POST" onsubmit="return cekLogin();">

							<label for="">Nomor Handphone / Whatsapp</label>
							<span class="badge bg-warning mb-3">Harap menggunakan format <b>62</b></span>

							<input type="number" class="form-control w-100" name="whatsapp" id="whatsapp" placeholder="Contoh : 628961125123">
							<div id="formatSalah" class="hidden">
								<span class="text-danger ps-2" style="font-size: 0.8rem;">Maaf, awali dengan format <b>62</b>, bukan dengan <b>0</b>.</span>
							</div>
							<div id="jumlahSalah" class="hidden">
								<span class="text-danger ps-2" style="font-size: 0.8rem;">Harap mengisi nomor whatsapp dengan benar! (11 s/d 16 digit).</span>
							</div>

							<div id="passwordInput">

							</div>
					</div>
					<div class="modal-footer">
						<button type="submit" name="login" class="btn bg-dark text-white w-100">Lanjut <i class="fas fa fa-arrow-circle-right"></i></button>
					</div>

					</form>

				</div>
			</div>
		</div>
	<?php endif; ?>

	<!-- tombol chat -->
	<a href="#" class="btn btn-primary position-fixed bottom-0 start-0 mb-3 ms-3">
		<i class="fas fa-comment fa-lg"></i>
	</a>

	<!-- jQuery Plugins -->
	<script src="../js/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script src="../js/slick.min.js"></script>
	<script src="../js/nouislider.min.js"></script>
	<script src="../js/jquery.zoom.min.js"></script>
	<script src="../js/main.js"></script>
	<script src="ajax.js"></script>

	<script>
		var menuHolder = document.getElementById('menuHolder')
		var siteBrand = document.getElementById('siteBrand')

		function menuToggle() {
			if (menuHolder.className === "drawMenu") menuHolder.className = ""
			else menuHolder.className = "drawMenu"
		}
		if (window.innerWidth < 426) siteBrand.innerHTML = "TOKO MOTOR"
		window.onresize = function() {
			if (window.innerWidth < 420) siteBrand.innerHTML = "TOKO MOTOR"
			else siteBrand.innerHTML = "TOKO MOTOR"
		}
	</script>

	<script src="validasi.js"></script>

</body>

</html>