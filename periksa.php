<?php
include_once("koneksi.php");

$id_pasien = '';
$id_dokter = '';
$tgl_periksa = '';
$catatan = '';
$id_obat = ''; // Use a single variable if only one medicine per examination

// Fetch data if there is an 'id' parameter in the URL for editing
if (isset($_GET['id'])) {
    $ambil = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id='" . $_GET['id'] . "'");
    if ($ambil && mysqli_num_rows($ambil) > 0) {
        $row = mysqli_fetch_array($ambil);
        $id_pasien = $row['id_pasien'];
        $id_dokter = $row['id_dokter'];
        $tgl_periksa = $row['tgl_periksa'];
        $catatan = $row['catatan'];
        $id_obat = $row['id_obat']; // Fetch the medicine ID directly from the periksa table
    } else {
        echo "Error: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periksa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h3>Form Periksa</h3>
    <form class="form row" method="POST" action="">
        <!-- Hidden input to store id if present -->
        <?php if (isset($_GET['id'])): ?>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group mt-2">
            <label for="inputPasien" class="form-label">Pasien</label>
            <select class="form-control" name="id_pasien" id="inputPasien" required>
                <option value="">Pilih Pasien</option>
                <?php
                $result_pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
                while ($row_pasien = mysqli_fetch_array($result_pasien)) {
                    $selected = ($row_pasien['id'] == $id_pasien) ? 'selected' : '';
                    echo "<option value='{$row_pasien['id']}' $selected>{$row_pasien['nama']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mt-2">
            <label for="inputDokter" class="form-label">Dokter</label>
            <select class="form-control" name="id_dokter" id="inputDokter" required>
                <option value="">Pilih Dokter</option>
                <?php
                $result_dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
                while ($row_dokter = mysqli_fetch_array($result_dokter)) {
                    $selected = ($row_dokter['id'] == $id_dokter) ? 'selected' : '';
                    echo "<option value='{$row_dokter['id']}' $selected>{$row_dokter['nama']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mt-2">
            <label for="inputTgl_Periksa" class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tgl_periksa" id="inputTgl_Periksa" value="<?php echo $tgl_periksa ?>" required>
        </div>
        
        <div class="form-group mt-2">
            <label for="inputCatatan" class="form-label">Catatan</label>
            <input type="text" class="form-control" name="catatan" id="inputCatatan" placeholder="Catatan" value="<?php echo $catatan ?>" required>
        </div>

        <div class="form-group mt-2">
            <label for="inputObat" class="form-label">Obat yang Dipilih</label>
            <select class="form-control" name="id_obat" id="inputObat" required>
                <option value="">Pilih Obat</option>
                <?php
                // Query to get the list of medicines from the database
                $result_obat = mysqli_query($mysqli, "SELECT * FROM obat");
                while ($row_obat = mysqli_fetch_array($result_obat)) {
                    $selected = ($row_obat['id'] == $id_obat) ? 'selected' : '';
                    echo "<option value='{$row_obat['id']}' $selected>{$row_obat['nama_obat']} - Rp {$row_obat['harga']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-sm-10 mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
        </div>
    </form>

    <!-- Table -->
    <h3 class="mt-5">Data Periksa</h3>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Pasien</th>
                <th scope="col">Dokter</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Catatan</th>
                <th scope="col">Obat</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result_periksa = mysqli_query($mysqli, "SELECT periksa.id, pasien.nama as nama_pasien, dokter.nama as nama_dokter, periksa.tgl_periksa, periksa.catatan, obat.nama_obat
                                                     FROM periksa 
                                                     JOIN pasien ON periksa.id_pasien = pasien.id 
                                                     JOIN dokter ON periksa.id_dokter = dokter.id 
                                                     LEFT JOIN obat ON periksa.id_obat = obat.id");
            if ($result_periksa && mysqli_num_rows($result_periksa) > 0) {
                $no = 1;
                while ($data = mysqli_fetch_array($result_periksa)) {
            ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama_pasien'] ?></td>
                        <td><?php echo $data['nama_dokter'] ?></td>
                        <td><?php echo $data['tgl_periksa'] ?></td>
                        <td><?php echo $data['catatan'] ?></td>
                        <td><?php echo $data['nama_obat'] ?></td>
                        <td>
                            <a class="btn btn-success rounded-pill px-3" href="periksa.php?id=<?php echo $data['id'] ?>">Ubah</a>
                            <a class="btn btn-danger rounded-pill px-3" href="periksa.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                            <a class="btn btn-warning rounded-pill px-3" href="invoice.php?id=<?php echo $data['id'] ?>">Nota</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada data ditemukan</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Process data submission
if (isset($_POST['simpan'])) {
    if (isset($_POST['id']) && $_POST['id'] != "") {
        // Update data
        $id_periksa = $_POST['id'];
        $id_pasien = $_POST['id_pasien'];
        $id_dokter = $_POST['id_dokter'];
        $tgl_periksa = $_POST['tgl_periksa'];
        $catatan = $_POST['catatan'];
        $id_obat = $_POST['id_obat'];

        $update_periksa = mysqli_query($mysqli, "UPDATE periksa SET id_pasien='$id_pasien', id_dokter='$id_dokter', tgl_periksa='$tgl_periksa', catatan='$catatan', id_obat='$id_obat' WHERE id='$id_periksa'");
        if ($update_periksa) {
            echo "<div class='alert alert-success'>Data berhasil diperbarui.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal memperbarui data: " . mysqli_error($mysqli) . "</div>";
        }
    } else {
        // Insert data
        $id_pasien = $_POST['id_pasien'];
        $id_dokter = $_POST['id_dokter'];
        $tgl_periksa = $_POST['tgl_periksa'];
        $catatan = $_POST['catatan'];
        $id_obat = $_POST['id_obat'];

        $insert_periksa = mysqli_query($mysqli, "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan, id_obat) 
                                                VALUES ('$id_pasien', '$id_dokter', '$tgl_periksa', '$catatan', '$id_obat')");
        if ($insert_periksa) {
            echo "<div class='alert alert-success'>Data berhasil disimpan.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menyimpan data: " . mysqli_error($mysqli) . "</div>";
        }
    }
}

// Process data deletion
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $hapus = mysqli_query($mysqli, "DELETE FROM periksa WHERE id = '" . $_GET['id'] . "'");
    if ($hapus) {
        echo "<div class='alert alert-success'>Data berhasil dihapus.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($mysqli) . "</div>";
    }
}
?>
</body>
</html>
