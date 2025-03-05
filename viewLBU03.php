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
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
        </div>

        <section id="content">

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
                        echo "<h2>" . $row['ExhibitRef'] . " - Exhibit Continuity Form (LBU03)</h2>";

                        echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                        echo "<tr><td><b>Case Reference:</b></td><td>" . $row['CaseReference'] . "</td></tr>";
                        echo "<tr><td><b>Exhibit Reference:</b></td><td>" . $row['ExhibitRef'] . "</td></tr>";
                        echo "</table>";
                        echo "<br/>";

                        // Rewind the pointer to the first row so that the loop can process it as well
                        mysqli_data_seek($results, 0); 
                    }

                    // Now loop through all results to display the dynamic content (Timestamp, Action, Actioner)
                    echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th><b>Timestamp</b></th><th><b>Action</b></th><th><b>Actioner</b></th></tr>";

                    // Loop through all the rows to display dynamic content
                    while ($row = mysqli_fetch_assoc($results)) {
                        echo "<tr><td>" . $row['Timestamp'] . "</td><td>" . $row['Action'] . "</td><td>" . $row['FullName'] . " (" . $row['Username'] . ")</td></tr>";
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