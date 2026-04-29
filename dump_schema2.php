<?php
$c=mysqli_connect('localhost','root','Raihan110123041','db_post');
$res = [];
$q=mysqli_query($c,'DESCRIBE tbl_stok');
while($r=mysqli_fetch_assoc($q)){$res['stok'][]=$r;}
$q=mysqli_query($c,'DESCRIBE tbl_master_barang');
while($r=mysqli_fetch_assoc($q)){$res['barang'][]=$r;}
file_put_contents('schema.json', json_encode($res, JSON_PRETTY_PRINT));
?>
