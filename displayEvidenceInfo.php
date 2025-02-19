<?php
if (isset($_GET['identifier'])) {

    $identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
    $evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

    // SQL query to get the evidence type
    $sqlCaseRef = "SELECT EvidenceType FROM evidence WHERE EvidenceID = ?";
    $stmt = $connection->prepare($sqlCaseRef);
    $stmt->bind_param("s", $evidenceID);
    $stmt->execute();
    $stmt->bind_result($evidenceType);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

    if($evidenceType === 'Computer') {
        $query = "SELECT CaseReference, ExhibitRef, SeizedTime, EditedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, 
        Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $identifier, $evidenceID);  
        $stmt->execute();
        $results = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($results)) {
            echo "<h2>Exhibit   " . $row['ExhibitRef'] . "  Information</h2>";
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<tr><td><b>Case Reference:</b></td><td>" . $row['CaseReference'] . "</td></tr>";
            echo "<tr><td><b>Seized Time:</b></td><td>" . $row['SeizedTime'] . "</td></tr>";
            echo "<tr><td><b>Edited Time:</b></td><td>" . $row['EditedTime'] . "</td></tr>";
            echo "<tr><td><b>Evidence Status:</b></td><td>" . $row['EvidenceStatus'] . "</td></tr>";
            echo "<tr><td><b>Current Seal Number:</b></td><td>" . $row['CurrentSeal'] . "</td></tr>";
            echo "<tr><td><b>Device Type:</b></td><td>" . $row['DeviceType'] . "</td></tr>";
            echo "<tr><td><b>Manufacturer:</b></td><td>" . $row['Manufacturer'] . "</td></tr>";
            echo "<tr><td><b>Device Model:</b></td><td>" . $row['Model'] . "</td></tr>";
            echo "<tr><td><b>Serial Number:</b></td><td>" . $row['SerialNumber'] . "</td></tr>";
            echo "<tr><td><b>Storage Capacity:</b></td><td>" . $row['Storage'] . "</td></tr>";
            echo "<tr><td><b>Operating System:</b></td><td>" . $row['OS'] . "</td></tr>";
            echo "<tr><td><b>CPU:</b></td><td>" . $row['CPU'] . "</td></tr>";
            echo "<tr><td><b>RAM:</b></td><td>" . $row['RAM'] . "</td></tr>";
            echo "<tr><td><b>MAC Address:</b></td><td>" . $row['MAC'] . "</td></tr>";
            echo "<tr><td><b>IP:</b></td><td>" . $row['IP'] . "</td></tr>";
            echo "<tr><td><b>Firmware:</b></td><td>" . $row['Firmware'] . "</td></tr>";
            echo "<tr><td><b>Peripheral Devices:</b></td><td>" . $row['Peripheral'] . "</td></tr>";
            echo "<tr><td><b>Network Information:</b></td><td>" . $row['Network'] . "</td></tr>";
            echo "</table>";


        }
        $stmt->close();

    } else if($evidenceType === 'Mobile') {
        $query = "SELECT CaseReference, ExhibitRef, SeizedTime, EditedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, 
        Model, SerialNumber, Storage, OS, MAC, IMEI, SIM, PhoneNumber, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $identifier, $evidenceID);  
        $stmt->execute();
        $results = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($results)) {
            echo "<h2>Exhibit   " . $row['ExhibitRef'] . "  Information</h2>";
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<tr><td><b>Exhibit Reference:</b></td><td>" . $row['ExhibitRef'] . "</td></tr>";
            echo "<tr><td><b>Case Reference:</b></td><td>" . $row['CaseReference'] . "</td></tr>";
            echo "<tr><td><b>Seized Time:</b></td><td>" . $row['SeizedTime'] . "</td></tr>";
            echo "<tr><td><b>Edited Time:</b></td><td>" . $row['EditedTime'] . "</td></tr>";
            echo "<tr><td><b>Evidence Status:</b></td><td>" . $row['EvidenceStatus'] . "</td></tr>";
            echo "<tr><td><b>Current Seal Number:</b></td><td>" . $row['CurrentSeal'] . "</td></tr>";
            echo "<tr><td><b>Device Type:</b></td><td>" . $row['DeviceType'] . "</td></tr>";
            echo "<tr><td><b>Manufacturer:</b></td><td>" . $row['Manufacturer'] . "</td></tr>";
            echo "<tr><td><b>Device Model:</b></td><td>" . $row['Model'] . "</td></tr>";
            echo "<tr><td><b>Serial Number:</b></td><td>" . $row['SerialNumber'] . "</td></tr>";
            echo "<tr><td><b>Storage Capacity:</b></td><td>" . $row['Storage'] . "</td></tr>";
            echo "<tr><td><b>Operating System:</b></td><td>" . $row['OS'] . "</td></tr>";
            echo "<tr><td><b>MAC Address:</b></td><td>" . $row['MAC'] . "</td></tr>";
            echo "<tr><td><b>IMEI Number:</b></td><td>" . $row['IMEI'] . "</td></tr>";
            echo "<tr><td><b>SIM Information:</b></td><td>" . $row['SIM'] . "</td></tr>";
            echo "<tr><td><b>Phone Number Linked to SIM:</b></td><td>" . $row['PhoneNumber'] . "</td></tr>";
            echo "<tr><td><b>Battery Health:</b></td><td>" . $row['BatteryHealth'] . "</td></tr>";
            echo "<tr><td><b>Installed Apps:</b></td><td>" . $row['InstalledApps'] . "</td></tr>";
            echo "<tr><td><b>Encryption Type:</b></td><td>" . $row['EncryptionType'] . "</td></tr>";
            echo "<tr><td><b>Account Information:</b></td><td>" . $row['AccountInfo'] . "</td></tr>";
            echo "<tr><td><b>Screen Lock:</b></td><td>" . $row['ScreenLock'] . "</td></tr>";
            echo "</table>";
            
        }
        $stmt->close();
    } else if($evidenceType === 'ExternalStorage') {
        $query = "SELECT CaseReference, ExhibitRef, SeizedTime, EditedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, 
        Model, SerialNumber, Storage, EncryptionType, InterfaceType, FileSystem FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $identifier, $evidenceID);  
        $stmt->execute();
        $results = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($results)) {
            echo "<h2>Exhibit   " . $row['ExhibitRef'] . "  Information</h2>";
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<tr><td><b>Case Reference:</b></td><td>" . $row['CaseReference'] . "</td></tr>";
            echo "<tr><td><b>Seized Time:</b></td><td>" . $row['SeizedTime'] . "</td></tr>";
            echo "<tr><td><b>Edited Time:</b></td><td>" . $row['EditedTime'] . "</td></tr>";
            echo "<tr><td><b>Evidence Status:</b></td><td>" . $row['EvidenceStatus'] . "</td></tr>";
            echo "<tr><td><b>Current Seal Number:</b></td><td>" . $row['CurrentSeal'] . "</td></tr>";
            echo "<tr><td><b>Device Type:</b></td><td>" . $row['DeviceType'] . "</td></tr>";
            echo "<tr><td><b>Manufacturer:</b></td><td>" . $row['Manufacturer'] . "</td></tr>";
            echo "<tr><td><b>Device Model:</b></td><td>" . $row['Model'] . "</td></tr>";
            echo "<tr><td><b>Serial Number:</b></td><td>" . $row['SerialNumber'] . "</td></tr>";
            echo "<tr><td><b>Storage Capacity:</b></td><td>" . $row['Storage'] . "</td></tr>";
            echo "<tr><td><b>Encryption Type:</b></td><td>" . $row['EncryptionType'] . "</td></tr>";
            echo "<tr><td><b>Interface Type:</b></td><td>" . $row['InterfaceType'] . "</td></tr>";
            echo "<tr><td><b>File System:</b></td><td>" . $row['FileSystem'] . "</td></tr>";
            echo "</table>";
            
        }
        $stmt->close();
    }

} else {
    header('location:index.php');
}
?>