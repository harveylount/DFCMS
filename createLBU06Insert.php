<?php
include 'SqlConnection.php';

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

if (isset($_POST['subEvent'])) {

    $sql = "SELECT CaseReference FROM evidence WHERE Identifier = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->bind_result($caseReference);
    $stmt->fetch();
    mysqli_stmt_close($stmt);

    $othersOnScene=$_POST['othersOnScene'];
    $disclosureOfEvidence=$_POST['disclosureOfEvidence'];

    $socoName = $_SESSION['fullName'];
    $socoUsername = $_SESSION['userId'];
    $socoNumber = $_SESSION['socoNumber'];
    $dateSceneExaminedDatabase = $_SESSION['dateExaminedDatabaseLBU06'];
    $timestampArrived = $_SESSION['timestampInDatabaseLBU06'];
    $timestampConcluded = date('Y-m-d H:i:s');

    $typeOfCrime = $_POST['txtTypeOfCrime'];
    $locationOfCrime = $_POST['txtLocationOfCrime'];
    $notes = $_POST['txtExaminationNotes'];

    // Evidence collected
    $numberOfScenePhotos = $_POST['txtPhotosOfSceneNumber'];
    $locationOfScenePhotos = $_POST['txtPhotosOfSceneLocation'];
    $numberOfSceneSketches = $_POST['txtSketchesOfSceneNumber'];
    $locationOfSceneSketches = $_POST['txtSketchesOfSceneLocation'];
    $numberOfItemsForExamination = $_POST['txtItemsNumber'];

    //Others on the scene
    $counterOthers = 1;
    $othersOnSceneArray = []; // Initialize an empty array
    $othersTimestampInArray = [];
    $othersTimestampOutArray = [];

    if ($othersOnScene > 0) {
        while ($counterOthers <= $othersOnScene) { 
            // Check if the input exists in $_POST before accessing it
            if (isset($_POST['txtOthers_' . $counterOthers])) {
                $othersOnSceneArray[] = $_POST['txtOthers_' . $counterOthers]; // Append value to array
            } 
            if (isset($_POST['dateTimeIn_' . $counterOthers])) {
                $othersTimeIn = $_POST['dateTimeIn_' . $counterOthers];
                $str_replaceTimeIn = str_replace("T", " ", $othersTimeIn);
                $othersTimestampInArray[] = $str_replaceTimeIn;
            } 
            if (isset($_POST['dateTimeOut_' . $counterOthers])) {
                $othersTimeOut = $_POST['dateTimeOut_' . $counterOthers];
                $str_replaceTimeOut = str_replace("T", " ", $othersTimeOut);
                $othersTimestampOutArray[] = $str_replaceTimeOut;
            } 
            
            $counterOthers++;
        }
    }

    $othersOnSceneString = serialize($othersOnSceneArray);
    $othersTimestampInString = serialize($othersTimestampInArray);
    $othersTimestampOutString = serialize($othersTimestampOutArray);

    // Disclosure of evidence
    $counterDisclosure = 1;
    $disclosureExhibitNumberArray = [];
    $disclosureEvidenceSeizedArray = [];
    $disclosureHandedSentByArray = [];
    $disclosureToPersonLocationArray = [];
    $disclosureTimestampArray = [];
    
    if ($disclosureOfEvidence > 0) {
        while ($counterDisclosure <= $disclosureOfEvidence) { 
            // Check if the input exists in $_POST before accessing it
            if (isset($_POST['txtExhibit_' . $counterDisclosure])) {
                $disclosureExhibitNumberArray[] = $_POST['txtExhibit_' . $counterDisclosure]; // Append value to array
            } 
            if (isset($_POST['txtEvidenceSeized_' . $counterDisclosure])) {
                $disclosureEvidenceSeizedArray[] = $_POST['txtEvidenceSeized_' . $counterDisclosure]; // Append value to array
            } 
            if (isset($_POST['txtHandedSentBy_' . $counterDisclosure])) {
                $disclosureHandedSentByArray[] = $_POST['txtHandedSentBy_' . $counterDisclosure]; // Append value to array
            } 
            if (isset($_POST['txtToPersonOrLocation_' . $counterDisclosure])) {
                $disclosureToPersonLocationArray[] = $_POST['txtToPersonOrLocation_' . $counterDisclosure]; // Append value to array
            } 
            if (isset($_POST['timestampHiddenEvidenceDatabase_' . $counterDisclosure])) {
                $disclosureTimestampArray[] = $_POST['timestampHiddenEvidenceDatabase_' . $counterDisclosure]; // Append value to array
            } 
            
            $counterDisclosure++;
        }
    }

    
    $disclosureExhibitNumberString = serialize($disclosureExhibitNumberArray);
    $disclosureEvidenceSeizedString = serialize($disclosureEvidenceSeizedArray);
    $disclosureHandedSentByString = serialize($disclosureHandedSentByArray);
    $disclosureToPersonLocationString = serialize($disclosureToPersonLocationArray);
    $disclosureTimestampString = serialize($disclosureTimestampArray);

    $signatureDataSoco = $_POST['signature_data_soco'];

    $query = "INSERT INTO lbu06
    (Identifier, CaseReference, SocoName, SocoUsername, SocoNumber, DateSceneExamined, SceneArriveTime, SceneConcluded, OthersOnScene, 
    OthersTimeIn, OthersTimeOut, TypeOfCrime, LocationOfCrime, ExaminationNotes, NumberOfPhotos, LocationOfPhotos, NumberOfSketches,
    LocationOfSketches, NumberOfItems, DisclosureExhibit, EvidenceSeized, HandedSentBy, ToPersonOrLocation, DisclosureTimestamp, SocoSig) 
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the parameters
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssss", $identifier, $caseReference, $socoName, $socoUsername, $socoNumber, $dateSceneExaminedDatabase,
    $timestampArrived, $timestampConcluded, $othersOnSceneString, $othersTimestampInString, $othersTimestampOutString, $typeOfCrime, $locationOfCrime, $notes, $numberOfScenePhotos, $locationOfScenePhotos,
    $numberOfSceneSketches, $locationOfSceneSketches, $numberOfItemsForExamination, $disclosureExhibitNumberString, $disclosureEvidenceSeizedString, $disclosureHandedSentByString, $disclosureToPersonLocationString,
    $disclosureTimestampString, $signatureDataSoco);

    
    // Manually construct the SQL query for debugging
