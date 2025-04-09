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

    $query = "SELECT Manufacturer, Model, SerialNumber, IMEI, SIM, PhoneNumber, MAC, Storage, OS, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock FROM evidence WHERE EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $evidenceID);
        $stmt->execute();
        $stmt->bind_result($manufacturerBackup, $modelBackup, $serialBackup, $IMEIBackup, $SIMBackup, $phoneNumberBackup, $MACBackup, $storageBackup, $OSBackup, $batteryHealthBackup, $installedAppsBackup, $encryptionTypeBackup, $accountInfoBackup, $screenLockBackup);
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

    if ($IMEI == '') {
        $IMEI = $IMEIBackup;
    }

    if ($SIM == '') {
        $SIM = $SIMBackup;
    }

    if ($phoneNumber == '') {
        $phoneNumber = $phoneNumberBackup;
    }

    if ($MAC == '') {
        $MAC = $MACBackup;
    }

    if ($storage == '') {
        $storage = $storageBackup;
    }

    if ($OS == '') {
        $OS = $OSBackup;
    }

    if ($batteryHealth == '') {
        $batteryHealth = $batteryHealthBackup;
    }

    if ($installedApps == '') {
        $installedApps = $installedAppsBackup;
    }

    if ($encryption == '') {
        $encryption = $encryptionTypeBackup;
    }

    if ($account == '') {
        $account = $accountInfoBackup;
    }

    if ($screenLock == '') {
        $screenLock = $screenLockBackup;
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
    } else {
        $IMEICheck = false;
        $_SESSION['txtIMEIM']='Maximum numerical string length of 15 characters';
    }

    if (preg_match('/^.{0,50}$/', $SIM)) {
        $SIMCheck = true;
    } else {
        $SIMCheck = false;
        $_SESSION['txtSIMM']='Maximum string length of 50 characters';
    }

    if (preg_match('/^[0-9+]{0,16}$/', $phoneNumber)) {
        $phoneNumberCheck = true;
    } else {
        $phoneNumberCheck = false;
        $_SESSION['txtNumberM']='Maximum string length of 16 characters, only characters + & 0-9';
    }

    if (preg_match('/^[0-9a-zA-Z-]{0,17}$/', $MAC)) {
        $MACCheck = true;
    } else {
        $MACCheck = false;
        $_SESSION['txtMACM']='Maximum string length of 17 characters';
    }

    if (preg_match('/^.{0,40}$/', $storage)) {
        $storageCheck = true;
    } else {
        $storageCheck = false;
        $_SESSION['txtStorageM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,20}$/', $OS)) {
        $OSCheck = true;
    } else {
        $OSCheck = false;
        $_SESSION['txtOSM']='Maximum string length of 20 characters';
    }

    if (preg_match('/^.{0,40}$/', $batteryHealth)) {
        $batteryHealthCheck = true;
    } else {
        $batteryHealthCheck = false;
        $_SESSION['txtBatteryM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,255}$/', $installedApps)) {
        $installedAppsCheck = true;
    } else {
        $installedAppsCheck = false;
        $_SESSION['txtAppsM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^.{0,50}$/', $encryption)) {
        $encryptionCheck = true;
    } else {
        $encryptionCheck = false;
        $_SESSION['txtEncryptionM']='Maximum string length of 50 characters';
    }

    if (preg_match('/^.{0,70}$/', $account)) {
        $accountCheck = true;
    } else {
        $accountCheck = false;
        $_SESSION['txtAccountM']='Maximum string length of 70 characters';
    }

    if (preg_match('/^.{0,40}$/', $screenLock)) {
        $screenLockCheck = true;
    } else {
        $screenLockCheck = false;
        $_SESSION['txtPasscodeM']='Maximum string length of 40 characters';
    }



    if ($manufacturerCheck && $modelCheck && $serialCheck && $IMEICheck && $SIMCheck && $phoneNumberCheck && $MACCheck && $storageCheck && $OSCheck && $batteryHealthCheck && $installedAppsCheck && $encryptionCheck && $accountCheck && $screenLockCheck) {
    
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
            (Identifier, EvidenceID, CaseReference, ExhibitRef, EvidenceType, Manufacturer, Model, SerialNumber, IMEI, SIM, PhoneNumber, MAC, Storage, OS, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock, Timestamp1, EditorOfBackupFullName, EditorOfBackupUsername) 
            SELECT Identifier, EvidenceID, CaseReference, ExhibitRef, EvidenceType, Manufacturer, Model, SerialNumber, IMEI, SIM, PhoneNumber, MAC, Storage, OS, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock, ?, ?, ? 
            FROM evidence WHERE EvidenceID = ?";
            
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $timestamp, $fullName, $username, $evidenceID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "UPDATE evidence SET EditedTime = ?, EditedByFullName = ?, EditedByUsername = ?, Manufacturer = ?, Model = ?, SerialNumber = ?, IMEI = ?, SIM = ?, PhoneNumber = ?, MAC = ?, Storage = ?, OS = ?, BatteryHealth = ?, InstalledApps = ?, EncryptionType = ?, AccountInfo = ?, ScreenLock = ? WHERE EvidenceID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssssi", $timestamp, $fullName, $username, $manufacturer, $model, $serial, $IMEI, $SIM, $phoneNumber, $MAC, $storage, $OS, $batteryHealth, $installedApps, $encryption, $account, $screenLock, $evidenceID);
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