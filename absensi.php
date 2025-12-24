<?php
require_once 'config.php';

$query_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY id_kelas ASC");
$id_kelas_selected = isset($_GET['id_kelas']) ? (int)$_GET['id_kelas'] : null;

if (isset($_POST['simpan_semua'])) {
    $id_kelas = $_POST['id_kelas_hidden'];
    
    foreach ($_POST['absen'] as $id_anggota => $pertemuans) {
        foreach ($pertemuans as $p_ke => $keterangan) {
            if (!empty($keterangan)) {
                
                $cek_tgl = mysqli_query($conn, "SELECT tanggal FROM jadwal_pertemuan WHERE id_kelas = $id_kelas AND pertemuan_ke = $p_ke");
                $tgl_data = mysqli_fetch_assoc($cek_tgl);
                $tanggal = ($tgl_data) ? $tgl_data['tanggal'] : date('Y-m-d');

                $cek_absen = mysqli_query($conn, "SELECT id_absensi FROM absensi WHERE id_anggota = $id_anggota AND pertemuan_ke = $p_ke");
                
                if (mysqli_num_rows($cek_absen) > 0) {
                    $sql_save = "UPDATE absensi SET keterangan = '$keterangan', tanggal = '$tanggal' 
                                 WHERE id_anggota = $id_anggota AND pertemuan_ke = $p_ke";
                } else {
                    $sql_save = "INSERT INTO absensi (id_anggota, pertemuan_ke, tanggal, keterangan) 
                                 VALUES ($id_anggota, $p_ke, '$tanggal', '$keterangan')";
                }
                mysqli_query($conn, $sql_save);
            }
        }
    }
    header("Location: absensi.php?id_kelas=$id_kelas&status=sukses-absen");
    exit;
}

$anggota = [];
$jadwal = [];
$data_simbol = [];

if ($id_kelas_selected) {
    $res_ang = mysqli_query($conn, "SELECT * FROM anggota WHERE id_kelas = $id_kelas_selected AND status = 'Aktif' ORDER BY nama ASC");
    while($r = mysqli_fetch_assoc($res_ang)) $anggota[] = $r;

    $res_jad = mysqli_query($conn, "SELECT * FROM jadwal_pertemuan WHERE id_kelas = $id_kelas_selected");
    while($r = mysqli_fetch_assoc($res_jad)) $jadwal[$r['pertemuan_ke']] = $r['tanggal'];

    $res_abs = mysqli_query($conn, "SELECT ab.* FROM absensi ab JOIN anggota a ON ab.id_anggota = a.id_anggota WHERE a.id_kelas = $id_kelas_selected");
    while($r = mysqli_fetch_assoc($res_abs)) $data_simbol[$r['id_anggota']][$r['pertemuan_ke']] = $r['keterangan'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi - Globalin Academy</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .table-absensi th, .table-absensi td { vertical-align: middle; text-align: center; font-size: 0.85rem; }
        .symbol-input { border: 1px solid #ddd; border-radius: 4px; padding: 2px; cursor: pointer; background-color: #fff; }
        .date-header { font-size: 0.7rem; display: block; color: #666; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">ABSENSI GLOBALIN ACADEMY</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Kelas Terlebih Dahulu</label>
                    <select name="id_kelas" class="form-select" onchange="this.form.submit()">
                        <option value="" disabled <?php echo !$id_kelas_selected ? 'selected' : ''; ?>>-- Pilih Kelas --</option>
                        <?php while ($k = mysqli_fetch_assoc($query_kelas)): ?>
                            <option value="<?php echo $k['id_kelas']; ?>" <?php echo ($id_kelas_selected == $k['id_kelas']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($k['nama_kelas']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if ($id_kelas_selected): ?>
    <form action="" method="POST">
        <input type="hidden" name="id_kelas_hidden" value="<?php echo $id_kelas_selected; ?>">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-absensi">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2" width="40">No.</th>
                                <th rowspan="2">Nama Anggota</th>
                                <th colspan="7">Pertemuan Ke-</th>
                                <th rowspan="2">Total</th>
                            </tr>
                            <tr>
                                <?php for($i=1; $i<=7; $i++): ?>
                                    <th>
                                        <a href="#" class="text-white text-decoration-none" onclick="setTanggal(<?php echo $i; ?>)"><?php echo $i; ?></a>
                                        <span class="date-header text-light opacity-75"><?php echo isset($jadwal[$i]) ? date('d/m', strtotime($jadwal[$i])) : '--/--'; ?></span>
                                    </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($anggota as $a): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="text-start fw-bold"><?php echo htmlspecialchars($a['nama']); ?></td>
                                    
                                    <?php 
                                    $total_h = 0;
                                    for($i=1; $i<=7; $i++): 
                                        $val = isset($data_simbol[$a['id_anggota']][$i]) ? $data_simbol[$a['id_anggota']][$i] : '';
                                        if($val == 'Hadir') $total_h++;
                                    ?>
                                        <td>
                                            <select name="absen[<?php echo $a['id_anggota']; ?>][<?php echo $i; ?>]" class="symbol-input">
                                                <option value="">-</option>
                                                <option value="Hadir" <?php echo ($val == 'Hadir') ? 'selected' : ''; ?>>✅</option>
                                                <option value="Tidak hadir" <?php echo ($val == 'Tidak hadir') ? 'selected' : ''; ?>>❌</option>
                                                <option value="Sakit" <?php echo ($val == 'Sakit') ? 'selected' : ''; ?>>🟡</option>
                                                <option value="Izin" <?php echo ($val == 'Izin') ? 'selected' : ''; ?>>🟦</option>
                                            </select>
                                        </td>
                                    <?php endfor; ?>
                                    
                                    <td class="fw-bold text-primary"><?php echo $total_h; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small text-muted">
                        Keterangan: ✅ Hadir | ❌ Alfa | 🟡 Sakit | 🟦 Izin
                    </div>
                    <button type="submit" name="simpan_semua" class="btn btn-primary px-5 fw-bold shadow-sm">
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
    function setTanggal(p) {
        let tgl = prompt("Set Tanggal Pertemuan " + p + " (YYYY-MM-DD):", "<?php echo date('Y-m-d'); ?>");
        if (tgl) {
            window.location.href = "simpan_jadwal.php?id_kelas=<?php echo $id_kelas_selected; ?>&p=" + p + "&tgl=" + tgl;
        }
    }
</script>
</body>
</html>