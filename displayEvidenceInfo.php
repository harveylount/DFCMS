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
        $query = "SELECT EditedTime, EditedByFullName, EditedByUsername, CaseReference, ExhibitRef, CurrentLocation, SeizedTime, EditedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, 
        Model, SerialNumber, Storage, OS, CPU, RAM, MAC, IP, Firmware, Peripheral, Network FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $identifier, $evidenceID);  
        $stmt->execute();
        $results = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($results)) {

            echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
            echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Information' . "</td></tr>"; 
            echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
            echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Seized Time</td><td>" . $row['SeizedTime'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Evidence Status</td><td>" . $row['EvidenceStatus'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Current Seal Number</td><td>" . $row['CurrentSeal'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Current Location</td><td>" . $row['CurrentLocation'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Recent Information Editor</td><td>" . $row['EditedByFullName'] . " (" . $row['EditedByUsername'] . ")" . "</td></tr>";
            echo "<tr><td class='lbu-high'>Information Edited Timestamp</td><td>" . $row['EditedTime'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Device Type</td><td>" . $row['DeviceType'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Device Model</td><td>" . $row['Model'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Storage Capacity</td><td>" . $row['Storage'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Operating System</td><td>" . $row['OS'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>CPU</td><td>" . $row['CPU'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>RAM</td><td>" . $row['RAM'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>MAC Address</td><td>" . $row['MAC'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>IP</td><td>" . $row['IP'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Firmware</td><td>" . $row['Firmware'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Peripheral Devices</td><td>" . $row['Peripheral'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Network Information</td><td>" . $row['Network'] . "</td></tr>";
            echo "</table>";


        }
        $stmt->close();

    } else if($evidenceType === 'Mobile') {
        $query = "SELECT EditedTime, EditedByFullName, EditedByUsername, CaseReference, ExhibitRef, CurrentLocation, SeizedTime, EditedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, 
        Model, SerialNumber, Storage, OS, MAC, IMEI, SIM, PhoneNumber, BatteryHealth, InstalledApps, EncryptionType, AccountInfo, ScreenLock FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $identifier, $evidenceID);  
        $stmt->execute();
        $results = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($results)) {
            
            echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
            echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Information' . "</td></tr>"; 
            echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
            echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Seized Time</td><td>" . $row['SeizedTime'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Evidence Status</td><td>" . $row['EvidenceStatus'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Current Seal Number</td><td>" . $row['CurrentSeal'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Current Location</td><td>" . $row['CurrentLocation'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Recent Information Editor</td><td>" . $row['EditedByFullName'] . " (" . $row['EditedByUsername'] . ")" . "</td></tr>";
            echo "<tr><td class='lbu-high'>Information Edited Timestamp</td><td>" . $row['EditedTime'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Device Type</td><td>" . $row['DeviceType'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Device Model</td><td>" . $row['Model'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Storage Capacity</td><td>" . $row['Storage'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Operating System</td><td>" . $row['OS'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>MAC Address</td><td>" . $row['MAC'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>IMEI Number</td><td>" . $row['IMEI'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>SIM Information</td><td>" . $row['SIM'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Phone Number Linked to SIM</td><td>" . $row['PhoneNumber'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Battery Health</td><td>" . $row['BatteryHealth'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Installed Apps</td><td>" . $row['InstalledApps'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Encryption Type</td><td>" . $row['EncryptionType'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Account Information</td><td>" . $row['AccountInfo'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Screen Lock</td><td>" . $row['ScreenLock'] . "</td></tr>";
            echo "</table>";
            
        }
        $stmt->close();
    } else if($evidenceType === 'ExternalStorage') {
        $query = "SELECT EditedTime, EditedByFullName, EditedByUsername, CaseReference, ExhibitRef, CurrentLocation, SeizedTime, EditedTime, EvidenceStatus, CurrentSeal, DeviceType, Manufacturer, 
        Model, SerialNumber, Storage, EncryptionType, InterfaceType, FileSystem FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $identifier, $evidenceID);  
        $stmt->execute();
        $results = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($results)) {
            
            echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
            echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>DFCMS</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Exhibit Information' . "</td></tr>"; 
            echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'>" . 'Page 1 of 1' . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-dark'>Case Reference</td><td>" . $row['CaseReference'] . "</td></tr>";
            echo "<tr><td class='lbu-dark'>Exhibit Reference</td><td>" . $row['ExhibitRef'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Seized Time</td><td>" . $row['SeizedTime'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Evidence Status</td><td>" . $row['EvidenceStatus'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Current Seal Number</td><td>" . $row['CurrentSeal'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Current Location</td><td>" . $row['CurrentLocation'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Recent Information Editor</td><td>" . $row['EditedByFullName'] . " (" . $row['EditedByUsername'] . ")" . "</td></tr>";
            echo "<tr><td class='lbu-high'>Information Edited Timestamp</td><td>" . $row['EditedTime'] . "</td></tr>";
            echo "</table>";
            echo "<br/>";

            echo "<table class='styled-table' border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>";
            echo "<tr><td class='lbu-high'>Device Type</td><td>" . $row['DeviceType'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Manufacturer</td><td>" . $row['Manufacturer'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Device Model</td><td>" . $row['Model'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Serial Number</td><td>" . $row['SerialNumber'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Storage Capacity</td><td>" . $row['Storage'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Encryption Type</td><td>" . $row['EncryptionType'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>Interface Type</td><td>" . $row['InterfaceType'] . "</td></tr>";
            echo "<tr><td class='lbu-high'>File System</td><td>" . $row['FileSystem'] . "</td></tr>";
            echo "</table>";
            
        }
        $stmt->close();
    }

} else {
    header('location:index.php');
}
?>