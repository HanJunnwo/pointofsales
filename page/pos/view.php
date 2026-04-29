<?php
include "./function/connection.php";

$sql = "
SELECT
p.kode_transaksi,
p.tanggal,
p.total,
p.bayar,
p.kembalian,
p.user,
d.kode_barang,
d.nama_barang,
d.harga,
d.qty,
d.subtotal
FROM tbl_penjualan p
LEFT JOIN tbl_detail_penjualan d
ON p.kode_transaksi = d.kode_transaksi
ORDER BY p.tanggal DESC
";

$query = mysqli_query($connection, $sql);

//cek error query
if (!$query) {
    die("query error: " . mysqli_error($connection));
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Penjualan</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tampil Data Penjualan
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=data_penjualan">Penjualan</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lihat Data Penjualan
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=tambah_data_penjualan" class="btn btn-primary btn-sm mb-3">Tambah Data</a>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Kembalian</th>
                                <th>User</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php if ($query->num_rows > 0) : ?>
                                <?php
                                $i = 1;
                                while ($data = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($data['kode_transaksi'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($data['tanggal'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($data['total'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($data['bayar'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['kembalian'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['user'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['kode_barang'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['nama_barang'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['harga'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['qty'] ?? '0') ?></td>
                                        <td><?= htmlspecialchars($data['subtotal'] ?? '0') ?></td>
                                    </tr>
                                <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="./assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="./assets/static/js/pages/simple-datatables.js"></script>