<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

$identifier = intval($_GET['identifier']);
$evidenceID = intval($_GET['EvidenceID']);


if (isset($_POST['subEvent'])) {
    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];
    $timestamp = date('Y-m-d H:i:s');



    $manufacturer=$_POST['txtManufacturer'];
    $model=$_POST['txtModel'];
    $serial=$_POST['txtSerial'];
    $storage=$_POST['txtStorage'];
    if (isset($_POST['interfaceType'])) {
        $interface=$_POST['interfaceType'];
    }
    if (isset($_POST['fileSystemType'])) {
        $fileSystem=$_POST['fileSystemType'];
    }
    $encryption=$_POST['txtEncryption'];


    $_SESSION['txtManufacturerF']=$manufacturer;
    $_SESSION['txtModelF']=$model;
    $_SESSION['txtSerialF']=$serial;
    $_SESSION['txtStorageF']=$storage;
    $_SESSION['txtEncryptionF']=$encryption;


    $query = "SELECT Manufacturer, Model, SerialNumber, Storage, EncryptionType, InterfaceType, FileSystem FROM evidence WHERE EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $evidenceID);
        $stmt->execute();
        $stmt->bind_result($manufacturerBackup, $modelBackup, $serialBackup, $storageBackup, $encryptionTypeBackup, $interfaceTypeBackup, $fileSystemType);
        $stmt->fetch();
        mysqli_stmt_close($stmt);

    if ($manufacturer == '') {
    $manufacturer = $manufacturerBackup;
    }

    if ($model == '') {
        $model = $modelBackup;
    }

    if ($serial == '') {
        $serial = $serialBackup;
    }

    if ($storage == '') {
        $storage = $storageBackup;
    }

    if ($encryption == '') {
        $encryption = $encryptionTypeBackup;
    }

    if (!isset($interface)) {
        $interface = $interfaceTypeBackup;
    }

    if (!isset($fileSystem)) {
        $fileSystem = $fileSystemType;
    }


    if (preg_match('/^.{0,20}$/', $manufacturer)) {
        $manufacturerCheck = true;
    } else {
        $manufacturerCheck = false;
        $_SESSION['txtManufacturerM']='Maximum string length of 20 characters';
    }
    
    if (preg_match('/^.{0,20}$/', $model)) {
        $modelCheck = true;
    } else {
        $modelCheck = false;
        $_SESSION['txtModelM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,40}$/', $serial)) {
        $serialCheck = true;
    } else {
        $serialCheck = false;
        $_SESSION['txtSerialM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,40}$/', $storage)) {
        $storageCheck = true;
    } else {
        $storageCheck = false;
        $_SESSION['txtStorageM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,40}$/', $encryption)) {
        $encryptionCheck = true;
    } else {
        $encryptionCheck = false;
        $_SESSION['txtEncryptionM']='Maximum string length of 40 characters';
    }



    if ($manufacturerCheck && $modelCheck && $serialCheck && $storageCheck && $encryptionCheck) {
    
        $session_vars = [
            'txtExhibitReferenceF', 'txtSealNumberF', 'txtLocationF', 
            'txtReceivedFromF', 'txtReceivedFromRankF', 'txtReceivedFromCompanyF',
            'txtTypeF', 'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtIMEIF', 'txtSIMF', 'txtNumberF', 'txtMACF', 'txtStorageF', 'txtOSF', 
            'txtBatteryF', 'txtAppsF', 'txtEncryptionF', 'txtAccountF', 'txtPasscodeF', 'txtDispatchByEmailF'];
        foreach ($session_vars as $var) {
            unset($_SESSION[$var]);
        }

        $query = "INSERT INTO exhibitinfobackup 
            (Identifier, EvidenceID, CaseReference, ExhibitRef, EvidenceType, Manufacturer, Model, SerialNumber, Storage, EncryptionType, InterfaceType, FileSystem, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername) 
            SELECT Identifier, EvidenceID, CaseReference, ExhibitRef, EvidenceType, Manufacturer, Model, SerialNumber, Storage, EncryptionType, InterfaceType, FileSystem, ?, ?, ? 
            FROM evidence WHERE EvidenceID = ?";
            
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $timestamp, $fullName, $username, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "UPDATE evidence SET EditedTime = ?, EditedByFullName = ?, EditedByUsername = ?, Manufacturer = ?, Model = ?, SerialNumber = ?, Storage = ?, EncryptionType = ?, InterfaceType = ?, FileSystem = ? WHERE EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $timestamp, $fullName, $username, $manufacturer, $model, $serial, $storage, $encryption, $interface, $fileSystem, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: viewEvidenceExhibit.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: editExhibitInfoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }

    exit();
    
}


?>