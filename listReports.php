<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']); 

include 'checkUserAddedToCaseFunction.php'; 

function formatBytes($bytes, $precision = 1) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

$query = "SELECT CaseReference FROM cases WHERE Identifier = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", $identifier);  
                $stmt->execute();
                $stmt->bind_result($caseReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Reports</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewCase.php?identifier=" . htmlspecialchars($identifier) ?>" class="logout-button">← Case</a>
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
            </br>
            <div id="navcase-bar">
                <a href="<?php echo "generateReport.php?identifier=" . htmlspecialchars($identifier)?>" id="navcase-button">Generate Report</a>
            </div>

            <p>
            <?php

                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 48px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Case Reports</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo "<tr><th class='lbu-dark' style='width:10%';>Report ID</th><th class='lbu-dark' style='width:40%';>File Name</th><th class='lbu-dark' style='width:15%';>File Type</th><th class='lbu-dark' style='width:10%';>File Size</th><th class='lbu-dark' style='width:20%';>Timestamp</th><th class='lbu-dark' style='width:10%';></th></tr>";

                $sql = "SELECT ReportID, Identifier, FileName, FileType, FileSize, GeneratedByFullName, GeneratedByUsername, GeneratedTimestamp FROM reportfiles WHERE Identifier = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("s", $identifier);
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = $results->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['ReportID'] . '</td>';
                    echo '<td>' . $row['FileName'] . '</td>';
                    echo '<td>' . $row['FileType'] . '</td>';
                    echo '<td>' . formatBytes($row['FileSize']) . '</td>';
                    echo '<td>' . $row['GeneratedTimestamp'] . '</td>';
                    echo '<td><a href="verifiedReport.php?identifier=' . $row['Identifier'] . '&ReportID=' . $row['ReportID'] .'">Download</a></td>';
                    echo '</tr>';
                    
                }

                echo "</table>";
                echo "<br/>";
                
                if (isset($_SESSION['countErrorMessage'])) {
                    echo '<p class="error-message">' . $_SESSION['countErrorMessage'] . '</p>';
                    unset($_SESSION['countErrorMessage']);
                }
        
            ?>


            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>