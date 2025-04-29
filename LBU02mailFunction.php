<?php
include 'checkUserAddedToCaseFunction.php'; 

require_once('vendor/autoload.php');

$pdf = new TCPDF();

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('DFCMS');
$pdf->SetTitle('LBU02 - Exhibit Dispatch Form (' . $exhibitReference . ')');

$pdf->SetHeaderData('', 0, 'Exhibit Number: ' . $exhibitReference, 'Case: ' . $caseReference);

$pdf->SetMargins(15, 20, 15);  // Left, Top, Right
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

$pdf->SetAutoPageBreak(TRUE, 25);

$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);

if ($externalBoolean === "true") {

    $html = '<table cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;">'; 
    $html .= '<tr><td rowspan="2" style="font-size: 30px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;">DFCMS</td> 
        <td style="text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 12px;">' . "LBU02 - Exhibit Dispatch Form" . '</td></tr>'; 
    $html .= '<tr><td style="text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 12px;">Page '.$pdf->getAliasNumPage().' of '. $pdf->getAliasNbPages(). '</td></tr>';
    $html .= '</table>';
    $html .= '<br/>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(0.5);

    $html = '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">External Email Recipient</td><td style="border: 1px solid black; padding: 5px;">' . $dispatchedByEmail . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Timestamp Sent</td><td style="border: 1px solid black; padding: 5px;">' . $emailTimestamp . '</td></tr>';
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(0.5);
    
    $html = '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Case Reference</td><td style="border: 1px solid black; padding: 5px;">' . $caseReference . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Location</td><td style="border: 1px solid black; padding: 5px;">' . $location . '</td></tr>';
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(0.5);
    
    $html = '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Dispatched By Rank</td><td style="border: 1px solid black; padding: 5px;">' . $receivedFromRank . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Dispatched By Name</td><td style="border: 1px solid black; padding: 5px;">' . $receivedFrom . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Dispatched By Company</td><td style="border: 1px solid black; padding: 5px;">' . $receivedByCompany . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Dispatched By Timestamp</td><td style="border: 1px solid black; padding: 5px;">' . $timestamp . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Dispatched By Signature</td><td style="border: 1px solid black; padding: 5px;"><img src="' . $signatureDataFrom . '" alt="Signature" width="100"></td></tr>';
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(0.5);
    
    $html = '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Received By Rank</td><td style="border: 1px solid black; padding: 5px;">' . $receivedByRank . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Received By Name</td><td style="border: 1px solid black; padding: 5px;">' . $receivedBy . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Received By Company</td><td style="border: 1px solid black; padding: 5px;">' . $receivedByCompany . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Received By Timestamp</td><td style="border: 1px solid black; padding: 5px;">' . $timestamp . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Received By Signature</td><td style="border: 1px solid black; padding: 5px;"><img src="' . $signatureDataBy . '" alt="Signature" width="100"></td></tr>';
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    
    $pdf->AddPage();

    $html = '<table cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;">'; 
    $html .= '<tr><td rowspan="2" style="font-size: 30px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;">DFCMS</td> 
        <td style="text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 12px;">' . "LBU02 - Exhibit Dispatch Form" . '</td></tr>'; 
    $html .= '<tr><td style="text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 12px;">Page '.$pdf->getAliasNumPage().' of '. $pdf->getAliasNbPages(). '</td></tr>';
    $html .= '</table>';
    $html .= '<br/>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(0.5);

    $html = '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Exhibit Number</td><td style="border: 1px solid black; padding: 5px;">' . $exhibitReference . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid black; font-weight: bold; background-color: #87c1ff; padding: 5px; color: white;">Seal Number</td><td style="border: 1px solid black; padding: 5px;">' . $sealNumber . '</td></tr>';
    $html .= '<tr><th style="border: 1px solid black; font-weight: bold; background-color: #5aaaff; color: white; color: white;" colspan="2">Initial Description</th></tr>';
    $html .= '<tr><td style="border: 1px solid black; padding: 5px;" colspan="2">' . $initialDescription . '</td></tr>';
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    
}

$pdfContent = $pdf->output('', 'S'); // Output the PDF as a string
$md5Hash = md5($pdfContent);
$sha1Hash = sha1($pdfContent);

$to = $dispatchedByEmail;
$subject = 'LBU02 - Exhibit Dispatch Form (' . $exhibitReference . ')';
$from = 'dfcmsmailservice@gmail.com';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"boundary1\"" . "\r\n";
$headers .= 'From: ' . $from . "\r\n";

$body = "--boundary1\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= "Please find the attached PDF file containing your LBU02 Exhibit Dispatch Form.\r\n\r\n";

$body .= "MD5 Hash of the LBU02 PDF: " . $md5Hash . "\r\n";
$body .= "SHA-1 Hash of the LBU02 PDF: " . $sha1Hash . "\r\n";

$body .= "--boundary1\r\n";
$body .= "Content-Type: application/pdf; name=\"LBU02 - Exhibit Dispatch Form ($exhibitReference).pdf\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment; filename=\"LBU02 - Exhibit Dispatch Form ($exhibitReference).pdf\"\r\n\r\n";
$body .= chunk_split(base64_encode($pdfContent)) . "\r\n";
$body .= "--boundary1--\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo 'Email sent successfully';
} else {
    echo 'Failed to send email';
}
?>