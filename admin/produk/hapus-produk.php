<?php 
require '../functions.php';

function hapus_produk($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM produk WHERE id = $id");

	return mysqli_affected_rows($conn);
}

$id = $_GET["id"];
if (hapus_produk($id) > 0 ) {
	echo "
		<script>
			document.location.href = 'index.php?success_hapus=true';
		</script>
	";
    } else {
	echo "
		<script>
			alert('Data gagal dihapus!');
			document.location.href = 'index.php';
		</script>
	";
	}
 ?>