<?php
if (isset($_GET['identifier'])) {

    $identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

    $query = "SELECT * FROM cases WHERE Identifier = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $identifier);  // "i" for integer type parameter
    $stmt->execute();
    $results = $stmt->get_result();
    
    while ($row = mysqli_fetch_assoc($results)) {
        echo "<h2>Case " . $row['CaseReference'] . "</h2>";
        echo "Case Reference: " . $row['CaseReference'];
        echo "<br /><br />Case Name: " . $row['CaseName'];
        echo "<br /><br />Lead Investigator: " . $row['LeadInvestigator'];
        echo "<br /><br />Investigators: " . $row['Investigator'];
        echo "<br /><br />Case Created: " . $row['DateCreated'];
        echo "<br /><br />Case Deadline: " . $row['DeadlineDate'];
        echo "<br /><br />Case Timezone: " . $row['Timezone'];
        echo "<br /><br />Suspects: " . $row['Suspect'];
        echo "<br /><br />Notes: " . $row['Notes'];

    }

} else {
    header('location:index.php');
}
?>