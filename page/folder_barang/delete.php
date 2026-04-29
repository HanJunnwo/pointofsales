<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $select = mysqli_query($connection, "SELECT nama_barang FROM tbl_master_barang WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=data_barang');
        }

        $query = mysqli_query($connection, "DELETE FROM tbl_master_barang WHERE id = '$id'");

        if ($query == TRUE) {
            $message = "Berhasil menghapus data barang";
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
            $message = "Gagal menghapus data barang";
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
    } else {
        $message = "ID tidak ditemukan";
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
