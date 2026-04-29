<?php
include "./function/connection.php";

try {
    // Check Kontak
    $query_kontak = mysqli_query($connection, "SELECT id FROM kontak");
    $count_kontak = $query_kontak ? mysqli_num_rows($query_kontak) : 0;

    // Check Barang
    $query_barang = mysqli_query($connection, "SELECT id FROM tbl_master_barang");
    $count_barang = $query_barang ? mysqli_num_rows($query_barang) : 0;

    // Check Stok
    $query_stok = mysqli_query($connection, "SELECT SUM(jumlah_stok) as total_stok FROM tbl_stok");
    $data_stok = $query_stok ? mysqli_fetch_assoc($query_stok) : null;
    $count_stok = ($data_stok && isset($data_stok['total_stok'])) ? $data_stok['total_stok'] : 0;

} catch (\Throwable $th) {
    // If table missing or db error
    $count_kontak = 0;
    $count_barang = 0;
    $count_stok = 0;
    // We gracefully default to 0 instead of causing a fatal error prompt.
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">
                    Halaman Utama Point of Sales
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Home
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">

                <!-- Card 1: Barang -->
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldBag"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Barang</h6>
                                    <h6 class="font-extrabold mb-0"><?= number_format($count_barang) ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Stok Total -->
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldBuy"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Stok Fisik</h6>
                                    <h6 class="font-extrabold mb-0"><?= number_format($count_stok) ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Kontak -->
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Kontak</h6>
                                    <h6 class="font-extrabold mb-0"><?= number_format($count_kontak) ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>