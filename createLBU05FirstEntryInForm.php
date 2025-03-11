<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  
$evidenceID = intval($_GET['EvidenceID']);  

$sql = "SELECT * FROM LBU05 WHERE Identifier = ? AND EvidenceID = ? ORDER BY LBU05id DESC LIMIT 1";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ii", $identifier, $evidenceID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    if ($row["Validate"] == "In") {
        header('Location: createLBU05EntryOutForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } elseif ($row["Validate"] == "Out") {
        header('Location: createLBU05EntryInForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        echo "Unexpected value for Validate: " . $row["Validate"];

    }
} else {
    // First Entry In
}
mysqli_stmt_close($stmt);


if (!isset($_SESSION['txtFirstNewLocationM'])) {
    $_SESSION['txtFirstNewLocationM']='';
}

$sql = "SELECT CaseReference, ExhibitRef, CurrentSeal FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitRef, $sealNumber);
$stmt->fetch();
mysqli_stmt_close($stmt);


$_SESSION['timestampFirstInDatabaseLBU05'] = date('Y-m-d H:i:s');
$_SESSION['timestampFirstInDisplayLBU05'] = date('d-m-Y H:i:s');

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <style>
        .tall-input {
            height: 50px;
            width: 254px;
        }
    </style>

    <title>Create LBU05 Action</title>

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
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <?php include 'lbu04notComputerFunction.php'; ?>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
        </div>

        <section id="content">

            <h2>Create First Exhibit Movement In Record (LBU05)</h2>

            <form method="post" action="createLBU05EntryInsert.php?identifier=<?php echo "$identifier"?>&EvidenceID=<?php echo "$evidenceID"?>">
                <fieldset class="field-set width">

                    <legend>
                    Enter movement details
                    </legend>

                    </br>Case Reference: <?php echo $caseReference; ?> </br></br>

                    Exhibit Reference: <?php echo $exhibitRef; ?> </br></br>

                    Exhibit Timestamp In: <?php echo $_SESSION['timestampFirstInDisplayLBU05'];?> </br></br>

                    <!-- New location field -->
                    <label for="txtFirstNewLocation">New Location:</label><br />
                    <input type="text" name="txtFirstNewLocation" size="32" value="<?php 
                        if(isset($_SESSION['txtFirstNewLocationF'])) {
                            echo $_SESSION['txtFirstNewLocationF'];
                            unset($_SESSION['txtFirstNewLocationF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtFirstNewLocationM']; unset($_SESSION['txtFirstNewLocationM']);?></p> <br /><br />

                    Seal Number: <?php echo $sealNumber;?> </br></br>

                    Actioner: <?php echo $_SESSION['fullName'];?> </br></br>
                    

                    <input type="submit" value="Submit" name="subEventLBU05FirstIn" />
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