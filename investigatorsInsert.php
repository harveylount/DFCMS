<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php';

if (!isset($_GET['identifier'])) {
    header("Location: index.php");
    exit();
}

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection

// Get selected users (investigators)
$selectedUsers = isset($_POST['users']) ? $_POST['users'] : [];

// Convert selected users to a comma-separated string
$investigators = $_SESSION['userId'] . ", " . implode(', ', $selectedUsers);

// Update the investigators in the database (excluding lead investigator)
$query = "UPDATE cases SET Investigator = ? WHERE Identifier = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("si", $investigators, $identifier);
$stmt->execute();
mysqli_stmt_close($stmt);

$sql = "SELECT CaseReference from cases WHERE Identifier = ?";
        $stmt = $connection->prepare($sql);
                $stmt->bind_param("s", $identifier);  
                $stmt->execute();
                $stmt->bind_result($caseReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);


        // Audit Log
        $action = "Updated case investigators. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ".";
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

header("Location: viewCase.php?identifier=" . $identifier);

exit();

?>