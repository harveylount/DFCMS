<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Evidence Exhibit</title>

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
        </div>

        <section id="content">

            <p>
                <?php
                $query = "SELECT * FROM lbu01 WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();
        
                while ($row = mysqli_fetch_assoc($results)) {
                    echo "<h2>" . $row['ExhibitRef'] . " - Exhibit Receipt Form (LBU01)</h2>";
                    
                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td><b>Case Reference:</b></td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td><b>Location:</b></td><td>" . $row['Location'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td><b>Received From Rank:</b></td><td>" . $row['ReceivedFromRank'] . "</td></tr>";
                    echo "<tr><td><b>Received From:</b></td><td>" . $row['ReceivedFromName'] . "</td></tr>";
                    echo "<tr><td><b>Company:</b></td><td>" . $row['ReceivedFromCompany'] . "</td></tr>";
                    echo "<tr><td><b>Timestamp:</b></td><td>" . $row['ReceivedFromTime'] . "</td></tr>";
                    echo "<tr><td><b>Signature:</b></td><td><img src='" . $row['ReceivedFromSig'] . "' alt='Signature'></td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td><b>Received By Rank:</b></td><td>" . $row['ReceivedByRank'] . "</td></tr>";
                    echo "<tr><td><b>Received By:</b></td><td>" . $row['ReceivedByName'] . "</td></tr>";
                    echo "<tr><td><b>Company:</b></td><td>" . $row['ReceivedByCompany'] . "</td></tr>";
                    echo "<tr><td><b>Timestamp:</b></td><td>" . $row['ReceivedByTime'] . "</td></tr>";
                    echo "<tr><td><b>Signature:</b></td><td><img src='" . $row['ReceivedBySig'] . "' alt='Signature'></td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th><b>Exhibit Number:</b></th><th><b>Seal Number:</b></th><th><b>Description:</b></th></tr>";
                    echo "<tr><td>" . $row['ExhibitRef'] . "</td><td>" . $row['InitialSealNumber'] . "</td><td>" . $row['InitialDescription'] . "</td></tr>";
                    echo "</table>";


        
                }
                $stmt->close();
                ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>