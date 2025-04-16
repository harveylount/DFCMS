<?php
include 'SqlConnection.php';
include 'timezoneFunction.php';

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


        $sql = "SELECT CaseReference from cases WHERE Identifier = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("s", $identifier);  
                $stmt->execute();
                $stmt->bind_result($caseReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);


        // Audit Log
        $action = "Updated case deadline. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ".";
        $type = "Case";
        $timestamp = date('Y-m-d H:i:s');
        $fullName = $_SESSION['fullName'];
        $username = $_SESSION['userId'];


        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $identifier, $caseReference, $type, $caseCreated, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);


        header('location:viewCase.php?identifier=' . $identifier);

        exit();

} else {
    header('location:caseAdmin.php?identifier=' . $identifier);
    exit();
    
}


?>