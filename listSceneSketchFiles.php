<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']); 
$LBU06id = intval($_GET['LBU06id']); 

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

    <title>Files</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewLBU06.php?identifier=" . $identifier . "&LBU06id=" . $LBU06id ?>" class="logout-button">← Scene Report</a>
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
            <a href="<?php echo "listScenePhotoFiles.php?identifier=" . $identifier . "&LBU06id=" . $LBU06id; ?>" id="navcase-button">Scene Photos</a>
            <a href="<?php echo "listSceneSketchFiles.php?identifier=" . $identifier . "&LBU06id=" . $LBU06id; ?>" id="navcase-button">Scene Sketches</a>
        </div>

        <section id="LBU">

            <p>
            <?php

                echo '<div id="navcase-bar">
                    <a href="uploadSceneSketchForm.php?identifier=' . $identifier . "&LBU06id=" . $LBU06id . '" id="navcase-button">Upload File</a>
                    </div>';
                echo "<br/>";

                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 42px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Crime Scene Sketches</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>";
                echo "</table>";
                echo "<br/>";

                echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
                echo "<tr><th class='lbu-dark' style='width: 90px'>Scene File ID</th><th class='lbu-dark'>Identifier Name</th><th class='lbu-dark''>File Name</th><th class='lbu-dark' style='width: 90px'>File Type</th><th class='lbu-dark' style='width: 90px'>File Size</th><th class='lbu-dark' style='width: 90px';>Timestamp</th><th class='lbu-dark' style='width: 35px;'></th><th class='lbu-dark' style='width: 72px;'></th></tr>";

                $sql = "SELECT SceneFileID, Identifier, LBU06id, UploadType, SetName, FileName, FileType, FileSize, UploaderFullName, UploaderUsername, UploadTimestamp FROM sceneuploadedfiles WHERE Identifier = ? AND LBU06id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("ss", $identifier, $LBU06id);
                $stmt->execute();
                $results = $stmt->get_result();

                while ($row = $results->fetch_assoc()) {
                    if ($row['UploadType'] == 'SceneSketch') {
                        echo '<tr>';
                        echo '<td>' . $row['SceneFileID'] . '</td>';
                        echo '<td>' . $row['SetName'] . '</td>';
                        echo '<td>' . $row['FileName'] . '</td>';
                        echo '<td>' . $row['FileType'] . '</td>';
                        echo '<td>' . formatBytes($row['FileSize']) . '</td>';
                        echo '<td>' . $row['UploadTimestamp'] . '</td>';
                        echo '<td><a href="viewSceneFile.php?identifier=' . $row['Identifier'] . '&LBU06id=' . $row['LBU06id'] .  '&SceneFileID=' . $row['SceneFileID'] . '">View</a></td>';
                        echo '<td><a href="downloadFile.php?identifier=' . $row['Identifier'] . '&LBU06id=' . $row['LBU06id'] .  '&SceneFileID=' . $row['SceneFileID'] . '">Download</a></td>';
                        echo '</tr>';
                    }
                }

                echo "</table>";
                echo "<br/>";

                if (isset($_SESSION['countErrorMessage'])) {
                    echo '<p class="error-message">' . $_SESSION['countErrorMessage'] . '</p>';
                    unset($_SESSION['countErrorMessage']);
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