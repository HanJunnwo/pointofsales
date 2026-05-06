<?php
$c = mysqli_connect('localhost', 'root', 'Raihan110123041', 'db_post');
$q = mysqli_query($c, 'SHOW TABLES');
while ($r = mysqli_fetch_row($q)) {
    echo $r[0] . "\n";
    $q2 = mysqli_query($c, 'DESCRIBE ' . $r[0]);
    while ($r2 = mysqli_fetch_assoc($q2)) {
        echo '  ' . $r2['Field'] . ' ' . $r2['Type'] . "\n";
    }
}
?>
