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

$sql = "SELECT Notes FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($notes);
$stmt->fetch();
mysqli_stmt_close($stmt);


?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Case Notes</title>

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
            <a href="<?php echo "viewCase.php?identifier=$identifier" ?>" id="navcase-button">Case Overview</a>
            <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" id="navcase-button">Evidence</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier" ?>" id="navcase-button">Crime Scene Reports</a>
            <a href="<?php echo "viewCaseNotes.php?identifier=$identifier" ?>" id="navcase-button">Case Notes</a>
        </div>

        <section id="LBU">

            <form method="post" action="editCaseNotesInsert.php?identifier=<?php echo "$identifier"?>">

            <?php
                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Edit Case Notes</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>";
                echo "</table>";
                echo "<br/>";
            ?>

                <label for="txtCaseNotes">Case Notes: *</label><br />
                <textarea name="txtCaseNotes" class="notes-input" required><?php if (isset($_SESSION['txtNewCaseNotesF'])) { echo $_SESSION['txtNewCaseNotesF']; unset($_SESSION['txtNewCaseNotesF']); } else { echo $notes; }?></textarea><br /><br />

                <?php 
                    if (isset($_SESSION['txtNewCaseNotesM'])) {
                        echo '<p class="error-message">' . $_SESSION['txtNewCaseNotesM'] . '</p></br></br>';
                        unset($_SESSION['txtNewCaseNotesM']);
                    }
                ?>

                <input type="submit" value="Submit" name="subEvent" />
                <input type="reset" value="Clear" />

            </form>
            


            


    

            



        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>