$sql_debug = sprintf(
    "INSERT INTO your_table_name (
        Identifier, CaseReference, SocoName, SocoUsername, SocoNumber, DateSceneExamined,
        SceneArriveTime, SceneConcluded, OthersOnScene, OthersTimeIn, OthersTimeOut,
        TypeOfCrime, LocationOfCrime, ExaminationNotes, NumberOfPhotos, LocationOfPhotos,
        NumberOfSketches, LocationOfSketches, NumberOfItems, DisclosureExhibit,
        EvidenceSeized, HandedSentBy, ToPersonOrLocation, DisclosureTimestamp, SocoSig
    ) VALUES (
        '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s'
    );",
    $identifier, $caseReference, $socoName, $socoUsername, $socoNumber, $dateSceneExaminedDatabase,
    $timestampArrived, $timestampConcluded, $othersOnSceneString, $othersTimestampInString, $othersTimestampOutString,
    $typeOfCrime, $locationOfCrime, $notes, $numberOfScenePhotos, $locationOfScenePhotos,
    $numberOfSceneSketches, $locationOfSceneSketches, $numberOfItemsForExamination, $disclosureExhibitNumberString,
    $disclosureEvidenceSeizedString, $disclosureHandedSentByString, $disclosureToPersonLocationString,
    $disclosureTimestampString, $signatureDataSoco
);

// Print the query
echo "SQL Query:\n" . $sql_debug;



    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);



    var_dump($othersOnSceneArray);
    var_dump($othersTimestampInArray);
    var_dump($othersTimestampOutArray);
    var_dump($othersOnSceneArray);
    var_dump($disclosureExhibitNumberArray);
    var_dump($disclosureEvidenceSeizedArray);
    var_dump($disclosureHandedSentByArray);
    var_dump($disclosureTimestampArray);
    exit();
    





    echo ($othersOnScene);
    echo ($disclosureOfEvidence);
}
?>