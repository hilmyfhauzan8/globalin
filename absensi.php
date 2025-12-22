<?php
require_once 'config.php';

$query_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY id_kelas ASC");

$id_kelas_selected = isset($_GET['id_kelas']) ? (int)$_GET['id_kelas'] : null;

$anggota = [];
$jadwal = [];
$data_simbol = [];

if ($id_kelas_selected) {
    $sql_anggota = "SELECT * FROM anggota WHERE id_kelas = $id_kelas_selected AND status = 'Aktif' ORDER BY nama ASC";
    $query_anggota = mysqli_query($conn, $sql_anggota);
    while ($row = mysqli_fetch_assoc($query_anggota)) {
        $anggota[] = $row;
    }

    $sql_jadwal = "SELECT pertemuan_ke, tanggal FROM jadwal_pertemuan WHERE id_kelas = $id_kelas_selected";
    $query_jadwal = mysqli_query($conn, $sql_jadwal);
    while ($row = mysqli_fetch_assoc($query_jadwal)) {
        $jadwal[$row['pertemuan_ke']] = $row['tanggal'];
    }

    $sql_absensi = "SELECT ab.id_anggota, ab.pertemuan_ke, ab.keterangan 
                    FROM absensi as ab 
                    JOIN anggota as a ON ab.id_anggota = a.id_anggota 
                    WHERE a.id_kelas = $id_kelas_selected";
    $query_absensi = mysqli_query($conn, $sql_absensi);
    while ($row = mysqli_fetch_assoc($query_absensi)) {
        $data_simbol[$row['id_anggota']][$row['pertemuan_ke']] = $row['keterangan'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Absensi - Globalin Academy</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .table-absensi th, .table-absensi td { vertical-align: middle; text-align: center; font-size: 0.9rem; }
        .date-header { font-size: 0.7rem; display: block; color: #666; }
        .header-p { cursor: pointer; color: inherit; text-decoration: none; }
        .header-p:hover { color: #2596be; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">ABSENSI ANGGOTA</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">Kembali ke Beranda</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Kelas</label>
                    <select name="id_kelas" class="form-select" onchange="this.form.submit()">
                        <option value="" disabled <?php echo !$id_kelas_selected ? 'selected' : ''; ?>>-- Pilih Kelas --</option>
                        <?php while ($k = mysqli_fetch_assoc($query_kelas)): ?>
                            <option value="<?php echo $k['id_kelas']; ?>" <?php echo ($id_kelas_selected == $k['id_kelas']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($k['nama_kelas']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 ms-auto text-end">
                    <label class="form-label fw-bold text-muted">Tanggal Hari Ini</label>
                    <h5 class="fw-bold"><?php echo date('d F Y'); ?></h5>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Daftar Hadir Anggota</h5>
        </div>
        <div class="card-body">
            <?php if (!$id_kelas_selected): ?>
                <div class="text-center py-5">
                    <p class="text-muted">Silakan pilih kelas terlebih dahulu untuk memunculkan data anggota.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-absensi">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" width="50">No.</th>
                                <th rowspan="2" width="100">ID Anggota</th>
                                <th rowspan="2">Nama Anggota</th>
                                <th colspan="7">Pertemuan Ke-</th>
                                <th rowspan="2" width="80">Total</th>
                            </tr>
                            <tr>
                                <?php for($i=1; $i<=7; $i++): ?>
                                    <th>
                                        <a href="#" class="header-p" onclick="setTanggal(<?php echo $i; ?>)">
                                            <?php echo $i; ?>
                                        </a>
                                        <span class="date-header"><?php echo isset($jadwal[$i]) ? date('d/m', strtotime($jadwal[$i])) : '--/--'; ?></span>
                                    </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($anggota as $a): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $a['id_anggota']; ?></td>
                                    <td class="text-start fw-bold"><?php echo htmlspecialchars($a['nama']); ?></td>
                                    
                                    <?php 
                                    $total_per_siswa = 0; 

                                    for($i=1; $i<=7; $i++): 
                                        $status = isset($data_simbol[$a['id_anggota']][$i]) ? $data_simbol[$a['id_anggota']][$i] : '';
                                        
                                        $simbol = '-';
                                        if ($status == 'Hadir') { $simbol = '✅'; $total_per_siswa++; }
                                        elseif ($status == 'Tidak hadir') $simbol = '❌';
                                        elseif ($status == 'Sakit') $simbol = '🟡';
                                        elseif ($status == 'Izin') $simbol = '🟦';
                                    ?>
                                        <td>
                                            <a href="update_absensi.php?id=<?php echo $a['id_anggota']; ?>&p=<?php echo $i; ?>&id_kelas=<?php echo $id_kelas_selected; ?>" class="text-decoration-none">
                                                <span style="font-size: 1.2rem;"><?php echo $simbol; ?></span>
                                            </a>
                                        </td>
                                    <?php endfor; ?>
                                    
                                    <td class="fw-bold text-primary"><?php echo $total_per_siswa; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 bg-light rounded border small">
                    <h6 class="fw-bold mb-2">Keterangan :</h6>
                    <div class="d-flex gap-4">
                        <span>✅ Hadir</span>
                        <span>❌ Tidak hadir</span>
                        <span>🟡 Sakit</span>
                        <span>🟦 Izin</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
    function setTanggal(pertemuan) {
        let tgl = prompt("Masukkan tanggal untuk pertemuan ke-" + pertemuan + " (YYYY-MM-DD):", "<?php echo date('Y-m-d'); ?>");
        if (tgl) {
            window.location.href = "simpan_jadwal.php?id_kelas=<?php echo $id_kelas_selected; ?>&p=" + pertemuan + "&tgl=" + tgl;
        }
    }
</script>
</body>
</html>