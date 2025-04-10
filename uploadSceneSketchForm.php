<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$LBU06id = intval($_GET['LBU06id']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$query = "SELECT NumberOfPhotos FROM lbu06 WHERE Identifier = ? AND LBU06id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $LBU06id);  
                $stmt->execute();
                $stmt->bind_result($numberOfPhotos);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Files</title>

    <style>
        .notes-input {
            height: 300px;
            width: 950px;
        }
    </style>

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
            <a href="<?php echo "listScenePhotoFiles.php?identifier=" . $identifier . "&LBU06id=" . $LBU06id; ?>" id="navcase-button">Scene Photos</a>
            <a href="<?php echo "listSceneSketchFiles.php?identifier=" . $identifier . "&LBU06id=" . $LBU06id; ?>" id="navcase-button">Scene Sketches</a>
        </div>

        <section id="LBU">

            <p>
            <?php

                $query = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", $identifier);  
                $stmt->execute();
                $stmt->bind_result($caseReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 46px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Upload Scene Sketch</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>";
                echo "</table>";
                echo "<br/>";
        
            ?>
            
            <form action="uploadFileInsert.php?identifier=<?php echo urlencode($identifier) . "&LBU06id=" . urlencode($LBU06id) ?>" method="POST" enctype="multipart/form-data">
            <fieldset class="field-set width">

            
                </br>
                <label for="txtFileName">Memorable File Name: *</label><br />
                <input type="text" name="txtFileName" value="<?php 
                    if(isset($_SESSION['txtFileNameF'])) {
                        echo $_SESSION['txtFileNameF'];
                        unset($_SESSION['txtFileNameF']);
                    }
                ?>" required/><p class="error-message"><?php 
                    if(isset($_SESSION['txtFileNameM'])) {
                        echo $_SESSION['txtFileNameM']; 
                        unset($_SESSION['txtFileNameM']);
                    }
                ?> </p><br /><br />

                <label for="file">Choose a file to upload *:</label></br></br>
                <input type="file" name="file" id="file" required></br></br>
                <?php
                    if(isset($_SESSION['errorMessage'])) {
                        echo '<p class="error-message">' . $_SESSION['errorMessage'] . '</p></br></br>'; 
                        unset($_SESSION['errorMessage']);
                    }
                ?>

                <label for="txtNotes">File Notes:</label><br />
                <textarea name="txtNotes" class="notes-input"><?php if (isset($_SESSION['txtNotesF'])) { echo $_SESSION['txtNotesF']; unset($_SESSION['txtNotesF']); }?></textarea><br /><br />
                <?php if (isset($_SESSION['txtNotesM'])) { echo '<p class="error-message">' . $_SESSION['txtNotesM'] . '</p></br></br>'; unset($_SESSION['txtNotesM']); } ?>

                <button type="submit" name="subSceneSketchEvent">Upload File</button>
            </form>



            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>