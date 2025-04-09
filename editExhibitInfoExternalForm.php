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
        header ('location:editExhibitInfoForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    if ($evidenceType == "Mobile") {
        header ('location:editExhibitInfoMobileForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        exit();
    }
    if ($evidenceType == "ExternalStorage") {
        //header ('location:editExhibitInfoExternalForm.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
        //exit();
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
            <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
            <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
            <a href="logoutFunction.php" id="logout-button">Logout</a>
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

            <form method="post" action="editExhibitInfoExternalInsert.php?identifier=<?php echo urlencode($identifier) . "&EvidenceID=" . urlencode($evidenceID) ?> ">
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
                    ?>" /><p class="error-message"><?php if (isset($_SESSION['txtManufacturerM'])) { echo $_SESSION['txtManufacturerM']; unset($_SESSION['txtManufacturerM']); } ?></p> [Manufacturer / brand name]<br /><br />

                    <!-- Model field -->
                    <label for="txtModel">Model:</label><br />
                    <input type="text" name="txtModel" size="32" value="<?php 
                        if(isset($_SESSION['txtModelF'])) {
                            echo $_SESSION['txtModelF'];
                            unset($_SESSION['txtModelF']);
                        }
                    ?>" /><p class="error-message"><?php if (isset($_SESSION['txtModelM'])) { echo $_SESSION['txtModelM']; unset($_SESSION['txtModelM']); } ?></p> [Specific model name or number]<br /><br />

                    <!-- Serial field -->
                    <label for="txtSerial">Serial Number:</label><br />
                    <input type="text" name="txtSerial" size="32" value="<?php 
                        if(isset($_SESSION['txtSerialF'])) {
                            echo $_SESSION['txtSerialF'];
                            unset($_SESSION['txtSerialF']);
                        }
                    ?>" /><p class="error-message"><?php if (isset($_SESSION['txtSerialM'])) { echo $_SESSION['txtSerialM']; unset($_SESSION['txtSerialM']); } ?></p> [Unique identifier provided by the manufacturer]<br /><br />

                    <!-- Storage field -->
                    <label for="txtStorage">Storage Capacity:</label><br />
                    <input type="text" name="txtStorage" size="32" value="<?php 
                        if(isset($_SESSION['txtStorageF'])) {
                            echo $_SESSION['txtStorageF'];
                            unset($_SESSION['txtStorageF']);
                        }
                    ?>" /><p class="error-message"><?php if (isset($_SESSION['txtStorageM'])) { echo $_SESSION['txtStorageM']; unset($_SESSION['txtStorageM']); } ?></p> [Size & type of internal storage (e.g. 1TB SSD)]<br /><br />

                    <!-- Interface type field -->
                    <label for="interfaceType">Interface Type: *</label><br />
                    <select name="interfaceType">
                        <option value="" disabled selected>Select to change</option>
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
                        <option value="" disabled selected>Select to change</option>
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
                    ?>" /><p class="error-message"><?php if (isset($_SESSION['txtEncryptionM'])) { echo $_SESSION['txtEncryptionM']; unset($_SESSION['txtEncryptionM']); } ?></p> [Device Encryption]<br /><br />

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