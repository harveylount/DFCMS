<?php
include 'SqlConnection.php';

if (isset($_POST['subEvent'])) {
    $caseReference=$_POST['txtCaseReference'];
    $caseName=$_POST['txtCaseName'];
    $dateDeadline=$_POST['dateDeadline'];
    $investigator=$_SESSION['userId'];
    $caseCreated = date('Y-m-d H:i:s');
    $CaseStatus = "open";

    $query = "INSERT INTO cases 
            (CaseReference, CaseName, LeadInvestigator, DateCreated, DeadlineDate, CaseStatus)
            VALUES
            (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $caseReference, $caseName, $investigator, $caseCreated, $dateDeadline, $CaseStatus);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('location:index.php');

    exit();

}


?>