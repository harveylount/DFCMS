<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection
$evidenceID = intval($_GET['EvidenceID']);  // Sanitize the input to prevent SQL injection

include 'checkUserAddedToCaseFunction.php'; 

$sql = "SELECT CaseReference, ExhibitRef FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $identifier, $evidenceID);
$stmt->execute();
$stmt->bind_result($caseReference, $exhibitReference);
$stmt->fetch();
mysqli_stmt_close($stmt);

function sanitizeFileName($string) {
    return preg_replace('/[\/\\\\:*?"<>|]/', '_', $string);
}

$safeCaseReference = sanitizeFileName($caseReference);
$safeExhibitReference = sanitizeFileName($exhibitReference);

$query = "SELECT * FROM auditlog WHERE Identifier = ? AND EvidenceID = ? AND EntryType IN ('Exhibit')";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $identifier, $evidenceID);  
$stmt->execute();
$result = $stmt->get_result();

// Define the directory to store files temporarily
$temp_directory = "uploads/";  // Make sure this directory exists and is writable

// Ensure the directory exists
if (!is_dir($temp_directory)) {
    mkdir($temp_directory, 0777, true);
}

// Prepare file names
$csv_filename = $temp_directory . 'Exhibit Audit Log - ' . $safeCaseReference . ' - ' . $safeExhibitReference . '.csv';
$md5_filename = $temp_directory . 'Exhibit Audit LogHash  - ' . $safeCaseReference . ' - ' . $safeExhibitReference . '.txt';

if (mysqli_num_rows($result) > 0) {

    $output = fopen($csv_filename, "w");
    fputcsv($output, ['Case Audit Log']);
    fputcsv($output, ['Generated: ' . date("Y-m-d H:i:s")]);
    fputcsv($output, ['Case Reference: ' . $caseReference]);
    fputcsv($output, ['Exhibit Reference: ' . $exhibitReference]);
    fputcsv($output, ['', '', '', 'Audit ID', 'Timestamp', 'Actioner Full Name', 'Actioner Username', 'Action']);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            '',
            '',
            '',
            $row['AuditID'],
            $row['Timestamp'],
            $row['ActionerFullName'],
            $row['ActionerUsername'],
            $row['Action']
        ]);
    }

    fclose($output);

    $csv_data = file_get_contents($csv_filename);
    $md5_hash = md5($csv_data);
    $sha1_hash = sha1($csv_data);

    $hash_content = "MD5 Hash:  " . $md5_hash . PHP_EOL .
                    "SHA1 Hash: " . $sha1_hash;

    file_put_contents($md5_filename, $hash_content);

    // Trigger JavaScript to download both files
    echo '
    <html>
    <head>
        <script type="text/javascript">
            function triggerDownloads() {
                // Trigger download of CSV file
                var csvLink = document.createElement("a");
                csvLink.href = "' . $csv_filename . '";
                csvLink.download = "Exhibit Audit Log - ' . $safeCaseReference . ' - ' . $safeExhibitReference . '.csv";
                csvLink.click();

                // Trigger download of MD5+SHA1 hash file
                var hashLink = document.createElement("a");
                hashLink.href = "' . $md5_filename . '";
                hashLink.download = "Exhibit Audit Log Hash - ' . $safeCaseReference . ' - ' . $safeExhibitReference .'.txt";
                hashLink.click();

                // Redirect to the previous page after a delay
                setTimeout(function() {
                    window.location.href = "auditExhibit.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID . '";
                }, 2000); // Adjust delay if needed
            }
            window.onload = triggerDownloads;
        </script>
    </head>
    <body>
        <p>Your files are being prepared for download...</p>
    </body>
    </html>';

    exit;
} else {
    header('Location: auditExhibit.php?identifier=' . $identifier . '&EvidenceID=' . $evidenceID);
}
?>
