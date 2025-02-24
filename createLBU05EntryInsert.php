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

        $querySealUpdate = "UPDATE evidence SET CurrentSeal = ? WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $querySealUpdate);
        mysqli_stmt_bind_param($stmt, "sii", $sealNumber, $identifier, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $unset_sessions = ['timestampInDatabaseLBU05', 'timestampInDisplayLBU05'];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        header('Location: viewLBU05.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: createLBU05EntryInForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}



if (isset($_POST['subEventLBU05Out'])) {
   
    $reasonOut=$_POST['txtReason'];
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


    if ($reasonCheck) {
        
        unset($_SESSION['txtReasonF']);

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
                (Identifier, CaseReference, EvidenceID, ExhibitRef, TimestampOut, OriginalLocation, ReasonOut, SealNumberOut, ActionerOut, ActionerOutUsername, Validate)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $timestampOutDatabase, $originalLocation, $reasonOut, $currentSeal, $fullName, $username, $validate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        
        $unset_sessions = ['timestampOutDatabaseLBU05', 'timestampOutDisplayLBU05'];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

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

        
        $unset_sessions = ['timestampFirstInDatabaseLBU05', 'timestampFirstInDisplayLBU05'];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        header('Location: viewLBU05.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: createLBU05FirstEntryInForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}
?>