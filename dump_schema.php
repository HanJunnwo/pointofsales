<?php
include "./function/connection.php";
$query = mysqli_query($connection, "SHOW CREATE TABLE tbl_stok");
$row = mysqli_fetch_assoc($query);
echo $row['Create Table'] . "\n\n";

$query2 = mysqli_query($connection, "SHOW CREATE TABLE tbl_master_barang");
$row2 = mysqli_fetch_assoc($query2);
echo $row2['Create Table'] . "\n\n";
?>
