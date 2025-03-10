<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  
$evidenceID = intval($_GET['EvidenceID']);  

$sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ?  AND EvidenceID = ? ";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

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

            <form method="post" action="createLBU06Insert.php?identifier=<?php echo "$identifier"?>">
                <fieldset class="field-set width">
                    <legend>Enter Computer Exhibit Details</legend>

                    <br><b>Case Reference:</b> <?php echo $caseReference;?> <br><br>
                    <b>Exhibit Reference:</b> <?php echo $exhibitReference;?> <br><br>

                    <h3>Details</h3>

                    <!-- Manufacturer field -->
                    <label for="txtManufacturer">Manufacturer: *</label><br />
                    <input type="text" name="txtManufacturer" size="32" required/><br /><br />

                    <!-- Model field -->
                    <label for="txtModel">Model: *</label><br />
                    <input type="text" name="txtModel" size="32" required/><br /><br />
                    
                    <!-- Serial Number field -->
                    <label for="txtSerialNumber">Serial Number: *</label><br />
                    <input type="text" name="txtSerialNumber" size="32" required/><br /><br />

                    <!-- Type field -->
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
                    <input type="text" name="txtBIOSDate" size="32" required/><br /><br />
    
                    <label for="txtBIOSTime">BIOS Time: *</label><br />
                    <input type="text" name="txtBIOSTime" size="32" required/><br /><br />

                    <label for="txtActualDate">Actual Date: *</label><br />
                    <input type="text" name="txtActualDate" size="32" required/><br /><br />
    
                    <label for="txtActualTime">Actual Time: *</label><br />
                    <input type="text" name="txtActualTime" size="32" required/><br /><br />

                    <label for="txtBIOSNotes">BIOS Notes:</label><br />
                    <textarea name="txtBIOSNotes" class="condition-notes-input"></textarea><br /><br />

                    <h3>Hard Drive Details</h3>


                    <!-- Modified Generative AI output. Reference: J, K, L - START -->
                    <!-- Form sets HDD -->
                    <div id="formSetsContainerHDD">

                    </div>
                    <button type="button" onclick="addFormSetHDD()">Add More</button>

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

                            // Append the new form set to the container
                            document.getElementById('formSetsContainerHDD').appendChild(newFormSetHDD);
                        }

                        // Remove a form set
                        function removeFormSetHDD(setIdHDD) {
                            const formSetHDD = document.getElementById('formSetHDD_' + setIdHDD);
                            formSetHDD.remove();
                        }

                    </script>
                    <!-- Modified Generative AI output. Reference: J, K, L - END -->


        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>