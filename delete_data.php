<?php
require_once 'config.php';

if ( isset($_GET['id_anggota']) ) {

    $id = $_GET['id_anggota'];
    $sql = "DELETE FROM anggota WHERE id=$id";
    $query = mysqli_query($conn, $sql);

    if ( $query ) {
        header('Location: pesertaa.php?status=sukses-hapus');
    } else {
        die("Gagal menghapus data...");
    }

} else {
    die("Akses dilarang...");
}
?>