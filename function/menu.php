<?php

if (isset($_GET['halaman'])) {
    $halaman = $_GET['halaman'];
    switch ($halaman) {
        case 'beranda':
            include "page/index.php";
            break;
        case 'logout':
            include "page/logout.php";
            break;
        case 'kontak':
            include "page/contact/view.php";
            break;
        case 'tambah_kontak':
            include "page/contact/add.php";
            break;
        case 'ubah_kontak':
            include "page/contact/edit.php";
            break;
        case 'hapus_kontak':
            include "page/contact/delete.php";
            break;
        case 'data_barang':
            include "page/folder_barang/view.php";
            break;
        case 'tambah_data_barang':
            include "page/folder_barang/add.php";
            break;
        case 'ubah_data_barang':
            include "page/folder_barang/edit.php";
            break;
        case 'hapus_data_barang':
            include "page/folder_barang/delete.php";
            break;
        case 'data_stok':
            include "page/folder_stok/view.php";
            break;
        case 'tambah_data_stok':
            include "page/folder_stok/add.php";
            break;
        case 'ubah_data_stok':
            include "page/folder_stok/edit.php";
            break;
        case 'hapus_data_stok':
            include "page/folder_stok/delete.php";
            break;
        case 'data_penjualan':
            include "page/pos/view.php";
            break;
        case 'tambah_data_penjualan':
            include "page/pos/add.php";
            break;
        default:
            include "page/error.php";
    }
} else {
    include "page/index.php";
}
