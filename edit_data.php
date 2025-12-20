<?php
include_once 'config_gg.php';

if ( !isset($_GET['id']) ) {
    header('Location: peserta_gg.php');
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM peserta WHERE id=$id";
$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);

if ( mysqli_num_rows($query) < 1 ) {
    die("Data tidak ditemukan...");
}

if (isset($_POST['simpan'])) {
    
    $id     = $_POST['id'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telp   = mysqli_real_escape_string($conn, $_POST['telp']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = $_POST['gender'];
    $status  = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE peserta SET 
            nama='$nama', 
            alamat='$alamat', 
            telp='$telp', 
            email='$email', 
            gender='$gender', 
            status='$status'
            WHERE id=$id";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        header('Location: pesertaa.php?status=sukses-edit');
    } else {
        echo "Gagal menyimpan perubahan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Peserta</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            max-width: 500px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="email"]:focus, textarea:focus {
            border-color: #f0e130;
            outline: none;
        }

        .radio-group {
            margin-bottom: 20px;
        }
        
        .radio-group label {
            display: inline-block;
            margin-right: 20px;
            font-weight: normal;
            cursor: pointer;
        }

        .form-actions {
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h3 class="form-header">Edit Data Peserta</h3>

        <form action="" method="POST">
            
            <input type="hidden" name="id" value="<?php echo $data['id'] ?>" />

            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" value="<?php echo $data['nama'] ?>" required />

            <label for="alamat">Alamat Domisili</label>
            <textarea name="alamat" style="font-family:sans-serif" rows="3" required><?php echo $data['alamat'] ?></textarea>

            <label for="telp">Nomor Telepon</label>
            <input type="text" name="telp" value="<?php echo $data['telp'] ?>" />

            <label for="email">Alamat Email</label>
            <input type="email" name="email" value="<?php echo $data['email'] ?>" />

            <label>Jenis Kelamin</label>
            <div class="radio-group">
                <?php $jk = $data['gender']; ?>
                <label>
                    <input type="radio" name="gender" value="Pria" <?php echo ($jk == 'Pria') ? "checked" : "" ?>> Pria
                </label>
                <label>
                    <input type="radio" name="gender" value="Wanita" <?php echo ($jk == 'Wanita') ? "checked" : "" ?>> Wanita
                </label>
            </div>

            <label>Status</label>
            <div class="radio-group">
                <?php $jk = $data['status']; ?>
                <label>
                    <input type="radio" name="status" value="Aktif" <?php echo ($jk == 'Aktif') ? "checked" : "" ?>> Aktif
                </label>
                <label>
                    <input type="radio" name="status" value="Tidak aktif" <?php echo ($jk == 'Tidak aktif') ? "checked" : "" ?>> Tidak aktif
                </label>
            </div>
            
            <div class="form-actions">
                <a href="pesertaa.php" class="button button-danger" style="margin-right: 10px;">Batal</a>
                <input type="submit" value="Simpan Perubahan" name="simpan" class="button button-warning" />
            </div>

        </form>
    </div>
</body>
</html>