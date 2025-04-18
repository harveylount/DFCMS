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
    echo '<th class="lbu-dark">Evidence ID</th>';
    echo '<th class="lbu-dark">Exhibit Reference</th>';
    echo '<th class="lbu-dark">Case Reference</th>';
    echo '<th class="lbu-dark">Seized Timestamp</th>';
    echo '<th class="lbu-dark">Edited Timestamp</th>';
    echo '<th class="lbu-dark">Device Type</th>';
    echo '<th class="lbu-dark">Manufacturer</th>';
    echo '<th class="lbu-dark">Model</th>';
    echo '<th class="lbu-dark">Status</th>';
    echo '<th class="lbu-dark"></th>';
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