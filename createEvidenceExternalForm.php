<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);

$unsetMsg = [
    'txtExhibitReferenceM', 'txtManufacturerM', 'txtModelM', 'txtSerialM', 
    'txtStorageM', 'txtEncryptionM', 'txtLocationM', 'txtReceivedFromM',
    'txtReceivedFromRankM', 'txtReceivedFromCompanyM', 'txtSealNumberM', 'txtDispatchByEmailM'];

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

            <h2>Create a External Storage Evidence Exhibit</h2>

            <form method="post" action="createEvidenceExternalInsert.php?identifier=<?php echo "$identifier" ?> " onsubmit="return validateForm()">
                <fieldset class="field-set width">
                    <legend>
                    Enter evidence details
                    </legend>

                    <?php include 'createEvidenceLBU01.php'; ?>

                    <!-- Device type field -->
                    <label for="deviceType">Device Type: *</label><br />
                    <select name="deviceType" required>
                        <option value="External Hard Drive" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'External Hard Drive') echo 'selected'; ?>>External Hard Drive</option>
                        <option value="External Solid State Drive" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'External Solid State Drive') echo 'selected'; ?>>External Solid State Drive</option>
                        <option value="External NVMe SSD" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'External NVMe SSD') echo 'selected'; ?>>External NVMe SSD</option>
                        <option value="External eMMC" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'External eMMC') echo 'selected'; ?>>External eMMC</option>
                        <option value="USB Flash Drive" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'USB Flash Drive') echo 'selected'; ?>>USB Flash Drive</option>
                        <option value="SD Card" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'SD Card') echo 'selected'; ?>>SD Card</option>
                        <option value="Micro SD Card" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Micro SD Card') echo 'selected'; ?>>Micro SD Card</option>
                        <option value="CD" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'CD') echo 'selected'; ?>>CD</option>
                        <option value="DVD" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'DVD') echo 'selected'; ?>>DVD</option>
                        <option value="Blue-ray" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Blu-ray') echo 'selected'; ?>>Blu-ray</option>
                        <option value="Cloud Storage" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Cloud Storage') echo 'selected'; ?>>Cloud Storage</option>
                    </select><br /><br />
                    
                    <!-- Manufacture field -->
                    <label for="txtManufacturer">Manufacturer:</label><br />
                    <input type="text" name="txtManufacturer" size="32" value="<?php 
                        if(isset($_SESSION['txtManufacturerF'])) {
                            echo $_SESSION['txtManufacturerF'];
                            unset($_SESSION['txtManufacturerF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtManufacturerM']; unset($_SESSION['txtManufacturerM']);?></p> [Manufacturer / brand name]<br /><br />

                    <!-- Model field -->
                    <label for="txtModel">Model:</label><br />
                    <input type="text" name="txtModel" size="32" value="<?php 
                        if(isset($_SESSION['txtModelF'])) {
                            echo $_SESSION['txtModelF'];
                            unset($_SESSION['txtModelF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtModelM']; unset($_SESSION['txtModelM']);?></p> [Specific model name or number]<br /><br />

                    <!-- Serial field -->
                    <label for="txtSerial">Serial Number:</label><br />
                    <input type="text" name="txtSerial" size="32" value="<?php 
                        if(isset($_SESSION['txtSerialF'])) {
                            echo $_SESSION['txtSerialF'];
                            unset($_SESSION['txtSerialF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtSerialM']; unset($_SESSION['txtSerialM']);?></p> [Unique identifier provided by the manufacturer]<br /><br />

                    <!-- Storage field -->
                    <label for="txtStorage">Storage Capacity:</label><br />
                    <input type="text" name="txtStorage" size="32" value="<?php 
                        if(isset($_SESSION['txtStorageF'])) {
                            echo $_SESSION['txtStorageF'];
                            unset($_SESSION['txtStorageF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtStorageM']; unset($_SESSION['txtStorageM']);?></p> [Size & type of internal storage (e.g. 1TB SSD)]<br /><br />

                    <!-- Interface type field -->
                    <label for="interfaceType">Interface Type: *</label><br />
                    <select name="interfaceType" required>
                        <option value="USB 2.0" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'USB 2.0') echo 'selected'; ?>>USB 2.0</option>
                        <option value="USB 3.0" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'USB 3.0') echo 'selected'; ?>>USB 3.0</option>
                        <option value="USB 3.1" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'USB 3.1') echo 'selected'; ?>>USB 3.1</option>
                        <option value="USB 3.2" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'USB 3.2') echo 'selected'; ?>>USB 3.2</option>
                        <option value="USB Type-C" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'USB Type-C') echo 'selected'; ?>>USB Type-C</option>
                        <option value="SATA" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'SATA') echo 'selected'; ?>>SATA</option>
                        <option value="eSATA" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'eSATA') echo 'selected'; ?>>eSATA</option>
                        <option value="FireWire" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'FireWire') echo 'selected'; ?>>FireWire</option>
                        <option value="Thunderbolt" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Thunderbolt') echo 'selected'; ?>>Thunderbolt</option>
                        <option value="PCIe" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'PCIe') echo 'selected'; ?>>PCIe</option>
                        <option value="mSATA" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'mSATA') echo 'selected'; ?>>mSATA</option>
                        <option value="M.2" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'M.2') echo 'selected'; ?>>M.2</option>
                        <option value="OTG" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'OTG') echo 'selected'; ?>>OTG</option>
                        <option value="UHS-I" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'UHS-I') echo 'selected'; ?>>UHS-I</option>
                        <option value="UHS-II" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'UHS-II') echo 'selected'; ?>>UHS-II</option>
                        <option value="Internet (HTTP/HTTPS)" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'Internet (HTTP/HTTPS)') echo 'selected'; ?>>Internet (HTTP/HTTPS)</option>
                        <option value="WebDAV" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'WebDAV') echo 'selected'; ?>>WebDAV</option>
                        <option value="FTP/SFTP" <?php if(isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'FTP/SFTP') echo 'selected'; ?>>FTP/SFTP</option>
                    </select><br /><br />

                    <!-- Interface type field -->
                    <label for="fileSystemType">File System Type:</label><br />
                    <select name="fileSystemType">
                        <option value="Unknown" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'Unknown') echo 'selected'; ?>>Unknown</option>
                        <option value="NTFS (New Technology File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'NTFS (New Technology File System)') echo 'selected'; ?>>NTFS (New Technology File System)</option>
                        <option value="FAT16 (File Allocation Table)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'FAT16 (File Allocation Table)') echo 'selected'; ?>>FAT16 (File Allocation Table)</option>
                        <option value="FAT32 (File Allocation Table)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'FAT32 (File Allocation Table)') echo 'selected'; ?>>FAT32 (File Allocation Table)</option>
                        <option value="exFAT (File Allocation Table)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'exFAT (File Allocation Table)') echo 'selected'; ?>>exFAT (File Allocation Table)</option>
                        <option value="HFS+ (Mac OS Extended)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'HFS+ (Mac OS Extended)') echo 'selected'; ?>>HFS+ (Mac OS Extended)</option>
                        <option value="APFS (Apple File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'APFS (Apple File System)') echo 'selected'; ?>>APFS (Apple File System)</option>
                        <option value="EXT2 (Extended File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'EXT2 (Extended File System)') echo 'selected'; ?>>EXT2 (Extended File System)</option>
                        <option value="EXT3 (Extended File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'EXT3 (Extended File System)') echo 'selected'; ?>>EXT3 (Extended File System)</option>
                        <option value="EXT4 (Extended File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'EXT4 (Extended File System)') echo 'selected'; ?>>EXT4 (Extended File System)</option>
                        <option value="Btrfs (B-tree File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'Btrfs (B-tree File System)') echo 'selected'; ?>>Btrfs (B-tree File System)</option>
                        <option value="ZFS (Zettabyte File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'ZFS (Zettabyte File System)') echo 'selected'; ?>>ZFS (Zettabyte File System)</option>
                        <option value="XFS" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'XFS') echo 'selected'; ?>>XFS</option>
                        <option value="ReFS (Resilient File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'ReFS (Resilient File System)') echo 'selected'; ?>>ReFS (Resilient File System)</option>
                        <option value="UFS (Unix File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'UFS (Unix File System)') echo 'selected'; ?>>UFS (Unix File System)</option>
                        <option value="F2FS (Flash-Friendly File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'F2FS (Flash-Friendly File System)') echo 'selected'; ?>>F2FS (Flash-Friendly File System)</option>
                        <option value="JFS (Journaled File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'JFS (Journaled File System)') echo 'selected'; ?>>JFS (Journaled File System)</option>
                        <option value="FFS (Fast File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'FFS (Fast File System)') echo 'selected'; ?>>FFS (Fast File System)</option>
                        <option value="ISO 9660 (CD-ROM File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'ISO 9660 (CD-ROM File System)') echo 'selected'; ?>>ISO 9660 (CD-ROM File System)</option>
                        <option value="CDFS (Compact Disc File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'CDFS (Compact Disc File System)') echo 'selected'; ?>>CDFS (Compact Disc File System)</option>
                        <option value="UDF (Universal Disk Format)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'UDF (Universal Disk Format)') echo 'selected'; ?>>UDF (Universal Disk Format)</option>
                        <option value="TMPFS (Temporary File System)" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'TMPFS (Temporary File System)') echo 'selected'; ?>>TMPFS (Temporary File System)</option>
                        <option value="Network File Systems" <?php if(isset($_SESSION['fileSystemType']) && $_SESSION['fileSystemType'] == 'Network File Systems') echo 'selected'; ?>>Network File Systems</option>
                    </select><br /><br />

                    <!-- Encryption field -->
                    <label for="txtEncryption">Encryption Status:</label><br />
                    <input type="text" name="txtEncryption" size="32" value="<?php 
                        if(isset($_SESSION['txtEncryptionF'])) {
                            echo $_SESSION['txtEncryptionF'];
                            unset($_SESSION['txtEncryptionF']);
                        }
                    ?>" /><p class="error-message"><?php echo $_SESSION['txtEncryptionM']; unset($_SESSION['txtEncryptionM']);?></p> [Device Encryption]<br /><br />

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