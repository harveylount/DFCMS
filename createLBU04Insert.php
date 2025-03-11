<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

if (isset($_POST['subEvent'])) {

    $sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->bind_result($caseReference, $exhibitReference);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

    $actionerName = $_SESSION['fullName'];
    $actionerUsername = $_SESSION['userId'];
    $timestamp = date('Y-m-d H:i:s');

    $hardDriveDetails= $_POST['hardDriveDetails'];
    $manufacturer= $_POST['txtManufacturer'];
    $model= $_POST['txtModel'];
    $serial= $_POST['txtSerialNumber'];
    $type= $_POST['txtType'];
    $condition= $_POST['selectCondition'];
    $conditionNotes= $_POST['txtConditionNotes'];
    $opticalDrive= $_POST['txtOpticalDrive'];
    $floppyDisk= $_POST['txtFloppyDisk'];
    $network= $_POST['txtNetwork'];
    $modem= $_POST['txtModem'];
    $firewire= $_POST['txtFirewire'];
    $mediaCardReader= $_POST['txtMediaCardReader'];
    $USB= $_POST['txtUSB'];
    $SIMSlot= $_POST['txtSIMSlot'];
    $battery= $_POST['txtBattery'];
    $videoCard= $_POST['txtVideoCard'];
    $otherPeripherals= $_POST['txtOtherPeripherals'];
    $BIOSKey= $_POST['txtBIOSKey'];
    $BIOSPassword= $_POST['txtBIOSPassword'];
    $BIOSSystem= $_POST['txtBIOSSystem'];
    $BootOrder= $_POST['txtBootOrder'];
    $BIOSDate= $_POST['txtBIOSDate'];
    $BIOSTime= $_POST['txtBIOSTime'];
    $actualDate= $_POST['txtActualDate'];
    $actualTime= $_POST['txtActualTime'];
    $BIOSNotes= $_POST['txtBIOSNotes'];

    // HDD
    $counterHDD = 0;
    $HDDReferenceArray = []; 
    $HDDModelArray = [];
    $HDDSerialNumberArray = [];
    $HDDTypeArray = [];
    $HDDSizeArray = [];
    $HDDImagingMethodArray = [];
    $HDDImageVerifiedArray = [];
    $HDDNotesArray = [];

    if ($hardDriveDetails > 0) {
        while ($counterHDD <= $hardDriveDetails) { 
            


            if (isset($_POST['txtReferenceHDD_' . $counterHDD])) {
                $HDDReferenceArray[] = $_POST['txtReferenceHDD_' . $counterHDD]; 
            } 
            if (isset($_POST['txtModelHDD_' . $counterHDD])) {
                $HDDModelArray[] = $_POST['txtModelHDD_' . $counterHDD]; 
            } 
            if (isset($_POST['txtSerialNumberHDD_' . $counterHDD])) {
                $HDDSerialNumberArray[] = $_POST['txtSerialNumberHDD_' . $counterHDD]; 
            } 
            if (isset($_POST['txtTypeHDD_' . $counterHDD])) {
                $HDDTypeArray[] = $_POST['txtTypeHDD_' . $counterHDD]; 
            } 
            if (isset($_POST['txtSizeHDD_' . $counterHDD])) {
                $HDDSizeArray[] = $_POST['txtSizeHDD_' . $counterHDD]; 
            } 
            if (isset($_POST['txtImagingMethodHDD_' . $counterHDD])) {
                $HDDImagingMethodArray[] = $_POST['txtImagingMethodHDD_' . $counterHDD]; 
            } 
            if (isset($_POST['selectImageVerified_' . $counterHDD])) {
                $HDDImageVerifiedArray[] = $_POST['selectImageVerified_' . $counterHDD]; 
            } 
            if (isset($_POST['txtNotesHDD_' . $counterHDD])) {
                $HDDNotesArray[] = $_POST['txtNotesHDD_' . $counterHDD]; 
            } 
            
            $counterHDD++;
        }
    }


    $query = "INSERT INTO lbu04
    (Identifier, CaseReference, EvidenceID, ExhibitRef, ActionerName, ActionerUsername, CreateTimestamp, Manufacturer, 
    Model, SerialNumber, Type, ItemCondition, ConditionNotes, OpticalDrive, FloppyDisk, Network, Modem, Firewire, 
    MediaCardReader, USB, SIMSlot, Battery, VideoCard, PeripheralsOther, BIOSKey, BIOSPassword, BIOSSystem, BootOrder, 
    BIOSDate, BIOSTime, ActualDate, ActualTime, BIOSNotes, HDDReference, HDDModel, HDDSerialNumber, HDDType, HDDSize, 
    ImagingMethod, ImageVerified, HardDriveNotes) 
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the parameters
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssssssssssssss", $identifier, $caseReference, $evidenceID, $exhibitReference, $actionerName, $actionerUsername,
    $timestamp, $manufacturer, $model, $serial, $type, $condition, $conditionNotes, $opticalDrive, $floppyDisk, $network, $modem, $firewire, $mediaCardReader, $USB, $SIMSlot, 
    $battery, $videoCard, $otherPeripherals, $BIOSKey, $BIOSPassword, $BIOSSystem, $BootOrder, $BIOSDate, $BIOSTime, $actualDate, $actualTime, $BIOSNotes, $HDDReferenceString, 
    $HDDModelString, $HDDSerialNumberString, $HDDTypeString, $HDDSizeString, $HDDImagingMethodString, $HDDImageVerifiedString, $HDDNotesString);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location: viewLBU04.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
    exit();
    
} else {
    header('Location: index.php');
    exit();
}
?>