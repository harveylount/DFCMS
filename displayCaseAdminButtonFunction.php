<?php
$sql = "SELECT LeadInvestigator FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($leadInvestigator);
$stmt->fetch();
mysqli_stmt_close($stmt);

if ($_SESSION['userId'] = $leadInvestigator) {
    echo '<a href="caseAdmin.php?identifier=' . urlencode($identifier) .'" id="navcase-button">Case Admin</a>';
}
?>