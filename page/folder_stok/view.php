<?php
include "./function/connection.php";

$query = mysqli_query($connection, "SELECT * FROM tbl_stok ORDER BY id ASC");
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Stok Barang</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tampil Data Stok Barang
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=data_stok">Stok Barang</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lihat Data Stok
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=tambah_data_stok" class="btn btn-primary btn-sm mb-3">Tambah Data</a>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query->num_rows > 0) : ?>
                                <?php
                                $i = 1;
                                while ($data = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($data['kode_transaksi'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($data['kode_barang'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($data['nama_barang'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($data['jumlah_stok'] ?? '0') ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="index.php?halaman=ubah_data_stok&id=<?= $data['id'] ?>">Ubah</a>
                                            <a class="btn btn-danger btn-sm" id="btn-hapus" href="index.php?halaman=hapus_data_stok&id=<?= $data['id'] ?>" onclick="confirmModal(event)">Hapus</a>
                                        </td>
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