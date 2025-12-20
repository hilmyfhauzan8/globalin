<?php
require_once 'config.php';

$query_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY id_kelas ASC");

if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $status = $_POST['status'];
    $id_kelas = $_POST['id_kelas'];

    $telpon_raw = $_POST['telpon'];
    $telpon_clean = str_replace([' ', '-', '.'], '', $telpon_raw); // Hapus spasi/strip

    if (substr($telpon_clean, 0, 3) == '+62') {
        $telpon_fix = '0' . substr($telpon_clean, 3);
    } elseif (substr($telpon_clean, 0, 2) == '62') {
        $telpon_fix = '0' . substr($telpon_clean, 2);
    } else {
        $telpon_fix = $telpon_clean;
    }
    $telpon = mysqli_real_escape_string($conn, $telpon_fix);

    $sql = "INSERT INTO anggota (nama, alamat, telpon, email, jenis_kelamin, id_kelas, status) 
            VALUES ('$nama', '$alamat', '$telpon_raw', '$email', '$jenis_kelamin', '$id_kelas', '$status')";
    
    $query = mysqli_query($conn, $sql);

    if ($query) {
        header('Location: index.php?status=sukses');
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Baru</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="bg-light"> <div class="container mt-5 mb-5">
        
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            
            <div class="card-header bg-white text-center py-3">
                <h4 class="mb-0 fw-bold text-primary">Formulir Tambah Peserta</h4>
            </div>

            <div class="card-body p-4">
                
                <form action="" method="POST">
                    
                    <div class="mb-3"> <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukan nama lengkap..." required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat Domisili</label>
                        <textarea class="form-control" name="alamat" rows="3" placeholder="Masukan alamat lengkap..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="telp" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control" name="telp" placeholder="Contoh: 0812xxxx">
                        <div class="form-text text-muted">Gunakan angka saja.</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Alamat Email</label>
                        <input type="email" class="form-control" name="email" placeholder="nama@email.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kelas --</option>
                                <?php
                                if (isset($query_kelas)) mysqli_data_seek($query_kelas, 0);
                                while ($kelas = mysqli_fetch_assoc($query_kelas)):
                                ?>
                                <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                                <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold d-block">Jenis Kelamin</label>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="pria" value="Pria" checked>
                                <label class="form-check-label" for="pria">Pria</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="wanita" value="Wanita">
                                <label class="form-check-label" for="wanita">Wanita</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold d-block">Status Keanggotaan</label>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="aktif" value="Aktif" checked>
                                <label class="form-check-label" for="aktif">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="nonaktif" value="Tidak aktif">
                                <label class="form-check-label" for="nonaktif">Tidak Aktif</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4"> <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-primary px-4">Simpan Data</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>