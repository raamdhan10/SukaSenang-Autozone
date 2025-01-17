<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "motor");


function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  };
  return $rows;
};

function tambah_sewa($data)
{
  global $conn;
  $nama = htmlspecialchars($data["nama"]);
  $deskripsi = htmlspecialchars($data["deskripsi"]);
  $kategori = htmlspecialchars($data["kategori"]);
  $harga = htmlspecialchars($data["harga"]);
  $diskon = htmlspecialchars($data["diskon"]);
  $biaya_admin = htmlspecialchars($data["biaya_admin"]);
  $biaya_cuci = htmlspecialchars($data["biaya_cuci"]);
  $stock = htmlspecialchars($data["stock"]);
  $foto = upload_foto();
  $kepemilikan = htmlspecialchars($data["kepemilikan"]);

  mysqli_query($conn, "INSERT INTO produk_sewa VALUES(NULl, '$nama', '$deskripsi', '$kategori', '$harga', '$diskon', '$biaya_admin', '$biaya_cuci', '$stock', '$foto', '$kepemilikan')");
  return mysqli_affected_rows($conn);
}

function edit_sewa($data)
{
  global $conn;

  $id = $data["id"];
  $nama = $data["nama"];
  $deskripsi = $data["deskripsi"];
  $kategori = $data["kategori"];
  $harga = $data["harga"];
  $diskon = $data["diskon"];
  $biaya_admin = $data["biaya_admin"];
  $biaya_cuci = $data["biaya_cuci"];
  $stock = $data["stock"];
  $kepemilikan = $data["kepemilikan"];

  $query = "UPDATE produk_sewa SET 
                nama = '$nama',
                deskripsi = '$deskripsi',
                kategori = '$kategori',
                harga = '$harga',
                diskon = '$diskon',
                biaya_admin = '$biaya_admin',
                biaya_cuci = '$biaya_cuci',
                stock = '$stock',
                kepemilikan = '$kepemilikan'
              WHERE id = $id
            ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function edit_gambar_sewa($data)
{
  global $conn;

  $id = $data["id"];
  $foto = upload_foto();

  $query = "UPDATE produk_sewa SET 
                foto = '$foto'
              WHERE id = $id
            ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function upload_foto()
{
  $namaFile = $_FILES['foto']['name'];
  $ukuranFile = $_FILES['foto']['size'];
  $error = $_FILES['foto']['error'];
  $tmpName = $_FILES['foto']['tmp_name'];


  $ekstensifile = explode('.', $namaFile);
  $ekstensifile = strtolower(end($ekstensifile));

  // generate nama file baru
  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensifile;
  move_uploaded_file($tmpName, 'foto/' . $namaFileBaru);

  return $namaFileBaru;
}
