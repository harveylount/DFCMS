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

    <title>LBU03</title>

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

        <section id="LBU">

            <div id="navcase-bar">
                <a href="<?php echo "createLBU03EntryForm.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Create Entry</a>
            </div>

            <p>
                <?php
                    // Query to fetch data
                    $query = "SELECT * FROM lbu03 WHERE Identifier = ? AND EvidenceID = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("ss", $identifier, $evidenceID);  
                    $stmt->execute();
                    $results = $stmt->get_result();

                    // Fetch the first row for static content (ExhibitRef and CaseReference)
                    if ($row = mysqli_fetch_assoc($results)) {
                        // Display ExhibitRef and Title (this will appear only once)
                        echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                        echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                                <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'LBU03 - Exhibit Continuity Form' . "</td></tr>"; 
                        echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
                        echo "</table>";
                        echo "<br/>";

                        echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                        echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
                        echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
                        echo "</table>";
                        echo "<br/>";

                        // Rewind the pointer to the first row so that the loop can process it as well
                        mysqli_data_seek($results, 0); 
                    }

                    // Now loop through all results to display the dynamic content (Timestamp, Action, Actioner)
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark LBU03-time'>Timestamp</th><th class='lbu-dark'>Action</th><th class='lbu-dark LBU03-actioner'>Actioner</th><th class='lbu-dark LBU03-x'>X</th><th class='lbu-dark'>Removed Reason</th></tr>";

                    // Loop through all the rows to display dynamic content
                    while ($row = mysqli_fetch_assoc($results)) {
                        if (isset($row['Removed']) && $row['Removed'] === "Removed") {
                            echo "<tr class='LBU03-removed'><td>" . $row['Timestamp'] . "</td><td>" . $row['Action'] . "</td><td>" . $row['FullName'] . "<br>(" . $row['Username'] . ")</td>
                            <td></td> <td>" . $row['RemovedReason'] . "</td></tr>";
                        } else {
                        echo "<tr><td>" . $row['Timestamp'] . "</td><td>" . $row['Action'] . "</td><td>" . $row['FullName'] . "<br>(" . $row['Username'] . ")</td>
                            <td><a href='removeLBU03EntryForm.php?identifier=" . urlencode($identifier) . "&EvidenceID=" . urlencode($evidenceID) . "&LBU03id=" . urlencode($row['LBU03id']) . "' id='navcase-button'>X</a></td></tr>";
                        }
                    }

                    echo "</table>";

                    // Close the prepared statement
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