<?php
require_once 'config.php';

if ( !isset($_GET['id']) ) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM anggota WHERE id_anggota=$id";
$query = mysqli_query($conn, $sql);
$query_kelas = mysqli_query($conn,"SELECT * FROM kelas ORDER BY id_kelas ASC");
$data = mysqli_fetch_assoc($query);

if ( mysqli_num_rows($query) < 1 ) {
    die("Data tidak ditemukan...");
}

if (isset($_POST['simpan'])) {
    
    $id     = $_POST['id_anggota'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $id_kelas = $_POST['id_kelas'];
    $status  = mysqli_real_escape_string($conn, $_POST['status']);

    $telpon_raw = $_POST['telpon'];
    $telpon_clean = str_replace([' ', '-', '.'], '', $telpon_raw);

    if (substr($telpon_clean, 0, 3) == '+62') {
        $telpon_fix = '0' . substr($telpon_clean, 3);
    } elseif (substr($telpon_clean, 0, 2) == '62') {
        $telpon_fix = '0' . substr($telpon_clean, 2);
    } else {
        $telpon_fix = $telpon_clean;
    }
    $telpon = mysqli_real_escape_string($conn, $telpon_fix);

    $sql = "UPDATE anggota SET
            nama='$nama',
            alamat='$alamat',
            telpon='$telpon',
            email='$email',
            jenis_kelamin='$jenis_kelamin',
            id_kelas='$id_kelas',
            status='$status'
            WHERE id_anggota=$id";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        header('Location: index.php?status=sukses-edit');
    } else {
        echo "Gagal menyimpan perubahan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Data Peserta</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-white text-center py-3">
                <h4 class="mb-0 fw-bold text-primary">Edit Data Peserta</h4>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <input type="hidden" name="id_anggota" value="<?php echo $data['id_anggota'] ?>" />

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo $data['nama'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Domisili</label>
                        <textarea class="form-control" name="alamat" rows="3" required><?php echo $data['alamat'] ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control" name="telpon" value="<?php echo $data['telpon'] ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $data['email'] ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <?php while ($kelas = mysqli_fetch_assoc($query_kelas)): ?>
                                <option value="<?php echo $kelas['id_kelas']; ?>" <?php echo ($data['id_kelas'] == $kelas['id_kelas']) ? "selected" : "" ?>>
                                    <?php echo $kelas['nama_kelas']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold d-block">Jenis Kelamin</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="pria" value="Pria" <?php echo ($data['jenis_kelamin'] == 'Pria') ? "checked" : "" ?>>
                                <label class="form-check-label" for="pria">Pria</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="wanita" value="Wanita" <?php echo ($data['jenis_kelamin'] == 'Wanita') ? "checked" : "" ?>>
                                <label class="form-check-label" for="wanita">Wanita</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold d-block">Status Keanggotaan</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="aktif" value="Aktif" <?php echo ($data['status'] == 'Aktif') ? "checked" : "" ?>>
                                <label class="form-check-label" for="aktif">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="nonaktif" value="Tidak aktif" <?php echo ($data['status'] == 'Tidak aktif') ? "checked" : "" ?>>
                                <label class="form-check-label" for="nonaktif">Tidak Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>