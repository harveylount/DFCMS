<?php
$hostname = '127.0.0.1';
$username = 'root'; //your standard uni id
$password = ''; // the password found on the W: drive
$databaseName = 'dfcms'; //the name of the db you are using on phpMyAdmin
$connection = mysqli_connect($hostname, $username, $password, $databaseName) or exit("Unable to connect to database!");
session_start();
?>

