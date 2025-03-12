<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  
$LBU03id = intval($_GET['LBU03id']);  

$query = "SELECT Username FROM lbu03 WHERE Identifier = ? AND EvidenceID = ? AND LBU03id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("sss", $identifier, $evidenceID, $LBU03id);  
$stmt->execute();
$stmt->bind_result($actionerUsername);
$stmt->fetch();
mysqli_stmt_close($stmt);

if ($_SESSION['userId'] != $actionerUsername) {
    header ('location:viewLBU03.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
    exit();
}

if (isset($_POST['subEvent'])) {

    $removeAction=$_POST['txtRemoveAction'];
    $removed = "Removed";
    $_SESSION['txtRemoveActionF']=$removeAction;



    if (preg_match('/^.{1,100}$/', $removeAction)) {
        $removeActionCheck = true;
    } else {
        $removeActionCheck = false;
        $_SESSION['txtRemoveActionM']='Maximum string length of 100 characters';
    }

    if ($removeActionCheck) {
        
        unset($_SESSION['txtRemoveActionF']);


        $query = "UPDATE lbu03 SET Removed = ?, RemovedReason = ? WHERE Identifier = ? AND EvidenceID = ? AND LBU03id = ?";
        $stmt = $connection->prepare($query);
        mysqli_stmt_bind_param($stmt, "sssss", $removed, $removeAction, $identifier, $evidenceID, $LBU03id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        

        header('Location: viewLBU03.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: removeLBU03EntryForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID . '&LBU03id=' . $LBU03id);
        exit();
    }
    
}


?>