<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection

$query = "SELECT Notes, CaseReference FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $identifier);  
$stmt->execute();
$stmt->bind_result($oldCaseNotes, $caseReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

$fullName=$_SESSION['fullName'];
$username=$_SESSION['userId'];
$timestamp = date('Y-m-d H:i:s');


if (isset($_POST['subEvent'])) {

    $newCaseNotes=$_POST['txtCaseNotes'];
    $_SESSION['txtNewCaseNotesF']=$newCaseNotes;

    if (strlen($newCaseNotes) <= 10000) {
        $newCaseNotesCheck = true;
    } else {
        $newCaseNotesCheck = false;
        $_SESSION['txtNewCaseNotesM'] = 'Maximum length of 10000 characters';
    }

    if ($newCaseNotesCheck) {
        
        unset($_SESSION['txtNewCaseNotesF']);

        $query = "UPDATE cases SET Notes = ?, NotesEditorFullName = ?, NotesEditorUsername = ? WHERE Identifier = ?";
        $stmt = $connection->prepare($query);
        mysqli_stmt_bind_param($stmt, "ssss", $newCaseNotes, $username, $fullName, $identifier);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "INSERT INTO casenotesbackup 
                (Identifier, CaseReference, CaseNotesBackup, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername)
                VALUES
                (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $identifier, $caseReference, $oldCaseNotes, $timestamp, $fullName, $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Updated case notes. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ".";
        $type = "Case";

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $identifier, $caseReference, $type, $timestamp, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        

        header('Location: viewCaseNotes.php?identifier=' . $identifier);
        exit();

    } else {
        header('Location: editCaseNotesForm.php?identifier=' . $identifier);
        exit();
    }
    
}


?>