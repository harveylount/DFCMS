<?php
include 'SqlConnection.php';

if (!isset($_GET['identifier'])) {
    header("Location: index.php");
    exit();
}

date_default_timezone_set("Europe/London");

$identifier = intval($_GET['identifier']);

if (isset($_POST['subEvent'])) {

    $dateDeadline=$_POST['dateDeadline'];

        $query = "UPDATE cases SET DeadlineDate = ? WHERE Identifier = ?";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ss", $dateDeadline, $identifier); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('location:viewCase.php?identifier=' . $identifier);

        exit();

} else {
    header('location:caseAdmin.php?identifier=' . $identifier);
    exit();
    
}


?>