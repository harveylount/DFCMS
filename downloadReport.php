<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include 'sqlConnection.php';  

if (isset($_GET['identifier']) && isset($_GET['ReportID'])) {
    $identifier = $_GET['identifier'];
    $reportID = $_GET['ReportID'];

    $stmt = $connection->prepare("SELECT FileName, FileType, FileSize, FileContent, MD5Hash, SHA1Hash FROM reportfiles WHERE Identifier = ? AND ReportID = ?");
    $stmt->bind_param("ss", $identifier, $reportID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($filename, $filetype, $filesize, $filecontent, $uploadedMD5Hash, $uploadedSHA1Hash);

    if ($stmt->fetch()) {

        header('Content-Type: ' . $filetype);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($filecontent));
        echo $filecontent;

        //header('location:verifiedReport.php?identifier=' . htmlspecialchars($identifier) . '&ReportID=' . htmlspecialchars($reportID));

    } else {
        echo "File not found!";
    }

    $stmt->close();
    $connection->close();
} else {
    echo "No file identifier provided!";
}

exit;


?>
