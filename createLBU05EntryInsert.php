<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);  
$evidenceID = intval($_GET['EvidenceID']);  

if (isset($_POST['subEventLBU05In'])) {
   
    $newLocation=$_POST['txtNewLocation'];
    $sealNumber=$_POST['txtSealNumber'];
    $timestampInDatabase=$_SESSION['timestampInDatabaseLBU05'];
    $timestampInDisplay=$_SESSION['timestampInDisplayLBU05'];
    $fullName=$_SESSION['fullName'];
    $username=$_SESSION['userId'];
    $validate="In";

    $_SESSION['txtNewLocationF']=$newLocation;
    $_SESSION['txtSealNumberF']=$sealNumber;
    

    if (preg_match('/^.{1,30}$/', $newLocation)) {
        $newLocationCheck = true;
    } else {
        $newLocationCheck = false;
        $_SESSION['txtNewLocationM']='Maximum string length of 30 characters';
    }

    if (preg_match('/^.{1,16}$/', $sealNumber)) {
        $sealNumberCheck = true;
    } else {
        $sealNumberCheck = false;
        $_SESSION['txtSealNumberM']='Maximum string length of 16 characters';
    }


    if ($newLocationCheck && $sealNumberCheck) {
        
        unset($_SESSION['txtNewLocationF']);
        unset($_SESSION['txtSealNumberF']);

        // SQL query to get case reference
        $sqlCaseRef = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($sqlCaseRef);
        $stmt->bind_param("ss", $identifier, $evidenceID);
        $stmt->execute();
        $stmt->bind_result($caseReference, $exhibitReference);
        $stmt->fetch();
        mysqli_stmt_close($stmt); 

        $query = "INSERT INTO lbu05 
                (Identifier, CaseReference, EvidenceID, ExhibitRef, TimestampIn, NewLocation, SealNumberIn, ActionerIn, ActionerInUsername, Validate)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampInDatabase, $newLocation, $sealNumber, $fullName, $username, $validate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $querySealUpdate = "UPDATE evidence SET CurrentSeal = ?, CurrentLocation = ? WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $querySealUpdate);
        mysqli_stmt_bind_param($stmt, "ssii", $sealNumber, $newLocation, $identifier, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $unset_sessions = ['timestampInDatabaseLBU05', 'timestampInDisplayLBU05'];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        $sql = "SELECT LBU05id from lbu05 WHERE Identifier = ? AND CaseReference = ? AND EvidenceID = ? AND ExhibitRef = ? AND TimestampIn = ? AND NewLocation = ? AND SealNumberIn = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("sssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampInDatabase, $newLocation, $sealNumber);  
                $stmt->execute();
                $stmt->bind_result($LBU05id);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Created an LBU05 in entry. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". LBU05 ID: " . $LBU05id . ".";
        $type = "Exhibit";
        $fullName = $_SESSION['fullName'];
        $username = $_SESSION['userId'];    

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, LBU05id, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $LBU05id, $timestampInDatabase, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: viewLBU05.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: createLBU05EntryInForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}



if (isset($_POST['subEventLBU05Out'])) {
   
    $reasonOut=$_POST['txtReason'];
    $tempLocation=$_POST['txtTempLocation'];
    $timestampOutDatabase=$_SESSION['timestampOutDatabaseLBU05'];
    $timestampOutDisplay=$_SESSION['timestampOutDisplayLBU05'];
    $fullName=$_SESSION['fullName'];
    $username=$_SESSION['userId'];
    $validate="Out";

    $_SESSION['txtReasonF']=$reasonOut;

    

    if (preg_match('/^.{1,30}$/', $reasonOut)) {
        $reasonCheck = true;
    } else {
        $reasonCheck = false;
        $_SESSION['txtReasonM']='Maximum string length of 30 characters';
    }

    if (preg_match('/^.{1,50}$/', $tempLocation)) {
        $tempLocationCheck = true;
    } else {
        $tempLocationCheck = false;
        $_SESSION['txtTempLocationM']='Maximum string length of 50 characters';
    }


    if ($reasonCheck && $tempLocationCheck) {
        
        unset($_SESSION['txtReasonF']);
        unset($_SESSION['txtTempLocationF']);

        // SQL query to get case reference
        $sqlCaseRef = "SELECT CaseReference, ExhibitRef, CurrentSeal FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($sqlCaseRef);
        $stmt->bind_param("ss", $identifier, $evidenceID);
        $stmt->execute();
        $stmt->bind_result($caseReference, $exhibitReference, $currentSeal);
        $stmt->fetch();
        mysqli_stmt_close($stmt); 

        $sql = "SELECT * FROM LBU05 WHERE Identifier = ? AND EvidenceID = ? ORDER BY LBU05id DESC LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $identifier, $evidenceID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $originalLocation = $row['NewLocation'];
        }
        mysqli_stmt_close($stmt); 


        $query = "INSERT INTO lbu05 
                (Identifier, CaseReference, EvidenceID, ExhibitRef, TimestampOut, OriginalLocation, TempLocation, ReasonOut, SealNumberOut, ActionerOut, ActionerOutUsername, Validate)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampOutDatabase, $originalLocation, $tempLocation, $reasonOut, $currentSeal, $fullName, $username, $validate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "UPDATE evidence SET CurrentLocation = ? WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sii", $tempLocation, $identifier, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $unset_sessions = ['timestampOutDatabaseLBU05', 'timestampOutDisplayLBU05'];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        $sql = "SELECT LBU05id from lbu05 WHERE Identifier = ? AND CaseReference = ? AND EvidenceID = ? AND ExhibitRef = ? AND TimestampOut = ? AND TempLocation = ? AND ReasonOut = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("sssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampOutDatabase, $tempLocation, $reasonOut);  
                $stmt->execute();
                $stmt->bind_result($LBU05id);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Created an LBU05 out entry. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". LBU05 ID: " . $LBU05id . ".";
        $type = "Exhibit";
        $fullName = $_SESSION['fullName'];
        $username = $_SESSION['userId'];    

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, LBU05id, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $LBU05id, $timestampOutDatabase, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: viewLBU05.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: createLBU05EntryInForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}



