<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 


if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  

include 'checkUserAddedToCaseFunction.php'; 

if (!isset($_SESSION['txtActionM'])) {
    $_SESSION['txtActionM']='';
}

$_SESSION['timestampDatabaseLBU03'] = date('Y-m-d H:i:s');
$_SESSION['timestampDisplayLBU03'] = date('d-m-Y H:i:s');

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <style>
        .tall-input {
            height: 50px;
            width: 254px;
        }
    </style>

    <title>Create LBU03 Action</title>

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
        </div>

        <section id="content">

            <h2>Create an Exhibit Continuity Action (LBU03)</h2>

            <form method="post" action="createLBU03EntryInsert.php?identifier=<?php echo "$identifier"?>&EvidenceID=<?php echo "$evidenceID"?>">
                <fieldset class="field-set width">

                    <legend>
                    Enter action details
                    </legend>

                    </br>Action Timestamp: <?php echo $_SESSION['timestampDisplayLBU03'];?> </br></br>

                    <!-- Action field -->
                    <label for="txtAction">Action:</label><br />
                    <textarea name="txtAction" class="tall-input" required><?php 
                        if(isset($_SESSION['txtActionF'])) {
                            echo $_SESSION['txtActionF'];
                            unset($_SESSION['txtActionF']);
                        }
                    ?></textarea><p class="error-message"><?php echo $_SESSION['txtActionM']; unset($_SESSION['txtActionM']);?></p> [Action carried out on exhibit]<br /><br />

                    Actioner: <?php echo $_SESSION['fullName'];?> </br></br>
                    

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />

                </fieldset>
                
            </form>

        </section>

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>