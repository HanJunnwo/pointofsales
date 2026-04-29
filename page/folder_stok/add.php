<?php
include "./function/connection.php";

try {
    $message = "";
    if (isset($_POST['submit'])) {
        $kode_transaksi = htmlspecialchars($_POST['kode_transaksi']);
        $id_barang = htmlspecialchars($_POST['id_barang']);
        $jumlah_stok = htmlspecialchars($_POST['jumlah_stok']);

        // Get kode_barang and nama_barang
        $get_barang = mysqli_query($connection, "SELECT kode_barang, nama_barang FROM tbl_master_barang WHERE id = '$id_barang'");
        $barang_data = mysqli_fetch_assoc($get_barang);
        $kode_barang = $barang_data['kode_barang'];
        $nama_barang = $barang_data['nama_barang'];

        $query = mysqli_query($connection, "INSERT INTO tbl_stok (kode_transaksi, kode_barang, nama_barang, jumlah_stok) VALUES ('$kode_transaksi', '$kode_barang', '$nama_barang', '$jumlah_stok')");

        if ($query == TRUE) {
            $message = "Berhasil menambahkan data stok";
            echo "<script>Swal.fire({title: 'Berhasil', text: '$message', icon: 'success', showConfirmButton: false, timer: 1000, timerProgressBar: true}).then(() => { window.location.href = 'index.php?halaman=data_stok'; })</script>";
        } else {
            $message = "Gagal menambahkan data stok";
            echo "<script>Swal.fire({title: 'Gagal', text: '$message', icon: 'error', showConfirmButton: false, timer: 2000, timerProgressBar: true}).then(() => { window.location.href = 'index.php?halaman=data_stok'; })</script>";
        }
    }
} catch (\Throwable $th) {
    echo "<script>Swal.fire({title: 'Gagal', text: 'Server error!', icon: 'error', showConfirmButton: false, timer: 2000, timerProgressBar: true}).then(() => { window.location.href = 'index.php?halaman=data_stok'; })</script>";
}

// Get list barang
$barang_query = mysqli_query($connection, "SELECT id, kode_barang, nama_barang FROM tbl_master_barang");
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Stok</h3>
                <p class="text-subtitle text-muted">Halaman Tambah Data Stok</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?halaman=data_stok">Data Stok</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Data Stok</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=data_stok" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="kode_transaksi" placeholder="TRX-001" name="kode_transaksi" required>
                        <label for="kode_transaksi">Kode Transaksi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="id_barang" name="id_barang" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php while ($barang = mysqli_fetch_assoc($barang_query)) : ?>
                                <option value="<?= $barang['id'] ?>"><?= htmlspecialchars($barang['kode_barang'] . ' - ' . $barang['nama_barang']) ?></option>
                            <?php endwhile ?>
                        </select>
                        <label for="id_barang">Pilih Barang</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="jumlah_stok" placeholder="100" name="jumlah_stok" required min="0">
                        <label for="jumlah_stok">Jumlah Stok Awal</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
