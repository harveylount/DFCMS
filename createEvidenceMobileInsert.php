<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);

if (isset($_POST['subEvent'])) {
    $exhibitReference=$_POST['txtExhibitReference'];
    $seizedTime = date('Y-m-d H:i:s');
    $status = "Seized";
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
    } else {
        $serialCheck = false;
        $_SESSION['txtSerialM']='Maximum string length of 40 characters';
    }

    if (preg_match('/^[0-9]{15}$/', $IMEI)) {
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

    if (preg_match('/^.{1,20}$/', $OS)) {
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

    if ($exhibitReferenceCheck && $deviceTypeCheck && $manufacturerCheck && $modelCheck && $serialCheck && $IMEICheck && $SIMCheck && $phoneNumberCheck && $MACCheck && $storageCheck && $OSCheck && $batteryHealthCheck && $installedAppsCheck && $encryptionCheck && $accountCheck && $screenLockCheck) {
        
        $session_vars = [
            'txtExhibitReferenceF', 'txtManufacturerF', 'txtModelF', 'txtSerialF', 
            'txtIMEIF', 'txtSIMF', 'txtNumberF', 'txtMACF', 'txtStorageF', 'txtOSF', 
            'txtBatteryF', 'txtAppsF', 'txtEncryptionF', 'txtAccountF', 'txtPasscodeF'];
        foreach ($session_vars as $var) {
            unset($_SESSION[$var]);
        }

        $query = "INSERT INTO evidence 
                (Identifier, ExhibitRef, SeizedTime, EvidenceStatus, DeviceType, Manufacturer, Model, SerialNumber, IMEI, SIM, PhoneNumber, MAC, Storage, OS, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssssss", $identifier, $exhibitReference, $seizedTime, $status, $deviceType, $manufacturer, $model, $serial, $IMEI, $SIM, $phoneNumber, $MAC, $storage, $OS, $batteryHealth, $installedApps, $encryption, $account, $screenLock);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: viewEvidence.php?identifier=' . $identifier);

        exit();

    } else {
        header('Location: createEvidenceMobileForm.php?identifier=' . $identifier);
        exit();
    }

    exit();
    
}


?>