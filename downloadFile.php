<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}
include 'timezoneFunction.php';

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
if (isset($_GET['EvidenceID'])) {
    $evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection
}
if (isset($_GET['LBU06id'])) {
    $LBU06id = intval($_GET['LBU06id']);  // Sanitize the input to prevent SQL injection
}
if (isset($_GET['FileID'])) {
    $fileID = intval($_GET['FileID']);  // Sanitize the input to prevent SQL injection
}
if (isset($_GET['SceneFileID'])) {
    $fileID = intval($_GET['SceneFileID']);  // Sanitize the input to prevent SQL injection
}

include 'checkUserAddedToCaseFunction.php'; 

$timestamp = date('Y-m-d H:i:s');
$fullName = $_SESSION['fullName'];
$username = $_SESSION['userId'];

$query = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $identifier);  
$stmt->execute();
$stmt->bind_result($caseReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

$query = "SELECT ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$stmt->bind_result($exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);


if (isset($_GET['EvidenceID'])) {
    $query = "SELECT UploadType FROM exhibituploadedfiles WHERE FileID = ? AND Identifier = ? AND EvidenceID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sss", $fileID, $identifier, $evidenceID);  
    $stmt->execute();
    $stmt->bind_result($uploadType);
    $stmt->fetch();
    mysqli_stmt_close($stmt);
} else if (isset($_GET['LBU06id'])) {
    $query = "SELECT UploadType FROM sceneuploadedfiles WHERE SceneFileID = ? AND Identifier = ? AND LBU06id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sss", $fileID, $identifier, $LBU06id);  
    $stmt->execute();
    $stmt->bind_result($uploadType);
    $stmt->fetch();
    mysqli_stmt_close($stmt);
} else {
    header('Location: index.php');
}



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

                if (in_array($uploadType, ["Image", "ExhibitPhoto"])) {
                    $sql = "SELECT FileName, FileType, FileContent FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ? AND FileID = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("sss", $identifier, $evidenceID, $fileID);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows === 1) {
                        $stmt->bind_result($fileName, $fileType, $fileContent);
                        $stmt->fetch();
                        
                        if (ob_get_level()) {
                            ob_end_clean();
                        }
                        
                        if (function_exists('apache_setenv')) {
                            @apache_setenv('no-gzip', '1');
                        }

                        header('Content-Description: File Transfer');
                        header("Content-Type: $fileType");
                        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . strlen($fileContent));

                        echo $fileContent;

                        // Audit Log
                        if ($uploadType === "Image") {
                            $action = "Downloaded an exhibit image file. Case Reference: " . $caseReference . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". Exhibit File ID: " . $fileID . ".";
                        }
                        if ($uploadType === "ExhibitPhoto") {
                            $action = "Downloaded an exhibit photo file. Case Reference: " . $caseReference . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". Exhibit File ID: " . $fileID . ".";
                        }

                        $type = "Exhibit";

                        $query = "INSERT INTO auditlog 
                            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, ExhibitFileID, Timestamp, ActionerFullName, ActionerUsername, Action)
                            VALUES
                            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, "ssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $fileID, $timestamp, $fullName, $username, $action);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        exit;
                    }
                } else if (in_array($uploadType, ["ScenePhoto", "SceneSketch"])) {
                    $sql = "SELECT FileName, FileType, FileContent FROM sceneuploadedfiles WHERE Identifier = ? AND LBU06id = ? AND SceneFileID = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("sss", $identifier, $LBU06id, $fileID);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows === 1) {
                        $stmt->bind_result($fileName, $fileType, $fileContent);
                        $stmt->fetch();
                        
                        if (ob_get_level()) {
                            ob_end_clean();
                        }
                        
                        if (function_exists('apache_setenv')) {
                            @apache_setenv('no-gzip', '1');
                        }

                        header('Content-Description: File Transfer');
                        header("Content-Type: $fileType");
                        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . strlen($fileContent));

                        echo $fileContent;
                        
                        // Audit Log
                        if ($uploadType === "ScenePhoto") {
                            $action = "Downloaded a crime scene photo file. Case Reference: " . $caseReference . ". LBU06 ID: " . $LBU06id . ". Scene File ID: " . $fileID . ".";
                        }
                        if ($uploadType === "SceneSketch") {
                            $action = "Downloaded a crime scene sketch file. Case Reference: " . $caseReference . ". LBU06 ID: " . $LBU06id . ". Scene File ID: " . $fileID . ".";
                        }
                        
                        $type = "Case";

                        $query = "INSERT INTO auditlog 
                            (Identifier, CaseReference, EntryType, LBU06id, SceneFileID, Timestamp, ActionerFullName, ActionerUsername, Action)
                            VALUES
                            (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, "sssssssss", $identifier, $caseReference, $type, $LBU06id, $fileID, $timestamp, $fullName, $username, $action);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        exit;
                    }
                } else {
                    header('Location: viewEvidenceExhibit.php?identifier=' . $identifier);
                    exit();
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