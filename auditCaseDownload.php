<?php
include 'sqlConnection.php'; 
include 'timezoneFunction.php'; 
if(!isset($_SESSION['userId'])){
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

$query = "SELECT * FROM auditlog WHERE Identifier = ? AND EntryType IN ('Case', 'Exhibit')";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $identifier);  
$stmt->execute();
$result = $stmt->get_result();

function sanitizeFileName($string) {
    return preg_replace('/[\/\\\\:*?"<>|]/', '_', $string);
}

$safeCaseReference = sanitizeFileName($caseReference);

$temp_directory = "uploads/"; 

if (!is_dir($temp_directory)) {
    mkdir($temp_directory, 0777, true);
}

$csv_filename = $temp_directory . 'Exhibit Audit Log - ' . $safeCaseReference . '.csv';
$md5_filename = $temp_directory . 'Exhibit Audit LogHash  - ' . $safeCaseReference . '.txt';

if (mysqli_num_rows($result) > 0) {

    $output = fopen($csv_filename, "w");
    fputcsv($output, ['Case Audit Log']);
    fputcsv($output, ['Generated: ' . date("Y-m-d H:i:s")]);
    fputcsv($output, ['Case Reference: ' . $caseReference]);
    fputcsv($output, ['', '', 'Audit ID', 'Timestamp', 'Actioner Full Name', 'Actioner Username', 'Action']);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
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
                csvLink.download = "Exhibit Audit Log - ' . $safeCaseReference . '.csv";
                csvLink.click();

                // Trigger download of MD5+SHA1 hash file
                var hashLink = document.createElement("a");
                hashLink.href = "' . $md5_filename . '";
                hashLink.download = "Exhibit Audit Log Hash - ' . $safeCaseReference . '.txt";
                hashLink.click();

                // Redirect to the previous page after a delay
                setTimeout(function() {
                    window.location.href = "auditCase.php?identifier=' . $identifier . '";
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
    header('Location: auditCase.php?identifier=' . $identifier);
}
?>
