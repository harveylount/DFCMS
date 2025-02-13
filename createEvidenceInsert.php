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
    $OS=$_POST['txtOS'];
    $CPU=$_POST['txtCPU'];
    $RAM=$_POST['txtRAM'];
    $MAC=$_POST['txtMAC'];
    $IP=$_POST['txtIP'];
    $firmware=$_POST['txtFirmware'];
    $peripheral=$_POST['txtPeripheral'];
    $network=$_POST['txtNetwork'];

    $_SESSION['txtExhibitReferenceF']=$exhibitReference;
    $_SESSION['txtManufacturerF']=$manufacturer;
    $_SESSION['txtModelF']=$model;
    $_SESSION['txtSerialF']=$serial;
    $_SESSION['txtStorageF']=$storage;
    $_SESSION['txtOSF']=$OS;
    $_SESSION['txtCPUF']=$CPU;
    $_SESSION['txtRAMF']=$RAM;
    $_SESSION['txtMACF']=$MAC;
    $_SESSION['txtIPF']=$IP;
    $_SESSION['txtFirmwareF']=$firmware;
    $_SESSION['txtPeripheralF']=$peripheral;
    $_SESSION['txtNetworkF']=$network;


    if (preg_match('/^.{1,10}$/', $exhibitReference)) {
        $exhibitReferenceCheck = true;
    } else {
        $exhibitReferenceCheck = false;
        $_SESSION['txtExhibitReferenceM']='Maximum string length of 10 characters';
    }

    if (preg_match('/^.{1,20}$/', $manufacturer)) {
        $manufacturerCheck = true;
    } else {
        $manufacturerCheck = false;
        $_SESSION['txtManufacturerM']='Maximum string length of 20 characters';
    }
    
    if (preg_match('/^.{1,20}$/', $model)) {
        $modelCheck = true;
    } else {
        $modelCheck = false;
        $_SESSION['txtModelM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{1,40}$/', $serial)) {
        $serialCheck = true;
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

    if (preg_match('/^.{1,20}$/', $OS)) {
        $OSCheck = true;
    } else {
        $OSCheck = false;
        $_SESSION['txtOSM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,40}$/', $CPU)) {
        $CPUCheck = true;
    } else {
        $CPUCheck = false;
        $_SESSION['txtCPUM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,20}$/', $RAM)) {
        $RAMCheck = true;
    } else {
        $RAMCheck = false;
        $_SESSION['txtRAMM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,17}$/', $MAC)) {
        $MACCheck = true;
    } else {
        $MACCheck = false;
        $_SESSION['txtMACM']='Maximum string length of 17 characters';
    }

    if (preg_match('/^.{0,20}$/', $IP)) {
        $IPCheck = true;
    } else {
        $IPCheck = false;
        $_SESSION['txtIPM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,5}$/', $firmware)) {
        $firmwareCheck = true;
    } else {
        $firmwareCheck = false;
        $_SESSION['txtFirmwareM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,80}$/', $peripheral)) {
        $peripheralCheck = true;
    } else {
        $peripheralCheck = false;
        $_SESSION['txtPeripheralM']='Maximum string length of 80 characters';
    }

    if (preg_match('/^.{0,40}$/', $network)) {
        $networkCheck = true;
    } else {
        $networkCheck = false;
        $_SESSION['txtNetworkM']='Maximum string length of 40 characters';
    }

    if ($exhibitReferenceCheck && $manufacturerCheck && $modelCheck && $serialCheck && $storageCheck && $OSCheck && $CPUCheck && $RAMCheck && $MACCheck && $IPCheck && $firmwareCheck && $peripheralCheck && $networkCheck) {
        
        $session_vars = [
            'txtExhibitReferenceF', 'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtStorageF', 'txtOSF', 'txtCPUF', 'txtRAMF', 'txtMACF', 'txtIPF', 
            'txtFirmwareF', 'txtPeripheralF', 'txtNetworkF'];
        foreach ($session_vars as $var) {
            unset($_SESSION[$var]);
        }

        $query = "INSERT INTO evidence 
                (Identifier, ExhibitRef, SeizedTime, EvidenceStatus, DeviceType, Manufacturer, Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $identifier, $exhibitReference, $seizedTime, $status, $deviceType, $manufacturer, $model, $serial, $storage, $OS, $CPU, $RAM, $MAC, $IP, $firmware, $peripheral, $network);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: viewEvidence.php?identifier=' . $identifier);

        exit();

    } else {
        header('Location: createEvidenceForm.php?identifier=' . $identifier);
        exit();
    }
    
}


?>