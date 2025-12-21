<?php
require_once 'config.php';

$id = (int)$_GET['id'];
$sql = "DELETE FROM anggota WHERE id_anggota='$id'";

if (mysqli_query($conn, $sql)) {
    header('Location: index.php?status=sukses-hapus');
} else {
    echo"Gagal hapus" . mysqli_error( $conn );
}
?>