if (isset($_POST['subEventLBU05FirstIn'])) {
   
    $newLocation=$_POST['txtFirstNewLocation'];
    $timestampFirstInDatabase=$_SESSION['timestampFirstInDatabaseLBU05'];
    $timestampFirstInDisplay=$_SESSION['timestampFirstInDisplayLBU05'];
    $fullName=$_SESSION['fullName'];
    $username=$_SESSION['userId'];
    $validate="In";

    $_SESSION['txtFirstNewLocationF']=$newLocation;
    

    if (preg_match('/^.{1,30}$/', $newLocation)) {
        $newLocationCheck = true;
    } else {
        $newLocationCheck = false;
        $_SESSION['txtFirstNewLocationM']='Maximum string length of 30 characters';
    }



    if ($newLocationCheck) {
        
        unset($_SESSION['txtFirstNewLocationF']);

        // SQL query to get case reference
        $sqlCaseRef = "SELECT CaseReference, ExhibitRef, CurrentSeal FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($sqlCaseRef);
        $stmt->bind_param("ss", $identifier, $evidenceID);
        $stmt->execute();
        $stmt->bind_result($caseReference, $exhibitReference, $currentSeal);
        $stmt->fetch();
        mysqli_stmt_close($stmt); 


        $query = "INSERT INTO lbu05 
                (Identifier, CaseReference, EvidenceID, ExhibitRef, TimestampIn, NewLocation, SealNumberIn, ActionerIn, ActionerInUsername, Validate)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampFirstInDatabase, $newLocation, $currentSeal, $fullName, $username, $validate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $querySealUpdate = "UPDATE evidence SET CurrentLocation = ? WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $querySealUpdate);
        mysqli_stmt_bind_param($stmt, "sii", $newLocation, $identifier, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        
        $unset_sessions = ['timestampFirstInDatabaseLBU05', 'timestampFirstInDisplayLBU05'];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        $sql = "SELECT LBU05id from lbu05 WHERE Identifier = ? AND CaseReference = ? AND EvidenceID = ? AND ExhibitRef = ? AND TimestampIn = ? AND NewLocation = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("ssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampFirstInDatabase, $newLocation);  
                $stmt->execute();
                $stmt->bind_result($LBU05id);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Created first LBU05 entry in. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". LBU05 ID: " . $LBU05id . ".";
        $type = "Exhibit";
        $fullName = $_SESSION['fullName'];
        $username = $_SESSION['userId'];    

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, LBU05id, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $LBU05id, $timestampInDatabase, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: viewLBU05.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: createLBU05FirstEntryInForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}
?>