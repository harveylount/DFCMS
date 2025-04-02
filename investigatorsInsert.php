<?php
include 'sqlConnection.php'; 

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
header("Location: viewCase.php?identifier=" . $identifier);

exit();

?>