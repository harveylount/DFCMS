<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']); 
$reportID = intval($_GET['ReportID']); 

include 'checkUserAddedToCaseFunction.php'; 

function formatBytes($bytes, $precision = 1) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

$query = "SELECT CaseReference FROM cases WHERE Identifier = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", $identifier);  
                $stmt->execute();
                $stmt->bind_result($caseReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Reports</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewCase.php?identifier=" . htmlspecialchars($identifier) ?>" class="logout-button">← Case</a>
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
            <a href="<?php echo "viewCase.php?identifier=$identifier" ?>" id="navcase-button">Case Overview</a>
            <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" id="navcase-button">Evidence</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier" ?>" id="navcase-button">Crime Scene Reports</a>
            <a href="<?php echo "viewCaseNotes.php?identifier=$identifier" ?>" id="navcase-button">Case Notes</a>
            <a href="<?php echo "listReports.php?identifier=$identifier" ?>" id="navcase-button">Reports</a>
            <a href="<?php echo "auditCase.php?identifier=$identifier" ?>" id="navcase-button">Case Audit</a>
            <?php include 'displayCaseAdminButtonFunction.php'; ?>
        </div>

        <section id="LBU">
            </br>
            <div id="navcase-bar">
                <a href="<?php echo 'downloadReport.php?identifier=' . htmlspecialchars($identifier) . '&ReportID=' . htmlspecialchars($reportID) ?>" id="navcase-button">Download Report</a>
            </div>

            <p>

            <?php

        if (isset($_GET['identifier']) && isset($_GET['ReportID'])) {
            $identifier = $_GET['identifier'];
            $reportID = $_GET['ReportID'];

            $stmt = $connection->prepare("SELECT FileName, FileType, FileSize, FileContent, MD5Hash, SHA1Hash 
                                            FROM reportfiles WHERE Identifier = ? AND ReportID = ?");
            $stmt->bind_param("ss", $identifier, $reportID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($filename, $filetype, $filesize, $filecontent, $uploadedMD5Hash, $uploadedSHA1Hash);

            if ($stmt->fetch()) {

                $retrievedMD5Hash = md5($filecontent);
                $retrievedSHA1Hash = sha1($filecontent);

                if ($retrievedMD5Hash === $uploadedMD5Hash && $retrievedSHA1Hash === $uploadedSHA1Hash) {
                    $hashResult = "✅ Hashes match. File integrity is verified.";
                } else {
                    $hashResult = "❌ Hash mismatch. Verification failed.";
                }
                
                $_SESSION['fileName'] = $filename;
                $_SESSION['fileType'] = $filetype;
                $_SESSION['fileSize'] = $filesize;
                $_SESSION['uploadedMD5Hash'] = $uploadedMD5Hash;
                $_SESSION['uploadedSHA1Hash'] = $uploadedSHA1Hash;
                $_SESSION['retrievedMD5Hash'] = $retrievedMD5Hash;
                $_SESSION['retrievedSHA1Hash'] = $retrievedSHA1Hash;


                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Report Integrity</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo "<tr><td class='lbu-high'>Report ID</td><td>" . $reportID . "</td></tr>";
                echo "<tr><td class='lbu-high'>Filename</td><td>" . $filename . "</td></tr>";
                echo "<tr><td class='lbu-high'>File Type</td><td>" . $filetype . "</td></tr>";
                echo "<tr><td class='lbu-high'>File Size</td><td>" . formatBytes($filesize) . "</td></tr>";
                echo "<tr><td class='lbu-high'>Uploaded File MD5 Hash</td><td>" . $uploadedMD5Hash . "</td></tr>";
                echo "<tr><td class='lbu-high'>Uploaded File SHA1 Hash</td><td>" . $uploadedSHA1Hash . "</td></tr>";
                echo "<tr><td class='lbu-high'>Retrieved File MD5 Hash</td><td>" . $retrievedMD5Hash . "</td></tr>";
                echo "<tr><td class='lbu-high'>Retrieved File SHA1 Hash</td><td>" . $retrievedSHA1Hash . "</td></tr>";
                echo "<tr><td class='lbu-high'>File Integrity Verification</td><td>" . $hashResult . "</td></tr>";
                echo "</table>";
                echo "<br/>";

        //header('location:downloadReport.php?identifier=' . htmlspecialchars($identifier) . '&ReportID=' . htmlspecialchars($reportID));
        
    } else {
        echo "File not found!";
    }

    $stmt->close();
    $connection->close();
} else {
    echo "No file identifier provided!";
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