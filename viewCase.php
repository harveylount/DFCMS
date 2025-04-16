<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php';
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT CaseReference FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($caseReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

// Audit Log
$timestamp = date('Y-m-d H:i:s');
$action = "Viewed case information. Case Reference: " . $caseReference . ". Case ID: " . $identifier;
$type = "Case";
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];

$query = "INSERT INTO auditlog 
    (Identifier, CaseReference, EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
    VALUES
    (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "sssssss", $identifier, $caseReference, $type, $timestamp, $fullName, $username, $action);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Case List</title>

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
            <?php include 'displayCaseAdminButtonFunction.php'; ?>
        </div>

        <section id="content">

            <p>
                <?php
                    include 'displayCaseInfo.php';
                ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>