<?php
$identifier = intval($_GET['identifier']);  
$sql = "SELECT Timezone FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($caseTimezone);
$stmt->fetch();
mysqli_stmt_close($stmt);
date_default_timezone_set($caseTimezone);
?>