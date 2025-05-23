<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php';
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection
$exhibitNotesBackupID = intval($_GET['ExhibitNotesBackupID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT CaseReference, ExhibitRef, ExhibitNotesBackup, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername FROM exhibitnotesbackup WHERE Identifier = ? AND EvidenceID = ? AND ExhibitNotesBackupID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("sss", $identifier, $evidenceID, $exhibitNotesBackupID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference, $exhibitNotesBackup, $timestamp, $fullName, $username);
$stmt->fetch();
mysqli_stmt_close($stmt);

$formattedBackupNotes = nl2br($exhibitNotesBackup);

// Audit Log
$timestamp = date('Y-m-d H:i:s');
$action = "Viewed backup exhibit note. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". Exhibit Note Backup ID: " . $exhibitNotesBackupID . ".";
$type = "Exhibit";
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];

$query = "INSERT INTO auditlog 
    (Identifier, CaseReference, ExhibitReference, EvidenceID, EntryType, ExhibitNotesBackupID, Timestamp, ActionerFullName, ActionerUsername, Action)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $exhibitReference, $evidenceID, $type, $exhibitNotesBackupID, $timestamp, $fullName, $username, $action);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>View Backup Note</title>

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
            <?php include 'lbu04notComputerFunction.php'; ?>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
            <a href="<?php echo "listImageFiles.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Files</a>
        </div>

        <section id="LBU">

            <p>
                <?php
                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>View Backup Note</td> 
                        <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $caseReference . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $exhibitReference . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Editor of the Backup Note</td><td>" . $fullName . " (" . $username . ")" . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Backup Note Edited Timestamp</td><td>" . $timestamp . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Notes</td></tr>";
                    echo "<tr><td>" . $formattedBackupNotes . "</td></tr>";
                    echo "</table>";
                ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>