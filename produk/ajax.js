// ambil elemen2 yang dibutuhkan
var whatsapp = document.getElementById('whatsapp');
var passwordInput = document.getElementById('passwordInput');


	// tambahkan event ketika filter ditulis
	$(document).on('keyup', '#whatsapp', function() {   

	// buat object ajax
	var xhr = new XMLHttpRequest();	

	// cek kesiapan ajax
	xhr.onreadystatechange = function() {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			passwordInput.innerHTML = xhr.responseText;
		}
	}

	// eksekusi ajax
	xhr.open('GET', 'ajax.php?whatsapp=' + whatsapp.value, true);
	xhr.send();

	});

