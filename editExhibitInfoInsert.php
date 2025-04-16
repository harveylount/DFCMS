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
    $OS=$_POST['txtOS'];
    $CPU=$_POST['txtCPU'];
    $RAM=$_POST['txtRAM'];
    $MAC=$_POST['txtMAC'];
    $IP=$_POST['txtIP'];
    $firmware=$_POST['txtFirmware'];
    $peripheral=$_POST['txtPeripheral'];
    $network=$_POST['txtNetwork'];


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


    $query = "SELECT Manufacturer, Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network FROM evidence WHERE EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $evidenceID);
        $stmt->execute();
        $stmt->bind_result($manufacturerBackup, $modelBackup, $serialBackup, $storageBackup, $OSBackup, $CPUBackup, $RAMBackup, $MACBackup, $IPBackup, $firmwareBackup, $peripheralBackup, $networkBackup);
        $stmt->fetch();
        mysqli_stmt_close($stmt);

    $query = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "si", $identifier, $evidenceID);
        $stmt->execute();
        $stmt->bind_result($caseReference, $exhibitReference);
        $stmt->fetch();
        mysqli_stmt_close($stmt);



    if ($manufacturer == '') {
        $manufacturer=$manufacturerBackup;
    }

    if ($model == '') {
        $model=$modelBackup;
    }

    if ($serial == '') {
        $serial=$serialBackup;
    }
    
    if ($storage == '') {
        $storage=$storageBackup;
    }

    if ($OS == '') {
        $OS=$OSBackup;
    }

    if ($CPU == '') {
        $CPU=$CPUBackup;
    }

    if ($RAM == '') {
        $RAM=$RAMBackup;
    }

    if ($MAC == '') {
        $MAC=$MACBackup;
    }

    if ($IP == '') {
        $IP=$IPBackup;
    }

    if ($firmware == '') {
        $firmware=$firmwareBackup;
    }

    if ($peripheral == '') {
        $peripheral=$peripheralBackup;
    }

    if ($network == '') {
        $network=$networkBackup;
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

    if (preg_match('/^.{0,40}$/', $OS)) {
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

    if (preg_match('/^.{0,30}$/', $firmware)) {
        $firmwareCheck = true;
    } else {
        $firmwareCheck = false;
        $_SESSION['txtFirmwareM']='Maximum string length of 30 characters';
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

    if ($manufacturerCheck && $modelCheck && $serialCheck && $storageCheck && $OSCheck && $CPUCheck && $RAMCheck && $MACCheck && $IPCheck && $firmwareCheck && $peripheralCheck && $networkCheck) {
        
        $session_vars = [
            'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtStorageF', 'txtOSF', 'txtCPUF', 'txtRAMF', 'txtMACF', 'txtIPF', 
            'txtFirmwareF', 'txtPeripheralF', 'txtNetworkF', 'txtDispatchByEmailF'];
        foreach ($session_vars as $var) {
            unset($_SESSION[$var]);
        }

        $query = "INSERT INTO exhibitinfobackup 
            (Identifier, EvidenceID, CaseReference, ExhibitRef, EvidenceType, Manufacturer, Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername) 
            SELECT Identifier, EvidenceID, CaseReference, ExhibitRef, EvidenceType, Manufacturer, Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network, ?, ?, ? 
            FROM evidence WHERE EvidenceID = ?";
            
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $timestamp, $fullName, $username, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "UPDATE evidence SET EditedTime = ?, EditedByFullName = ?, EditedByUsername = ?, Manufacturer = ?, Model = ?, SerialNumber = ?, Storage = ?, OS = ?, CPU = ?, RAM = ?, MAC = ?, IP = ?, Firmware = ?, Peripheral = ?, Network = ? WHERE EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssi", $timestamp, $fullName, $username, $manufacturer, $model, $serial, $storage, $OS, $CPU, $RAM, $MAC, $IP, $firmware, $peripheral, $network, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Updated exhibit information.";
        $type = "Evidence";

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $timestamp, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: viewEvidenceExhibit.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();

    } else {
        header('Location: editExhibitInfoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    
}


?>