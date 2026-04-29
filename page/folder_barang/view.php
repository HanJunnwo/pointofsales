<?php
include "./function/connection.php";

$query = mysqli_query($connection, "SELECT * FROM tbl_master_barang");
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Barang</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tampil Data Barang
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=data_barang">Barang</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lihat Data Barang
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=tambah_data_barang" class="btn btn-primary btn-sm mb-3">Tambah Data</a>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
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
                                        <td><?= $data['kode_barang'] ?></td>
                                        <td><?= $data['nama_barang'] ?></td>
                                        <td><?= $data['harga_beli'] ?></td>
                                        <td><?= $data['harga_jual'] ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="index.php?halaman=ubah_data_barang&id=<?= $data['id'] ?>">Ubah</a>
                                            <a class="btn btn-danger btn-sm" id="btn-hapus" href="index.php?halaman=hapus_data_barang&id=<?= $data['id'] ?>" onclick="confirmModal(event)">Hapus</a>
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