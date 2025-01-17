<?php
session_start();

include 'functions.php';

if (!isset($_SESSION['user_akusara'])) {
	header("Location: ../");
	exit;
}


function paymentFun($data)
{
	global $conn;

	$zona_waktu = time() + (60 * 60 * 7);
	$tanggal = gmdate('Y-m-d H:i:s', $zona_waktu);
	$waktu = strtotime($tanggal);

	$file = upload_file();

	$lama_sewa = 0;
	$tanggal_ambil = $data['tanggal_ambil'];
	$tambah = "+$lama_sewa days";
	$tgl_kembali = date('Y-m-d', strtotime($tambah, strtotime($tanggal_ambil)));
	$whatsapp = $_SESSION['whatsapp'];

	$cek = mysqli_query($conn, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'");

	foreach ($cek as $min) {
		$id_produk_min = $min['id_produk'];
		$jumlah = $min['jumlah'];
		$query4 = "UPDATE produk SET 
			        stock = stock - $jumlah
			        WHERE id = $id_produk_min
			      ";
		mysqli_query($conn, $query4);
	}

	if ($lama_sewa > 2) {
		foreach ($cek as $key_cek) {
			$idproduk = $key_cek['id_produk'];
			$lama = $lama_sewa / 2;
			$harga_fix = $key_cek['harga'] * $lama;

			$query2 = "UPDATE pesanan SET 
			        harga = '$harga_fix'
			        WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu' AND id_produk = $idproduk
			      ";
			mysqli_query($conn, $query2);
		}
	}


	$query = "UPDATE pesanan SET 
        bukti_transfer = '$file',
        tanggal = '$tanggal_ambil',
        status = 'Pembayaran Sukses'
        WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'
      ";


	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

if (isset($_POST["add"])) {

	if (paymentFun($_POST) > 0) {

		$whatsapp = $_SESSION['whatsapp'];
		$file = upload_file();

		echo "<script>
				    window.location.href='../pesanan';
				    </script>";
		exit;
	} else {
		echo mysqli_error($conn);

		$file = upload_file();
		$whatsapp = $_SESSION['whatsapp'];

		echo "<script>
				    window.location.href='../pesanan';
				    </script>";
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<body>



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
					<div><strong><small>Amankan akun pian dengan membuat password<button type="button" data-bs-toggle="modal" data-bs-target="#createPasswordModal" style="background-color:transparent;outline: none;border: none;" class="text-info" href="profil/"> disini.</button style="background-color:transparent;"></small></strong></div>
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
				<?php // if (isset($_SESSION['user_akusara'])): 
				?>
				<!-- <a href="../profile" class="nav-menu-item"><i class="fas fa-user-circle me-3"></i>Akun</a> -->
				<?php // endif 
				?>
				<a href="../katalog" class="nav-menu-item"><i class="fas fa-store me-3"></i>Produk</a>
				<a href="#" class="nav-menu-item"><i class="fas fa-shopping-basket me-3"></i>Keranjang
					<span class="ms-3 start-100 translate-middle badge rounded-pill bg-warning">
						<?php
						$zona = time() + (60 * 60 * 7);
						$tanggal = gmdate('Y-m-d H:i:s', $zona);
						$time = gmdate('H:i:s', $zona);
						$waktu = strtotime($tanggal);
						?>
						<?php $whatsapp = $_SESSION['whatsapp']; ?>
						<?php $cek = query("SELECT COUNT(*) as jumlah FROM pesanan WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'")[0]; ?>
						<?= $cek['jumlah']; ?>
					</span>
				</a>
				<a href="../pesanan" class="nav-menu-item"><i class="fas fa-receipt me-3"></i>Pesanan</a>

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

				<!-- Order Details -->
				<div class="col">
					<form action="" id="checkoutForm" onsubmit="return cekpayment();" method="post" enctype="multipart/form-data">

						<div onclick="window.location.href='../katalog/'" style="cursor: pointer;" class="text-center btn mb-3 bg-info text-white">
							<span><i class="fas fa-long-arrow-alt-left"></i> Belanja Lagi</span>
						</div>
						<div class="text-center alert bg-warning text-white">
							<span> Keranjang</span>
						</div>
						<?php
						$zona_waktu = time() + (60 * 60 * 7);
						$tanggal = gmdate('Y-m-d H:i:s', $zona_waktu);
						$waktu = strtotime($tanggal);
						$max_tanggal = strtotime('+7 day', $zona_waktu);
						?>
						<input class="form-control" id="dateInput" type="hidden" value="<?= date('Y-m-d', $zona_waktu); ?>" name="tanggal_ambil" required>
						<script>
							$("#dateInput").validate();
						</script>

						<div id="container">

							<div class="order-summary">
								<div class="order-col">
									<div><strong>PRODUCT</strong></div>
									<div><strong>SUBTOTAL</strong></div>
								</div>
								<?php $keranjang = mysqli_query($conn, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'"); ?>
								<?php $total_harga = 0; ?>
								<?php $total_diskon = 0; ?>
								<?php foreach ($keranjang as $data) : ?>
									<div class="order-products">
										<div class="order-col">
											<div>
												<small>
													<?php $idp = $data['id_produk']; ?>
													<?php $produk = query("SELECT * FROM produk WHERE id = $idp")[0]; ?>
													<?= $produk['nama']; ?> - @<?= $data['jumlah']; ?> item
												</small>

												<small>
													<a href="hapus.php?id=<?= $data['id']; ?>" class="badge bg-danger"><i class="fas fa-times"></i> Hapus</a>
												</small>
											</div>
											<div>
												<small>
													<?php $diskon = $produk['harga'] * $produk['diskon'] / 100; ?>
													<?php $harga_setelah = $produk['harga'] - $diskon; ?>

													<?php $harga = $harga_setelah * $data['jumlah']; ?>
													<?php $harga_sebelum = $produk['harga'] * $data['jumlah']; ?>
													<span>Rp<?= number_format($harga, 0, ',', '.'); ?></span>
													<span style="text-decoration: line-through;font-size: 0.5rem">Rp<?= number_format($harga_sebelum, 0, ',', '.'); ?></span>
												</small>
											</div>
										</div>
										<small>Checkout Sebelum : <span class="badge bg-success text-white" style="font-size:0.7rem !important;"><?= $batas = date('d F Y - H:i', $data['akhir']); ?></span></small>
										<hr>
									</div>
									<?php error_reporting(0); ?>

									<?php $diskon_jumlah = $diskon * $data['jumlah']; ?>
									<?php $total_harga += $harga; ?>
									<?php $total_diskon += $diskon_jumlah; ?>
								<?php endforeach; ?>
								<div class="order-col">
									<div>Total Diskon</div>
									<div class="text-success">
										<small>
											<i class="fas fa-check-circle"></i>
											Rp<?= number_format($total_diskon, 0, ',', '.'); ?>
										</small>
									</div>

								</div>
								<div class="order-col">
									<div>Total Belanja</div>
									<?php $total = query("SELECT SUM(jumlah * harga) as biaya FROM pesanan WHERE whatsapp = '$whatsapp' AND status = 'Menunggu Pembayaran' AND akhir > '$waktu'")[0]; ?>
									<div><strong class="">
											<?php $sebelum = $total['biaya'] + $total_diskon; ?>
											<?php if ($total['biaya'] == NULL) {
												$total['biaya'] = 0;
											} ?>
											Rp<?= number_format($total['biaya'], 0, ',', '.'); ?>
											<span style="font-size:0.7rem;text-decoration: line-through;color: grey;">Rp<?= number_format($sebelum, 0, ',', '.'); ?></span>
										</strong></div>
								</div>
								<div class="pt-2" style="border-bottom: 1px dotted black;"></div>
							</div>
							<?php if ($total['biaya'] == 0 or $total['biaya'] == NULL or $total['biaya'] == "") : ?>
								<div class="alert alert-warning text-center">
									Yahh, keranjangmu masih kosong <i class="fas fa-frown"></i> <br>
								</div>
								<a class="btn btn-info text-white w-100" href="../katalog"><i class="fas fa-long-arrow-alt-left"></i> Belanja Sekarang Yuk</a>
							<?php else : ?>
								<p>Pilih Metode Pembayaran : </p>
								<div class="payment-method">
									<!-- <div class="input-radio">
									<input type="radio" name="payment" id="payment-2">
									<label for="payment-2">
										<span></span>
										QRIS Shopeepay
									</label>
									<div class="caption">
										<img src="../img/qris.jpeg" class="img-fluid" alt="Refresh Jika QRIS tidak muncul.">
									</div>
								</div> -->
									<div class="input-radio">
										<input type="radio" name="payment" id="payment-1">
										<label for="payment-1">
											<span></span>
											Transfer Bank
										</label>
										<div class="caption">
											<p class="mb-2">
												<span class="badge bg-warning">Atas Nama : Mochammad Dimas Agustia</span>
											<ul>
												<li>BCA : <u>6825 3144 86</u></li>
											</ul>
											</p>
											<p class="fw-bold">Total Pembayaran : <span class="text-success">Rp<?= number_format($total['biaya'], 0, ',', '.'); ?> </span></p>
										</div>
									</div>
									<!-- <div class="input-radio">
										<input type="radio" name="payment" id="payment-3">
										<label for="payment-3">
											<span></span>
											Gopay / OVO / Dana / ShopeePay
										</label>
										<div class="caption">
											<span class="badge bg-warning mb-2">Atas Nama : TOKO MOTOR</span>
											<p>Nomor Handphone : <br><u>NOMOR</u></p>
											<p class="fw-bold">Total Pembayaran : <span class="text-success">Rp<?= number_format($total['biaya'], 0, ',', '.'); ?> </span></p>
										</div>
									</div> -->

								</div>
						</div>
						<hr>

						<label class="form-label">Upload Bukti Pembayaran <i class="fas fa-long-arrow-alt-down"></i></label>
						<br>
						<input type="file" name="file" id="file">
						<hr>
						<br>
						<button type="submit" name="add" class="primary-btn order-submit w-100">
							<span class="spinner-border spinner-border-sm" id="loader" style="visibility: hidden;" role="status" aria-hidden="true"></span> CHECKOUT
						</button>
					</form>


					<script>
						function cekpayment() {

							var file = document.getElementById('file');

							var filePath = file.value;
							var allowedExtensions =
								/(\.jpg|\.pdf|\.JPG|\.HEIC|\.heic|\.jpeg|\.JPEG|\.png|\.PNG)$/i;

							if (filePath != '') {

								if (!allowedExtensions.exec(filePath)) {
									Swal.fire({
										icon: 'warning',
										toast: true,
										text: 'Jangan upload file selain bukti transfer yaa....',
										showConfirmButton: true,
										timer: 5000
									});
									return false;
								}

							}

							if (file.value == '') {
								Swal.fire({
									icon: 'warning',
									toast: true,
									text: 'Mohon isi bukti transfer nya yaa...',
									showConfirmButton: true,
									timer: 5000
								});
								file.focus();
								return false;
							}

							if (file.value != '' && filePath != '' && allowedExtensions.exec(filePath)) {
								$('#loader').css('visibility', 'visible');
							}

						}
					</script>

				<?php endif; ?>
				</div>
			</div>

		</div>


	</div>
	<!-- /Order Details -->
	</div>
	<!-- /row -->
	</div>
	<!-- /container -->
	</div>
	<!-- /SECTION -->

	<?php include '../footer.php'; ?>


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

</body>

</html>