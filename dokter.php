<?php
include_once("koneksi.php");

// Proses form jika disubmit
if (isset($_POST['simpan'])) {
    if (isset($_POST['id']) && $_POST['id'] != "") {
        // Proses update data
        $ubah = mysqli_query($mysqli, "UPDATE dokter SET 
                                        nama = '" . $_POST['nama'] . "',
                                        alamat = '" . $_POST['alamat'] . "',
                                        no_hp = '" . $_POST['no_hp'] . "'
                                        WHERE id = '" . $_POST['id'] . "'");
        if ($ubah) {
            echo "<div class='alert alert-success'>Data berhasil diubah.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal mengubah data: " . mysqli_error($mysqli) . "</div>";
        }
    } else {
        // Proses tambah data
        $tambah = mysqli_query($mysqli, "INSERT INTO dokter (nama, alamat, no_hp) 
                                        VALUES ( 
                                            '" . $_POST['nama'] . "',
                                            '" . $_POST['alamat'] . "',
                                            '" . $_POST['no_hp'] . "'
                                            )");
        if ($tambah) {
            echo "<div class='alert alert-success'>Data berhasil ditambahkan.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($mysqli) . "</div>";
        }
    }
}

// Proses hapus data
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $hapus = mysqli_query($mysqli, "DELETE FROM dokter WHERE id = '" . $_GET['id'] . "'");
    if ($hapus) {
        echo "<div class='alert alert-success'>Data berhasil dihapus.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($mysqli) . "</div>";
    }
}

// Ambil data untuk form edit
$nama = '';
$alamat = '';
$no_hp = '';
if (isset($_GET['id'])) {
    $ambil = mysqli_query($mysqli, "SELECT * FROM dokter WHERE id='" . $_GET['id'] . "'");
    if ($ambil) {
        while ($row = mysqli_fetch_array($ambil)) {
            $nama = $row['nama'];
            $alamat = $row['alamat'];
            $no_hp = $row['no_hp'];
        }
    } else {
        echo "<div class='alert alert-danger'>Gagal mengambil data: " . mysqli_error($mysqli) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-5">
        <h3>Form Input Data Dokter</h3>
        <form class="form row" method="POST" action="" name="myForm">
            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <div class="form-group mt-2">
                <label for="inputNama" class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" id="inputNama" placeholder="Nama" value="<?php echo $nama ?>" required>
            </div>
            <div class="form-group mt-2">
                <label for="inputAlamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" name="alamat" id="inputAlamat" placeholder="Alamat" value="<?php echo $alamat ?>" required>
            </div>
            <div class="form-group mt-2">
                <label for="inputNo_Hp" class="form-label">No Hp</label>
                <input type="text" class="form-control" name="no_hp" id="inputNo_Hp" placeholder="No Hp" value="<?php echo $no_hp ?>" required>
            </div>
            <div class="col-sm-10 mt-4">
                <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
            </div>
        </form>

        <!-- Tabel Data Dokter -->
        <h3 class="mt-5">Data Dokter</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokter</th>
                    <th>Alamat</th>
                    <th>No Hp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($mysqli, "SELECT * FROM dokter");
                if ($result) {
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $data['nama'] . "</td>";
                        echo "<td>" . $data['alamat'] . "</td>";
                        echo "<td>" . $data['no_hp'] . "</td>";
                        echo "<td>";
                        echo "<a class='btn btn-success rounded-pill px-3' href='dokter.php?id=" . $data['id'] . "'>Ubah</a> ";
                        echo "<a class='btn btn-danger rounded-pill px-3' href='dokter.php?id=" . $data['id'] . "&aksi=hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Gagal mengambil data: " . mysqli_error($mysqli) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
