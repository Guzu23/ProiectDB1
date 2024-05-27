<?php 
$dbms = 'mysql';
$host = 'mysql_db';
$db = 'carsPDO';
$user = 'root';
$pass = 'toor';
$dsn = "$dbms:host=$host;dbname=$db";
$con=new PDO($dsn, $user, $pass);

$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);