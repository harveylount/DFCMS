<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

if (isset($_POST['subEvent'])) {
    $caseReference=$_POST['txtCaseReference'];
    $caseName=$_POST['txtCaseName'];
    $dateDeadline=$_POST['dateDeadline'];
    $investigator=$_SESSION['userId'];
    $caseCreated = date('Y-m-d H:i:s');
    $CaseStatus = "Open";
    $_SESSION['txtCaseReferenceF']=$caseReference;
    $_SESSION['txtCaseNameF']=$caseName;
    $timezone = $_POST['timezone'];

    if (preg_match('/^[a-zA-Z0-9\/]+$/', $caseReference)) {
        $caseReferenceCheck = true;
    } else {
        $caseReferenceCheck = false;
        $_SESSION['txtCaseReferenceM']=' Must only contain alpha, numbers and "/" characters';
    }

    if (preg_match('/^[a-zA-Z0-9\s]+$/', $caseName)) {
        $caseNameCheck = true;
    } else {
        $caseNameheck = false;
        $_SESSION['txtCaseNameM']=' Must only contain alpha, numbers and space characters';
    }

    if ($caseReferenceCheck && $caseNameCheck) {
        unset($_SESSION['txtCaseReferenceF']);
        unset($_SESSION['txtCaseNameF']);

        $query = "INSERT INTO cases 
                (CaseReference, CaseName, LeadInvestigator, DateCreated, DeadlineDate, CaseStatus, Timezone)
                VALUES
                (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $caseReference, $caseName, $investigator, $caseCreated, $dateDeadline, $CaseStatus, $timezone);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('location:index.php');

        exit();

    } else {
        header('location:createCaseForm.php');
        exit();
    }
    
}


?>