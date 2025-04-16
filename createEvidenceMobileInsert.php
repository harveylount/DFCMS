<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

$identifier = intval($_GET['identifier']);

if (isset($_POST['subEvent'])) {
    $fullName = $_SESSION['fullName'];
    $username = $_SESSION['userId'];

    $evidenceType = 'Mobile';
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


    $deviceType=$_POST['txtDevice'];
    $manufacturer=$_POST['txtManufacturer'];
    $model=$_POST['txtModel'];
    $serial=$_POST['txtSerial'];
    $IMEI=$_POST['txtIMEI'];
    $SIM=$_POST['txtSIM'];
    $phoneNumber=$_POST['txtNumber'];
    $MAC=$_POST['txtMAC'];
    $storage=$_POST['txtStorage'];
    $OS=$_POST['txtOS'];
    $batteryHealth=$_POST['txtBattery'];
    $installedApps=$_POST['txtApps'];
    $encryption=$_POST['txtEncryption'];
    $account=$_POST['txtAccount'];
    $screenLock=$_POST['txtPasscode'];



    $_SESSION['txtExhibitReferenceF']=$exhibitReference;
    $_SESSION['txtSealNumberF']=$sealNumber;
    $_SESSION['txtLocationF']=$location;
    $_SESSION['txtReceivedFromF']=$receivedFrom;
    $_SESSION['txtReceivedFromRankF']=$receivedFromRank;
    $_SESSION['txtReceivedFromCompanyF']=$receivedFromCompany;
    $_SESSION['txtDispatchByEmailF']=$dispatchedByEmail;


    $_SESSION['txtDeviceF']=$deviceType;
    $_SESSION['txtManufacturerF']=$manufacturer;
    $_SESSION['txtModelF']=$model;
    $_SESSION['txtSerialF']=$serial;
    $_SESSION['txtIMEIF']=$IMEI;
    $_SESSION['txtSIMF']=$SIM;
    $_SESSION['txtNumberF']=$phoneNumber;
    $_SESSION['txtMACF']=$MAC;
    $_SESSION['txtStorageF']=$storage;
    $_SESSION['txtOSF']=$OS;
    $_SESSION['txtBatteryF']=$batteryHealth;
    $_SESSION['txtAppsF']=$installedApps;
    $_SESSION['txtEncryptionF']=$encryption;
    $_SESSION['txtAccountF']=$account;
    $_SESSION['txtPasscodeF']=$screenLock;

    $sql = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->bind_result($caseReference);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

    $sql = "SELECT ExhibitRef FROM evidence WHERE Identifier = ? AND ExhibitRef = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $identifier, $exhibitReference);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        $_SESSION['txtExhibitReferenceExistsM'] = 'Exhibit Reference already exists';
        header('Location: createEvidenceForm.php?identifier=' . $identifier);
        exit();
    }


    if (preg_match('/^.{1,6}$/', $exhibitReference)) {
        $exhibitReferenceCheck = true;
    } else {
        $exhibitReferenceCheck = false;
        $_SESSION['txtExhibitReferenceM']='Maximum string length of 6 characters';
    }

    if (preg_match('/^.{1,20}$/', $deviceType)) {
        $deviceTypeCheck = true;
    } else {
        $deviceTypeCheck = false;
        $_SESSION['txtDeviceM']='Maximum string length of 20 characters';
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

    if (preg_match('/^.{0,40}$/', $serial)) {
        $serialCheck = true;
        if (($serial) == '') {
            $serial = 'Unknown Serial Number';
        }
    } else {
        $serialCheck = false;
        $_SESSION['txtSerialM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^[0-9]{0,15}$/', $IMEI)) {
        $IMEICheck = true;
        if (($IMEI) == '') {
            $IMEI = 'Unknown IMEI Number';
        }
    } else {
        $IMEICheck = false;
        $_SESSION['txtIMEIM']='Maximum numerical string length of 15 characters';
    }

    if (preg_match('/^.{0,50}$/', $SIM)) {
        $SIMCheck = true;
        if (($SIM) == '') {
            $SIM = 'Unknown SIM Information';
        }
    } else {
        $SIMCheck = false;
        $_SESSION['txtSIMM']='Maximum string length of 50 characters';
    }

    if (preg_match('/^[0-9+]{0,16}$/', $phoneNumber)) {
        $phoneNumberCheck = true;
        if (($phoneNumber) == '') {
            $phoneNumber = 'Unknown Phone Number';
        }
    } else {
        $phoneNumberCheck = false;
        $_SESSION['txtNumberM']='Maximum string length of 16 characters, only characters + & 0-9';
    }

    if (preg_match('/^[0-9a-zA-Z-]{0,17}$/', $MAC)) {
        $MACCheck = true;
        if (($MAC) == '') {
            $MAC = 'Unknown MAC';
        }
    } else {
        $MACCheck = false;
        $_SESSION['txtMACM']='Maximum string length of 17 characters';
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

    if (preg_match('/^.{0,20}$/', $OS)) {
        $OSCheck = true;
        if (($OS) == '') {
            $OS = 'Unknown Operating System';
        }
    } else {
        $OSCheck = false;
        $_SESSION['txtOSM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,40}$/', $batteryHealth)) {
        $batteryHealthCheck = true;
        if (($batteryHealth) == '') {
            $batteryHealth = 'Unknown Battery Health Information';
        }
    } else {
        $batteryHealthCheck = false;
        $_SESSION['txtBatteryM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,255}$/', $installedApps)) {
        $installedAppsCheck = true;
        if (($installedApps) == '') {
            $installedApps = 'Unknown Installed Apps';
        }
    } else {
        $installedAppsCheck = false;
        $_SESSION['txtAppsM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,50}$/', $encryption)) {
        $encryptionCheck = true;
        if (($encryption) == '') {
            $encryption = 'Unknown Encryption Information';
        }
    } else {
        $encryptionCheck = false;
        $_SESSION['txtEncryptionM']='Maximum string length of 50 characters';
    }

    if (preg_match('/^.{0,70}$/', $account)) {
        $accountCheck = true;
        if (($account) == '') {
            $account = 'Unknown Account Information';
        }
    } else {
        $accountCheck = false;
        $_SESSION['txtAccountM']='Maximum string length of 70 characters';
    }

    if (preg_match('/^.{0,40}$/', $screenLock)) {
        $screenLockCheck = true;
        if (($screenLock) == '') {
            $screenLock = 'Unknown Screen Lock Information';
        }
    } else {
        $screenLockCheck = false;
        $_SESSION['txtPasscodeM']='Maximum string length of 40 characters';
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



    $initialDescription = implode(', ', [$deviceType, $manufacturer, $model, $serial, $IMEI, $SIM, $phoneNumber, $MAC, $storage, $OS, $batteryHealth, $installedApps, $encryption, $account, $screenLock]);

    if ($exhibitReferenceCheck && $sealNumberCheck && $locationCheck && $receivedFromCheck && $receivedFromRankCheck && $receivedFromCompanyCheck && $dispatchedByEmailCheck && $deviceTypeCheck && $manufacturerCheck && $modelCheck && $serialCheck && $IMEICheck && $SIMCheck && $phoneNumberCheck && $MACCheck && $storageCheck && $OSCheck && $batteryHealthCheck && $installedAppsCheck && $encryptionCheck && $accountCheck && $screenLockCheck) {
    
        $session_vars = [
            'txtExhibitReferenceF', 'txtSealNumberF', 'txtLocationF', 
            'txtReceivedFromF', 'txtReceivedFromRankF', 'txtReceivedFromCompanyF',
            'txtTypeF', 'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtIMEIF', 'txtSIMF', 'txtNumberF', 'txtMACF', 'txtStorageF', 'txtOSF', 
            'txtBatteryF', 'txtAppsF', 'txtEncryptionF', 'txtAccountF', 'txtPasscodeF', 'txtDispatchByEmailF'];
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
                (Identifier, CaseReference, EvidenceType, ExhibitRef, SeizedByName, SeizedByUsername, SeizedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, Model, SerialNumber, IMEI, SIM, PhoneNumber, MAC, Storage, OS, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssss", $identifier, $caseReference, $evidenceType, $exhibitReference, $fullName, $username, $seizedTime, $status, $sealNumber, $deviceType, $manufacturer, $model, $serial, $IMEI, $SIM, $phoneNumber, $MAC, $storage, $OS, $batteryHealth, $installedApps, $encryption, $account, $screenLock);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $sqlEvidenceID = "SELECT EvidenceID FROM evidence WHERE Identifier = ? AND CaseReference = ? AND EvidenceType = ? AND SeizedByUsername = ? AND SeizedTime = ?";
        $stmt = $connection->prepare($sqlEvidenceID);
        $stmt->bind_param("sssss", $identifier, $caseReference, $evidenceType, $username, $seizedTime);
        $stmt->execute();
        $stmt->bind_result($evidenceID);
        $stmt->fetch();
        mysqli_stmt_close($stmt);

        // Audit Log
        $action = "Created an evidence exhibit. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ".";
        $type = "Exhibit";

        $query = "INSERT INTO auditlog 
            (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, Timestamp, ActionerFullName, ActionerUsername, Action)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $timestamp, $fullName, $username, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!empty($signatureDataFrom) && !empty($signatureDataBy)) {
            $querySignature = "INSERT INTO lbu01
                (Identifier, CaseReference, EvidenceID, ExhibitRef, Location, ReceivedFromName, ReceivedFromRank, ReceivedFromTime, ReceivedFromSig, ReceivedFromCompany, 
                ReceivedByName, ReceivedByRank, ReceivedByTime, ReceivedBySig, ReceivedByCompany, InitialSealNumber, InitialDescription) 
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtSignature = mysqli_prepare($connection, $querySignature);

            mysqli_stmt_bind_param($stmtSignature, "sssssssssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $location, $receivedFrom, $receivedFromRank, $timestamp, $signatureDataFrom, $receivedFromCompany, $receivedBy, $receivedByRank, $timestamp, $signatureDataBy, $receivedByCompany, $sealNumber, $initialDescription);
            
            mysqli_stmt_execute($stmtSignature);
            mysqli_stmt_close($stmtSignature);

            // Audit Log
            $action = "Created an LBU01 form. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ".";
            $type = "Exhibit";

            $query = "INSERT INTO auditlog 
                (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, Timestamp, ActionerFullName, ActionerUsername, Action)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $timestamp, $fullName, $username, $action);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
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

            // Audit Log
            $action = "Created an LBU02 form. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ".";
            $type = "Exhibit";

            $query = "INSERT INTO auditlog 
                (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, Timestamp, ActionerFullName, ActionerUsername, Action)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $timestamp, $fullName, $username, $action);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

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

            // Audit Log
            $action = "Created an LBU02 form. Case Reference: " . $caseReference . ". Case ID: " . $identifier . ". Exhibit Reference: " . $exhibitReference . ". Exhibit ID: " . $evidenceID . ".";
            $type = "Exhibit";

            $query = "INSERT INTO auditlog 
                (Identifier, CaseReference, EntryType, EvidenceID, ExhibitReference, Timestamp, ActionerFullName, ActionerUsername, Action)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssssss", $identifier, $caseReference, $type, $evidenceID, $exhibitReference, $timestamp, $fullName, $username, $action);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
        }
        
        $unset_sessions = ['timestampDatabase', 'receivedBy', 'receivedByRank', 'receivedByCompany',
        'timestampDisplay', 'receivedBy', 'receivedByRank', 'receivedByCompany',];
        foreach ($unset_sessions as $sessionVar) {
            unset($_SESSION[$sessionVar]);
        }

        header('Location: viewEvidence.php?identifier=' . $identifier);
        exit();

    } else {
    
        header('Location: createEvidenceMobileForm.php?identifier=' . $identifier);
        exit();
    }

    exit();
    
}


?>