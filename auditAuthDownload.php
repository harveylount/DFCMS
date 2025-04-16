<?php
include 'sqlConnection.php'; 

if (!isset($_SESSION['userId'])) {
    header('Location: loginForm.php');
    exit;
}

$query = "SELECT * FROM auditlog WHERE EntryType IN ('Auth')";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$temp_directory = "uploads/";  // Make sure this directory exists and is writable

if (!is_dir($temp_directory)) {
    mkdir($temp_directory, 0777, true);
}

$csv_filename = $temp_directory . 'Authentication Audit Log.csv';
$md5_filename = $temp_directory . 'Authentication Audit Log Hash.txt';

if (mysqli_num_rows($result) > 0) {

    $output = fopen($csv_filename, "w");
    fputcsv($output, ['Authentication Audit Log']);
    fputcsv($output, ['Generated: ' . date("Y-m-d H:i:s")]);
    fputcsv($output, ['', 'Audit ID', 'Timestamp', 'Actioner Full Name', 'Actioner Username', 'Action']);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
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
                csvLink.download = "Authentication Audit Log.csv";
                csvLink.click();

                // Trigger download of MD5+SHA1 hash file
                var hashLink = document.createElement("a");
                hashLink.href = "' . $md5_filename . '";
                hashLink.download = "Authentication Audit Log Hash.txt";
                hashLink.click();

                // Redirect to the previous page after a delay
                setTimeout(function() {
                    window.location.href = "auditAuth.php";
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
    header('Location: auditAuth.php');
    exit;
}
?>
