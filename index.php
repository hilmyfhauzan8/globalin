<?php

include_once 'config_gg.php';

$sql = 'SELECT * FROM peserta ORDER BY id ASC';
// $sql = 'SELECT * FROM peserta ORDER BY nama ASC';
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
                <a href="add_new_data_gg.php" class="btn btn-primary">
                    + Tambah Data Baru
                </a>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-striped table-hover table-bordered align-middle">
                        <thead class="table-dark"> <tr>
                                <th class="text-center">No.</th>
                                <th>ID Anggota</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Telpon</th>
                                <th>Email</th>
                                <th class="text-center">Gender</th>
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
                                    <td class="text-center"><?php echo $row['id']; ?></td>
                                    <td class="fw-bold"><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td><?php echo $row['telp']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td class="text-center"><?php echo $row['gender']; ?></td>
                                    
                                    <td class="text-center">
                                        <?php if($row['status'] == 'Aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="edit_data_gg.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-sm">Edit</a>
                                        
                                        <a href="delete_data_gg.php?id=<?php echo $row['id'];?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
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

</body>
</html>