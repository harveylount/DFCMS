<?php
include 'SqlConnection.php';
include 'timezoneFunction.php';

$identifier = intval($_GET['identifier']);  // Sanitized input to prevent SQL injection

if (isset($_GET['EvidenceID'])) { 
    $evidenceID = intval($_GET['EvidenceID']);  
}
if (isset($_GET['LBU06id'])) { 
    $LBU06id = intval($_GET['LBU06id']);  
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
            
            $allowedExtensions = ['e01', 'dd', 'img', 'iso', 'vmdk'];

            if (strlen($notes) <= 1000) {
                //continue code
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