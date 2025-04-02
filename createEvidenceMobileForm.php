<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);

if (!isset($_SESSION['txtExhibitReferenceExistsM'])) {
    $_SESSION['txtExhibitReferenceExistsM']='';
}


$unsetMsg = [
    'txtExhibitReferenceM', 'txtDeviceM', 'txtManufacturerM', 'txtModelM', 'txtSerialM', 
    'txtIMEIM', 'txtSIMM', 'txtNumberM', 'txtMACM', 'txtStorageM', 'txtOSM', 
    'txtBatteryM', 'txtAppsM', 'txtAccountM', 'txtEncryptionM', 'txtPasscodeM', 
    'txtLocationM', 'txtReceivedFromM', 'txtReceivedFromRankM', 'txtReceivedFromCompanyM', 'txtSealNumberM', 'txtDispatchByEmailM'
];

foreach ($unsetMsg as $msg) {
    if (!isset($_SESSION[$msg])) {
        $_SESSION[$msg]='';
    }
}

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            <a href="<?php echo "createEvidenceMobileForm.php?identifier=$identifier" ?>" id="navcase-button">Mobile Devices</a>
            <a href="<?php echo "createEvidenceExternalForm.php?identifier=$identifier" ?>" id="navcase-button">External Storage</a>
        </div>

        <section id="content">

            <h2>Create a Mobile Device Evidence Exhibit</h2>

            <form method="post" action="createEvidenceMobileInsert.php?identifier=<?php echo "$identifier" ?> " onsubmit="return validateForm()">
                <fieldset class="field-set width">
                    <legend>
                    Enter evidence details
                    </legend>

                    <?php include 'createEvidenceLBU01.php'; ?>

                    <!-- Device type field -->
                    <label for="deviceType">Device Type: *</label><br />
                    <input type="text" name="txtDevice" size="32" value="<?php 
                        if(isset($_SESSION['txtDeviceF'])) {
                            echo $_SESSION['txtDeviceF'];
                            unset($_SESSION['txtDeviceF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtDeviceM']; unset($_SESSION['txtDeviceM']);?></p> [e.g. Mobile phone, tablet, smartwatch]<br /><br />
                    
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
                    <label for="txtSerial">Serial Number:</label><br />
                    <input type="text" name="txtSerial" size="32" value="<?php 
                        if(isset($_SESSION['txtSerialF'])) {
                            echo $_SESSION['txtSerialF'];
                            unset($_SESSION['txtSerialF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtSerialM']; unset($_SESSION['txtSerialM']);?></p> [Unique identifier provided by the manufacturer]<br /><br />

                    <!-- IMEI field -->
                    <label for="txtIMEI">IMEI Number:</label><br />
                    <input type="text" name="txtIMEI" size="32" value="<?php 
                        if(isset($_SESSION['txtIMEIF'])) {
                            echo $_SESSION['txtIMEIF'];
                            unset($_SESSION['txtIMEIF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtIMEIM']; unset($_SESSION['txtIMEIM']);?></p> [International Mobile Equipment Identity number]<br /><br />

                    <!-- Sim field -->
                    <label for="txtSIM">SIM Card Information:</label><br />
                    <input type="text" name="txtSIM" size="32" value="<?php 
                        if(isset($_SESSION['txtSIMF'])) {
                            echo $_SESSION['txtSIMF'];
                            unset($_SESSION['txtSIMF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtSIMM']; unset($_SESSION['txtSIMM']);?></p> [Carrier, ICCID, SIM Size]<br /><br />

                    <!-- Number field -->
                    <label for="txtNumber">Phone Number Linked to SIM Card:</label><br />
                    <input type="text" name="txtNumber" size="32" value="<?php 
                        if(isset($_SESSION['txtNumberF'])) {
                            echo $_SESSION['txtNumberF'];
                            unset($_SESSION['txtNumberF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtNumberM']; unset($_SESSION['txtNumberM']);?></p> [Country code, Number]<br /><br />

                    <!-- MAC information field -->
                    <label for="txtMAC">MAC Information:</label><br />
                    <input type="text" name="txtMAC" size="32" value="<?php 
                        if(isset($_SESSION['txtMACF'])) {
                            echo $_SESSION['txtMACF'];
                            unset($_SESSION['txtMACF']);
                        }
                    ?>"/><p class="error-message"><?php echo $_SESSION['txtMACM']; unset($_SESSION['txtMACM']);?></p> [Unique network identifier for the device (e.g. A1-B2-C3-D4-E5-F6)]<br /><br />

                    <!-- Storage field -->
                    <label for="txtStorage">Storage Capacity:</label><br />
                    <input type="text" name="txtStorage" size="32" value="<?php 
                        if(isset($_SESSION['txtStorageF'])) {
                            echo $_SESSION['txtStorageF'];
                            unset($_SESSION['txtStorageF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtStorageM']; unset($_SESSION['txtStorageM']);?></p> [Size of internal storage (e.g. 1TB SSD)]<br /><br />

                    <!-- Operating System field -->
                    <label for="txtOS">Operating System:</label><br />
                    <input type="text" name="txtOS" size="32" value="<?php 
                        if(isset($_SESSION['txtOSF'])) {
                            echo $_SESSION['txtOSF'];
                            unset($_SESSION['txtOSF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtOSM']; unset($_SESSION['txtOSM']);?></p> [Type & version (e.g. iOS 17.2)]<br /><br />

                    <!-- Battery health field -->
                    <label for="txtBattery">Battery Health Information:</label><br />
                    <input type="text" name="txtBattery" size="32" value="<?php 
                        if(isset($_SESSION['txtBatteryF'])) {
                            echo $_SESSION['txtBatteryF'];
                            unset($_SESSION['txtBatteryF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtBatteryM']; unset($_SESSION['txtBatteryM']);?></p> [Battery health, cycle count (e.g. Battery Health: 98, Cycles: 80)]<br /><br />

                    <!-- installed apps field -->
                    <label for="txtApps">Installed Apps:</label><br />
                    <textarea name="txtApps" class="tall-input"><?php 
                        if(isset($_SESSION['txtAppsF'])) {
                            echo $_SESSION['txtAppsF'];
                            unset($_SESSION['txtAppsF']);
                        }
                    ?></textarea><p class="error-message"><?php echo $_SESSION['txtAppsM']; unset($_SESSION['txtAppsM']);?></p> [List of applications, versions (e.g. Facebook, 14.2.1)]<br /><br />

                    <!-- Encryption field -->
                    <label for="txtEncryption">Encryption Type:</label><br />
                    <input type="text" name="txtEncryption" size="32" value="<?php 
                        if(isset($_SESSION['txtEncryptionF'])) {
                            echo $_SESSION['txtEncryptionF'];
                            unset($_SESSION['txtEncryptionF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtEncryptionM']; unset($_SESSION['txtEncryptionM']);?></p> [Device Encryption (e.g. Encryption App: FileVault)]<br /><br />

                    <!-- Account field -->
                    <label for="txtAccount">Account Information:</label><br />
                    <input type="text" name="txtAccount" size="32" value="<?php 
                        if(isset($_SESSION['txtAccountF'])) {
                            echo $_SESSION['txtAccountF'];
                            unset($_SESSION['txtAccountF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtAccountM']; unset($_SESSION['txtAccountM']);?></p> [Linked account information (e.g. iCloud Account: user@icloud.com)]<br /><br />

                    <!-- Passcode field -->
                    <label for="txtPasscode">Screen Lock Information:</label><br />
                    <input type="text" name="txtPasscode" size="32" value="<?php 
                        if(isset($_SESSION['txtPasscodeF'])) {
                            echo $_SESSION['txtPasscodeF'];
                            unset($_SESSION['txtPasscodeF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtPasscodeM']; unset($_SESSION['txtPasscodeM']);?></p> [Passcode, Password, Biometrics (e.g. Passcode: 1234)]<br /><br />

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />

                </fieldset>
                
            </form>

            <?php include 'signatureLBU01Script.php'; ?>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>