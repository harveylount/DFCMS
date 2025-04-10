<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection
$fileID = intval($_GET['FileID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$query = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

$query = "SELECT UploadType FROM exhibituploadedfiles WHERE FileID = ? AND Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("sss", $fileID, $identifier, $evidenceID);  
$stmt->execute();
$stmt->bind_result($uploadType);
$stmt->fetch();
mysqli_stmt_close($stmt);





function formatBytes($bytes, $precision = 2) {
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

                if ($uploadType == "Image") {

                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>View Image File</td> 
                        <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Reference: ' . $exhibitReference . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $caseReference . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $exhibitReference . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    $sql = "SELECT UploadType, SetName, FileName, FileType, FileSize, FileContent, UploaderFullName, UploaderUsername, UploadTimestamp, Notes, MD5Hash, SHA1Hash FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ? AND FileID = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("sss", $identifier, $evidenceID, $fileID);
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $row = mysqli_fetch_assoc($results);
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Memorable Name</td><td>" . $row['SetName'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Uploaded By</td><td>" . $row['UploaderFullName'] . " (" . $row['UploaderUsername'] . ")" . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Timestamp</td><td>" . $row['UploadTimestamp'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Filename</td><td>" . $row['FileName'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File Type</td><td>" . $row['FileType'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File Size</td><td>" . formatBytes($row['FileSize']) . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File MD5 Hash</td><td>" . $row['MD5Hash'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File SHA1 Hash</td><td>" . $row['SHA1Hash'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    $formattedNotes = nl2br($row['Notes']);
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>File Notes</th></tr>";
                    echo "<tr><td>" . $formattedNotes . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>Preview</th></tr>";
                    echo "<tr><td>No preview available</td></tr>";
                    echo "</table>";
                    echo "<br/>";
                }

                if ($uploadType == "ExhibitPhoto") {

                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>View Exhibit Photo</td> 
                        <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                    echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Reference: ' . $exhibitReference . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $caseReference . "</td></tr>";
                    echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $exhibitReference . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    $sql = "SELECT UploadType, SetName, FileName, FileType, FileSize, FileContent, UploaderFullName, UploaderUsername, UploadTimestamp, Notes, MD5Hash, SHA1Hash FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ? AND FileID = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("sss", $identifier, $evidenceID, $fileID);
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $row = mysqli_fetch_assoc($results);
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Memorable Name</td><td>" . $row['SetName'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Uploaded By</td><td>" . $row['UploaderFullName'] . " (" . $row['UploaderUsername'] . ")" . "</td></tr>";
                    echo "<tr><td class='lbu-high'>Timestamp</td><td>" . $row['UploadTimestamp'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><td class='lbu-high'>Filename</td><td>" . $row['FileName'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File Type</td><td>" . $row['FileType'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File Size</td><td>" . formatBytes($row['FileSize']) . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File MD5 Hash</td><td>" . $row['MD5Hash'] . "</td></tr>";
                    echo "<tr><td class='lbu-high'>File SHA1 Hash</td><td>" . $row['SHA1Hash'] . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    $formattedNotes = nl2br($row['Notes']);
                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>File Notes</th></tr>";
                    echo "<tr><td>" . $formattedNotes . "</td></tr>";
                    echo "</table>";
                    echo "<br/>";

                    $fileContent = $row['FileContent'];
                    $fileName = $row['FileName'];
                    $fileType = $row['FileType'];

                    $image_data = base64_encode($fileContent);

                    echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                    echo "<tr><th class='lbu-dark'>Preview</th></tr>";
                    echo "<tr><td><img src='data:{$fileType};base64,{$image_data}' alt='{$fileName}' width='100%'></td></tr>";
                    echo "</table>";
                    echo "<br/>";
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