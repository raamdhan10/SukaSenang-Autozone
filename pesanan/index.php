<?php
session_start();

include 'functions.php';

if (!isset($_SESSION['user_akusara'])) {
	header("Location: ../");
	exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<?php if (isset($success)) : ?>

	<script>
		Swal.fire({
			icon: 'success',
			title: 'Berhasil',
			text: 'Silahkan cek pesanan anda',
			showConfirmButton: true,
			timer: 3000
		});
	</script>

<?php endif; ?>

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
				<a href="../keranjang" class="nav-menu-item"><i class="fas fa-shopping-basket me-3"></i>Keranjang
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
				<a href="#" class="nav-menu-item"><i class="fas fa-receipt me-3"></i>Pesanan</a>

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
					<div class="section-title text-center alert bg-warning text-white">
						<span> Riwayat Pesanan</span>
					</div>
					<div class="order-summary">
						<?php $pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' AND status != 'Menunggu Pembayaran'"); ?>
						<?php foreach ($pesanan as $data) : ?>
							<div class="card p-3 my-2 shadow">
								<div style="border-bottom: 2px dotted black;">
									<p style="font-size:0.8rem;">Tanggal Pemesanan : <?= date('d F Y', strtotime($data['waktu'])); ?></p>
								</div>
								<div class="order-col">
									<div><strong>PRODUCT</strong></div>
									<div><strong>SUBTOTAL</strong></div>
								</div>
								<?php $total_harga = 0; ?>
								<?php $total_diskon = 0; ?>
								<div class="order-products">
									<?php $kode_pesanan = $data['kode_pesanan']; ?>
									<?php $detail_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' AND status != 'Menunggu Pembayaran' AND kode_pesanan = '$kode_pesanan' "); ?>
									<?php foreach ($detail_pesanan as $detail) : ?>
										<?php $kode = $detail['kode_pesanan']; ?>
										<div class="order-col">
											<div>
												<small>
													<?php $idp = $detail['id_produk']; ?>
													<?php $produk = query("SELECT * FROM produk WHERE id = $idp")[0]; ?>
													<?= $produk['nama']; ?> &nbsp;
													X <?= $detail['jumlah']; ?>
												</small>
											</div>
											<div>
												<small>
													<?php $harga_fix = $detail['harga'] * $detail['jumlah']; ?>
													<span>Rp<?= number_format($harga_fix, 0, ',', '.'); ?></span>
												</small>
											</div>
										</div>
									<?php endforeach; ?>
									<div class="order-col">
										<div class="text-success fw-bold">Total Belanja</div>
										<?php $total = query("SELECT SUM(jumlah * harga) as biaya FROM pesanan WHERE whatsapp = '$whatsapp' AND kode_pesanan = '$kode' AND status != 'Menunggu Pembayaran'")[0]; ?>
										<div class="text-success fw-bold"><strong class="">
												Rp<?= number_format($total['biaya'], 0, ',', '.'); ?>
											</strong></div>
									</div>
									<div style="border-top: 2px dotted #474747;" class="mt-3 pt-3">
										<span class="badge bg-success">Tanggal Pemesanan : <?= date('d F Y', strtotime($data['tanggal'])); ?></span>
										<br>
										<span style="font-size:0.8rem;">Bukti Transfer : </span><a download="" class="badge bg-dark text-white" href="../bukti_transfer/<?= $data['bukti_transfer']; ?>">Download <i class="fas fa-download"></i></a>
									</div>
								</div>
							</div>

						<?php endforeach; ?>

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