<?php

    $username=$_SESSION['userId'];

    $query = "SELECT * FROM cases WHERE FIND_IN_SET(?, REPLACE(Investigator, ' ', '')) > 0";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $results = $stmt->get_result();

    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";

    echo '<tr>'; // Start of the title row
    echo '<th class="lbu-dark">Case Reference</th>';
    echo '<th class="lbu-dark">Case Name</th>';
    echo '<th class="lbu-dark">Lead Investigator</th>';
    echo '<th class="lbu-dark">Timestamp Created</th>';
    echo '<th class="lbu-dark">Deadline Date</th>';
    echo '<th class="lbu-dark">Status</th>';
    echo '<th class="lbu-dark"></th>';
    echo '</tr>'; // End of the title row
    
    while ($row = mysqli_fetch_assoc($results)) {
        echo '<tr>'; // Start of a data row
        echo '<td>' . $row['CaseReference'] . '</td>';
        echo '<td>' . $row['CaseName'] . '</td>';
        echo '<td>' . $row['LeadInvestigator'] . '</td>';
        echo '<td>' . $row['DateCreated'] . '</td>';
        echo '<td>' . $row['DeadlineDate'] . '</td>';
        echo '<td>' . $row['CaseStatus'] . '</td>';
        echo '<td><a href="viewCase.php?identifier=' . $row['Identifier'] . '">View Case</a></td>';
        echo '</tr>'; // End of a data row
    }
    
    echo '</table>';

?>