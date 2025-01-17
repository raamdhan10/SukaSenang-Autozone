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
