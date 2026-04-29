<?php
include "./function/connection.php";

if (!isset($_SESSION['nama'])) {
    header('Location: index.php?halaman=login');
}


try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Select Data
        $select = mysqli_query($connection, "SELECT * FROM tbl_master_barang WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=data_barang');
        }

        // Submit
        if (isset($_POST['submit'])) {
            $kode_barang = htmlspecialchars($_POST['kode_barang']);
            $nama_barang = htmlspecialchars($_POST['nama_barang']);
            $harga_beli = htmlspecialchars($_POST['harga_beli']);
            $harga_jual = htmlspecialchars($_POST['harga_jual']);

            $query = mysqli_query($connection, "UPDATE tbl_master_barang SET kode_barang = '$kode_barang', nama_barang = '$nama_barang', harga_beli = '$harga_beli', harga_jual = '$harga_jual' WHERE id = '$id'");

            if ($query == TRUE) {
                $message = "Berhasil mengubah data barang";
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
                $message = "Gagal mengubah data barang";
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
                    Halaman Ubah Data Barang
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=data_barang">Data Barang</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Ubah Data Barang
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
                        <input type="text" class="form-control" id="kode_barang" placeholder="Kode Barang" name="kode_barang" value="<?= $data['kode_barang'] ?>" required>
                        <label for="kode_barang">Kode Barang</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_barang" placeholder="Nama Barang" name="nama_barang" value="<?= $data['nama_barang'] ?>" required>
                        <label for="nama_barang">Nama Barang</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="harga_beli" placeholder="0" name="harga_beli" step="0.01" value="<?= $data['harga_beli'] ?>" required>
                        <label for="harga_beli">Harga Beli</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="harga_jual" placeholder="0" name="harga_jual" step="0.01" value="<?= $data['harga_jual'] ?>" required>
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