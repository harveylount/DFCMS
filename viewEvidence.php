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

    <title>Evidence</title>

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

            </br>
            <div id="navcase-bar">
                <a href="<?php echo "createEvidenceForm.php?identifier=$identifier" ?>" id="navcase-button">Create Evidence</a>
            </div>
            </br>

            <?php
                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Evidence Exhibits</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo '<tr><td class="lbu-dark">Case Reference</td><td>' . $caseReference . '</td></tr>';
                echo '</table>';
                echo "<br/>";
            ?>

            <p>
                <?php include 'displayEvidence.php'; ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>