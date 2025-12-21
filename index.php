<?php

require_once 'config.php';

$sql = 'SELECT a.*, k.nama_kelas FROM anggota as a
        LEFT JOIN kelas as k ON a.id_kelas = k.id_kelas
        GROUP BY a.id_anggota
        ORDER BY a.id_anggota ASC';
// $sql = 'SELECT * FROM anggota ORDER BY id_anggota ASC';
// $sql = 'SELECT * FROM anggota ORDER BY nama ASC';
$query = mysqli_query($conn, $sql);

if (!$query) {
    die('SQL error: ' . mysqli_error($conn));
};

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta Globalin Academy</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        h2 { margin-top: 30px; margin-bottom: 30px; font-weight: bold;}
    </style>
</head>

<body class="bg-light"> <div class="container">

        <h2 class="text-center text-primary">DAFTAR ANGGOTA GLOBALIN ACADEMY</h2>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <a href="add_new_data.php" class="btn btn-primary">
                    + Tambah Data Baru
                </a>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-striped table-hover table-bordered align-middle">
                        <thead class="table-dark"> <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">ID Anggota</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">Telpon</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query) > 0) {
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($query)){
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><?php echo $row['id_anggota']; ?></td>
                                    <td class="fw-bold"><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td><?php echo $row['telpon']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td class="text-center"><?php echo $row['jenis_kelamin']; ?></td>
                                    <td class="text-center">
                                        <?php if ($row['nama_kelas']): ?>
                                            <span class="badge bg-primary"><?php echo $row['nama_kelas']; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($row['status'] == 'Aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="edit_data.php?id=<?php echo $row['id_anggota'];?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_data.php?id=<?php echo $row['id_anggota'];?>" class="btn btn-danger btn-sm">Hapus</a>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="8" class="text-center p-5">
                                        Data Masih Kosong
                                    </td>
                                </tr>
                            <?php }; ?>
                        </tbody>
                    </table>

                </div> </div> </div> <br><br>
    </div> <script src="js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #f2878e;">
                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="text-danger" style="font-size: 3rem;">⚠️</i>
                <p class="mt-3">Apakah Anda yakin ingin menghapus data anggota bernama <br> <strong id="namaAnggota"></strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer bg-light border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <a id="btnHapusConfirm" href="#" class="btn text-white px-4" style="background-color: #f2878e;">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    var modalHapus = document.getElementById('modalHapus');
    modalHapus.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');

        document.getElementById('namaAnggota').textContent = nama;
        document.getElementById('btnHapusConfirm').href = 'delete_data.php?id=' + id;
    });
</script>

</body>
</html>