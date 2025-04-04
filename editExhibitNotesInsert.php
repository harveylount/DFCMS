<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

$query = "SELECT Notes, CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$stmt->bind_result($oldExhibitNotes, $caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

$fullName=$_SESSION['fullName'];
$username=$_SESSION['userId'];
$timestamp = date('Y-m-d H:i:s');


if (isset($_POST['subEvent'])) {

    $newExhibitNotes=$_POST['txtExhibitNotes'];
    $_SESSION['txtNewExhibitNotesF']=$newExhibitNotes;

    if (strlen($newExhibitNotes) <= 10000) {
        $newExhibitNotesCheck = true;
    } else {
        $newExhibitNotesCheck = false;
        $_SESSION['txtNewExhibitNotesM'] = 'Maximum length of 10000 characters';
    }

    if ($newExhibitNotesCheck) {
        
        unset($_SESSION['txtNewExhibitNotesF']);

        $query = "UPDATE evidence SET Notes = ?, NotesEditorFullName = ?, NotesEditorUsername = ? WHERE Identifier = ?";
        $stmt = $connection->prepare($query);
        mysqli_stmt_bind_param($stmt, "ssss", $newExhibitNotes, $username, $fullName, $identifier);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "INSERT INTO exhibitnotesbackup 
                (Identifier, CaseReference, EvidenceID, ExhibitRef, ExhibitNotesBackup, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $oldExhibitNotes, $timestamp, $fullName, $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        

        header('Location: viewExhibitNotes.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: editExhibitNotesForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}


?>