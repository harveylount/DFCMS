<?php

    $query = "SELECT * FROM evidence WHERE Identifier = $identifier";
    $results = mysqli_query($connection, $query);
    $query2 = "SELECT CaseReference FROM cases WHERE Identifier = $identifier";
    $results2 = mysqli_query($connection, $query2);

    $caseReferenceRow = mysqli_fetch_assoc($results2);
    $caseReference = $caseReferenceRow['CaseReference'] ?? 'No Case Reference';

    echo '<style>';
    echo 'table { border-collapse: collapse; }'; 
    echo 'td, th { border: 1px solid black; padding: 5px; }';
    echo '</style>';

    echo '<table>';
    echo '<tr>'; // Start of the title row
    echo '<th>Evidence ID</th>';
    echo '<th>Exhibit Reference</th>';
    echo '<th>Case Reference</th>';
    echo '<th>Seized Timestamp</th>';
    echo '<th>Edited Timestamp</th>';
    echo '<th>Device Type</th>';
    echo '<th>Manufacturer</th>';
    echo '<th>Model</th>';
    echo '<th>Status</th>';
    echo '<th></th>';
    echo '</tr>'; // End of the title row
    
    while ($row = mysqli_fetch_assoc($results)) {
        echo '<tr>'; // Start of a data row
        echo '<td>' . $row['EvidenceID'] . '</td>';
        echo '<td>' . $row['ExhibitRef'] . '</td>';
        echo '<td>' . $caseReference . '</td>';
        echo '<td>' . $row['SeizedTime'] . '</td>';
        echo '<td>' . $row['EditedTime'] . '</td>';
        echo '<td>' . $row['DeviceType'] . '</td>';
        echo '<td>' . $row['Manufacturer'] . '</td>';
        echo '<td>' . $row['Model'] . '</td>';
        echo '<td>' . $row['EvidenceStatus'] . '</td>';
        echo '<td><a href="viewEvidenceExhibit.php?identifier=' . $row['Identifier'] . '&EvidenceID=' . $row['EvidenceID'] . '">View Evidence</a></td>';
        echo '</tr>'; // End of a data row
    }
    
    echo '</table>';

?>