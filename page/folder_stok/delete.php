<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $select = mysqli_query($connection, "SELECT id FROM tbl_stok WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=data_stok');
        }

        $query = mysqli_query($connection, "DELETE FROM tbl_stok WHERE id = '$id'");

        if ($query == TRUE) {
            $message = "Berhasil menghapus data stok";
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
            window.location.href = 'index.php?halaman=data_stok';
        })
        </script>
        ";
        } else {
            $message = "Gagal menghapus data stok";
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
            window.location.href = 'index.php?halaman=data_stok';
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
            window.location.href = 'index.php?halaman=data_stok';
        })
        </script>
        ";
}
?>
