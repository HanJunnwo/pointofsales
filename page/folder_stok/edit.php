<?php
include "./function/connection.php";

if (!isset($_SESSION['nama'])) {
    header('Location: index.php?halaman=login');
}

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $select = mysqli_query($connection, "SELECT * FROM tbl_stok WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=data_stok');
        }

        // Submit
        if (isset($_POST['submit'])) {
            $kode_transaksi = htmlspecialchars($_POST['kode_transaksi']);
            $id_barang = htmlspecialchars($_POST['id_barang']);
            $jumlah_stok = htmlspecialchars($_POST['jumlah_stok']);

            $get_barang = mysqli_query($connection, "SELECT kode_barang, nama_barang FROM tbl_master_barang WHERE id = '$id_barang'");
            $barang_data = mysqli_fetch_assoc($get_barang);
            $kode_barang = $barang_data['kode_barang'];
            $nama_barang = $barang_data['nama_barang'];

            $query = mysqli_query($connection, "UPDATE tbl_stok SET kode_transaksi = '$kode_transaksi', kode_barang = '$kode_barang', nama_barang = '$nama_barang', jumlah_stok = '$jumlah_stok' WHERE id = '$id'");

            if ($query == TRUE) {
                echo "<script>Swal.fire({title: 'Berhasil', text: 'Berhasil mengubah data stok', icon: 'success', showConfirmButton: false, timer: 1000, timerProgressBar: true}).then(() => { window.location.href = 'index.php?halaman=data_stok'; })</script>";
            } else {
                echo "<script>Swal.fire({title: 'Gagal', text: 'Gagal mengubah data stok', icon: 'error', showConfirmButton: false, timer: 2000, timerProgressBar: true}).then(() => { window.location.href = 'index.php?halaman=data_stok'; })</script>";
            }
        }
    }
} catch (\Throwable $th) {
    echo "<script>Swal.fire({title: 'Gagal', text: 'Server error!', icon: 'error', showConfirmButton: false, timer: 2000, timerProgressBar: true}).then(() => { window.location.href = 'index.php?halaman=data_stok'; })</script>";
}

$barang_query = mysqli_query($connection, "SELECT id, kode_barang, nama_barang FROM tbl_master_barang");
$current_kode_barang = isset($data['kode_barang']) ? $data['kode_barang'] : '';
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Stok</h3>
                <p class="text-subtitle text-muted">Halaman Ubah Data Stok</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?halaman=data_stok">Data Stok</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ubah Data Stok</li>
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
                        <input type="text" class="form-control" id="kode_transaksi" placeholder="TRX-001" name="kode_transaksi" value="<?= htmlspecialchars($data['kode_transaksi'] ?? '') ?>" required>
                        <label for="kode_transaksi">Kode Transaksi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="id_barang" name="id_barang" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php while ($barang = mysqli_fetch_assoc($barang_query)) : ?>
                                <option value="<?= $barang['id'] ?>" <?= ($barang['kode_barang'] == $current_kode_barang) ? 'selected' : '' ?>><?= htmlspecialchars($barang['kode_barang'] . ' - ' . $barang['nama_barang']) ?></option>
                            <?php endwhile ?>
                        </select>
                        <label for="id_barang">Pilih Barang</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="jumlah_stok" placeholder="100" name="jumlah_stok" value="<?= htmlspecialchars($data['jumlah_stok'] ?? '') ?>" required min="0">
                        <label for="jumlah_stok">Jumlah Stok</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>