<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 
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
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "listExhibitPhotoFiles.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" class="logout-button">← Photos</a>
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
            <a href="<?php echo "listImageFiles.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" id="navcase-button">Image Files</a>
            <a href="<?php echo "listExhibitPhotoFiles.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" id="navcase-button">Exhibit Photos</a>
        </div>

        <section id="LBU">

            <p>
            <?php

                $query = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("ss", $identifier, $evidenceID);  
                $stmt->execute();
                $stmt->bind_result($caseReference, $exhibitReference);
                $stmt->fetch();
                mysqli_stmt_close($stmt);

                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Upload Photo</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Reference: ' . $exhibitReference . "</td></tr>";
                echo "</table>";
                echo "<br/>";
        
            ?>
            
            <form action="uploadFileInsert.php?identifier=<?php echo urlencode($identifier) . "&EvidenceID=" . urlencode($evidenceID) ?>" method="POST" enctype="multipart/form-data">
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

                <button type="submit" name="subPhotoEvent">Upload File</button>
            </form>



            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>