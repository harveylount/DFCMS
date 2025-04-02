<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php'; 

if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
    exit();
}

if (!isset($_GET['identifier'])) {
    header("Location: index.php");
    exit();
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

$sql = "SELECT LeadInvestigator FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($leadInvestigator);
$stmt->fetch();
mysqli_stmt_close($stmt);

if ($_SESSION['userId'] != $leadInvestigator) {
    header ('location:viewCase.php?identifier=' . $identifier);
    exit();
}

$sql = "SELECT CaseReference FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($caseReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

if (!$caseReference) {
    header('location:index.php');
}

$sql = "SELECT DeadlineDate FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($currentDeadlineDate);
$stmt->fetch();
mysqli_stmt_close($stmt);

$maxDate = date('Y-m-d', strtotime('+5 years'));
$minDate = date('Y-m-d', strtotime($currentDeadlineDate . ' -5 years'));

?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Case Admin</title>

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
            <a href="<?php echo "caseAdmin.php?identifier=$identifier" ?>" id="navcase-button">Case Admin</a>
            <a href="<?php echo "addInvestigatorsForm.php?identifier=$identifier" ?>" id="navcase-button">Add Investigators</a>
        </div>

        <section id="content">

            <p>
                <?php

                    echo "<h2>" . $caseReference . " - Case Admin</h2>";

                    echo "<h3>Change Case Deadline</h3>";
                ?>
                <form method="post" action="newDeadlineInsert.php?identifier=<?php echo "$identifier"?>">

                    <!-- Case deadline date field -->
                    <label for="dateDeadline">New Deadline Date:</label><br />
                        <input type="date" id="dateDeadline" name="dateDeadline" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" value="<?php
                        if(isset($_SESSION['txtDateDeadlineF'])) {
                            echo $_SESSION['txtDateDeadlineF'];
                            unset($_SESSION['txtDateDeadlineF']);
                        }
                        ?>" required> <br /><br />

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />
                
                </form>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>