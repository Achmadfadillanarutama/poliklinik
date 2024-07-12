<?php
include_once("koneksi.php");

// Get the examination ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id) {
    // Fetch examination details
    $query_periksa = mysqli_query($mysqli, "SELECT periksa.*, pasien.nama AS nama_pasien, pasien.alamat AS alamat_pasien, pasien.no_hp AS hp_pasien, dokter.nama AS nama_dokter, dokter.alamat AS alamat_dokter, dokter.no_hp AS hp_dokter 
                                            FROM periksa 
                                            JOIN pasien ON periksa.id_pasien = pasien.id 
                                            JOIN dokter ON periksa.id_dokter = dokter.id 
                                            WHERE periksa.id='$id'");
    $data_periksa = mysqli_fetch_array($query_periksa);

    // Fetch medicine details
    $query_obat = mysqli_query($mysqli, "SELECT obat.nama_obat, obat.harga, obat.kemasan 
                                         FROM obat 
                                         WHERE obat.id='{$data_periksa['id_obat']}'");
    $data_obat = mysqli_fetch_array($query_obat);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }
        .invoice-header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        .invoice-body {
            padding: 30px;
            background: linear-gradient(to bottom right, #ffffff, #f1f1f1);
        }
        .invoice-footer {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: right;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
        }
        .invoice-section-title {
            font-size: 18px;
            margin-top: 20px;
            font-weight: bold;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }
        .invoice-detail {
            margin-bottom: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .invoice-detail span {
            font-weight: bold;
            color: #343a40;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            color: green;
        }
        .invoice-icon {
            color: #343a40;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card invoice-card">
        <div class="card-header invoice-header">
            <h5 class="card-title invoice-title">Nota Pembayaran</h5>
        </div>
        <div class="card-body invoice-body">
            <div class="invoice-detail"><i class="invoice-icon bi bi-receipt"></i><strong>Nomor Periksa:</strong> <span><?php echo $data_periksa['id']; ?></span></div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-calendar-date"></i><strong>Tanggal Periksa:</strong> <span><?php echo $data_periksa['tgl_periksa']; ?></span></div>

            <div class="invoice-section-title">Pasien</div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-person"></i><strong>Nama:</strong> <span><?php echo $data_periksa['nama_pasien']; ?></span></div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-geo-alt"></i><strong>Alamat:</strong> <span><?php echo $data_periksa['alamat_pasien']; ?></span></div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-telephone"></i><strong>No. HP:</strong> <span><?php echo $data_periksa['hp_pasien']; ?></span></div>

            <div class="invoice-section-title">Dokter</div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-person-badge"></i><strong>Nama:</strong> <span><?php echo $data_periksa['nama_dokter']; ?></span></div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-geo-alt"></i><strong>Alamat:</strong> <span><?php echo $data_periksa['alamat_dokter']; ?></span></div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-telephone"></i><strong>No. HP:</strong> <span><?php echo $data_periksa['hp_dokter']; ?></span></div>

            <div class="invoice-section-title">Detail</div>
            <div class="invoice-detail"><i class="invoice-icon bi bi-cash"></i><strong>Jasa Dokter</strong> <span>Rp 150,000</span></div>
            <?php
            $total_obat = 0;
            if ($data_obat) {
                echo "<div class='invoice-detail'><i class='invoice-icon bi bi-capsule'></i><strong>{$data_obat['nama_obat']} ({$data_obat['kemasan']})</strong> <span>Rp {$data_obat['harga']}</span></div>";
                $total_obat = $data_obat['harga'];
            }
            ?>
            <div class="invoice-detail"><i class="invoice-icon bi bi-cash"></i><strong>Subtotal Obat</strong> <span>Rp <?php echo $total_obat; ?></span></div>
        </div>
        <div class="card-footer invoice-footer">
            <div class="total"><strong>Total:</strong> Rp <?php echo 150000 + $total_obat; ?></div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>
