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

$sql = "SELECT FileName, FileType, FileContent FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ? AND FileID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("sss", $identifier, $evidenceID, $fileID);

// Execute the query
$stmt->execute();

// Store the result to access num_rows
$stmt->store_result();

// Check if a row was returned
if ($stmt->num_rows === 1) {
    // Bind the result to variables
    $stmt->bind_result($fileName, $fileType, $fileContent);
    $stmt->fetch();
    
    // Clear any previous output
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Disable compression (if enabled in output buffer settings)
    if (function_exists('apache_setenv')) {
        @apache_setenv('no-gzip', '1');
    }

    // Set headers for the file download
    header('Content-Description: File Transfer');
    header("Content-Type: $fileType");
    header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($fileContent));

    // Output the file content
    echo $fileContent;
    exit;
} else {
    // No matching file found
    echo "❌ File not found.";
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