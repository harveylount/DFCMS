<?php

    $username=$_SESSION['userId'];

    $query = "SELECT * FROM cases WHERE FIND_IN_SET(?, REPLACE(Investigator, ' ', '')) > 0";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $results = $stmt->get_result();

    echo '<style>';
    echo 'table { border-collapse: collapse; }'; 
    echo 'td, th { border: 1px solid black; padding: 5px; }';
    echo '</style>';

    echo '<table>';
    echo '<tr>'; // Start of the title row
    echo '<th>Case Reference</th>';
    echo '<th>Case Name</th>';
    echo '<th>Lead Investigator</th>';
    echo '<th>Timestamp Created</th>';
    echo '<th>Deadline Date</th>';
    echo '<th>Status</th>';
    echo '<th></th>';
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