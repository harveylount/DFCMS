<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

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
            <a href="<?php echo "exhibitAudit.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Audit</a>
        </div>

        <section id="LBU">

            </br>
            <div id="navcase-bar">
                <a href="<?php echo "auditExhibitDownload.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Export Exhibit Audit</a>
            </div>

            <p>
                <?php
                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Exhibit Audit Log</td> 
                           <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . '' . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $caseReference . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark LBU03-time' style='width: 46px;'>Audit ID</th><th class='lbu-dark' style='width: 100px;'>Timestamp</th><th class='lbu-dark LBU03-actioner'>Actioner</th><th class='lbu-dark'>Action</th></tr>";
                    
                    $query = "SELECT * FROM auditlog WHERE Identifier = ? AND EntryType IN ('Exhibit')";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("s", $identifier);  
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if (mysqli_num_rows($result) > 0) {
                        
                    
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['AuditID']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Timestamp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ActionerFullName']) . "</br>(" . htmlspecialchars($row['ActionerUsername']) . ")</td>";
                            echo "<td>" . htmlspecialchars($row['Action']) . "</td>";
                            echo "</tr>";
                        }
                    
                        echo "</table>";
                    } else {
                        echo "<p>No audit log entries found for this case.</p>";
                    }
                ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>