<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$LBU06id = intval($_GET['LBU06id']);  // Sanitize the input to prevent SQL injection
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>LBU06</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
            <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
            <a href="logoutFunction.php" id="logout-button">Logout</a>
        </div>

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        <div id="navcase-bar">
            <a href="<?php echo "viewCase.php?identifier=$identifier" ?>" id="navcase-button">Case Overview</a>
            <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" id="navcase-button">Evidence</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier" ?>" id="navcase-button">Crime Scene Reports</a>
        </div>

        <section id="content">

            <p>
            <?php
                $query = "SELECT * FROM lbu06 WHERE Identifier = ? AND LBU06id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $LBU06id);  
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = mysqli_fetch_assoc($results)) {
                    echo "<h2>" . $row['CaseReference'] . " - Crime Scene Report (LBU06)</h2>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td><b>SOCO Name:</b></td><td>" . $row['SocoName'] . " (" . $row['SocoUsername'] . ")</td> <td><b>Case Reference:</b></td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td><b>SOCO Number:</b></td><td>" . $row['SocoNumber'] . "</td> <td><b>Date Scene Examined:</b></td><td>" . $row['DateSceneExamined'] . "</td></tr>";
                    echo "</table><br/>";
                    
                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td><b>Time Arrived:</b></td><td>" . $row['SceneArriveTime'] . "</td></tr>";
                    echo "<tr><td><b>Time Concluded:</b></td><td>" . $row['SceneConcluded'] . "</td></tr>";
                    echo "</table><br/>";

                    // Includes Generative AI. Reference: R - START
                    // Unserialize the stored arrays
                    $othersOnScene = unserialize($row['OthersOnScene']);
                    $othersTimeIn = unserialize($row['OthersTimeIn']);
                    $othersTimeOut = unserialize($row['OthersTimeOut']);

                    // Ensure arrays are valid
                    if (!is_array($othersOnScene)) $othersOnScene = [];
                    if (!is_array($othersTimeIn)) $othersTimeIn = [];
                    if (!is_array($othersTimeOut)) $othersTimeOut = [];

                    // Get the maximum row count
                    $rowCount = max(count($othersOnScene), count($othersTimeIn), count($othersTimeOut));

                    // Display Others on Scene table
                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th>Others on the Scene</th> <th>Time In</th> <th>Time Out</th></tr>";

                    if ($rowCount > 0) {
                        for ($i = 0; $i < $rowCount; $i++) {
                            echo "<tr>
                                    <td>" . ($othersOnScene[$i] ?? 'N/A') . "</td>
                                    <td>" . ($othersTimeIn[$i] ?? 'N/A') . "</td>
                                    <td>" . ($othersTimeOut[$i] ?? 'N/A') . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No data available</td></tr>";
                    }
                    // Includes Generative AI. Reference: R - END

                    echo "</table><br/>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th><b>Type of Crime:</b></th><td>" . $row['TypeOfCrime'] . "</td></tr>";
                    echo "<tr><th><b>Location of Crime:</b></th><td>" . $row['LocationOfCrime'] . "</td></tr>";
                    echo "</table></br>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th><b>Examination Notes:</b></th></tr>";
                    echo "<tr><td>" . $row['ExaminationNotes'] . "</td></tr>";
                    echo "</table></br>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th><b>Evidence Collected</b></th> <th><b>Number</b></th> <th><b>Location of Item Stored</b></th></tr>";
                    echo "<tr><td><b>Photographs of Scene</b></td> <td>" . $row['NumberOfPhotos'] . "</td> <td>" . $row['LocationOfPhotos'] . "</td> </tr>";
                    echo "<tr><td><b>Sketches of Scene</b></td> <td>" . $row['NumberOfSketches'] . "</td> <td>" . $row['LocationOfSketches'] . "</td></tr>";
                    echo "<tr><td><b>Items for Forensic Examination Scene</td> <td colspan='2'>" . $row['NumberOfItems'] . "</td></tr>";
                    echo "</table></br>";

                    // Includes Modified Generative AI. Reference: R - START
                    // Unserialize the stored arrays
                    $disclosureExhibit = unserialize($row['DisclosureExhibit']);
                    $evidenceSeized = unserialize($row['EvidenceSeized']);
                    $handedSentBy = unserialize($row['HandedSentBy']);
                    $toPersonOrLocation = unserialize($row['ToPersonOrLocation']);
                    $disclosureTimestamp = unserialize($row['DisclosureTimestamp']);


                    // Ensure arrays are valid
                    if (!is_array($disclosureExhibit)) $disclosureExhibit = [];
                    if (!is_array($evidenceSeized)) $evidenceSeized = [];
                    if (!is_array($handedSentBy)) $handedSentBy = [];
                    if (!is_array($toPersonOrLocation)) $toPersonOrLocation = [];
                    if (!is_array($disclosureTimestamp)) $disclosureTimestamp = [];

                    // Get the maximum row count
                    $rowCountDisclosure = max(count($disclosureExhibit), count($evidenceSeized), count($handedSentBy), count($toPersonOrLocation), count($disclosureTimestamp));

                    // Display Others on Scene table
                    

                    if ($rowCountDisclosure > 0) {
                        for ($i = 0; $i < $rowCountDisclosure; $i++) {
                            echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                            echo "<tr><th>Disclosure of Evidence</th> <th>Details</th> </tr>";
                            echo "<tr> <td>Exhibit number</td> <td>" . ($disclosureExhibit[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td>Evidence seized</td> <td>" . ($evidenceSeized[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td>Handed or sent by</td><td>" . ($handedSentBy[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td>To</td><td>" . ($toPersonOrLocation[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td>Timestamp</td><td>" . ($disclosureTimestamp[$i] ?? 'N/A') . "</td> </tr>";
                            echo "</table></br>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No data available</td></tr>";
                    }
                    // Includes Modified Generative AI. Reference: R - END

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td><b>SOCO Signature</b></td><td><img src='" . $row['SocoSig'] . "' alt='Signature'></td></tr>";
                    echo "</table>";


                }

                $stmt->close();
            ?>


            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>