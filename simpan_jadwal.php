<?php
require_once 'config.php';

if (isset($_GET['id_kelas']) && isset($_GET['p']) && isset($_GET['tgl'])) {
    
    $id_kelas = (int)$_GET['id_kelas'];
    $p_ke = (int)$_GET['p'];
    $tanggal = mysqli_real_escape_string($conn, $_GET['tgl']);

    $cek_jadwal = mysqli_query($conn, "SELECT id_jadwal FROM jadwal_pertemuan 
                                      WHERE id_kelas = $id_kelas AND pertemuan_ke = $p_ke");

    if (mysqli_num_rows($cek_jadwal) > 0) {
        $sql = "UPDATE jadwal_pertemuan SET tanggal = '$tanggal' 
                WHERE id_kelas = $id_kelas AND pertemuan_ke = $p_ke";
    } else {
        $sql = "INSERT INTO jadwal_pertemuan (id_kelas, pertemuan_ke, tanggal) 
                VALUES ($id_kelas, $p_ke, '$tanggal')";
    }

    $query = mysqli_query($conn, $sql);

    if ($query) {
        header("Location: absensi_baru.php?id_kelas=$id_kelas&status=sukses-jadwal");
    } else {
        echo "Gagal menyimpan jadwal: " . mysqli_error($conn);
    }

} else {
    header('Location: index.php');
}
?>