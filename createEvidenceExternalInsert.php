<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

$identifier = intval($_GET['identifier']);

if (isset($_POST['subEvent'])) {
    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];

    $evidenceType = 'ExternalStorage';
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
    $signatureDataBy=$_POST['signature_data_by'];
    $receivedByRank=$_SESSION['receivedByRank'];
    $receivedByCompany=$_SESSION['receivedByCompany'];

    $dispatchedByEmail = $_POST['txtDispatchByEmail'];


    $deviceType=$_POST['deviceType'];
    $manufacturer=$_POST['txtManufacturer'];
    $model=$_POST['txtModel'];
    $serial=$_POST['txtSerial'];
    $storage=$_POST['txtStorage'];
    $interface=$_POST['interfaceType'];
    $fileSystem=$_POST['fileSystemType'];
    $encryption=$_POST['txtEncryption'];
    


    $_SESSION['txtExhibitReferenceF']=$exhibitReference;
    $_SESSION['txtSealNumberF']=$sealNumber;
    $_SESSION['txtLocationF']=$location;
    $_SESSION['txtReceivedFromF']=$receivedFrom;
    $_SESSION['txtReceivedFromRankF']=$receivedFromRank;
    $_SESSION['txtReceivedFromCompanyF']=$receivedFromCompany;
    $_SESSION['txtDispatchByEmailF']=$dispatchedByEmail;


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


    if (isset($_POST['txtDispatchByEmail']) && !empty($_POST['txtDispatchByEmail'])) {

        if (filter_var($dispatchedByEmail, FILTER_VALIDATE_EMAIL)) {
            $dispatchedByEmailCheck = true;
        } else {
            $dispatchedByEmailCheck = false;
            $_SESSION['txtDispatchByEmailM'] = 'Not a valid email address';
        }
    } else {
        $dispatchedByEmailCheck = true;
    }


    if (preg_match('/^.{0,20}$/', $manufacturer)) {
        $manufacturerCheck = true;
        if (($manufacturer) == '') {
            $manufacturer = 'Unknown Manufacturer';
        }
    } else {
        $manufacturerCheck = false;
        $_SESSION['txtManufacturerM']='Maximum string length of 20 characters';
    }
    
    if (preg_match('/^.{0,20}$/', $model)) {
        $modelCheck = true;
        if (($model) == '') {
            $model = 'Unknown Model';
        }
    } else {
        $modelCheck = false;
        $_SESSION['txtModelM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,40}$/', $serial)) {
        $serialCheck = true;
        if (($serial) == '') {
            $serial = 'Unknown Serial Number';
        }
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

    if (preg_match('/^.{0,40}$/', $encryption)) {
        $encryptionCheck = true;
        if (($encryption) == '') {
            $encryption = 'No Encryption';
        }
    } else {
        $encryptionCheck = false;
        $_SESSION['txtEncryptionM']='Maximum string length of 40 characters';
    }

    $initialDescription = implode(', ', [$deviceType, $manufacturer, $model, $serial, $storage, $interface, $fileSystem, $encryption]);

    if ($exhibitReferenceCheck && $sealNumberCheck && $locationCheck && $receivedFromCheck && $receivedFromRankCheck && $receivedFromCompanyCheck && $dispatchedByEmailCheck && $manufacturerCheck && $modelCheck && $serialCheck && $storageCheck && $encryptionCheck) {
        
        $session_vars = [
            'txtExhibitReferenceF', 'txtSealNumberF', 'txtLocationF', 
            'txtReceivedFromF', 'txtReceivedFromRankF', 'txtReceivedFromCompanyF',
            'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtStorageF', 'interfaceTypeF', 'fileSystemTypeF', 'txtEncryptionF', 'txtDispatchByEmailF'];
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
                (Identifier, CaseReference, EvidenceType, ExhibitRef, SeizedByName, SeizedByUsername, SeizedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, Model, SerialNumber, Storage, InterfaceType, FileSystem, EncryptionType)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $identifier, $caseReference, $evidenceType, $exhibitReference, $fullName, $username, $seizedTime, $status, $sealNumber, $deviceType, $manufacturer, $model, $serial, $storage, $interface, $fileSystem, $encryption);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        
        $sqlEvidenceID = "SELECT EvidenceID FROM evidence WHERE Identifier = ? AND CaseReference = ? AND EvidenceType = ? AND SeizedByUsername = ? AND SeizedTime = ?";
        $stmt = $connection->prepare($sqlEvidenceID);
        $stmt->bind_param("sssss", $identifier, $caseReference, $evidenceType, $username, $seizedTime);
        $stmt->execute();
        $stmt->bind_result($evidenceID);
        $stmt->fetch();
        mysqli_stmt_close($stmt);


        if (!empty($signatureDataFrom) && !empty($signatureDataBy)) {
            
            $querySignature = "INSERT INTO lbu01
                (Identifier, CaseReference, EvidenceID, ExhibitRef, Location, ReceivedFromName, ReceivedFromRank, ReceivedFromTime, ReceivedFromSig, ReceivedFromCompany, 
                ReceivedByName, ReceivedByRank, ReceivedByTime, ReceivedBySig, ReceivedByCompany, InitialSealNumber, InitialDescription) 
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and bind the parameters
            $stmtSignature = mysqli_prepare($connection, $querySignature);

            mysqli_stmt_bind_param($stmtSignature, "sssssssssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $location, $receivedFrom, $receivedFromRank, $timestamp, $signatureDataFrom, $receivedFromCompany, $receivedBy, $receivedByRank, $timestamp, $signatureDataBy, $receivedByCompany, $sealNumber, $initialDescription);
            
            mysqli_stmt_execute($stmtSignature);
            mysqli_stmt_close($stmtSignature);
            
        }
        

        if (isset($_POST['txtDispatchByEmail']) && !empty($_POST['txtDispatchByEmail'])) {

            $externalBoolean = "true";
            $emailTimestamp = date('Y-m-d H:i:s');

            include 'LBU02mailFunction.php';

            $queryLBU02 = "INSERT INTO lbu02
                (Identifier, CaseReference, EvidenceID, ExhibitRef, Location, DispatchedByName, DispatchedByRank, DispatchedByTime, DispatchedBySig, DispatchedByCompany, 
                ReceivedByName, ReceivedByRank, ReceivedByTime, ReceivedBySig, ReceivedByCompany, InitialSealNumber, InitialDescription, ExternalBoolean, ExternalEmail, EmailTimestamp, PDFMD5Hash, PDFSHA1Hash) 
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and bind the parameters
            $stmtSignature = mysqli_prepare($connection, $queryLBU02);
            mysqli_stmt_bind_param($stmtSignature, "ssssssssssssssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $location, $receivedFrom, $receivedFromRank, $timestamp, $signatureDataFrom, $receivedFromCompany, $receivedBy, $receivedByRank, $timestamp, $signatureDataBy, $receivedByCompany, $sealNumber, $initialDescription, $externalBoolean, $dispatchedByEmail, $emailTimestamp, $md5Hash, $sha1Hash);
            mysqli_stmt_execute($stmtSignature);
            mysqli_stmt_close($stmtSignature);

        } else {
            $externalBoolean = "false";

            $queryLBU02 = "INSERT INTO lbu02
                (Identifier, CaseReference, EvidenceID, ExhibitRef, Location, DispatchedByName, DispatchedByRank, DispatchedByTime, DispatchedBySig, DispatchedByCompany, 
                ReceivedByName, ReceivedByRank, ReceivedByTime, ReceivedBySig, ReceivedByCompany, InitialSealNumber, InitialDescription, ExternalBoolean) 
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and bind the parameters
            $stmtSignature = mysqli_prepare($connection, $queryLBU02);
            mysqli_stmt_bind_param($stmtSignature, "ssssssssssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $location, $receivedFrom, $receivedFromRank, $timestamp, $signatureDataFrom, $receivedFromCompany, $receivedBy, $receivedByRank, $timestamp, $signatureDataBy, $receivedByCompany, $sealNumber, $initialDescription, $externalBoolean);
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
        header('Location: createEvidenceExternalForm.php?identifier=' . $identifier);
        exit();
    }
    
}


?>