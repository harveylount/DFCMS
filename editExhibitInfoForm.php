<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);
$evidenceID = intval($_GET['EvidenceID']);

include 'checkUserAddedToCaseFunction.php'; 

if (!isset($_SESSION['txtExhibitReferenceExistsM'])) {
    $_SESSION['txtExhibitReferenceExistsM']='';
}

$sql = "SELECT CaseReference, ExhibitRef, EvidenceType FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference, $evidenceType);
$stmt->fetch();
mysqli_stmt_close($stmt);

if (isset($evidenceType) && !empty($evidenceType)) {

    if ($evidenceType == "Computer") {
        //header ('location:editExhibitInfoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        //exit();
    }
    if ($evidenceType == "Mobile") {
        header ('location:editExhibitInfoMobileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    if ($evidenceType == "ExternalStorage") {
        header ('location:editExhibitInfoExternalForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }

} else {
    header ('location:index.php');
}

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Exhibit Information</title>

    <style>
        .tall-input {
            height: 50px;
            width: 254px;
        }
    </style>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewEvidence.php?identifier=" . $identifier . "&EvidenceID=" . $evidenceID ?>" class="logout-button">← Exhibits</a>
            </div>
            <div class="right-group">
                <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
                <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
                <a href="logoutFunction.php" class="logout-button">Logout</a>
            </div>
        </div>

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        <div id="navcase-bar">
            <a href="<?php echo "viewEvidenceExhibit.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Evidence Overview</a>
            <a href="<?php echo "viewLBU01.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU01</a>
            <a href="<?php echo "viewLBU02.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU02</a>
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <?php include 'lbu04notComputerFunction.php'; ?>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
            <a href="<?php echo "viewExhibitNotes.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Notes</a>
            <a href="<?php echo "listImageFiles.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">Files</a>
        </div>

        <section id="LBU">

            <?php
                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Edit Exhibit Details</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Case Reference: ' . $caseReference . "</td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Reference: ' . $exhibitReference . "</td></tr>";
                echo "</table>";
                echo "<br/>"; 
            ?>

            <form method="post" action="editExhibitInfoInsert.php?identifier=<?php echo urlencode($identifier) . "&EvidenceID=" . urlencode($evidenceID) ?> ">
                <fieldset class="field-set width">

                    <legend>
                    Enter evidence details
                    </legend>
                    
                    <!-- Manufacture field -->
                    <label for="txtManufacturer">Manufacturer:</label><br />
                    <input type="text" name="txtManufacturer" size="32" value="<?php 
                        if(isset($_SESSION['txtManufacturerF'])) {
                            echo $_SESSION['txtManufacturerF'];
                            unset($_SESSION['txtManufacturerF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtManufacturerM'])) { echo $_SESSION['txtManufacturerM']; unset($_SESSION['txtManufacturerM']); }?></p> [Manufacturer / brand name]<br /><br />

                    <!-- Model field -->
                    <label for="txtModel">Model:</label><br />
                    <input type="text" name="txtModel" size="32" value="<?php 
                        if(isset($_SESSION['txtModelF'])) {
                            echo $_SESSION['txtModelF'];
                            unset($_SESSION['txtModelF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtModelM'])) { echo $_SESSION['txtModelM']; unset($_SESSION['txtModelM']); }?></p> [Specific model name or number]<br /><br />

                    <!-- Serial field -->
                    <label for="txtSerial">Serial Number:</label><br />
                    <input type="text" name="txtSerial" size="32" value="<?php 
                        if(isset($_SESSION['txtSerialF'])) {
                            echo $_SESSION['txtSerialF'];
                            unset($_SESSION['txtSerialF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtSerialM'])) { echo $_SESSION['txtSerialM']; unset($_SESSION['txtSerialM']); }?></p> [Unique identifier provided by the manufacturer]<br /><br />

                    <!-- Storage field -->
                    <label for="txtStorage">Storage Capacity:</label><br />
                    <input type="text" name="txtStorage" size="32" value="<?php 
                        if(isset($_SESSION['txtStorageF'])) {
                            echo $_SESSION['txtStorageF'];
                            unset($_SESSION['txtStorageF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtStorageM'])) { echo $_SESSION['txtStorageM']; unset($_SESSION['txtStorageM']); }?></p> [Size & type of internal storage (e.g. 1TB SSD)]<br /><br />

                    <!-- Operating System field -->
                    <label for="txtOS">Operating System: </label><br />
                    <input type="text" name="txtOS" size="32" value="<?php 
                        if(isset($_SESSION['txtOSF'])) {
                            echo $_SESSION['txtOSF'];
                            unset($_SESSION['txtOSF']);
                        }
                    ?>" /><p class="error-message"><?php if (isset($_SESSION['txtOSM'])) { echo $_SESSION['txtOSM']; unset($_SESSION['txtOSM']); }?></p> [Version & build number (e.g. Windows 11 22H2)]<br /><br />

                    <!-- CPU information field -->
                    <label for="txtCPU">CPU Information:</label><br />
                    <input type="text" name="txtCPU" size="32" value="<?php 
                        if(isset($_SESSION['txtCPUF'])) {
                            echo $_SESSION['txtCPUF'];
                            unset($_SESSION['txtCPUF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtCPUM'])) { echo $_SESSION['txtCPUM']; unset($_SESSION['txtCPUM']); }?></p> [CPU model, number of cores, speed (e.g. i7 7700k, 6 core, 3.6GHz)]<br /><br />

                    <!-- RAM information field -->
                    <label for="txtRAM">RAM Information:</label><br />
                    <input type="text" name="txtRAM" size="32" value="<?php 
                        if(isset($_SESSION['txtRAMF'])) {
                            echo $_SESSION['txtRAMF'];
                            unset($_SESSION['txtRAMF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtRAMM'])) { echo $_SESSION['txtRAMM']; unset($_SESSION['txtRAMM']); }?></p> [Size & type of (e.g. 16GB DDR4)] <br /><br />

                    <!-- MAC information field -->
                    <label for="txtMAC">MAC Information:</label><br />
                    <input type="text" name="txtMAC" size="32" value="<?php 
                        if(isset($_SESSION['txtMACF'])) {
                            echo $_SESSION['txtMACF'];
                            unset($_SESSION['txtMACF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtMACM'])) { echo $_SESSION['txtMACM']; unset($_SESSION['txtMACM']); }?></p> [Unique network identifier for the device (e.g. A1-B2-C3-D4-E5-F6)]<br /><br />

                    <!-- IP information field -->
                    <label for="txtIP">IP Address Information:</label><br />
                    <input type="text" name="txtIP" size="32" value="<?php 
                        if(isset($_SESSION['txtIPF'])) {
                            echo $_SESSION['txtIPF'];
                            unset($_SESSION['txtIPF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtIPM'])) { echo $_SESSION['txtIPM']; unset($_SESSION['txtIPM']); }?></p> [Current or last known IP address used]<br /><br />

                    <!-- Firmware version field -->
                    <label for="txtFirmware">Firmware Version:</label><br />
                    <input type="text" name="txtFirmware" size="32" value="<?php
                        if(isset($_SESSION['txtFirmwareF'])) {
                            echo $_SESSION['txtFirmwareF'];
                            unset($_SESSION['txtFirmwareF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtFirmwareM'])) { echo $_SESSION['txtFirmwareM']; unset($_SESSION['txtFirmwareM']); }?></p> [BIOS or UEFI version]<br /><br />

                    <label for="txtPeripheral">Peripheral Devices:</label><br />
                    <textarea name="txtPeripheral" class="tall-input"><?php 
                        if(isset($_SESSION['txtPeripheralF'])) {
                            echo $_SESSION['txtPeripheralF'];
                            unset($_SESSION['txtPeripheralF']);
                        }
                    ?></textarea><p class="error-message"><?php if (isset($_SESSION['txtPeripheralM'])) { echo $_SESSION['txtPeripheralM']; unset($_SESSION['txtPeripheralM']); }?></p> [Connected devices (e.g. mouse, keyboard, etc.)]<br /><br />

                    <!-- Network interface information field -->
                    <label for="txtNetwork">Network Interface Information:</label><br />
                    <input type="text" name="txtNetwork" size="32" value="<?php 
                        if(isset($_SESSION['txtNetworkF'])) {
                            echo $_SESSION['txtNetworkF'];
                            unset($_SESSION['txtNetworkF']);
                        }
                    ?>"/><p class="error-message"><?php if (isset($_SESSION['txtNetworkM'])) { echo $_SESSION['txtNetworkM']; unset($_SESSION['txtNetworkM']); }?></p> [Wired (Ethernet) or wireless (Wi-Fi) network details]<br /><br />

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />

                </fieldset>
                
            </form>

            



        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>