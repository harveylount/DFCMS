<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>LBU05</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewEvidence.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" class="logout-button">← Exhibits</a>
            </div>
            <div class="right-group">
                <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
                <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
                <a href="logoutFunction.php" class="logout-button">Logout</a>
            </div>
        </div>

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        <div id="navcase-bar">
            <a href="<?php echo "viewEvidenceExhibit.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Evidence Overview</a>
            <a href="<?php echo "viewLBU01.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU01</a>
            <a href="<?php echo "viewLBU02.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU02</a>
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <?php include 'lbu04notComputerFunction.php'; ?>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
            <a href="<?php echo "listImageFiles.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Files</a>
        </div>

        <section id="LBU">

            <div id="navcase-bar">
                <a href="<?php echo "createLBU05EntryInForm.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Create Entry</a>
            </div>

            <p>
                <?php
                    // Step 1: Fetch the first row (for the first table)
                    $sqlFirstRow = 
                        "SELECT TimestampIn, NewLocation, SealNumberIn, ActionerIn, ActionerInUsername, ActionerOutUsername, Validate
                        FROM LBU05 
                        WHERE Identifier = ? AND EvidenceID = ? 
                        ORDER BY LBU05id ASC LIMIT 1";

                    $stmtFirstRow = $connection->prepare($sqlFirstRow);
                    $stmtFirstRow->bind_param("ii", $identifier, $evidenceID);
                    $stmtFirstRow->execute();
                    $resultFirstRow = $stmtFirstRow->get_result();

                    $query = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("ss", $identifier, $evidenceID);  
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $rowRef = mysqli_fetch_assoc($results);
                    $stmt->close();

                    $sqlTempLocation = 
                        "SELECT TempLocation
                        FROM LBU05 
                        WHERE Identifier = ? AND EvidenceID = ? 
                        ORDER BY LBU05id DESC LIMIT 1";

                    $stmtTempLocation = $connection->prepare($sqlTempLocation);
                    $stmtTempLocation->bind_param("ii", $identifier, $evidenceID);
                    $stmtTempLocation->execute();
                    $resultTempLocation = $stmtTempLocation->get_result();

                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                            <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'LBU05 - Exhibit Log Book' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $rowRef['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $rowRef['ExhibitRef'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    if ($resultTempLocation->num_rows > 0) {
                        $rowTempLocation = $resultTempLocation->fetch_assoc();
                        
                        if (!empty($rowTempLocation['TempLocation'])) {
                            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                            echo "<tr><td class='lbu-high'>Temporary Location</td><td>" . $rowTempLocation['TempLocation'] . "</td></tr>";
                            echo "</table>";
                            echo "<br/>";
                        }
                    }

                    if ($resultFirstRow->num_rows > 0) {
                        $rowFirst = $resultFirstRow->fetch_assoc();
                        
                        // Display the first row in the first table
                        echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                        echo '<thead><tr><th class="lbu-high">Timestamp In</th><th class="lbu-high">New Location</th><th class="lbu-high">Seal Number</th><th class="lbu-high">Actioner</th></tr></thead>';
                        echo '<tbody>';
                        echo '<tr>';
                        echo '<td>' . ($rowFirst['TimestampIn'] ?? 'N/A') . '</td>';
                        echo '<td>' . ($rowFirst['NewLocation'] ?? 'N/A') . '</td>';
                        echo '<td>' . ($rowFirst['SealNumberIn'] ?? 'N/A') . '</td>';
                        echo '<td>' . ($rowFirst['ActionerIn'] . " (" . $rowFirst['ActionerInUsername'] . ")" ?? 'N/A') . '</td>';
                        echo '</tr>';
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo 'No first row found.';
                    }

                    echo "</br>";

                    // Close the first row statement
                    $stmtFirstRow->close();

                    // Step 1: Fetch the rows (skip the first one, and join the rest)
                    $sqlRows = "SELECT TimestampOut, OriginalLocation, ReasonOut, SealNumberOut, ActionerOut, 
                                TimestampIn, NewLocation, SealNumberIn, ActionerIn, ActionerInUsername, ActionerOutUsername, Validate
                                FROM LBU05 
                                WHERE Identifier = ? AND EvidenceID = ? 
                                ORDER BY LBU05id ASC"; // No limit here; we need all rows

                    $stmtRows = $connection->prepare($sqlRows);
                    $stmtRows->bind_param("ii", $identifier, $evidenceID);
                    $stmtRows->execute();
                    $resultRows = $stmtRows->get_result();

                    if ($resultRows->num_rows > 0) {
                        // Start displaying the table for joined "Out" and "In" rows
                        echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                        echo '<thead>
                        <tr>
                        <th class="lbu-high">Timestamp Out</th>
                        <th class="lbu-high">Original Location</th>
                        <th class="lbu-high">Reason Out</th>
                        <th class="lbu-high">Seal Number Out</th>
                        <th class="lbu-high">Actioner Out</th>
                        <th class="lbu-high">Timestamp In</th>
                        <th class="lbu-high">New Location</th>
                        <th class="lbu-high">Seal Number In</th>
                        <th class="lbu-high">Actioner In</th>
                        </tr>
                        </thead>';
                        echo '<tbody>';

                        $prevRow = null; // To hold the previous row for joining with the next one

                        while ($row = $resultRows->fetch_assoc()) {
                            // If the current row is "Out", we display it and check if there's an "In" to join it with
                            if ($row['Validate'] == 'Out') {
                                // If there was a previous "Out" row without a corresponding "In", display it separately
                                if ($prevRow && $prevRow['Validate'] == 'Out' && $prevRow['TimestampIn'] == null) {
                                    echo '<tr>';
                                    echo '<td>' . ($prevRow['TimestampOut'] ?? 'N/A') . '</td>';
                                    echo '<td>' . ($prevRow['OriginalLocation'] ?? 'N/A') . '</td>';
                                    echo '<td>' . ($prevRow['ReasonOut'] ?? 'N/A') . '</td>';
                                    echo '<td>' . ($prevRow['SealNumberOut'] ?? 'N/A') . '</td>';
                                    echo '<td>' . ($prevRow['ActionerOut'] . " (" . $prevRow['ActionerOutUsername'] . ")"  ?? 'N/A') . '</td>';
                                    echo '<td colspan="4">No matching "In" row</td>';
                                    echo '</tr>';
                                    $prevRow = null; // Reset the previous row
                                }
                                
                                // Store the current "Out" row for later pairing
                                $prevRow = $row;
                            } 
                            // If the current row is "In", display it alongside the previous "Out" row
                            elseif ($row['Validate'] == 'In' && $prevRow) {
                                // Display the "Out" and "In" rows together
                                echo '<tr>';
                                echo '<td>' . ($prevRow['TimestampOut'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($prevRow['OriginalLocation'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($prevRow['ReasonOut'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($prevRow['SealNumberOut'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($prevRow['ActionerOut'] . " (" . $prevRow['ActionerOutUsername'] . ")" ?? 'N/A') . '</td>';

                                echo '<td>' . ($row['TimestampIn'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($row['NewLocation'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($row['SealNumberIn'] ?? 'N/A') . '</td>';
                                echo '<td>' . ($row['ActionerIn'] . " (" . $row['ActionerInUsername'] . ")"  ?? 'N/A') . '</td>';
                                echo '</tr>';

                                $prevRow = null; // Reset the previous row for the next pair
                            }
                        }

                        // If there is an "Out" row left without an "In" row, display it
                        if ($prevRow && $prevRow['Validate'] == 'Out') {
                            echo '<tr>';
                            echo '<td>' . ($prevRow['TimestampOut'] ?? 'N/A') . '</td>';
                            echo '<td>' . ($prevRow['OriginalLocation'] ?? 'N/A') . '</td>';
                            echo '<td>' . ($prevRow['ReasonOut'] ?? 'N/A') . '</td>';
                            echo '<td>' . ($prevRow['SealNumberOut'] ?? 'N/A') . '</td>';
                            echo '<td>' . ($prevRow['ActionerOut'] . " (" . $prevRow['ActionerOutUsername'] . ")"  ?? 'N/A') . '</td>';
                            echo '<td colspan="4"></td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo 'No rows found.';
                    }

                    // Close the statement
                    $stmtRows->close();






                ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>