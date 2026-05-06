<?php
include "./function/connection.php";

try {
    // 1. Total Barang
    $query_barang = mysqli_query($connection, "SELECT id FROM tbl_master_barang");
    $count_barang = $query_barang ? mysqli_num_rows($query_barang) : 0;

    // 2. Total Stok Fisik
    $query_stok = mysqli_query($connection, "SELECT SUM(jumlah_stok) as total_stok FROM tbl_stok");
    $data_stok = $query_stok ? mysqli_fetch_assoc($query_stok) : null;
    $count_stok = ($data_stok && isset($data_stok['total_stok'])) ? $data_stok['total_stok'] : 0;

    // 3. Omset Hari Ini
    $query_omset = mysqli_query($connection, "SELECT SUM(total) as omset_hari_ini FROM tbl_penjualan WHERE DATE(tanggal) = CURDATE()");
    $data_omset = $query_omset ? mysqli_fetch_assoc($query_omset) : null;
    $omset_hari_ini = ($data_omset && isset($data_omset['omset_hari_ini'])) ? $data_omset['omset_hari_ini'] : 0;

    // 4. Total Transaksi (Bulan Ini)
    $query_transaksi = mysqli_query($connection, "SELECT COUNT(id) as total_trx FROM tbl_penjualan WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())");
    $data_transaksi = $query_transaksi ? mysqli_fetch_assoc($query_transaksi) : null;
    $count_transaksi = ($data_transaksi && isset($data_transaksi['total_trx'])) ? $data_transaksi['total_trx'] : 0;

    // 5. Data Chart: 7 Hari Terakhir
    $chart_dates = [];
    $chart_totals = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $chart_dates[] = date('d M', strtotime($date));
        
        $q_chart = mysqli_query($connection, "SELECT SUM(total) as daily_total FROM tbl_penjualan WHERE DATE(tanggal) = '$date'");
        $d_chart = $q_chart ? mysqli_fetch_assoc($q_chart) : null;
        $chart_totals[] = ($d_chart && isset($d_chart['daily_total'])) ? (int)$d_chart['daily_total'] : 0;
    }

    // 6. 5 Transaksi Terakhir
    $query_recent = mysqli_query($connection, "
        SELECT kode_transaksi, tanggal, total, user 
        FROM tbl_penjualan 
        ORDER BY id DESC LIMIT 5
    ");
    $recent_trx = [];
    if ($query_recent) {
        while($row = mysqli_fetch_assoc($query_recent)){
            $recent_trx[] = $row;
        }
    }

} catch (\Throwable $th) {
    // Graceful degradation
    $count_barang = 0;
    $count_stok = 0;
    $omset_hari_ini = 0;
    $count_transaksi = 0;
    $chart_dates = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $chart_totals = [0, 0, 0, 0, 0, 0, 0];
    $recent_trx = [];
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">Ringkasan Aktivitas Point of Sales</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="row">
            <!-- Card 1: Omset Hari Ini -->
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldWallet"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Omset Hari Ini</h6>
                                <h6 class="font-extrabold mb-0">Rp <?= number_format($omset_hari_ini, 0, ',', '.') ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Transaksi -->
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldDocument"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Trx Bulan Ini</h6>
                                <h6 class="font-extrabold mb-0"><?= number_format($count_transaksi) ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Barang -->
            <div class="col-6 col-lg-3 col-md-6">
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

            <!-- Card 4: Stok Total -->
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
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
        </div>

        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h4>Tren Pendapatan (7 Hari Terakhir)</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-revenue"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h4>Transaksi Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <?php if(count($recent_trx) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($recent_trx as $trx): ?>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md bg-light-primary me-3">
                                                <span class="avatar-content"><i class="bi bi-cart-check text-primary"></i></span>
                                            </div>
                                            <div>
                                                <p class="font-bold mb-0"><?= htmlspecialchars($trx['kode_transaksi']) ?></p>
                                                <small class="text-muted"><?= htmlspecialchars($trx['user']) ?></small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <p class="font-bold mb-0 text-success">Rp <?= number_format($trx['total'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Belum ada transaksi</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="index.php?halaman=data_penjualan" class="btn btn-block btn-xl btn-light-primary font-bold">Lihat Semua Transaksi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Scripts for Chart -->
<script src="./assets/extensions/apexcharts/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var chartDates = <?= json_encode($chart_dates) ?>;
    var chartTotals = <?= json_encode($chart_totals) ?>;

    var options = {
        series: [{
            name: 'Omset (Rp)',
            data: chartTotals
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: chartDates,
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return "Rp " + value.toLocaleString('id-ID');
                }
            }
        },
        colors: ['#435ebe'],
        tooltip: {
            y: {
                formatter: function (val) {
                    return "Rp " + val.toLocaleString('id-ID')
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-revenue"), options);
    chart.render();
});
</script>