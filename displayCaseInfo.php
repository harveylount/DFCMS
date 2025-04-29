<?php
if (isset($_GET['identifier'])) {

    $identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

    $query = "SELECT * FROM cases WHERE Identifier = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $identifier); 
    $stmt->execute();
    $results = $stmt->get_result();

    $sql = "SELECT CaseReference, LeadInvestigator FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($caseReference, $leadInvestigator);
$stmt->fetch();
mysqli_stmt_close($stmt);

$sql = "SELECT FullName FROM users WHERE Username = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $leadInvestigator);
$stmt->execute();
$stmt->bind_result($leadInvestigatorFullName);
$stmt->fetch();
mysqli_stmt_close($stmt);

    $sql = "SELECT Investigator FROM cases WHERE Identifier = ?";
    $stmtInvestigator = $connection->prepare($sql);
    $stmtInvestigator->bind_param("s", $identifier);
    $stmtInvestigator->execute();
    $stmtInvestigator->bind_result($investigator);
    $stmtInvestigator->fetch();
    mysqli_stmt_close($stmtInvestigator);

    $usernames = array_map('trim', explode(',', $investigator));
    $placeholders = implode(',', array_fill(0, count($usernames), '?'));

    $sql = "SELECT Username, FullName FROM users WHERE Username IN ($placeholders)";
    $stmtUsers = $connection->prepare($sql);
    $stmtUsers->execute($usernames);

    $resultsUsers = $stmtUsers->get_result();
    $investigatorNames = [];

    while ($rowUsers = $resultsUsers->fetch_assoc()) {
        $investigatorNames[$rowUsers['Username']] = $rowUsers['FullName'];
    }

    $displayNames = [];
    foreach ($usernames as $usernameFormat) {
        $displayNames[] = $investigatorNames[$usernameFormat] . ' (' . $usernameFormat . ')<br/>';

    }
    $formattedInvestigators = implode($displayNames);

    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Case Information</td> 
        <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>"; 
    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>";
    echo "</table>";
    echo "<br/>";

    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
    echo '<tr><td class="lbu-dark">Case Reference</td><td>' . $caseReference . '</td></tr>';
    echo '</table>';
    echo "<br/>";
    
    while ($row = mysqli_fetch_assoc($results)) {
        echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
        echo '<tr><td class="lbu-high">Case Name</td><td>' . $row['CaseName'] . '</td></tr>';
        echo '<td class="lbu-high">Lead Investigator</td><td>' . $leadInvestigatorFullName . ' (' . $row['LeadInvestigator'] . ')</td></tr>';
        echo '<td class="lbu-high">Investigators</td><td>' . $formattedInvestigators . '</td></tr>';
        echo '<tr><td class="lbu-high">Timestamp Created</td><td>' . $row['DateCreated'] . '</td></tr>';
        echo '<tr><td class="lbu-high">Case Deadline</td><td>' . $row['DeadlineDate'] . '</td></tr>';
        echo '<tr><td class="lbu-high">Case Timezone</td><td>' . $row['Timezone'] . '</td></tr>';
        echo '</table>';
    }

} else {
    header('location:index.php');
}
?>