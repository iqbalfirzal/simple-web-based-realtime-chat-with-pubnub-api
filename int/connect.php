<?php
error_reporting(0);
$koneksi=mysql_connect("localhost","root","root") or die("Akses ditolak");
$koneksi_database=mysql_select_db("onlinechat",$koneksi);
?>