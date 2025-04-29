<?php
include 'SqlConnection.php';
date_default_timezone_set("Europe/London");

if (isset($_POST['subEvent'])) {
    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];

    $caseReference=$_POST['txtCaseReference'];
    $caseName=$_POST['txtCaseName'];
    $dateDeadline=$_POST['dateDeadline'];
    $investigator=$_SESSION['userId'];
    $caseCreated = date('Y-m-d H:i:s');
    $CaseStatus = "Open";
    $timezone = $_POST['timezone'];
    $_SESSION['txtCaseReferenceF']=$caseReference;
    $_SESSION['txtCaseNameF']=$caseName;
    $_SESSION['txtDateDeadlineF']=$dateDeadline;
    $_SESSION['txtTimezoneF']=$timezone;

    if (preg_match('/^[a-zA-Z0-9\/]{11}$/', $caseReference)) {
        $caseReferenceCheck = true;
    } else {
        $caseReferenceCheck = false;
        $_SESSION['txtCaseReferenceM']=' Must only contain alpha, numbers, "/" characters and 11 character length';
    }

    if (preg_match('/^[a-zA-Z0-9\s]+$/', $caseName)) {
        $caseNameCheck = true;
    } else {
        $caseNameheck = false;
        $_SESSION['txtCaseNameM']=' Must only contain alpha, numbers and space characters';
    }

    $sql = "SELECT CaseReference FROM cases WHERE CaseReference = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $caseReference);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['txtcaseReferenceExistsM'] = 'Case Reference already exists';
        header('Location: createCaseForm.php');
        exit();
    }

    if ($caseReferenceCheck && $caseNameCheck) {

        unset($_SESSION['txtCaseReferenceF']);
        unset($_SESSION['txtCaseNameF']);

        $query = "INSERT INTO cases 
            (CaseReference, CaseName, LeadInvestigator, Investigator, DateCreated, DeadlineDate, CaseStatus, Timezone)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssss", $caseReference, $caseName, $investigator, $investigator, $caseCreated, $dateDeadline, $CaseStatus, $timezone);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $sql = "SELECT Identifier from cases WHERE CaseReference = ? AND CaseName = ? AND DateCreated = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("sss", $caseReference, $caseName, $caseCreated);  
                $stmt->execute();
                $stmt->bind_result($identifier);
                $stmt->fetch();
                mysqli_stmt_close($stmt);


        // Audit Log
        $action = "Created a case. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ".";
        $type = "Case";

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $identifier, $caseReference, $type, $caseCreated, $fullName, $username, $action);
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