<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_POST['submit'])) {
        $kode_barang = htmlspecialchars($_POST['kode_barang']);
        $nama_barang = htmlspecialchars($_POST['nama_barang']);
        $harga_beli = htmlspecialchars($_POST['harga_beli']);
        $harga_jual = htmlspecialchars($_POST['harga_jual']);

        $query = mysqli_query($connection, "INSERT INTO tbl_master_barang VALUES (null, '$kode_barang', '$nama_barang', '$harga_beli', '$harga_jual')");

        if ($query == TRUE) {
            $message = "Berhasil menambahkan data barang";
            echo "
        <script>
        Swal.fire({
            title: 'Berhasil',
            text: '$message',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
        }).then(() => {
            window.location.href = 'index.php?halaman=data_barang';
        })
        </script>
        ";
        } else {
            $message = "Gagal menambahkan data barang";
            echo "
        <script>
        Swal.fire({
            title: 'Gagal',
            text: '$message',
            icon: 'error',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        }).then(() => {
            window.location.href = 'index.php?halaman=data_barang';
        })
        </script>
        ";
        }
    }
} catch (\Throwable $th) {
    echo "
        <script>
        Swal.fire({
            title: 'Gagal',
            text: 'Server error!',
            icon: 'error',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        }).then(() => {
            window.location.href = 'index.php?halaman=data_barang';
        })
        </script>
        ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Barang</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tambah Data Barang
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=data_barang">Data Barang</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Data Barang
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=data_barang" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="kode_barang" placeholder="B001" name="kode_barang" required>
                        <label for="kode_barang">Kode Barang</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_barang" placeholder="Nama Barang" name="nama_barang" required>
                        <label for="nama_barang">Nama Barang</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="harga_beli" placeholder="0" name="harga_beli" step="0.01" required>
                        <label for="harga_beli">Harga Beli</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="harga_jual" placeholder="0" name="harga_jual" step="0.01" required>
                        <label for="harga_jual">Harga Jual</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>