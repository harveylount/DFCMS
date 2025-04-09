<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

function formatBytes($bytes, $precision = 1) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Files</title>

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
            <a href="<?php echo "listImageFiles.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" id="navcase-button">Image Files</a>
            <a href="<?php echo "listExhibitPhotoFiles.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" id="navcase-button">Exhibit Photos</a>
        </div>

        <section id="LBU">

            <p>
            <?php

                echo '<div id="logout-bar">
                    <a href="uploadImageFileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID . '" id="logout-button">Upload File</a>
                    </div>';
                echo "<br/>";

                $query = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $stmt->bind_result($caseReference, $exhibitReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Exhibit Image Files</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Reference: ' . $exhibitReference . "</td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo "<tr><th class='lbu-dark'>Identifier Name</th><th class='lbu-dark''>File Name</th><th class='lbu-dark' style='width: 90px'>File Type</th><th class='lbu-dark' style='width: 90px'>File Size</th><th class='lbu-dark' style='width: 90px';>Timestamp</th><th class='lbu-dark' style='width: 35px;'></th><th class='lbu-dark' style='width: 72px;'></th></tr>";

                $sql = "SELECT FileID, Identifier, EvidenceID, UploadType, SetName, FileName, FileType, FileSize, UploaderFullName, UploaderUsername, UploadTimestamp FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("ss", $identifier, $evidenceID);
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = $results->fetch_assoc()) {
                    if ($row['UploadType'] == 'Image') {
                        echo '<tr>';
                        echo '<td>' . $row['SetName'] . '</td>';
                        echo '<td>' . $row['FileName'] . '</td>';
                        echo '<td>' . $row['FileType'] . '</td>';
                        echo '<td>' . formatBytes($row['FileSize']) . '</td>';
                        echo '<td>' . $row['UploadTimestamp'] . '</td>';
                        echo '<td><a href="viewFile.php?identifier=' . $row['Identifier'] . '&EvidenceID=' . $row['EvidenceID'] .  '&FileID=' . $row['FileID'] . '">View</a></td>';
                        echo '<td><a href="downloadFile.php?identifier=' . $row['Identifier'] . '&EvidenceID=' . $row['EvidenceID'] .  '&FileID=' . $row['FileID'] . '">Download</a></td>';
                        echo '</tr>';
                    }
                }

                echo "</table>";
                echo "<br/>";

        
            ?>


            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>