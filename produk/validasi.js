function cekLogin() {
	let formatSalah = document.getElementById("formatSalah");
	let jumlahSalah = document.getElementById("jumlahSalah");
	let whatsapp = document.forms["loginForm"]["whatsapp"];

	if (whatsapp.value == "") {
		Swal.fire({
		  position: 'middle',
		  toast: true,
		  icon: 'warning',
		  title: '<span class="text-warning">Harap mengisi nomor whatsapp sebelum melanjutkan</span>',
		  showConfirmButton: true,
		  timer: 3000
		});
		whatsapp.classList.add("border-danger");
		return false;
	}

	else if (whatsapp.value[0] != 6) {
		jumlahSalah.classList.add("hidden");
		formatSalah.classList.remove("hidden");
		whatsapp.classList.add("border-danger");
		Swal.fire({
		  position: 'middle',
		  toast: true,
		  icon: 'error',
		  title: 'Maaf, Nomor harus diawali dengan format <span class="text-success">62</span>, contoh : 6289577679982',
		  showConfirmButton: true,
		  timer: 3000
		});
		return false;
	}

	if (whatsapp.value.length < 11) {
		formatSalah.classList.add("hidden");
		jumlahSalah.classList.remove("hidden");
		whatsapp.classList.add("border-danger");
		Swal.fire({
		  position: 'middle',
		  toast: true,
		  icon: 'warning',
		  title: '<span class="text-warning">Harap mengisi nomor whatsapp dengan benar! (11 s/d 16 digit)</span>',
		  showConfirmButton: true,
		  timer: 3000
		});
		return false;
	} else if (whatsapp.value.length > 16) {
		whatsapp.classList.add("border-danger");
		Swal.fire({
		  position: 'middle',
		  toast: true,
		  icon: 'warning',
		  title: '<span class="text-warning">Harap mengisi nomor whatsapp dengan benar! (11 s/d 16 digit)</span>',
		  showConfirmButton: true,
		  timer: 3000
		});
		return false;
	}

	
}
