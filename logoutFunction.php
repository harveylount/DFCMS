<?php
include 'sqlconnection.php'; 
date_default_timezone_set("Europe/London");
$timestamp = date('Y-m-d H:i:s');

$sql = "SELECT ID FROM users WHERE Username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $_SESSION['userId']);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

// Audit Log
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];
$action = "User logged out. Full Name: " . $fullName . ". Username: " . $username . ". User ID: " . $userId;
$type = "Auth";

$query = "INSERT INTO auditlog 
    (UserID, EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
    VALUES
(?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssss", $userId, $type, $timestamp, $fullName, $username, $action);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

unset($_SESSION['userId']);
session_destroy();
header('location:./index.php');

?>