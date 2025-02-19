<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);

if (isset($_POST['subEvent'])) {
    $evidenceType = 'Computer';
    $exhibitReference=$_POST['txtExhibitReference'];
    $seizedTime = $_SESSION['timestampDatabase'];
    $status = "Seized";
    $sealNumber=$_POST['txtSealNumber'];
    $location=$_POST['txtLocation'];
    $receivedFrom=$_POST['txtReceivedFrom'];
    $receivedFromRank=$_POST['txtReceivedFromRank'];
    $timestamp=$_SESSION['timestampDatabase'];
    $signatureDataFrom = $_POST['signature_data_from'];
    $receivedFromCompany=$_POST['txtReceivedFromCompany'];
    $receivedBy=$_SESSION['receivedBy'];
    $receivedByRank=$_POST['signature_data_by'];
    $signatureDataBy=$_SESSION['receivedByRank'];
    $receivedByCompany=$_SESSION['receivedByCompany'];


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
    $_SESSION['txtSealNumberF']=$sealNumber;
    $_SESSION['txtLocationF']=$location;
    $_SESSION['txtReceivedFromF']=$receivedFrom;
    $_SESSION['txtReceivedFromRankF']=$receivedFromRank;
    $_SESSION['txtReceivedFromCompanyF']=$receivedFromCompany;


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

    if (preg_match('/^.{1,16}$/', $sealNumber)) {
        $sealNumberCheck = true;
    } else {
        $sealNumberCheck = false;
        $_SESSION['txtSealNumberM']='Maximum string length of 16 characters';
    }

    if (preg_match('/^.{1,50}$/', $location)) {
        $locationCheck = true;
    } else {
        $locationCheck = false;
        $_SESSION['txtLocationM']='Maximum string length of 50 characters';
    }

    if (preg_match('/^.{1,50}$/', $receivedFrom)) {
        $receivedFromCheck = true;
    } else {
        $receivedFromCheck = false;
        $_SESSION['txtReceivedFromM']='Maximum string length of 50 characters';
    }

    if (preg_match('/^.{1,20}$/', $receivedFromRank)) {
        $receivedFromRankCheck = true;
    } else {
        $receivedFromRankCheck = false;
        $_SESSION['txtReceivedFromRankM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{1,40}$/', $receivedFromCompany)) {
        $receivedFromCompanyCheck = true;
    } else {
        $receivedFromCompanyCheck = false;
        $_SESSION['txtReceivedFromCompanyM']='Maximum string length of 40 characters';
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

    if (preg_match('/^.{0,40}$/', $storage)) {
        $storageCheck = true;
        if (($storage) == '') {
            $storage = 'Unknown Storage Capacity';
        }
    } else {
        $storageCheck = false;
        $_SESSION['txtStorageM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,40}$/', $OS)) {
        $OSCheck = true;
        if (($OS) == '') {
            $OS = 'Unknown Operating System';
        }
    } else {
        $OSCheck = false;
        $_SESSION['txtOSM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,20}$/', $CPU)) {
        $CPUCheck = true;
        if (($CPU) == '') {
            $CPU = 'Unknown CPU';
        }
    } else {
        $CPUCheck = false;
        $_SESSION['txtCPUM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,20}$/', $RAM)) {
        $RAMCheck = true;
        if (($RAM) == '') {
            $RAM = 'Unknown RAM';
        }
    } else {
        $RAMCheck = false;
        $_SESSION['txtRAMM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,17}$/', $MAC)) {
        $MACCheck = true;
        if (($MAC) == '') {
            $MAC = 'Unknown MAC';
        }
    } else {
        $MACCheck = false;
        $_SESSION['txtMACM']='Maximum string length of 17 characters';
    }

    if (preg_match('/^.{0,20}$/', $IP)) {
        $IPCheck = true;
        if (($IP) == '') {
            $IP = 'Unknown IP Address';
        }
    } else {
        $IPCheck = false;
        $_SESSION['txtIPM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,30}$/', $firmware)) {
        $firmwareCheck = true;
        if (($firmware) == '') {
            $firmware = 'Unknown Firmware';
        }
    } else {
        $firmwareCheck = false;
        $_SESSION['txtFirmwareM']='Maximum string length of 30 characters';
    }

    if (preg_match('/^.{0,80}$/', $peripheral)) {
        $peripheralCheck = true;
        if (($peripheral) == '') {
            $peripheral = 'No Peripheral Devices';
        }
    } else {
        $peripheralCheck = false;
        $_SESSION['txtPeripheralM']='Maximum string length of 80 characters';
    }

    if (preg_match('/^.{0,40}$/', $network)) {
        $networkCheck = true;
        if (($network) == '') {
            $network = 'Unknown Network Information';
        }
    } else {
        $networkCheck = false;
        $_SESSION['txtNetworkM']='Maximum string length of 40 characters';
    }

    $initialDescription = implode(', ', [$deviceType, $manufacturer, $model, $serial, $storage, $OS, $CPU, $RAM, $MAC, $IP, $firmware, $peripheral, $network]);

    if ($exhibitReferenceCheck && $sealNumberCheck && $locationCheck && $receivedFromCheck && $receivedFromRankCheck && $receivedFromCompanyCheck && $manufacturerCheck && $modelCheck && $serialCheck && $storageCheck && $OSCheck && $CPUCheck && $RAMCheck && $MACCheck && $IPCheck && $firmwareCheck && $peripheralCheck && $networkCheck) {
        
        $session_vars = [
            'txtExhibitReferenceF', 'txtSealNumberF', 'txtLocationF', 
            'txtReceivedFromF', 'txtReceivedFromRankF', 'txtReceivedFromCompanyF',
            'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtStorageF', 'txtOSF', 'txtCPUF', 'txtRAMF', 'txtMACF', 'txtIPF', 
            'txtFirmwareF', 'txtPeripheralF', 'txtNetworkF'];
        foreach ($session_vars as $var) {
            unset($_SESSION[$var]);
        }

        // SQL query to get case reference
        $sqlCaseRef = "SELECT CaseReference FROM cases WHERE Identifier = ?";
        $stmt = $connection->prepare($sqlCaseRef);
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $stmt->bind_result($caseReference);
        $stmt->fetch();
        mysqli_stmt_close($stmt);

        $query = "INSERT INTO evidence 
                (Identifier, CaseReference, EvidenceType, ExhibitRef, SeizedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssssdsssssss", $identifier, $caseReference, $evidenceType, $exhibitReference, $timestamp, $status, $sealNumber, $deviceType, $manufacturer, $model, $serial, $storage, $OS, $CPU, $RAM, $MAC, $IP, $firmware, $peripheral, $network);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        

        
        if (!empty($signatureDataFrom) && !empty($signatureDataBy)) {
            $querySignature = "INSERT INTO lbu01
                (Identifier, CaseReference, ExhibitRef, Location, ReceivedFromName, ReceivedFromRank, ReceivedFromTime, ReceivedFromSig, ReceivedFromCompany, 
                ReceivedByName, ReceivedByRank, ReceivedByTime, ReceivedBySig, ReceivedByCompany, InitialSealNumber, InitialDescription) 
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and bind the parameters
            $stmtSignature = mysqli_prepare($connection, $querySignature);

            mysqli_stmt_bind_param($stmtSignature, "ssssssssssssssss", $identifier, $caseReference, $exhibitReference, $location, $receivedFrom, $receivedFromRank, $timestamp, $signatureDataFrom, $receivedFromCompany, $receivedBy, $receivedByRank, $timestamp, $signatureDataBy, $receivedByCompany, $sealNumber, $initialDescription);
            
            mysqli_stmt_execute($stmtSignature);
            mysqli_stmt_close($stmtSignature);
            
        }
        
        $unset_sessions = ['timestampDatabase', 'receivedBy', 'receivedByRank', 'receivedByCompany',
        'timestampDisplay', 'receivedBy', 'receivedByRank', 'receivedByCompany',];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        header('Location: viewEvidence.php?identifier=' . $identifier);
        exit();

    } else {
        header('Location: createEvidenceForm.php?identifier=' . $identifier);
        exit();
    }
    
}


?>