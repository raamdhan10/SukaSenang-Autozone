<?php
session_start();
require "functions.php";

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>
	<?php include 'header.php'; ?>

	<!-- SECTION -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<!-- shop -->
				<div class="col-md-4 col-xs-6">
					<div class="shop">
						<div class="shop-img" style="padding-bottom: 200px;">
						</div>
						<div class="shop-body">
							<h3>Motor Baru</h3>
							<a href="katalog/" class="cta-btn">Lihat Produk <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- /shop -->

				<!-- shop -->
				<div class="col-md-4 col-xs-6">
					<div class="shop">
						<div class="shop-img" style="padding-bottom: 200px;">
						</div>
						<div class="shop-body">
							<h3>Sparepart</h3>
							<a href="katalog/" class="cta-btn">Lihat Produk <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- /shop -->

				<!-- shop -->
				<div class="col-md-4 col-xs-6">
					<div class="shop">
						<div class="shop-img" style="padding-bottom: 200px;">
						</div>
						<div class="shop-body">
							<h3>Lainnya</h3>
							<a href="katalog/" class="cta-btn">Lihat Produk <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- /shop -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /SECTION -->

	<!-- SECTION -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">

				<!-- section title -->
				<div class="col-md-12">
					<div class="section-title">
						<h3 class="title">Rekomendasi Produk untuk kamu</h3>
						<!-- <div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab1">Tenda</a></li>
									<li><a data-toggle="tab" href="#tab1">Tas Carrier</a></li>
									<li><a data-toggle="tab" href="#tab1">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab1">Accessories</a></li>
								</ul>
							</div> -->
					</div>
				</div>
				<!-- /section title -->

				<!-- Products tab & slick -->
				<div class="col-md-12">
					<div class="row">
						<div class="products-tabs">
							<!-- tab -->
							<div id="tab1" class="tab-pane active">
								<div class="products-slick" data-nav="#slick-nav-1">
									<?php $produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY rand() LIMIT 7"); ?>
									<?php foreach ($produk as $m) : ?>
										<!-- product -->
										<?php $id = $m['id']; ?>
										<div class="product" onclick="window.location.href='produk/?id=<?= $id; ?>';">
											<div class="product-img">
												<img src="./foto/<?= $m['foto']; ?>" alt="">
												<div class="product-label">
													<?php if ($m['diskon'] > 0) : ?>
														<span class="sale">-<?= $m['diskon']; ?>%</span>
													<?php endif; ?>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category"><?= $m['kategori']; ?></p>
												<h3 class="product-name"><?= $m['nama']; ?></h3>
												<h4 class="product-price">
													<?php $diskon = $m['harga'] * $m['diskon'] / 100; ?>
													<?php $harga = $m['harga'] - $diskon; ?>
													Rp<?= number_format($harga, 0, ',', '.'); ?>
													<del class="product-old-price">Rp<?= number_format($m['harga'], 0, ',', '.'); ?></del>
												</h4>
											</div>
										</div>
										<!-- /product -->
									<?php endforeach; ?>



								</div>
								<div id="slick-nav-1" class="products-slick-nav"></div>
							</div>
							<!-- /tab -->
						</div>
					</div>
				</div>
				<!-- Products tab & slick -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /SECTION -->

	<!-- MAPS SECTION -->
	<div class="section mt-5">
		<!-- container -->
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126916.09794862253!2d106.77001346703047!3d-6.246850388054725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ed224cb0802b%3A0x2f7ff7981b8c00fe!2sNavy&#39;s%20Motor!5e0!3m2!1sid!2sid!4v1683637673540!5m2!1sid!2sid" class="w-100" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" height="450" style="border:0;width: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		<!-- /container -->
	</div>
	<!-- /MAPS SECTION -->





	<?php include 'footer.php'; ?>

	<!-- jQuery Plugins -->
	<script src="js/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.zoom.min.js"></script>
	<script src="js/main.js"></script>

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