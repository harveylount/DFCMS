<!DOCTYPE html>
<html>
<?php
include 'SqlConnection.php';
include 'timezoneFunction.php'; 

if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($caseReference);
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

    <title>Create LBU06</title>

    <style>
        .notes-input {
            height: 300px;
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
            <a href="<?php echo "viewCase.php?identifier=$identifier" ?>" id="navcase-button">Case Overview</a>
            <a href="<?php echo "viewEvidence.php?identifier=$identifier" ?>" id="navcase-button">Evidence</a>
            <a href="<?php echo "viewCrimeSceneReports.php?identifier=$identifier" ?>" id="navcase-button">Crime Scene Reports</a>
            <a href="<?php echo "viewCaseNotes.php?identifier=$identifier" ?>" id="navcase-button">Case Notes</a>
            <?php include 'displayCaseAdminButtonFunction.php'; ?>
        </div>

        <section id="content">

            <h2><?php echo $caseReference; ?> - Create a Crime Scene Report (LBU06)</h2>

            <!-- Includes Generative AI. Reference: J, K, L - START -->
            <?php
                // Get the current date and time in the required format
                $currentDateTime = date("Y-m-d\TH:i:s", time());  // Current date and time
                
                $maxDateTime = date("Y-m-d\TH:i:s", strtotime('+24 hours'));  // Max date (24 hours from now)
            ?>
            <!-- Includes Generative AI. Reference: J, K, L - END -->

            <form method="post" action="createLBU06Insert.php?identifier=<?php echo "$identifier"?>" id="dynamicForm" onsubmit="return validateFormSignature()">
                <fieldset class="field-set width">
                    <legend>Enter Crime Scene Details</legend>

                    <br><b>SOCO Name:</b> <?php echo $_SESSION['fullName'];?> <br><br>
                    <b>SOCO Number:</b> <?php echo $_SESSION['socoNumber'];?> <br><br>
                    <b>Case Reference:</b> <?php echo $caseReference; ?> <br><br>
                    <b>Date Scene Examined:</b> <?php echo $_SESSION['dateExaminedDisplayLBU06']; ?> <br><br>
                    <br><br>
                    <b>Timestamp Arrived:</b> <?php echo $_SESSION['timestampInDisplayLBU06']; ?> <br><br>
                    <b>Timestamp Concluded:</b> Timestamped on submission <br><br>

                    <br><br>

                    <h3>Others on the scene</h3>

                    <!-- Form sets container -->
                    <div id="formSetsContainer">
                        
                    </div>

                    <button type="button" onclick="addFormSet()">Add More</button>

                    <br /><br /><br /><br />

                    <!-- Type of Crime field -->
                    <label for="txtTypeOfCrime">Type of Crime: *</label><br />
                    <input type="text" name="txtTypeOfCrime" size="32" required/><br /><br />

                    <!-- Location of Crime field -->
                    <label for="txtLocationOfCrime">Location of Crime: *</label><br />
                    <input type="text" name="txtLocationOfCrime" size="32" required/><br /><br />

                    <!-- Examination notes field -->
                    <label for="txtExaminationNotes">Examination Notes: *</label><br />
                    <textarea name="txtExaminationNotes" class="notes-input" required></textarea><br /><br />

                    </br></br>
                    <h3>Evidence Collected</h3>

                    <!-- Number of scene photographs field -->
                    <label for="txtPhotosOfSceneNumber">Number of Scene Photographs: *</label><br />
                    <input type="text" name="txtPhotosOfSceneNumber" size="32" required/><br /><br />

                    <!-- Location of scene photographs field -->
                    <label for="txtPhotosOfSceneLocation">Location of Scene Photographs: *</label><br />
                    <input type="text" name="txtPhotosOfSceneLocation" size="32" required/><br /><br />

                    <!-- Number of scene sketchs field -->
                    <label for="txtSketchesOfSceneNumber">Number of Scene Sketches: *</label><br />
                    <input type="text" name="txtSketchesOfSceneNumber" size="32" required/><br /><br />

                    <!-- Location of scene sketches field -->
                    <label for="txtSketchesOfSceneLocation">Location of Scene Sketches: *</label><br />
                    <input type="text" name="txtSketchesOfSceneLocation" size="32" required/><br /><br />

                    <!-- Number of Examination Items field -->
                    <label for="txtItemsNumber">Number of Items for Forensics Examination: *</label><br />
                    <input type="text" name="txtItemsNumber" size="32" required/> <br /><br />

                    </br></br>
                    <h3>Disclosure of Evidence</h3>

                    <!-- Modified Generative AI output. Reference: J, K, L - START -->
                    <!-- Form sets container -->
                    <div id="formSetsContainerEvidence">

                    </div>

                    <button type="button" onclick="addFormSetEvidence()">Add More</button>
                    <!-- Modified Generative AI output. Reference: J, K, L - END -->

                    </br></br>
                    </br></br>
                    <h3>Signature</h3>
                    <?php include 'signatureLBU06Script.php'; ?>
                    
                    
                    
                    
                    <input type="hidden" id="othersOnScene" name="othersOnScene" value="">
                    <input type="hidden" id="disclosureOfEvidence" name="disclosureOfEvidence" value="">
                    <br /><br />
                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />
                </fieldset>
            </form>
            

            <!-- Includes Generative AI. Reference: J, K, L - START -->
            <script>
                // Pass PHP values to JavaScript
                const currentDateTime = "<?php echo $currentDateTime; ?>";
                const maxDateTime = "<?php echo $maxDateTime; ?>";

                let formCount = 0; // Track the number of form sets

                // Function to ensure that Time In and Time Out are correctly set
                function setupTimeValidation() {
                    document.querySelectorAll('.formSet').forEach(function(formSet) {
                        const timeInElement = formSet.querySelector('input[name^="dateTimeIn"]');
                        const timeOutElement = formSet.querySelector('input[name^="dateTimeOut"]');

                        // Set min time for Time In
                        timeInElement.setAttribute('min', currentDateTime);

                        // Ensure Time Out can't be before Time In
                        timeInElement.addEventListener('input', function () {
                            const timeInValue = this.value;

                            // If Time In is set to a time earlier than the current time, reset to current time
                            if (new Date(timeInValue) < new Date(currentDateTime)) {
                                alert("Time In cannot be set in the past.");
                                this.value = currentDateTime;  // Reset to current date and time
                            }

                            // Ensure Time Out's min is updated to Time In value
                            timeOutElement.setAttribute('min', timeInValue);
                        });

                        // Ensure Time Out is not set before Time In
                        timeOutElement.addEventListener('input', function () {
                            const timeInValue = timeInElement.value;
                            const timeOutValue = this.value;

                            // If Time Out is set earlier than Time In, reset Time Out to Time In
                            if (new Date(timeOutValue) < new Date(timeInValue)) {
                                alert("Time Out cannot be earlier than Time In.");
                                this.value = timeInValue;  // Reset to Time In value
                            }
                        });
                    });
                }

                // Includes Generative AI. Reference: O - START
                // Function to validate "Others" field
                function validateOthersInput(event) {
                    const inputField = event.target;
                    const value = inputField.value;
                    const regex = /^[A-Za-z]{0,50}$/; // Allows only letters (A-Z, a-z) and max 50 chars

                    if (!regex.test(value)) {
                        alert("The 'Others' field can only contain letters (A-Z, a-z) and must be 50 characters or less.");
                        inputField.value = value.replace(/[^A-Za-z]/g, "").substring(0, 50); // Remove invalid chars & truncate
                    }
                }

                // Function to validate form before submission
                function validateFormOthers(event) {
                    let isValid = true;
                    
                    document.querySelectorAll('input[name^="txtOthers"]').forEach(input => {
                        const value = input.value;
                        const regex = /^[A-Za-z]{1,50}$/; // Ensure only letters and max 50 chars

                        if (!regex.test(value)) {
                            isValid = false;
                            alert("Each 'Others' field must contain only letters (A-Z, a-z) and be 50 characters or less.");
                        }
                    });

                    if (!isValid) {
                        event.preventDefault(); // Stop form submission
                    }
                }
                // Includes Generative AI. Reference: O - END

                // Add new form set
                function addFormSet() {
                    formCount++;

                    // Create a new set of fields
                    const newFormSet = document.createElement('div');
                    newFormSet.classList.add('formSet');
                    newFormSet.id = 'formSet_' + formCount;
                    newFormSet.innerHTML = `
                        <label for="txtOthers_${formCount}">Others: *</label><br />
                        <input type="text" id="txtOthers_${formCount}" name="txtOthers_${formCount}" size="32" required/><br /><br />

                        <label for="dateTimeIn_${formCount}">Time In:</label><br />
                        <input type="datetime-local" id="dateTimeIn_${formCount}" name="dateTimeIn_${formCount}" 
                            min="${currentDateTime}" max="${maxDateTime}" required><br /><br />

                        <label for="dateTimeOut_${formCount}">Time Out:</label><br />
                        <input type="datetime-local" id="dateTimeOut_${formCount}" name="dateTimeOut_${formCount}" 
                            min="${currentDateTime}" max="${maxDateTime}" required><br /><br />

                        <button type="button" onclick="removeFormSet(${formCount})">Remove</button><br /><br />
                    `;

                    // Append the new form set to the container
                    document.getElementById('formSetsContainer').appendChild(newFormSet);

                    // Attach event listener to the "Others" input field
                    document.getElementById(`txtOthers_${formCount}`).addEventListener("input", validateOthersInput);

                    // Initialize Time In and Time Out validation for the new form set
                    setupTimeValidation();
                }

                // Includes Generative AI. Reference: O - START
                // Validate existing "Others" field on page load
                window.onload = function() {
                    setupTimeValidation();
                    document.querySelectorAll('input[name^="txtOthers"]').forEach(input => {
                        input.addEventListener("input", validateOthersInput);
                    });
                }
                // Includes Generative AI. Reference: O - END

                // Remove a form set
                function removeFormSet(setId) {
                    const formSet = document.getElementById('formSet_' + setId);
                    formSet.remove();
                }

                // Initialize Time Validation on page load (first form entry and any dynamic ones)
                window.onload = function() {
                    setupTimeValidation();
                };
            </script>
            <!-- Includes Generative AI. Reference: J, K, L - END -->



        

            <!-- Modified Generative AI output. Reference: J, K, L - START -->
            <script>
                let formCountEvidence = 0; // Track the number of form sets

                function formatTimestampDatabase() {
                    const date = new Date();
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');

                    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                }

                function formatTimestampDisplay() {
                    const date = new Date();
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');

                    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
                }

                // Includes Generative AI output. Reference: Q - START
                // Function to validate Exhibit Number (A-Z, a-z, 0-9, /, max 10 characters)
                function validateExhibitNumberInput(event) {
                    const inputField = event.target;
                    const value = inputField.value;
                    const regex = /^[A-Za-z0-9/]{0,10}$/; // Allows letters, numbers, and '/' up to 10 characters

                    if (!regex.test(value)) {
                        alert("Exhibit Number can only contain letters (A-Z, a-z), numbers (0-9), and '/' with a max of 10 characters.");
                        inputField.value = value.replace(/[^A-Za-z0-9/]/g, "").substring(0, 10); // Remove invalid characters and truncate
                    }
                }

                // Function to validate general text fields (A-Z, a-z, max 50 characters)
                function validateTextInput(event) {
                    const inputField = event.target;
                    const value = inputField.value;
                    const regex = /^[A-Za-z ]{0,50}$/; // Allows only letters and spaces, max 50 chars

                    if (!regex.test(value)) {
                        alert("This field can only contain letters (A-Z, a-z) and must be 50 characters or less.");
                        inputField.value = value.replace(/[^A-Za-z ]/g, "").substring(0, 50); // Remove invalid characters and truncate
                    }
                }

                // Function to validate the form before submission
                function validateFormDisclosure(event) {
                    let isValid = true;

                    // Validate Exhibit Number fields
                    document.querySelectorAll('input[name^="txtExhibit"]').forEach(input => {
                        const value = input.value;
                        const regex = /^[A-Za-z0-9/]{1,10}$/; // Only letters, numbers, and '/' with max 10 characters

                        if (!regex.test(value)) {
                            isValid = false;
                            alert("Exhibit Number must contain only letters (A-Z, a-z), numbers (0-9), and '/' with a max of 10 characters.");
                        }
                    });

                    // Validate other text fields (A-Z, a-z, max 50 characters)
                    document.querySelectorAll('input[name^="txtEvidenceSeized"], input[name^="txtHandedSentBy"], input[name^="txtToPersonOrLocation"]').forEach(input => {
                        const value = input.value;
                        const regex = /^[A-Za-z ]{1,50}$/; // Only letters and spaces, max 50 characters

                        if (!regex.test(value)) {
                            isValid = false;
                            alert("All text fields must contain only letters (A-Z, a-z) and be 50 characters or less.");
                        }
                    });

                    if (!isValid) {
                        event.preventDefault(); // Stop form submission if any field is invalid
                    }
                }
                // Includes Generative AI output. Reference: Q - END

                // Add new form set
                function addFormSetEvidence() {
                    formCountEvidence++;

                    // Create a new set of fields
                    const newFormSetEvidence = document.createElement('div');
                    newFormSetEvidence.classList.add('formSetEvidence');
                    newFormSetEvidence.id = 'formSetEvidence_' + formCountEvidence;

                    const timestampDatabase = formatTimestampDatabase();
                    const timestampDisplay = formatTimestampDisplay();

                    // Create the formset content, including the timestamp as a visible label and hidden input
                    newFormSetEvidence.innerHTML = `
                        <label for="txtExhibit_${formCountEvidence}">Exhibit Number: *</label><br />
                        <input type="text" name="txtExhibit_${formCountEvidence}" size="32" required/><br /><br />

                        <label for="txtEvidenceSeized_${formCountEvidence}">Evidence Seized: *</label><br />
                        <input type="text" name="txtEvidenceSeized_${formCountEvidence}" size="32" required/><br /><br />

                        <label for="txtHandedSentBy_${formCountEvidence}">Handed / Sent by: *</label><br />
                        <input type="text" name="txtHandedSentBy_${formCountEvidence}" size="32" required/><br /><br />

                        <label for="txtToPersonOrLocation_${formCountEvidence}">To Person or Location: *</label><br />
                        <input type="text" name="txtToPersonOrLocation_${formCountEvidence}" size="32" required/><br /><br />

                        <!-- Display timestamp inside the formset -->
                        <label for="timestampDisplay_${formCountEvidence}">Timestamp:</label>
                        <span id="timestampDisplay_${formCountEvidence}">${timestampDisplay}</span><br /><br />

                        <!-- Hidden input to store timestamp -->
                        <input type="hidden" name="timestampHiddenEvidenceDatabase_${formCountEvidence}" value="${timestampDatabase}" />

                        <button type="button" onclick="removeFormSetEvidence(${formCountEvidence})">Remove</button><br /><br />
                    `;

                    // Attach real-time validation to the new form set inputs
                    newFormSetEvidence.querySelector(`input[name="txtExhibit_${formCountEvidence}"]`).addEventListener("input", validateExhibitNumberInput);
                    newFormSetEvidence.querySelector(`input[name="txtEvidenceSeized_${formCountEvidence}"]`).addEventListener("input", validateTextInput);
                    newFormSetEvidence.querySelector(`input[name="txtHandedSentBy_${formCountEvidence}"]`).addEventListener("input", validateTextInput);
                    newFormSetEvidence.querySelector(`input[name="txtToPersonOrLocation_${formCountEvidence}"]`).addEventListener("input", validateTextInput);

                    // Append the new form set to the container
                    document.getElementById('formSetsContainerEvidence').appendChild(newFormSetEvidence);
                }

                // Remove a form set
                function removeFormSetEvidence(setIdEvidence) {
                    const formSetEvidence = document.getElementById('formSetEvidence_' + setIdEvidence);
                    formSetEvidence.remove();
                }

                // Includes Generative AI output. Reference: Q - START
                // Attach validation to form submission on page load
                window.onload = function () {
                    // Attach the form validation to the form's submit event
                    document.getElementById("dynamicForm").addEventListener("submit", validateFormDisclosure);

                    // Attach real-time validation to existing form fields
                    document.querySelectorAll('input[name^="txtExhibit"]').forEach(input => {
                        input.addEventListener("input", validateExhibitNumberInput);
                    });

                    document.querySelectorAll('input[name^="txtEvidenceSeized"], input[name^="txtHandedSentBy"], input[name^="txtToPersonOrLocation"]').forEach(input => {
                        input.addEventListener("input", validateTextInput);
                    });
                };
                // Includes Generative AI output. Reference: Q - END
            </script>

            

            <!-- Modified Generative AI output. Reference: J, K, L - END -->

            <script>
                // Includes Generative AI. Reference: P - START
                // Function to validate text fields (only A-Z, a-z, max 50 characters)
                function validateTextInput(event) {
                    const inputField = event.target;
                    const value = inputField.value;
                    const regex = /^[A-Za-z ]{0,50}$/; // Allows letters (A-Z, a-z) and spaces, max 50 chars

                    if (!regex.test(value)) {
                        alert("This field can only contain letters (A-Z, a-z) and must be 50 characters or less.");
                        inputField.value = value.replace(/[^A-Za-z ]/g, "").substring(0, 50);
                    }
                }

                // Function to validate the textarea (max 500 characters)
                function validateTextareaInput(event) {
                    const inputField = event.target;
                    if (inputField.value.length > 500) {
                        alert("Examination Notes cannot exceed 500 characters.");
                        inputField.value = inputField.value.substring(0, 500);
                    }
                }

                // Function to validate numeric fields (only 0-9, max 3 characters)
                function validateNumberInput(event) {
                    const inputField = event.target;
                    const value = inputField.value;
                    const regex = /^[0-9]{0,3}$/; // Allows only numbers, max 3 digits

                    if (!regex.test(value)) {
                        alert("This field can only contain numbers (0-9) and must be 3 digits or less.");
                        inputField.value = value.replace(/[^0-9]/g, "").substring(0, 3);
                    }
                }

                // Function to validate the form before submission
                function validateFormDefault(event) {
                    let isValid = true;

                    // Validate text fields (A-Z, a-z, max 50 characters)
                    document.querySelectorAll('input[name="txtTypeOfCrime"], input[name="txtLocationOfCrime"], input[name="txtPhotosOfSceneLocation"], input[name="txtSketchesOfSceneLocation"]').forEach(input => {
                        const value = input.value;
                        const regex = /^[A-Za-z ]{1,50}$/; // Only letters and spaces, max 50 characters

                        if (!regex.test(value)) {
                            isValid = false;
                            alert("All text fields must contain only letters (A-Z, a-z) and be 50 characters or less.");
                        }
                    });

                    // Validate the textarea (max 500 characters)
                    const notesField = document.querySelector('textarea[name="txtExaminationNotes"]');
                    if (notesField.value.length > 500) {
                        isValid = false;
                        alert("Examination Notes cannot exceed 500 characters.");
                    }

                    // Validate numeric fields (0-9, max 3 characters)
                    document.querySelectorAll('input[name="txtPhotosOfSceneNumber"], input[name="txtSketchesOfSceneNumber"], input[name="txtItemsNumber"]').forEach(input => {
                        const value = input.value;
                        const regex = /^[0-9]{1,3}$/; // Only numbers, max 3 characters

                        if (!regex.test(value)) {
                            isValid = false;
                            alert("All number fields must contain only digits (0-9) and be 3 characters or less.");
                        }
                    });

                    if (!isValid) {
                        event.preventDefault(); // Stop form submission
                    }
                }

                // Attach validation to input fields on page load
                window.onload = function () {
                    // Add real-time validation to text fields (A-Z, a-z, max 50 characters)
                    document.querySelectorAll('input[name="txtTypeOfCrime"], input[name="txtLocationOfCrime"], input[name="txtPhotosOfSceneLocation"], input[name="txtSketchesOfSceneLocation"]').forEach(input => {
                        input.addEventListener("input", validateTextInput);
                    });

                    // Add real-time validation to textarea (max 500 characters)
                    document.querySelector('textarea[name="txtExaminationNotes"]').addEventListener("input", validateTextareaInput);

                    // Add real-time validation to numeric fields (0-9, max 3 characters)
                    document.querySelectorAll('input[name="txtPhotosOfSceneNumber"], input[name="txtSketchesOfSceneNumber"], input[name="txtItemsNumber"]').forEach(input => {
                        input.addEventListener("input", validateNumberInput);
                    });

                    // Attach form validation to the form submission
                    document.getElementById("dynamicForm").addEventListener("submit", validateFormDefault);
                };

                // Includes Generative AI. Reference: P - END

                document.getElementById('dynamicForm').addEventListener('submit', function() {
                    document.getElementById('othersOnScene').value = formCount;
                    document.getElementById('disclosureOfEvidence').value = formCountEvidence;
                });
            </script>


            


    

            



        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>