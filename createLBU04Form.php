<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  
$evidenceID = intval($_GET['EvidenceID']);  

// If evidence is not a computer device redirects
$query = "SELECT EvidenceType FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$results = $stmt->get_result();
$row = $results->fetch_assoc();
$stmt->close();

if ($row['EvidenceType'] !== "Computer") {
    header('Location: viewEvidenceExhibit.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
    exit();
}

$sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ?  AND EvidenceID = ? ";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference);
$stmt->fetch();
$stmt->close();

$_SESSION['dateExaminedDatabaseLBU06'] = date('Y-m-d');
$_SESSION['dateExaminedDisplayLBU06'] = date('d-m-y');
$_SESSION['timestampInDatabaseLBU06'] = date('Y-m-d H:i:s');
$_SESSION['timestampInDisplayLBU06'] = date('d-m-Y H:i:s');




?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create LBU04</title>

    <style>
        .condition-notes-input {
            height: 70px;
            width: 950px;
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
            <a href="<?php echo "viewLBU03.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU03</a>
            <a href="<?php echo "viewLBU04.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU04</a>
            <a href="<?php echo "viewLBU05.php?identifier=$identifier&EvidenceID=$evidenceID" ?>" id="navcase-button">LBU05</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier"?>" id="navcase-button">LBU06</a>
        </div>

        <section id="content">

            <h2><?php echo $caseReference; ?> - Create a Computer Exhibit Detail Form (LBU04)</h2>

            <form method="post" action="createLBU04Insert.php?identifier=<?php echo "$identifier"?>&EvidenceID=<?php echo "$evidenceID"?>" id="dynamicForm"> 
                <fieldset class="field-set width">
                    <legend>Enter Computer Exhibit Details</legend>

                    <br><b>Case Reference:</b> <?php echo $caseReference;?> <br><br>
                    <b>Exhibit Reference:</b> <?php echo $exhibitReference;?> <br><br>

                    <h3>Details</h3>

                    <label for="txtManufacturer">Manufacturer: *</label><br />
                    <input type="text" name="txtManufacturer" size="32" required/><br /><br />

                    <label for="txtModel">Model: *</label><br />
                    <input type="text" name="txtModel" size="32" required/><br /><br />
                    
                    <label for="txtSerialNumber">Serial Number: *</label><br />
                    <input type="text" name="txtSerialNumber" size="32" required/><br /><br />

                    <label for="txtType">Type: *</label><br />
                    <input type="text" name="txtType" size="32" required/><br /><br />

                    <h3>Notable Damage / Marking</h3>

                    <label for="selectCondition">Condition: *</label><br />
                    <select name="selectCondition" required>
                        <option value="Excellent">Excellent</option>
                        <option value="Good">Good</option>
                        <option value="Poor">Poor</option>
                    </select><br /><br />

                    <label for="txtConditionNotes">Condition Notes: *</label><br />
                    <textarea name="txtConditionNotes" class="condition-notes-input" required></textarea><br /><br />
                    
                    <h3>Peripherals</h3>

                    <label for="txtOpticalDrive">Optical Drive: *</label><br />
                    <input type="text" name="txtOpticalDrive" size="32" required/><br /><br />

                    <label for="txtFloppyDisk">Floppy Disk: *</label><br />
                    <input type="text" name="txtFloppyDisk" size="32" required/><br /><br />

                    <label for="txtNetwork">Network: *</label><br />
                    <input type="text" name="txtNetwork" size="32" required/><br /><br />

                    <label for="txtModem">Modem: *</label><br />
                    <input type="text" name="txtModem" size="32" required/><br /><br />

                    <label for="txtFirewire">Firewire: *</label><br />
                    <input type="text" name="txtFirewire" size="32" required/><br /><br />

                    <label for="txtMediaCardReader">Media Card Reader: *</label><br />
                    <input type="text" name="txtMediaCardReader" size="32" required/><br /><br />

                    <label for="txtUSB">USB: *</label><br />
                    <input type="text" name="txtUSB" size="32" required/><br /><br />

                    <label for="txtSIMSlot">SIM Slot: *</label><br />
                    <input type="text" name="txtSIMSlot" size="32" required/><br /><br />

                    <label for="txtBattery">Battery: *</label><br />
                    <input type="text" name="txtBattery" size="32" required/><br /><br />

                    <label for="txtVideoCard">Video Card: *</label><br />
                    <input type="text" name="txtVideoCard" size="32" required/><br /><br />

                    <label for="txtOtherPeripherals">Other:</label><br />
                    <textarea name="txtOtherPeripherals" class="condition-notes-input"></textarea><br /><br />

                    <h3>BIOS Details</h3>

                    <label for="txtBIOSKey">BIOS Key: *</label><br />
                    <input type="text" name="txtBIOSKey" size="32" required/><br /><br />
    
                    <label for="txtBIOSPassword">BIOS Password: *</label><br />
                    <input type="text" name="txtBIOSPassword" size="32" required/><br /><br />
                
                    <label for="txtBIOSSystem">BIOS System: *</label><br />
                    <input type="text" name="txtBIOSSystem" size="32" required/><br /><br />
    
                    <label for="txtBootOrder">Boot Order: *</label><br />
                    <input type="text" name="txtBootOrder" size="32" required/><br /><br />

                    <label for="txtBIOSDate">BIOS Date: *</label><br />
                    <input type="date" name="txtBIOSDate" required/><br /><br />
    
                    <label for="txtBIOSTime">BIOS Time: *</label><br />
                    <input type="time" name="txtBIOSTime" size="32" required/><br /><br />

                    <label for="txtActualDate">Actual Date: *</label><br />
                    <input type="date" name="txtActualDate" required/><br /><br />
    
                    <label for="txtActualTime">Actual Time: *</label><br />
                    <input type="time" name="txtActualTime" size="32" required/><br /><br />

                    <label for="txtBIOSNotes">BIOS Notes:</label><br />
                    <textarea name="txtBIOSNotes" class="condition-notes-input"></textarea><br /><br />

                    <h3>Hard Drive Details</h3>


                    <!-- Modified Generative AI output. Reference: J, K, L - START -->
                    <!-- Form sets HDD -->
                    <div id="formSetsContainerHDD">

                    </div>
                    <button type="button" onclick="addFormSetHDD()">Add More</button>

                    
                    <input type="hidden" id="hardDriveDetails" name="hardDriveDetails" value="">
                    <br /><br />
                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />
                </fieldset>
            </form>

                    <script>

                        let formCountHDD = 0; // Track the number of form sets

                        // Add new form set
                        function addFormSetHDD() {
                            formCountHDD++;

                            // Create a new set of fields
                            const newFormSetHDD = document.createElement('div');
                            newFormSetHDD.classList.add('formSetHDD');
                            newFormSetHDD.id = 'formSetHDD_' + formCountHDD;

                            // Create the formset content, including the timestamp as a visible label and hidden input
                            newFormSetHDD.innerHTML = `
                                <label for="txtReferenceHDD_${formCountHDD}">Reference: *</label><br />
                                <input type="text" name="txtReferenceHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="txtManufacturerHDD_${formCountHDD}">Manufacturer: *</label><br />
                                <input type="text" name="txtManufacturerHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="txtModelHDD_${formCountHDD}">Model: *</label><br />
                                <input type="text" name="txtModelHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="txtSerialNumberHDD_${formCountHDD}">Serial Number: *</label><br />
                                <input type="text" name="txtSerialNumberHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="txtTypeHDD_${formCountHDD}">Type: *</label><br />
                                <input type="text" name="txtTypeHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="txtSizeHDD_${formCountHDD}">Size: *</label><br />
                                <input type="text" name="txtSizeHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="txtImagingMethodHDD_${formCountHDD}">Imaging Method: *</label><br />
                                <input type="text" name="txtImagingMethodHDD_${formCountHDD}" size="32" required/><br /><br />

                                <label for="selectImageVerified_${formCountHDD}">Image Verified: *</label><br />
                                <select name="selectImageVerified_${formCountHDD}" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select><br /><br />

                                <label for="txtNotesHDD_${formCountHDD}">Hard Drive Notes:</label><br />
                                <textarea name="txtNotesHDD_${formCountHDD}" class="condition-notes-input"></textarea><br /><br />

                                <button type="button" onclick="removeFormSetHDD(${formCountHDD})">Remove</button><br /><br />
                            `;

                            document.getElementById("formSetsContainerHDD").appendChild(newFormSetHDD);

                            newFormSetHDD.querySelector(`input[name="txtReferenceHDD_${formCountHDD}"]`).addEventListener("input", validateHDDReference);
                            newFormSetHDD.querySelector(`input[name="txtManufacturerHDD_${formCountHDD}"]`).addEventListener("input", validateHDDManufacturer);
                            newFormSetHDD.querySelector(`input[name="txtModelHDD_${formCountHDD}"]`).addEventListener("input", validateHDDModel);
                            newFormSetHDD.querySelector(`input[name="txtSerialNumberHDD_${formCountHDD}"]`).addEventListener("input", validateHDDSerial);
                            newFormSetHDD.querySelector(`input[name="txtTypeHDD_${formCountHDD}"]`).addEventListener("input", validateHDDType);
                            newFormSetHDD.querySelector(`input[name="txtSizeHDD_${formCountHDD}"]`).addEventListener("input", validateHDDSize);
                            newFormSetHDD.querySelector(`input[name="txtImagingMethodHDD_${formCountHDD}"]`).addEventListener("input", validateHDDImagingMethod);
                            newFormSetHDD.querySelector(`textarea[name="txtNotesHDD_${formCountHDD}"]`).addEventListener("input", validateHDDNotes);

                        }

                        // Remove a form set
                        function removeFormSetHDD(setIdHDD) {
                            const formSetHDD = document.getElementById('formSetHDD_' + setIdHDD);
                            formSetHDD.remove();
                        }
                        
                        window.onload = function () {
                            
                            document.querySelector('input[name="txtManufacturer"]').addEventListener("input", validateManufacturer);
                            document.querySelector('input[name="txtModel"]').addEventListener("input", validateModel);
                            document.querySelector('input[name="txtSerialNumber"]').addEventListener("input", validateSerial);
                            document.querySelector('input[name="txtType"]').addEventListener("input", validateType);
                            document.querySelector('textarea[name="txtConditionNotes"]').addEventListener("input", validateConditionNotes);

                            document.querySelector('input[name="txtOpticalDrive"]').addEventListener("input", validateOpticalDrive);
                            document.querySelector('input[name="txtFloppyDisk"]').addEventListener("input", validateFloppyDisk);
                            document.querySelector('input[name="txtNetwork"]').addEventListener("input", validateNetwork);
                            document.querySelector('input[name="txtModem"]').addEventListener("input", validateModem);
                            document.querySelector('input[name="txtFirewire"]').addEventListener("input", validateFirewire);
                            document.querySelector('input[name="txtMediaCardReader"]').addEventListener("input", validateMediaCard);
                            document.querySelector('input[name="txtUSB"]').addEventListener("input", validateUSB);
                            document.querySelector('input[name="txtSIMSlot"]').addEventListener("input", validateSIMSlot);
                            document.querySelector('input[name="txtBattery"]').addEventListener("input", validateBattery);
                            document.querySelector('input[name="txtVideoCard"]').addEventListener("input", validateVideoCard);
                            document.querySelector('textarea[name="txtOtherPeripherals"]').addEventListener("input", validateOther);
                            document.querySelector('input[name="txtBIOSKey"]').addEventListener("input", validateBIOSKey);
                            document.querySelector('input[name="txtBIOSPassword"]').addEventListener("input", validatePassword);
                            document.querySelector('input[name="txtBIOSSystem"]').addEventListener("input", validateBIOSSystem);
                            document.querySelector('input[name="txtBootOrder"]').addEventListener("input", validateBootOrder);
                            document.querySelector('textarea[name="txtBIOSNotes"]').addEventListener("input", validateBIOSNotes);
                            
                        };

                    </script>
                    <!-- Modified Generative AI output. Reference: J, K, L - END -->

                    <script>
                        function validateManufacturer(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Manufacturer can only contain letters (A-Z, a-z) with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z]/g, "").substring(0, 50);
                            }
                        }

                        function validateType(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Type can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateOpticalDrive(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Optical Drive can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateFloppyDisk(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Floppy Disk can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateNetwork(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Network can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateModem(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Modem can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateFirewire(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Firewire can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateMediaCard(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Media Card can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateBattery(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/;

                            if (!regex.test(value)) {
                                alert("Battery can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateModel(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Model can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateSIMSlot(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("SIM Slot can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateBootOrder(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,100}$/;

                            if (!regex.test(value)) {
                                alert("Boot Order can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 100 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 100);
                            }
                        }

                        function validateVideoCard(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s\-]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Video Card can only contain letters (A-Z, a-z), numbers (0-9), spaces, and hyphens (-) with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s\-]/g, "").substring(0, 50); 
                            }
                        }

                        function validateUSB(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s\.]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("USB can only contain letters (A-Z, a-z), numbers (0-9), spaces, and dots (.) with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s\.]/g, "").substring(0, 50); 
                            }
                        }

                        function validateBIOSSystem(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,10}$/; 

                            if (!regex.test(value)) {
                                alert("BIOS System can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 10 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 10);
                            }
                        }

                        function validateBIOSKey(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,10}$/; 

                            if (!regex.test(value)) {
                                alert("BIOS Key can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 10 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 10);
                            }
                        }

                        function validateSerial(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 50) {
                                alert("Serial cannot exceed 50 characters.");
                                inputField.value = value.substring(0, 255); 
                            }
                        }

                        function validatePassword(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 255) {
                                alert("Password cannot exceed 255 characters.");
                                inputField.value = value.substring(0, 255); 
                            }
                        }

                        function validateOther(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 500) {
                                alert("Othere Notes cannot exceed 500 characters.");
                                inputField.value = value.substring(0, 500); 
                            }
                        }

                        function validateConditionNotes(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 500) {
                                alert("Condition Notes cannot exceed 500 characters.");
                                inputField.value = value.substring(0, 500); 
                            }
                        }

                        function validateBIOSNotes(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 500) {
                                alert("BIOS Notes cannot exceed 500 characters.");
                                inputField.value = value.substring(0, 500); 
                            }
                        }

                        function validateHDDReference(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s\-\/]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Hard Drive Reference can only contain letters (A-Z, a-z), numbers (0-9), spaces, hyphens (-), and forward slashes (/) with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s\-\/]/g, "").substring(0, 50); 
                            }
                        }

                        function validateHDDManufacturer(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Hard Drive Manufacturer can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateHDDModel(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Hard Drive Model can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateHDDType(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Hard Drive Type can only contain letters (A-Z, a-z) and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateHDDSize(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,20}$/; 

                            if (!regex.test(value)) {
                                alert("Hard Drive Size can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 20 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 20);
                            }
                        }

                        function validateHDDImagingMethod(event) {
                            const inputField = event.target;
                            const value = inputField.value;
                            const regex = /^[A-Za-z0-9\s]{0,50}$/; 

                            if (!regex.test(value)) {
                                alert("Hard Drive Imaging Method can only contain letters (A-Z, a-z), numbers (0-9), and spaces with a max of 50 characters.");
                                inputField.value = value.replace(/[^A-Za-z0-9\s]/g, "").substring(0, 50);
                            }
                        }

                        function validateHDDSerial(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 30) {
                                alert("Hard Drive Notes cannot exceed 30 characters.");
                                inputField.value = value.substring(0, 30); 
                            }
                        }

                        function validateHDDNotes(event) {
                            const inputField = event.target;
                            const value = inputField.value;

                            if (value.length > 500) {
                                alert("Hard Drive Notes cannot exceed 500 characters.");
                                inputField.value = value.substring(0, 500); 
                            }
                        }

                        document.getElementById('dynamicForm').addEventListener('submit', function() {
                            document.getElementById('hardDriveDetails').value = formCountHDD;
                            
                        });

                    </script>


        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>