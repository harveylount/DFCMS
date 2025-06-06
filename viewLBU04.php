<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php';
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  
$evidenceID = intval($_GET['EvidenceID']);  

include 'checkUserAddedToCaseFunction.php'; 

                // If evidence is not a computer device redirects
                $query = "SELECT EvidenceType FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();
                $row = $results->fetch_assoc();

                if ($row['EvidenceType'] !== "Computer") {
                    header('Location: viewEvidenceExhibit.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                    $stmt->close();
                    exit();
                }
                

                // If LBU04 doesn't exist redirects to create form
                $query = "SELECT * FROM lbu04 WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();

                if ($results->num_rows == 0) {
                    header('Location: createLBU04Form.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                    $stmt->close();
                    exit();
                }

$sql = "SELECT LBU04id, CaseReference, ExhibitRef from lbu04 WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$stmt->bind_result($LBU04id, $caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

// Audit Log
$action = "Viewed an LBU04 form. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". LBU04 ID: " . $LBU04id . ".";
$type = "Exhibit";
$timestamp = date('Y-m-d H:i:s');
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];

$query = "INSERT INTO auditlog 
    (Identifier, CaseReference, ExhibitReference, EvidenceID, EntryType, LBU04id, Timestamp, ActionerFullName, ActionerUsername, Action)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $exhibitReference, $evidenceID, $type, $LBU04id, $timestamp, $fullName, $username, $action);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>LBU04</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" class="logout-button">← Exhibits</a>
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
            <a href="<?php echo "viewLBU04.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU04</a>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
            <a href="<?php echo "listImageFiles.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Files</a>
        </div>

        <section id="LBU">

            <p>
            <?php
                $query = "SELECT * FROM lbu04 WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = mysqli_fetch_assoc($results)) {

                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                            <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'LBU04 - Computer Exhibit Detail Form' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark' colspan='4'>Details</th></tr>";
                    echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td> <td class='lbu-high'>Model</td> <td>" . $row['Model'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td> <td class='lbu-high'>Type</td> <td>" . $row['Type'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark' colspan='4'>Notable Damage / Marking</th></tr>";
                    echo "<tr><td class='lbu-high'>Condition</td><td colspan='3'>" . $row['ItemCondition'] . "</td></tr>";
                    echo "<tr><td class='lbu-high' colspan='1'>Condition Notes</td><td colspan='3'>" . $row['ConditionNotes'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";


                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark' colspan='4'>Peripherals</th></tr>";
                    echo "<tr><td class='lbu-high'>Optical Drive</td><td>" . $row['OpticalDrive'] . "</td> <td class='lbu-high'>Floppy Disk</td> <td>" . $row['FloppyDisk'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Network</td><td>" . $row['Network'] . "</td> <td class='lbu-high'>Modem</td> <td>" . $row['Modem'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Firewire</td><td>" . $row['Firewire'] . "</td> <td class='lbu-high'>Media Card Reader</td> <td>" . $row['MediaCardReader'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>USB</td><td>" . $row['USB'] . "</td> <td class='lbu-high'>SIM Slot</td> <td>" . $row['SIMSlot'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Battery</td><td>" . $row['Battery'] . "</td> <td class='lbu-high'>Video Card</td> <td>" . $row['VideoCard'] . "</td></tr>";
                    echo "<tr><td class='lbu-high' colspan='1'>Other Peripherals</td><td colspan='3'>" . $row['PeripheralsOther'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark' colspan='4'>Bios Details</th></tr>";
                    echo "<tr><td class='lbu-high'>BIOS Key</td><td>" . $row['BIOSKey'] . "</td> <td class='lbu-high'>BIOS Password</td> <td>" . $row['BIOSPassword'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>BIOS System</td><td>" . $row['BIOSSystem'] . "</td> <td class='lbu-high'>Boot Order</td> <td>" . $row['BootOrder'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>BIOS Date</td><td>" . $row['BIOSDate'] . "</td> <td class='lbu-high'>BIOS Time</td> <td>" . $row['BIOSTime'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Actual Date</td><td>" . $row['ActualDate'] . "</td> <td class='lbu-high'>ActualTime</td> <td>" . $row['ActualTime'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Battery</td><td>" . $row['Battery'] . "</td> <td class='lbu-high'>Video Card</td> <td>" . $row['VideoCard'] . "</td></tr>";
                    echo "<tr><td class='lbu-high' colspan='1'>BIOS Notes</td><td colspan='3'>" . $row['BIOSNotes'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    // Includes Modified Generative AI. Reference: R - START
                    // Unserialize the stored arrays
                    $HDDReference = unserialize($row['HDDReference']);
                    $HDDManufacturer = unserialize($row['HDDManufacturer']);
                    $HDDModel = unserialize($row['HDDModel']);
                    $HDDSerialNumber = unserialize($row['HDDSerialNumber']);
                    $HDDType = unserialize($row['HDDType']);
                    $HDDSize = unserialize($row['HDDSize']);
                    $ImagingMethod = unserialize($row['ImagingMethod']);
                    $ImageVerified = unserialize($row['ImageVerified']);
                    $HardDriveNotes = unserialize($row['HardDriveNotes']);
                    
                    // Ensure arrays are valid
                    if (!is_array($HDDReference)) $HDDReference = [];
                    if (!is_array($HDDManufacturer)) $HDDManufacturer = [];
                    if (!is_array($HDDModel)) $HDDModel = [];
                    if (!is_array($HDDSerialNumber)) $HDDSerialNumber = [];
                    if (!is_array($HDDType)) $HDDType = [];
                    if (!is_array($HDDSize)) $HDDSize = [];
                    if (!is_array($ImagingMethod)) $ImagingMethod = [];
                    if (!is_array($ImageVerified)) $ImageVerified = [];
                    if (!is_array($HardDriveNotes)) $HardDriveNotes = [];

                    // Get the maximum row count
                    $rowCount = max(count($HDDReference), count($HDDManufacturer), count($HDDModel), count($HDDSerialNumber), count($HDDType), 
                                count($HDDSize), count($ImagingMethod), count($ImageVerified), count($HardDriveNotes)
                    );

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark' colspan='4'>Hard Disk </th></tr>";

                    if ($rowCount > 0) {
                        for ($i = 0; $i < $rowCount; $i++) {
                            echo "<tr> <td class='lbu-high'>Reference</td> <td>" . ($HDDReference[$i] ?? 'N/A') . "</td> <td class='lbu-high'>Manufacturer</td> <td>" . ($HDDManufacturer[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Model</td> <td>" . ($HDDModel[$i] ?? 'N/A') . "</td> <td class='lbu-high'>Serial Number</td> <td>" . ($HDDSerialNumber[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Type</td> <td>" . ($HDDType[$i] ?? 'N/A') . "</td> <td class='lbu-high'>Size</td> <td>" . ($HDDSize[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high'>Imaging Method</td> <td>" . ($ImagingMethod[$i] ?? 'N/A') . "</td> <td class='lbu-high'>Image Verified</td> <td>" . ($ImageVerified[$i] ?? 'N/A') . "</td> </tr>";
                            echo "<tr> <td class='lbu-high' colspan='1'>Notes</td><td colspan='3'>" . ($HardDriveNotes[$i] ?? 'N/A') . "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No data available</td></tr>";
                    }
                    echo "</table></br>";

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