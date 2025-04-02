<?php

$username=$_SESSION['userId'];

$query = "SELECT COUNT(*) FROM cases WHERE Identifier = ? AND FIND_IN_SET(?, REPLACE(Investigator, ' ', '')) > 0";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $identifier, $username);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count == 0) {
    header("Location: index.php");
    exit();
}

?>