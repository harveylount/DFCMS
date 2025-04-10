<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$LBU06id = intval($_GET['LBU06id']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 
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
            <a href="<?php echo "viewCaseNotes.php?identifier=$identifier" ?>" id="navcase-button">Case Notes</a>
        </div>

        <section id="LBU">

            <p>

            <div id="navcase-bar">
                <a href="<?php echo "listScenePhotoFiles.php?identifier=" . $identifier . "&LBU06id=" . $LBU06id ?>" id="navcase-button">Crime Scene Files</a>
            </div>

            </br>

            <?php
                $query = "SELECT * FROM lbu06 WHERE Identifier = ? AND LBU06id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $LBU06id);  
                $stmt->execute();
                $results = $stmt->get_result();

                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'LBU06 - Crime Scene Report' . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                echo "</table>";
                echo "<br/>";

                while ($row = mysqli_fetch_assoc($results)) {

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>SOCO Name</td><td>" . $row['SocoName'] . " (" . $row['SocoUsername'] . ")</td> <td class='lbu-high'>Case Reference:</td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>SOCO Number</td><td>" . $row['SocoNumber'] . "</td> <td class='lbu-high'>Date Scene Examined:</td><td>" . $row['DateSceneExamined'] . "</td></tr>";
                    echo "</table><br/>";
                    
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Time Arrived</td><td>" . $row['SceneArriveTime'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Time Concluded</td><td>" . $row['SceneConcluded'] . "</td></tr>";
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
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>Others on the Scene</th> <th class='lbu-dark'>Time In</th> <th class='lbu-dark'>Time Out</th></tr>";

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

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Type of Crime</td><td>" . $row['TypeOfCrime'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Location of Crime</td><td>" . $row['LocationOfCrime'] . "</td></tr>";
                    echo "</table></br>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>Examination Notes</th></tr>";
                    echo "<tr><td>" . $row['ExaminationNotes'] . "</td></tr>";
                    echo "</table></br>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>Evidence Collected</th> <th class='lbu-dark'>Number of Items</th> <th class='lbu-dark'>Location of Item Stored</th></tr>";
                    echo "<tr><td class='lbu-high'>Photographs of Scene</td> <td>" . $row['NumberOfPhotos'] . "</td> <td>" . $row['LocationOfPhotos'] . "</td> </tr>";
                    echo "<tr><td class='lbu-high'>Sketches of Scene</td> <td>" . $row['NumberOfSketches'] . "</td> <td>" . $row['LocationOfSketches'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Items for Forensic Examination Scene</td> <td colspan='2'>" . $row['NumberOfItems'] . "</td></tr>";
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
                            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                            echo "<tr><th class='lbu-dark'>Disclosure of Evidence</th> <th class='lbu-dark'>Details</th> </tr>";
                            echo "<tr> <td class='lbu-high'>Exhibit number</td> <td>" . ($disclosureExhibit[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Evidence seized</td> <td>" . ($evidenceSeized[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Handed or Sent By</td><td>" . ($handedSentBy[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Handed or Sent To</td><td>" . ($toPersonOrLocation[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Timestamp</td><td>" . ($disclosureTimestamp[$i] ?? 'N/A') . "</td> </tr>";
                            echo "</table></br>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No data available</td></tr>";
                    }
                    // Includes Modified Generative AI. Reference: R - END

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>SOCO Signature</td><td><img src='" . $row['SocoSig'] . "' alt='Signature'></td></tr>";
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