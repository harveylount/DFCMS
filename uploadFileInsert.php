<?php
include 'SqlConnection.php';
include 'timezoneFunction.php';

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection

if (isset($_GET['EvidenceID'])) { 
    $evidenceID = intval($_GET['EvidenceID']);  

    $sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $identifier, $evidenceID);
    $stmt->execute();
    $stmt->bind_result($caseReference, $exhibitReference);
    $stmt->fetch();
    mysqli_stmt_close($stmt);
}
if (isset($_GET['LBU06id'])) { 
    $LBU06id = intval($_GET['LBU06id']);  
    $sql = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->bind_result($caseReference);
    $stmt->fetch();
    mysqli_stmt_close($stmt);
}


if (isset($_POST['subImageEvent'])) {

    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];
    $timestamp = date('Y-m-d H:i:s');
    $name = $_POST['txtFileName'];
    $type = "Image";
    $notes=$_POST['txtNotes'];

    $_SESSION['txtFileNameF']=$name;
    $_SESSION['txtNotesF']=$notes;

    if (preg_match('/^[a-zA-Z0-9!@#$%^&*(),.?":{}|<>_-]{0,50}$/', $name)) {

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $filename = $_FILES['file']['name'];
            $filetype = $_FILES['file']['type'];
            $filesize = $_FILES['file']['size'];
            $filecontent = file_get_contents($_FILES['file']['tmp_name']); // Read the file content
            $MD5Hash = md5($filecontent);
            $SHA1Hash = sha1($filecontent);
            
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            

            if (strlen($notes) <= 1000) {
                //continue code
            } else {
                $_SESSION['txtNotesM'] = 'Maximum length of 1000 characters';
                header('Location: uploadImageFileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                exit();
            }
            
            $allowedExtensions = ['e01', 'dd', 'img', 'iso', 'vmdk'];
            if (in_array($fileExt, $allowedExtensions)) {

                unset($_SESSION['txtFileNameF']);
                unset($_SESSION['txtFileNameM']);
                unset($_SESSION['txtNotesF']);
                unset($_SESSION['txtNotesM']);

                $stmt = $connection->prepare("INSERT INTO exhibituploadedfiles (Identifier, EvidenceID, UploadType, SetName, FileName, FileType, FileSize, FileContent, UploaderFullName, UploaderUsername, UploadTimestamp, Notes, MD5Hash, SHA1Hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssss", $identifier, $evidenceID, $type, $name, $filename, $filetype, $filesize, $filecontent, $fullName, $username, $timestamp, $notes, $MD5Hash, $SHA1Hash);
                if ($stmt->execute()) {

                    // Audit Log
                    $sqlFileID = "SELECT FileID FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ? AND UploadType = ? AND SetName = ? AND FileName = ? AND FileSize = ?";
                    $stmt = $connection->prepare($sqlFileID);
                    $stmt->bind_param("ssssss", $identifier, $evidenceID, $type, $name, $filename, $filesize);
                    $stmt->execute();
                    $stmt->bind_result($fileID);
                    $stmt->fetch();
                    mysqli_stmt_close($stmt);

                    $action = "Uploaded an exhibit image file. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". Exhibit File ID: " . $fileID . ". Upload MD5 Hash: " . $MD5Hash . ". Upload SHA-1 Hash: " . $SHA1Hash . ".";
                    $type = "Exhibit";

                    $query = "INSERT INTO auditlog 
                        (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, ExhibitFileID, MD5Hash, SHA1Hash, Timestamp, ActionerFullName, ActionerUsername, Action)
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "ssssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $fileID, $MD5Hash, $SHA1Hash, $timestamp, $fullName, $username, $action);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);


                    header('Location: listImageFIles.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                    exit();
                } else {
                    echo "Error uploading file: " . $stmt->error;
                    exit();
                }
                $stmt->close();
                exit();

            } else {
                $_SESSION['errorMessage']='Invalid file type. Only .e01, .dd, .img, .iso, .vmdk are allowed';
                header('Location: uploadImageFileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                exit();
            }

        } else {
            $_SESSION['errorMessage']='No file uploaded or there was an error with the file';
            header('Location: uploadImageFileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
            exit();
        }

    } else {
        $_SESSION['txtFileNameM']='Only alpha, 0-9 & (!@#$%^&*(),.?":{}|<>_-) characters allowed with maximum length of 50';
        header('Location: uploadImageFileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }

} else if (isset($_POST['subPhotoEvent'])) {

    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];
    $timestamp = date('Y-m-d H:i:s');
    $name = $_POST['txtFileName'];
    $type = "ExhibitPhoto";
    $notes=$_POST['txtNotes'];

    $_SESSION['txtFileNameF']=$name;
    $_SESSION['txtNotesF']=$notes;

    if (preg_match('/^[a-zA-Z0-9!@#$%^&*(),.?":{}|<>_-]{0,50}$/', $name)) {

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $filename = $_FILES['file']['name'];
            $filetype = $_FILES['file']['type'];
            $filesize = $_FILES['file']['size'];
            $filecontent = file_get_contents($_FILES['file']['tmp_name']); // Read the file content
            $MD5Hash = md5($filecontent);
            $SHA1Hash = sha1($filecontent);
            
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'tiff', 'tif', 'bmp', 'webp', 'heif', 'heic', 'raw', 'cr2', 'nef', 'arw'];

            if (strlen($notes) <= 1000) {
                // continue code
            } else {
                $_SESSION['txtNotesM'] = 'Maximum length of 1000 characters';
                header('Location: uploadImageFileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                exit();
            }
            
            if (in_array($fileExt, $allowedExtensions)) {

                unset($_SESSION['txtFileNameF']);
                unset($_SESSION['txtFileNameM']);
                unset($_SESSION['txtNotesF']);
                unset($_SESSION['txtNotesM']);

                $stmt = $connection->prepare("INSERT INTO exhibituploadedfiles (Identifier, EvidenceID, UploadType, SetName, FileName, FileType, FileSize, FileContent, UploaderFullName, UploaderUsername, UploadTimestamp, Notes, MD5Hash, SHA1Hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssss", $identifier, $evidenceID, $type, $name, $filename, $filetype, $filesize, $filecontent, $fullName, $username, $timestamp, $notes, $MD5Hash, $SHA1Hash);
                if ($stmt->execute()) {

                    // Audit Log
                    $sqlFileID = "SELECT FileID FROM exhibituploadedfiles WHERE Identifier = ? AND EvidenceID = ? AND UploadType = ? AND SetName = ? AND FileName = ? AND FileSize = ?";
                    $stmt = $connection->prepare($sqlFileID);
                    $stmt->bind_param("ssssss", $identifier, $evidenceID, $type, $name, $filename, $filesize);
                    $stmt->execute();
                    $stmt->bind_result($fileID);
                    $stmt->fetch();
                    mysqli_stmt_close($stmt);

                    $action = "Uploaded an exhibit photo file. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ". Exhibit File ID: " . $fileID . ". Upload MD5 Hash: " . $MD5Hash . ". Upload SHA-1 Hash: " . $SHA1Hash . ".";
                    $type = "Exhibit";

                    $query = "INSERT INTO auditlog 
                        (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, ExhibitFileID, MD5Hash, SHA1Hash, Timestamp, ActionerFullName, ActionerUsername, Action)
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "ssssssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $fileID, $MD5Hash, $SHA1Hash, $timestamp, $fullName, $username, $action);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);


                    header('Location: listExhibitPhotoFIles.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                    exit();
                } else {
                    echo "Error uploading file: " . $stmt->error;
                    exit();
                }
                $stmt->close();
                exit();

            } else {
                $_SESSION['errorMessage']='Invalid file type. Only .jpg, .jpeg, .png, .gif, .tiff, .tif, .bmp, .webp, .heif, .heic, .raw, .cr2, .nef, .arw are allowed';
                header('Location: uploadExhibitPhotoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
                exit();
            }

        } else {
            $_SESSION['errorMessage']='No file uploaded or there was an error with the file';
            header('Location: uploadExhibitPhotoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
            exit();
        }

    } else {
        $_SESSION['txtFileNameM']='Only alpha, 0-9 & (!@#$%^&*(),.?":{}|<>_-) characters allowed with maximum length of 50';
        header('Location: uploadExhibitPhotoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }

} else if (isset($_POST['subScenePhotoEvent'])) {

    // Check if number of uploaded photos is equal to or more than specified amount
    $query = "SELECT NumberOfPhotos FROM lbu06 WHERE Identifier = ? AND LBU06id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $identifier, $LBU06id);  
    $stmt->execute();
    $stmt->bind_result($setNumberOfPhotos);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

    $query = "SELECT COUNT(*) AS photoCount FROM sceneuploadedfiles WHERE Identifier = ? AND LBU06id = ? AND UploadType = 'ScenePhoto'"; 
    $stmt = $connection->prepare($query);  
    $stmt->bind_param("ss", $identifier, $LBU06id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $scenePhotoCount = $row['photoCount'];
    $stmt->close();

    if ($scenePhotoCount >= $setNumberOfPhotos) {
        $_SESSION['countErrorMessage']="Cannot upload more scene photos, specified number in report reached";
        header('Location: listScenePhotoFiles.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
        exit();
    }

    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];
    $timestamp = date('Y-m-d H:i:s');
    $name = $_POST['txtFileName'];
    $type = "ScenePhoto";
    $notes=$_POST['txtNotes'];

    $_SESSION['txtFileNameF']=$name;
    $_SESSION['txtNotesF']=$notes;

    if (preg_match('/^[a-zA-Z0-9!@#$%^&*(),.?":{}|<>_-]{0,50}$/', $name)) {

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $filename = $_FILES['file']['name'];
            $filetype = $_FILES['file']['type'];
            $filesize = $_FILES['file']['size'];
            $filecontent = file_get_contents($_FILES['file']['tmp_name']); // Read the file content
            $MD5Hash = md5($filecontent);
            $SHA1Hash = sha1($filecontent);
            
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'tiff', 'tif', 'bmp', 'webp', 'heif', 'heic', 'raw', 'cr2', 'nef', 'arw'];

            if (strlen($notes) <= 1000) {
                // continue code
            } else {
                $_SESSION['txtNotesM'] = 'Maximum length of 1000 characters';
                header('Location: uploadScenePhotoForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
                exit();
            }
            
            if (in_array($fileExt, $allowedExtensions)) {

                unset($_SESSION['txtFileNameF']);
                unset($_SESSION['txtFileNameM']);
                unset($_SESSION['txtNotesF']);
                unset($_SESSION['txtNotesM']);

                $stmt = $connection->prepare("INSERT INTO sceneuploadedfiles (Identifier, LBU06id, UploadType, SetName, FileName, FileType, FileSize, FileContent, UploaderFullName, UploaderUsername, UploadTimestamp, Notes, MD5Hash, SHA1Hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssss", $identifier, $LBU06id, $type, $name, $filename, $filetype, $filesize, $filecontent, $fullName, $username, $timestamp, $notes, $MD5Hash, $SHA1Hash);
                if ($stmt->execute()) {

                    // Audit Log
                    $sqlFileID = "SELECT SceneFileID FROM sceneuploadedfiles WHERE Identifier = ? AND LBU06id = ? AND UploadType = ? AND SetName = ? AND FileName = ? AND FileSize = ?";
                    $stmt = $connection->prepare($sqlFileID);
                    $stmt->bind_param("ssssss", $identifier, $LBU06id, $type, $name, $filename, $filesize);
                    $stmt->execute();
                    $stmt->bind_result($fileID);
                    $stmt->fetch();
                    mysqli_stmt_close($stmt);

                    $action = "Uploaded a crime scene photo file. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". LBU06 ID: " . $LBU06id . ". Scene File ID: " . $fileID . ". Upload MD5 Hash: " . $MD5Hash . ". Upload SHA-1 Hash: " . $SHA1Hash . ".";
                    $type = "Case";

                    $query = "INSERT INTO auditlog 
                        (Identifier, CaseReference, EntryType, LBU06id, SceneFileID, MD5Hash, SHA1Hash, Timestamp, ActionerFullName, ActionerUsername, Action)
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "sssssssssss", $identifier, $caseReference, $type, $LBU06id, $fileID, $MD5Hash, $SHA1Hash, $timestamp, $fullName, $username, $action);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    header('Location: listScenePhotoFiles.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
                    exit();
                } else {
                    echo "Error uploading file: " . $stmt->error;
                    exit();
                }
                $stmt->close();
                exit();

            } else {
                $_SESSION['errorMessage']='Invalid file type. Only .jpg, .jpeg, .png, .gif, .tiff, .tif, .bmp, .webp, .heif, .heic, .raw, .cr2, .nef, .arw are allowed';
                header('Location: uploadScenePhotoForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
                exit();
            }

        } else {
            $_SESSION['errorMessage']='No file uploaded or there was an error with the file';
            header('Location: uploadScenePhotoForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
            exit();
        }

    } else {
        $_SESSION['txtFileNameM']='Only alpha, 0-9 & (!@#$%^&*(),.?":{}|<>_-) characters allowed with maximum length of 50';
        header('Location: uploadScenePhotoForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
        exit();
    }

} else if (isset($_POST['subSceneSketchEvent'])) {

    // Check if number of uploaded sketches is equal to or more than specified amount
    $query = "SELECT NumberOfSketches FROM lbu06 WHERE Identifier = ? AND LBU06id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $identifier, $LBU06id);  
    $stmt->execute();
    $stmt->bind_result($setNumberOfSketches);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

    $query = "SELECT COUNT(*) AS sketchCount FROM sceneuploadedfiles WHERE Identifier = ? AND LBU06id = ? AND UploadType = 'SceneSketch'"; 
    $stmt = $connection->prepare($query);  
    $stmt->bind_param("ss", $identifier, $LBU06id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $sceneSketchCount = $row['sketchCount'];
    $stmt->close();

    if ($sceneSketchCount >= $setNumberOfSketches) {
        $_SESSION['countErrorMessage']="Cannot upload more scene sketches, specified number in report reached";
        header('Location: listSceneSketchFiles.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
        exit();
    }

    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];
    $timestamp = date('Y-m-d H:i:s');
    $name = $_POST['txtFileName'];
    $type = "SceneSketch";
    $notes=$_POST['txtNotes'];

    $_SESSION['txtFileNameF']=$name;
    $_SESSION['txtNotesF']=$notes;

    if (preg_match('/^[a-zA-Z0-9!@#$%^&*(),.?":{}|<>_-]{0,50}$/', $name)) {

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $filename = $_FILES['file']['name'];
            $filetype = $_FILES['file']['type'];
            $filesize = $_FILES['file']['size'];
            $filecontent = file_get_contents($_FILES['file']['tmp_name']); // Read the file content
            $MD5Hash = md5($filecontent);
            $SHA1Hash = sha1($filecontent);
            
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'tiff', 'tif', 'bmp', 'webp', 'heif', 'heic', 'raw', 'cr2', 'nef', 'arw'];

            if (strlen($notes) <= 1000) {
                // continue code
            } else {
                $_SESSION['txtNotesM'] = 'Maximum length of 1000 characters';
                header('Location: uploadSceneSketchForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
                exit();
            }
            
            if (in_array($fileExt, $allowedExtensions)) {

                unset($_SESSION['txtFileNameF']);
                unset($_SESSION['txtFileNameM']);
                unset($_SESSION['txtNotesF']);
                unset($_SESSION['txtNotesM']);

                $stmt = $connection->prepare("INSERT INTO sceneuploadedfiles (Identifier, LBU06id, UploadType, SetName, FileName, FileType, FileSize, FileContent, UploaderFullName, UploaderUsername, UploadTimestamp, Notes, MD5Hash, SHA1Hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssss", $identifier, $LBU06id, $type, $name, $filename, $filetype, $filesize, $filecontent, $fullName, $username, $timestamp, $notes, $MD5Hash, $SHA1Hash);
                if ($stmt->execute()) {

                    // Audit Log
                    $sqlFileID = "SELECT SceneFileID FROM sceneuploadedfiles WHERE Identifier = ? AND LBU06id = ? AND UploadType = ? AND SetName = ? AND FileName = ? AND FileSize = ?";
                    $stmt = $connection->prepare($sqlFileID);
                    $stmt->bind_param("ssssss", $identifier, $LBU06id, $type, $name, $filename, $filesize);
                    $stmt->execute();
                    $stmt->bind_result($fileID);
                    $stmt->fetch();
                    mysqli_stmt_close($stmt);

                    $action = "Uploaded a crime scene sketch file. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". LBU06 ID: " . $LBU06id . ". Scene File ID: " . $fileID . ". Upload MD5 Hash: " . $MD5Hash . ". Upload SHA-1 Hash: " . $SHA1Hash . ".";
                    $type = "Case";

                    $query = "INSERT INTO auditlog 
                        (Identifier, CaseReference, EntryType, LBU06id, SceneFileID, MD5Hash, SHA1Hash, Timestamp, ActionerFullName, ActionerUsername, Action)
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "sssssssssss", $identifier, $caseReference, $type, $LBU06id, $fileID, $MD5Hash, $SHA1Hash, $timestamp, $fullName, $username, $action);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    header('Location: listSceneSketchFiles.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
                    exit();
                } else {
                    echo "Error uploading file: " . $stmt->error;
                    exit();
                }
                $stmt->close();
                exit();

            } else {
                $_SESSION['errorMessage']='Invalid file type. Only .jpg, .jpeg, .png, .gif, .tiff, .tif, .bmp, .webp, .heif, .heic, .raw, .cr2, .nef, .arw are allowed';
                header('Location: uploadSketchPhotoForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
                exit();
            }

        } else {
            $_SESSION['errorMessage']='No file uploaded or there was an error with the file';
            header('Location: uploadSceneSketchForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
            exit();
        }

    } else {
        $_SESSION['txtFileNameM']='Only alpha, 0-9 & (!@#$%^&*(),.?":{}|<>_-) characters allowed with maximum length of 50';
        header('Location: uploadSceneSketchForm.php?identifier=' . $identifier . '&LBU06id=' . $LBU06id);
        exit();
    }

} else {
    header('Location: index.php');
    exit();
}
?>