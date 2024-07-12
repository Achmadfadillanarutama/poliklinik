<?php
include_once("koneksi.php");

// Proses form jika disubmit
if (isset($_POST['simpan'])) {
    if (isset($_POST['id']) && $_POST['id'] != "") {
        // Proses update data
        $ubah = mysqli_query($mysqli, "UPDATE obat SET 
                                        nama_obat = '" . $_POST['nama_obat'] . "',
                                        kemasan= '" . $_POST['kemasan'] . "',
                                        harga = '" . $_POST['harga'] . "'
                                        WHERE id = '" . $_POST['id'] . "'");
        if ($ubah) {
            echo "<div class='alert alert-success'>Data berhasil diubah.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal mengubah data: " . mysqli_error($mysqli) . "</div>";
        }
    } else {
        // Proses tambah data
        $tambah = mysqli_query($mysqli, "INSERT INTO obat (nama_obat, kemasan, harga) 
                                        VALUES ( 
                                            '" . $_POST['nama_obat'] . "',
                                            '" . $_POST['kemasan'] . "',
                                            '" . $_POST['harga'] . "'
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
    $hapus = mysqli_query($mysqli, "DELETE FROM obat WHERE id = '" . $_GET['id'] . "'");
    if ($hapus) {
        echo "<div class='alert alert-success'>Data berhasil dihapus.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($mysqli) . "</div>";
    }
}

// Ambil data untuk form edit
$nama_obat = '';
$kemasan = '';
$harga = '';
if (isset($_GET['id'])) {
    $ambil = mysqli_query($mysqli, "SELECT * FROM obat WHERE id='" . $_GET['id'] . "'");
    if ($ambil) {
        while ($row = mysqli_fetch_array($ambil)) {
            $nama_obat = $row['nama_obat'];
            $kemasan = $row['kemasan'];
            $harga = $row['harga'];
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
    <title>Data Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-5">
        <h3>Form Input Data Obat</h3>
        <form class="form row" method="POST" action="" name="myForm">
            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <div class="form-group mt-2">
                <label for="inputNama_Obat" class="form-label">Nama Obat</label>
                <input type="text" class="form-control" name="nama_obat" id="inputNama_Obat" placeholder="Nama Obat" value="<?php echo $nama_obat ?>" required>
            </div>
            <div class="form-group mt-2">
                <label for="inputKemasan" class="form-label">Kemasan</label>
                <input type="text" class="form-control" name="kemasan" id="inputKemasan" placeholder="Kemasan" value="<?php echo $kemasan ?>" required>
            </div>
            <div class="form-group mt-2">
                <label for="inputHarga" class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga" id="inputHarga" placeholder="Harga" value="<?php echo $harga ?>" required>
            </div>
            <div class="col-sm-10 mt-4">
                <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
            </div>
        </form>

        <!-- Tabel Data Obat -->
        <h3 class="mt-5">Data Obat</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Kemasan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($mysqli, "SELECT * FROM obat");
                if ($result) {
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $data['nama_obat'] . "</td>";
                        echo "<td>" . $data['kemasan'] . "</td>";
                        echo "<td>" . $data['harga'] . "</td>";
                        echo "<td>";
                        echo "<a class='btn btn-success rounded-pill px-3' href='obat.php?id=" . $data['id'] . "'>Ubah</a> ";
                        echo "<a class='btn btn-danger rounded-pill px-3' href='obat.php?id=" . $data['id'] . "&aksi=hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>";
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
