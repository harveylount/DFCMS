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
            </div>

        <section id="content">

            <h2><?php echo $caseReference ?> - View Evidence Exhibits</h2>

            <div id="navcase-bar">
                <a href="<?php echo "createEvidenceForm.php?identifier=$identifier" ?>" id="navcase-button">Create Evidence</a>
            </div>

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