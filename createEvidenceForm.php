<!DOCTYPE html>
<html>
<?php
session_start();
if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);

$unsetMsg = [
    'txtExhibitReferenceM', 'txtManufacturerM', 'txtModelM', 'txtSerialM', 
    'txtStorageM', 'txtOSM', 'txtCPUM', 'txtRAMM', 'txtMACM', 'txtIPM', 
    'txtFirmwareM', 'txtPeripheralM', 'txtNetworkM'
];

foreach ($unsetMsg as $msg) {
    if (!isset($_SESSION[$msg])) {
        $_SESSION[$msg]='';
    }
}

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Create Evidence</title>

    <style>
        .tall-input {
            height: 50px; /* Adjust the height as needed */
            width: 254px;
        }
    </style>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
            <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
            <a href="logoutFunction.php" id="logout-button">Logout</a>
        </div>

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        <div id="navcase-bar">
            <a href="<?php echo "createEvidenceForm.php?identifier=$identifier" ?>" id="navcase-button">Computers & Laptops</a>
            <a href="<?php echo "createEvidenceFormMobile.php?identifier=$identifier" ?>" id="navcase-button">Mobile Devices</a>
            <a href="<?php echo "createEvidenceFormExternal.php?identifier=$identifier" ?>" id="navcase-button">External Storage</a>
            <a href="<?php echo "createEvidenceFormNetwork.php?identifier=$identifier" ?>" id="navcase-button">Network Devices</a>
        </div>

        <section id="content">

            <h2>Create a Computers & Laptops Evidence Exhibit</h2>

            <form method="post" action="createEvidenceInsert.php?identifier=<?php echo "$identifier" ?>">
                <fieldset class="field-set width">
                    <legend>
                    Enter evidence details
                    </legend>

                    <!-- Case reference field -->
                    <label for="txtExhibitReference">Exhibit Reference: *</label><br />
                    <input type="text" name="txtExhibitReference" size="32" value="<?php 
                        if(isset($_SESSION['txtExhibitReferenceF'])) {
                            echo $_SESSION['txtExhibitReferenceF'];
                            unset($_SESSION['txtExhibitReferenceF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtExhibitReferenceM']; unset($_SESSION['txtExhibitReferenceM']);?></p><br /><br />

                    <!-- Devise type field -->
                    <label for="deviceType">Device Type: *</label><br />
                    <select name="deviceType" required>
                        <option value="Desktop" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Desktop') echo 'selected'; ?>>Desktop</option>
                        <option value="Laptop" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Laptop') echo 'selected'; ?>>Laptop</option>
                        <option value="Workstation" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Workstation') echo 'selected'; ?>>Workstation</option>
                    </select><br /><br />
                    
                    <!-- Manufacture field -->
                    <label for="txtManufacturer">Manufacturer: *</label><br />
                    <input type="text" name="txtManufacturer" size="32" value="<?php 
                        if(isset($_SESSION['txtManufacturerF'])) {
                            echo $_SESSION['txtManufacturerF'];
                            unset($_SESSION['txtManufacturerF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtManufacturerM']; unset($_SESSION['txtManufacturerM']);?></p> [Manufacturer / brand name]<br /><br />

                    <!-- Model field -->
                    <label for="txtModel">Model: *</label><br />
                    <input type="text" name="txtModel" size="32" value="<?php 
                        if(isset($_SESSION['txtModelF'])) {
                            echo $_SESSION['txtModelF'];
                            unset($_SESSION['txtModelF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtModelM']; unset($_SESSION['txtModelM']);?></p> [Specific model name or number]<br /><br />

                    <!-- Serial field -->
                    <label for="txtSerial">Serial Number: *</label><br />
                    <input type="text" name="txtSerial" size="32" value="<?php 
                        if(isset($_SESSION['txtSerialF'])) {
                            echo $_SESSION['txtSerialF'];
                            unset($_SESSION['txtSerialF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtSerialM']; unset($_SESSION['txtSerialM']);?></p> [Unique identifier provided by the manufacturer]<br /><br />

                    <!-- Storage field -->
                    <label for="txtStorage">Storage Capacity: *</label><br />
                    <input type="text" name="txtStorage" size="32" value="<?php 
                        if(isset($_SESSION['txtStorageF'])) {
                            echo $_SESSION['txtStorageF'];
                            unset($_SESSION['txtStorageF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtStorageM']; unset($_SESSION['txtStorageM']);?></p> [Size & type of internal storage (e.g. 1TB SSD)]<br /><br />

                    <!-- Operating System field -->
                    <label for="txtOS">Operating System: *</label><br />
                    <input type="text" name="txtOS" size="32" value="<?php 
                        if(isset($_SESSION['txtOSF'])) {
                            echo $_SESSION['txtOSF'];
                            unset($_SESSION['txtOSF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtOSM']; unset($_SESSION['txtOSM']);?></p> [Version & build number (e.g. Windows 11 22H2)]<br /><br />

                    <!-- CPU information field -->
                    <label for="txtCPU">CPU Information:</label><br />
                    <input type="text" name="txtCPU" size="32" value="<?php 
                        if(isset($_SESSION['txtCPUF'])) {
                            echo $_SESSION['txtCPUF'];
                            unset($_SESSION['txtCPUF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtCPUM']; unset($_SESSION['txtCPUM']);?></p> [CPU model, number of cores, speed (e.g. i7 7700k, 6 core, 3.6GHz)]<br /><br />

                    <!-- RAM information field -->
                    <label for="txtRAM">RAM Information:</label><br />
                    <input type="text" name="txtRAM" size="32" value="<?php 
                        if(isset($_SESSION['txtRAMF'])) {
                            echo $_SESSION['txtRAMF'];
                            unset($_SESSION['txtRAMF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtRAMM']; unset($_SESSION['txtRAMM']);?></p> [Size & type of (e.g. 16GB DDR4)] <br /><br />

                    <!-- MAC information field -->
                    <label for="txtMAC">MAC Information:</label><br />
                    <input type="text" name="txtMAC" size="32" value="<?php 
                        if(isset($_SESSION['txtMACF'])) {
                            echo $_SESSION['txtMACF'];
                            unset($_SESSION['txtMACF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtMACM']; unset($_SESSION['txtMACM']);?></p> [Unique network identifier for the device (e.g. A1-B2-C3-D4-E5-F6)]<br /><br />

                    <!-- IP information field -->
                    <label for="txtIP">IP Address Information:</label><br />
                    <input type="text" name="txtIP" size="32" value="<?php 
                        if(isset($_SESSION['txtIPF'])) {
                            echo $_SESSION['txtIPF'];
                            unset($_SESSION['txtIPF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtIPM']; unset($_SESSION['txtIPM']);?></p> [Current or last known IP address used]<br /><br />

                    <!-- Firmware version field -->
                    <label for="txtFirmware">Firmware Version:</label><br />
                    <input type="text" name="txtFirmware" size="32" value="<?php
                        if(isset($_SESSION['txtFirmwareF'])) {
                            echo $_SESSION['txtFirmwareF'];
                            unset($_SESSION['txtFirmwareF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtFirmwareM']; unset($_SESSION['txtFirmwareM']);?></p> [BIOS or UEFI version]<br /><br />

                    <label for="txtPeripheral">Peripheral Devices:</label><br />
                    <textarea name="txtPeripheral" class="tall-input"><?php 
                        if(isset($_SESSION['txtPeripheralF'])) {
                            echo $_SESSION['txtPeripheralF'];
                            unset($_SESSION['txtPeripheralF']);
                        }
                    ?></textarea><p class="error-message"><?php echo $_SESSION['txtPeripheralM']; unset($_SESSION['txtPeripheralM']);?></p> [Connected devices (e.g. mouse, keyboard, etc.)]<br /><br />

                    <!-- Network interface information field -->
                    <label for="txtNetwork">Network Interface Information:</label><br />
                    <input type="text" name="txtNetwork" size="32" value="<?php 
                        if(isset($_SESSION['txtNetworkF'])) {
                            echo $_SESSION['txtNetworkF'];
                            unset($_SESSION['txtNetworkF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtNetworkM']; unset($_SESSION['txtNetworkM']);?></p> [Wired (Ethernet) or wireless (Wi-Fi) network details]<br /><br />

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