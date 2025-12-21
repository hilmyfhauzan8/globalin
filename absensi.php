<?php
require_once 'config.php';

// 1. Ambil daftar kelas untuk dropdown
$query_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY id_kelas ASC");

// 2. Ambil data jika kelas sudah dipilih
$id_kelas_selected = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;
$anggota = [];
$jadwal = [];

if ($id_kelas_selected) {
    // Ambil anggota di kelas tersebut
    $sql_anggota = "SELECT * FROM anggota WHERE id_kelas = $id_kelas_selected ORDER BY nama ASC";
    $query_anggota = mysqli_query($conn, $sql_anggota);
    while ($row = mysqli_fetch_assoc($query_anggota)) {
        $anggota[] = $row;
    }

    // Ambil jadwal tanggal pertemuan (1-7) untuk kelas ini
    $sql_jadwal = "SELECT * FROM jadwal_pertemuan WHERE id_kelas = $id_kelas_selected";
    $query_jadwal = mysqli_query($conn, $sql_jadwal);
    while ($row = mysqli_fetch_assoc($query_jadwal)) {
        $jadwal[$row['pertemuan_ke']] = $row['tanggal'];
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
        .bg-custom { background-color: #2596be; color: white; }
        .table-absensi th, .table-absensi td { vertical-align: middle; text-align: center; font-size: 0.9rem; }
        .symbol-select { border: none; background: transparent; cursor: pointer; font-size: 1.2rem; }
        .date-header { font-size: 0.7rem; display: block; color: #666; }
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
                                <?php echo $k['nama_kelas']; ?>
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
                                        <a href="#" class="text-decoration-none text-dark" onclick="setTanggal(<?php echo $i; ?>)">
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
                                    $total_hadir = 0;
                                    for($i=1; $i<=7; $i++): 
                                        // Nanti di sini kita panggil data absen dari database
                                        $status = ''; // Dummy status
                                    ?>
                                        <td>
                                            <select class="symbol-select">
                                                <option value="">-</option>
                                                <option value="Hadir">✅</option>
                                                <option value="Tidak hadir">❌</option>
                                                <option value="Sakit">🟡</option>
                                                <option value="Izin">🟦</option>
                                            </select>
                                        </td>
                                    <?php endfor; ?>
                                    
                                    <td class="fw-bold text-primary">0</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 bg-light rounded border">
                    <h6 class="fw-bold mb-2">Keterangan :</h6>
                    <div class="d-flex gap-4">
                        <span>✅ : Hadir</span>
                        <span>❌ : Tidak hadir</span>
                        <span>🟡 : Sakit (Warna Kuning)</span>
                        <span>🟦 : Izin (Warna Biru)</span>
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
            // Nanti di sini kita buat script PHP untuk simpan tanggal ke tabel jadwal_pertemuan
            window.location.href = "simpan_jadwal.php?id_kelas=<?php echo $id_kelas_selected; ?>&p=" + pertemuan + "&tgl=" + tgl;
        }
    }
</script>
</body>
</html>