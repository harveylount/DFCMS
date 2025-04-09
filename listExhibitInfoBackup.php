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
            <?php include 'lbu04notComputerFunction.php'; ?>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
            <a href="<?php echo "listImageFiles.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Files</a>
        </div>

        <section id="LBU">

            <?php


                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 42px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Backup Exhibit Info</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Reference: ' . $exhibitReference . "</td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo "<tr><th class='lbu-dark' style='width: 160px;'>Case Reference</th><th class='lbu-dark' style='width: 100px;'>Exhibit Reference</th><th class='lbu-dark'>Editor of Exhibit Information</th><th class='lbu-dark' style='width: 160px;'>Timestamp</th><th class='lbu-dark' style='width: 100px;'></th></tr>";
                

                $sql = "SELECT ExhibitInfoBackupID, Identifier, CaseReference, EvidenceID, ExhibitRef, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername FROM exhibitinfobackup WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("ss", $identifier, $evidenceID);
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = $results->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['CaseReference'] . '</td>';
                    echo '<td>' . $row['ExhibitRef'] . '</td>';
                    echo '<td>' . $row['EditorOfBackupFullName'] . ' (' . $row['EditorOfBackupUsername'] . ')' . '</td>';
                    echo '<td>' . $row['Timestamp1'] . '</td>';
                    echo '<td><a href="viewExhibitInfoBackup.php?identifier=' . $row['Identifier'] . '&EvidenceID=' . $row['EvidenceID'] .  '&ExhibitInfoBackupID=' . $row['ExhibitInfoBackupID'] . '">View Backup</a></td>';
                    echo '</tr>';
                }

                echo "</table>";
                echo "<br/>";


                mysqli_stmt_close($stmt);
            ?>



            

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>