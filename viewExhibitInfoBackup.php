<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection
$ExhibitInfoBackupID = intval($_GET['ExhibitInfoBackupID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php';

$sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>List Backup Exhibit Notes</title>

    <style>
        .notes-input {
            height: 300px;
            width: 950px;
        }
    </style>

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
        <a href="<?php echo "viewEvidenceExhibit.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Evidence Overview</a>
            <a href="<?php echo "viewLBU01.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU01</a>
            <a href="<?php echo "viewLBU02.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU02</a>
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <a href="<?php echo "viewLBU04.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU04</a>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
        </div>

        <section id="LBU">

            <?php
            // SQL query to get the evidence type
            $sql = "SELECT EvidenceType FROM exhibitinfobackup WHERE Identifier = ? AND EvidenceID = ? AND ExhibitInfoBackupID = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sss", $identifier, $evidenceID, $ExhibitInfoBackupID);  
            $stmt->execute();
            $stmt->bind_result($evidenceType);
            $stmt->fetch();
            mysqli_stmt_close($stmt);

            if($evidenceType === 'Computer') {
                $query = "SELECT CaseReference, ExhibitRef, EvidenceType, Manufacturer, 
                Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network, EditorOfBackupFullName, EditorOfBackupUsername, Timestamp1 FROM exhibitinfobackup WHERE ExhibitInfoBackupID = ? AND Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("sss", $ExhibitInfoBackupID, $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = mysqli_fetch_assoc($results)) {

                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                            <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Information' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Editor of the Backup Note</td><td>" . $row['EditorOfBackupFullName'] . " (" . $row['EditorOfBackupUsername'] . ")" . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Backup Note Edited Timestamp</td><td>" . $row['Timestamp1'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Device Model</td><td>" . $row['Model'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Storage Capacity</td><td>" . $row['Storage'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Operating System</td><td>" . $row['OS'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>CPU</td><td>" . $row['CPU'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>RAM</td><td>" . $row['RAM'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>MAC Address</td><td>" . $row['MAC'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>IP</td><td>" . $row['IP'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Firmware</td><td>" . $row['Firmware'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Peripheral Devices</td><td>" . $row['Peripheral'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Network Information</td><td>" . $row['Network'] . "</td></tr>";
                    echo "</table>";


                }
                $stmt->close();

            } else if($evidenceType === 'Mobile') {
                $query = "SELECT CaseReference, ExhibitRef, EvidenceType, Manufacturer, 
                Model, SerialNumber, Storage, OS, MAC, IMEI, SIM, PhoneNumber, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock, EditorOfBackupFullName, EditorOfBackupUsername, Timestamp1 FROM exhibitinfobackup WHERE ExhibitInfoBackupID = ? AND Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("sss", $ExhibitInfoBackupID, $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = mysqli_fetch_assoc($results)) {
                    
                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                            <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Information' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Editor of the Backup Note</td><td>" . $row['EditorOfBackupFullName'] . " (" . $row['EditorOfBackupUsername'] . ")" . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Backup Note Edited Timestamp</td><td>" . $row['Timestamp1'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Device Model</td><td>" . $row['Model'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Storage Capacity</td><td>" . $row['Storage'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Operating System</td><td>" . $row['OS'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>MAC Address</td><td>" . $row['MAC'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>IMEI Number</td><td>" . $row['IMEI'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>SIM Information</td><td>" . $row['SIM'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Phone Number Linked to SIM</td><td>" . $row['PhoneNumber'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Battery Health</td><td>" . $row['BatteryHealth'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Installed Apps</td><td>" . $row['InstalledApps'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Encryption Type</td><td>" . $row['EncryptionType'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Account Information</td><td>" . $row['AccountInfo'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Screen Lock</td><td>" . $row['ScreenLock'] . "</td></tr>";
                    echo "</table>";
                    
                }
                $stmt->close();
            } else if($evidenceType === 'ExternalStorage') {
                $query = "SELECT CaseReference, ExhibitRef, EvidenceType, Manufacturer, 
                Model, SerialNumber, Storage, EncryptionType, InterfaceType, FileSystem, EditorOfBackupFullName, EditorOfBackupUsername, Timestamp1 FROM exhibitinfobackup WHERE ExhibitInfoBackupID = ? AND Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("sss", $ExhibitInfoBackupID, $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = mysqli_fetch_assoc($results)) {
                    
                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                            <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Information' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Editor of the Backup Note</td><td>" . $row['EditorOfBackupFullName'] . " (" . $row['EditorOfBackupUsername'] . ")" . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Backup Note Edited Timestamp</td><td>" . $row['Timestamp1'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Device Model</td><td>" . $row['Model'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Storage Capacity</td><td>" . $row['Storage'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Encryption Type</td><td>" . $row['EncryptionType'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Interface Type</td><td>" . $row['InterfaceType'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File System</td><td>" . $row['FileSystem'] . "</td></tr>";
                    echo "</table>";
                    
                }
                $stmt->close();
            }
            ?>



            

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>