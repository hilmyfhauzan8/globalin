<?php
include_once 'config.php';

if ( isset($_GET['id']) ) {

    $id = $_GET['id'];
    $sql = "DELETE FROM peserta WHERE id=$id";
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