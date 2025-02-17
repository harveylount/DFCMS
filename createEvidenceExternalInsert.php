<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);

if (isset($_POST['subEvent'])) {
    $exhibitReference=$_POST['txtExhibitReference'];
    $seizedTime = date('Y-m-d H:i:s');
    $status = "Seized";
    $deviceType=$_POST['deviceType'];
    $manufacturer=$_POST['txtManufacturer'];
    $model=$_POST['txtModel'];
    $serial=$_POST['txtSerial'];
    $storage=$_POST['txtStorage'];
    $interface=$_POST['interfaceType'];
    $fileSystem=$_POST['fileSystemType'];
    $encryption=$_POST['txtEncryption'];
    

    $_SESSION['txtExhibitReferenceF']=$exhibitReference;
    $_SESSION['txtManufacturerF']=$manufacturer;
    $_SESSION['txtModelF']=$model;
    $_SESSION['txtSerialF']=$serial;
    $_SESSION['txtStorageF']=$storage;
    $_SESSION['txtEncryptionF']=$encryption;
    

    if (preg_match('/^.{1,10}$/', $exhibitReference)) {
        $exhibitReferenceCheck = true;
    } else {
        $exhibitReferenceCheck = false;
        $_SESSION['txtExhibitReferenceM']='Maximum string length of 10 characters';
    }

    if (preg_match('/^.{0,20}$/', $manufacturer)) {
        $manufacturerCheck = true;
        if (($manufacturer) == '') {
            $manufacturer = 'No Manufacturer';
        }
    } else {
        $manufacturerCheck = false;
        $_SESSION['txtManufacturerM']='Maximum string length of 20 characters';
    }
    
    if (preg_match('/^.{0,20}$/', $model)) {
        $modelCheck = true;
        if (($model) == '') {
            $model = 'No Model';
        }
    } else {
        $modelCheck = false;
        $_SESSION['txtModelM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,40}$/', $serial)) {
        $serialCheck = true;
        if (($serial) == '') {
            $serial = 'No Serial Number';
        }
    } else {
        $serialCheck = false;
        $_SESSION['txtSerialM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{1,40}$/', $storage)) {
        $storageCheck = true;
    } else {
        $storageCheck = false;
        $_SESSION['txtStorageM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,40}$/', $encryption)) {
        $encryptionCheck = true;
        if (($encryption) == '') {
            $encryption = 'No Encryption';
        }
    } else {
        $encryptionCheck = false;
        $_SESSION['txtEncryptionM']='Maximum string length of 40 characters';
    }


    if ($exhibitReferenceCheck && $manufacturerCheck && $modelCheck && $serialCheck && $storageCheck && $encryptionCheck) {
        
        $session_vars = [
            'txtExhibitReferenceF', 'deviceTypeF', 'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtStorageF', 'interfaceTypeF', 'fileSystemTypeF', 'txtEncryptionF'];
        foreach ($session_vars as $var) {
            unset($_SESSION[$var]);
        }

        $query = "INSERT INTO evidence 
                (Identifier, ExhibitRef, SeizedTime, EvidenceStatus, DeviceType, Manufacturer, Model, SerialNumber, Storage, InterfaceType, FileSystem, EncryptionType)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssss", $identifier, $exhibitReference, $seizedTime, $status, $deviceType, $manufacturer, $model, $serial, $storage, $interface, $fileSystem, $encryption);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: viewEvidence.php?identifier=' . $identifier);

        exit();

    } else {
        header('Location: createEvidenceExternalForm.php?identifier=' . $identifier);
        exit();
    }
    
}


?>