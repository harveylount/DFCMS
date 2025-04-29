<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php';
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT LBU01id, CaseReference, ExhibitRef from lbu01 WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$stmt->bind_result($LBU01id, $caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

// Audit Log
$action = "Viewed an LBU01 form. Case Reference: " . $caseReference . ". 
            Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". 
            Exhibit ID: " . $evidenceID . ". LBU01 ID: " . $LBU01id . ".";
$type = "Exhibit";
$timestamp = date('Y-m-d H:i:s');
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];

$query = "INSERT INTO auditlog 
    (Identifier, CaseReference, ExhibitReference, EvidenceID, EntryType, LBU01id, Timestamp, 
    ActionerFullName, ActionerUsername, Action)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $exhibitReference, $evidenceID, 
$type, $LBU01id, $timestamp, $fullName, $username, $action);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>LBU01</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" class="logout-button">← Exhibits</a>
                
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
            <a href="<?php echo "viewEvidenceExhibit.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Evidence Overview</a>
            <a href="<?php echo "viewLBU01.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU01</a>
            <a href="<?php echo "viewLBU02.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU02</a>
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <?php include 'lbu04notComputerFunction.php'; ?>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
            <a href="<?php echo "listImageFiles.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Files</a>
        </div>

        <section id="LBU">

            <p>
                <?php
                $query = "SELECT * FROM lbu01 WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $results = $stmt->get_result();
        
                while ($row = mysqli_fetch_assoc($results)) {

                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                        echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                                <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'LBU01 - Exhibit Receipt Form' . "</td></tr>"; 
                        echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                        echo "</table>";
                        echo "<br/>";

                        echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Location</td><td>" . $row['Location'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Received From Rank</td><td>" . $row['ReceivedFromRank'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Received From</td><td>" . $row['ReceivedFromName'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Company</td><td>" . $row['ReceivedFromCompany'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Timestamp</td><td>" . $row['ReceivedFromTime'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Signature</td><td><img src='" . $row['ReceivedFromSig'] . "' alt='Signature'></td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Received By Rank</td><td>" . $row['ReceivedByRank'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Received By</td><td>" . $row['ReceivedByName'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Company</td><td>" . $row['ReceivedByCompany'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Timestamp</td><td>" . $row['ReceivedByTime'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Signature</td><td><img src='" . $row['ReceivedBySig'] . "' alt='Signature'></td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Exhibit Number</td><td>" . $row['ExhibitRef'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Seal Number</td><td>" . $row['InitialSealNumber'] . "</td></tr>";

                    echo "<tr><th class='lbu-dark' colspan='2'>Initial Description</th></tr>";
                    echo "<tr><td colspan='2'>" . $row['InitialDescription'] . "</td></tr>";
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