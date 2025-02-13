<?php
include 'sqlconnection.php'; 
unset($_SESSION['userId']);
session_destroy();
header('location:./index.php');

?>