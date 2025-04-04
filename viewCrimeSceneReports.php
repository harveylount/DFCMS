<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$query2 = "SELECT CaseReference FROM cases WHERE Identifier = $identifier";
$results2 = mysqli_query($connection, $query2);

$caseReferenceRow = mysqli_fetch_assoc($results2);
$caseReference = $caseReferenceRow['CaseReference'] ?? 'No Case Reference';
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Crime Scene Reports</title>

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

        <section id="content">

            <h2><?php echo $caseReference ?> - Crime Scene Reports</h2>

            <div id="navcase-bar">
                <a href="<?php echo "createLBU06Form.php?identifier=$identifier" ?>" id="navcase-button">Create Crime Scene Report</a>
            </div>

            <p>
                <?php

                    $query = "SELECT LBU06id, DateSceneExamined, SocoName, SocoUsername FROM LBU06 WHERE Identifier = $identifier";
                    $results = mysqli_query($connection, $query);

                    echo "<style>";
                    echo "table { border-collapse: collapse; }"; 
                    echo "td, th { border: 1px solid black; padding: 5px; }";
                    echo "</style>";

                    echo "<table>";
                    echo "<tr>";
                    echo "<th>LBU06 ID</th>";
                    echo "<th>Case Reference</th>";
                    echo "<th>Date Examined</th>";
                    echo "<th>SOCO</th>";
                    echo "<th></th>";
                    echo "</tr>";

                    while ($row = mysqli_fetch_assoc($results)) {
                        echo "<tr>";
                        echo "<td>" . $row['LBU06id'] . "</td>";
                        echo "<td>" . $caseReference . "</td>";

                        echo "<td>" . $row['DateSceneExamined'] . "</td>";
                        echo "<td>" . $row['SocoName'] . " (" . $row['SocoUsername'] . ")" . "</td>";
                        echo '<td><a href="viewLBU06.php?identifier=' . $identifier . '&LBU06id=' . $row['LBU06id'] . '">View Report</a></td>';
                        echo "</tr>";
                    }

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