<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$caseNotesBackupID = intval($_GET['CaseNotesBackupID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT CaseReference, CaseNotesBackup, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername FROM casenotesbackup WHERE Identifier = ? AND CaseNotesBackupID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $caseNotesBackupID);
$stmt->execute();
$stmt->bind_result($caseReference, $caseNotesBackup, $timestamp, $fullName, $username);
$stmt->fetch();
mysqli_stmt_close($stmt);

$formattedBackupNotes = nl2br($caseNotesBackup);

// Audit Log
$action = "Viewed backup case note. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Case Notes Backup ID: " . $caseNotesBackupID . ".";
$type = "Case";
$timestamp = date('Y-m-d H:i:s');
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];


$query = "INSERT INTO auditlog 
    (Identifier, CaseReference, EntryType, CaseNotesBackupID, Timestamp, ActionerFullName, ActionerUsername, Action)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssssss", $identifier, $caseReference, $caseNotesBackupID, $type, $timestamp, $fullName, $username, $action);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Case Notes</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "listCaseNotesBackup.php?identifier=" . $identifier ?>" class="logout-button">← Note Backups</a>
                
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
            <a href="<?php echo "viewCase.php?identifier=$identifier" ?>" id="navcase-button">Case Overview</a>
            <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" id="navcase-button">Evidence</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier" ?>" id="navcase-button">Crime Scene Reports</a>
            <a href="<?php echo "viewCaseNotes.php?identifier=$identifier" ?>" id="navcase-button">Case Notes</a>
            <a href="<?php echo "listReports.php?identifier=$identifier" ?>" id="navcase-button">Reports</a>
            <?php include 'displayCaseAdminButtonFunction.php'; ?>
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