<?php
session_start();

include 'functions.php';

$whatsapp = $_GET["whatsapp"];

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>

	<?php
	$cekuserPassword = mysqli_query($conn, "SELECT * FROM user WHERE whatsapp = '$whatsapp' AND password IS NOT NULL");
	?>


	<?php if (mysqli_num_rows($cekuserPassword) > 0) : ?>
		<div id="passwordInput">
			<label class="form-label mt-3 " for="password">Masukkan Password</label>
			<input type="password" class="form-control w-100" name="password" id="password" required>
		</div>
	<?php endif; ?>

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