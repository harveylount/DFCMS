<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  

if (isset($_POST['subEvent'])) {

    $action=$_POST['txtAction'];

    $timestampDatabaseLBU03=$_SESSION['timestampDatabaseLBU03'];
    $timestampDisplayLBU03=$_SESSION['timestampDisplayLBU03'];
    $fullName=$_SESSION['fullName'];
    $username=$_SESSION['userId'];

    $_SESSION['txtActionF']=$action;


    if (preg_match('/^.{1,400}$/', $action)) {
        $actionCheck = true;
    } else {
        $actionCheck = false;
        $_SESSION['txtActionM']='Maximum string length of 400 characters';
    }

    if ($actionCheck) {
        
        unset($_SESSION['txtActionF']);
        unset($_SESSION['timestampDisplayLBU03']);
        unset($_SESSION['timestampDatabaseLBU03']);


        // SQL query to get case reference
        $sqlCaseRef = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($sqlCaseRef);
        $stmt->bind_param("ss", $identifier, $evidenceID);
        $stmt->execute();
        $stmt->bind_result($caseReference, $exhibitReference);
        $stmt->fetch();
        mysqli_stmt_close($stmt); 

        $query = "INSERT INTO lbu03
                (Identifier, CaseReference, EvidenceID, ExhibitRef, Timestamp, Action, FullName, UserName)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampDatabaseLBU03, $action, $fullName, $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $sql = "SELECT LBU03id from lbu03 WHERE Identifier = ? AND CaseReference = ? AND EvidenceID = ? AND ExhibitRef = ? AND Timestamp = ? AND Action = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("ssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampDatabaseLBU03, $action);  
                $stmt->execute();
                $stmt->bind_result($LBU03id);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Created an LBU03 entry. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". LBU03 ID: " . $LBU03id . ".";
        $type = "Exhibit";
        $fullName = $_SESSION['fullName'];
        $username = $_SESSION['userId'];    

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, LBU03id, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $LBU03id, $timestampDatabaseLBU03, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        

        header('Location: viewLBU03.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: createLBU03EntryForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}


?>