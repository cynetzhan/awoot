<?php
 $config['db_host'] = 'localhost';
 $config['db_user'] = 'root';
 $config['db_pass'] = '';
 $config['db_name'] = 'dev';
 $db = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('Koneksi Tidak Dapat Dibuat!');
?>
