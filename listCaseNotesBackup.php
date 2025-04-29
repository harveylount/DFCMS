<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php';

$sql = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($caseReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Backup Case Notes</title>

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
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
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
            <a href="<?php echo "auditCase.php?identifier=$identifier" ?>" id="navcase-button">Case Audit</a>
            <?php include 'displayCaseAdminButtonFunction.php'; ?>
        </div>

        <section id="LBU">

            <form method="post" action="editCaseNotesInsert.php?identifier=<?php echo "$identifier"?>">

            <?php


                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Backup Case Notes</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo "<tr><th class='lbu-dark' style='width: 160px;'>Case Reference</th><th class='lbu-dark'>Editor of Backup</th><th class='lbu-dark' style='width: 160px;'>Timestamp</th><th class='lbu-dark' style='width: 100px;'></th></tr>";
                

                $sql = "SELECT CaseNotesBackupID, Identifier, CaseReference, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername FROM casenotesbackup WHERE Identifier = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("s", $identifier);
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = $results->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['CaseReference'] . '</td>';
                    echo '<td>' . $row['EditorOfBackupFullName'] . ' (' . $row['EditorOfBackupUsername'] . ')' . '</td>';
                    echo '<td>' . $row['Timestamp1'] . '</td>';
                    echo '<td><a href="viewCaseNotesBackup.php?identifier=' . $row['Identifier'] . '&CaseNotesBackupID=' . $row['CaseNotesBackupID'] . '">View Backup</a></td>';
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