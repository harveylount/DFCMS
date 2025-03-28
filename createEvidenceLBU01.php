<style>
    .signature-box {
        border: 1px solid black;
        width: 400px;
        height: 200px;
        margin-bottom: 10px;
    }
    #clear-btn-from, #clear-btn-by {
        margin-top: 10px;
    }
</style>

<?php
include 'timezoneFunction.php'; 

$_SESSION['timestampDatabase'] = date('Y-m-d H:i:s');
$_SESSION['timestampDisplay'] = date('d-m-Y H:i:s');


//$userid = 2; // Example: replace with dynamic user ID or input from GET/POST
$userid = $_SESSION['userId'];

// SQL query
$sql = "SELECT FullName, Rank, Company FROM users WHERE Username = ?";
// Prepare statement
$stmt = $connection->prepare($sql);
// Bind the parameter to the prepared statement
$stmt->bind_param("s", $userid);
// Execute the statement
$stmt->execute();
// Bind the result to PHP variables
$stmt->bind_result($byName, $byRank, $byCompany);
// Fetch the result
$stmt->fetch();

$_SESSION['receivedBy']=$byName;
$_SESSION['receivedByRank']=$byRank;
$_SESSION['receivedByCompany']=$byCompany;

?>


<!-- Exhibit reference field -->
<label for="txtExhibitReference">Exhibit Reference: *</label><br />
<input type="text" name="txtExhibitReference" size="32" value="<?php 
    if(isset($_SESSION['txtExhibitReferenceF'])) {
        echo $_SESSION['txtExhibitReferenceF'];
        unset($_SESSION['txtExhibitReferenceF']);
    }
?>" required/><p class="error-message"><?php echo $_SESSION['txtExhibitReferenceM']; unset($_SESSION['txtExhibitReferenceM']);?></p><br /><br />

<!-- Seal Number field -->
<label for="txtSealNumber">Seal Number: *</label><br />
<input type="text" name="txtSealNumber" size="32" value="<?php 
    if(isset($_SESSION['txtSealNumberF'])) {
        echo $_SESSION['txtSealNumberF'];
        unset($_SESSION['txtSealNumberF']);
    }
?>" required/><p class="error-message"><?php echo $_SESSION['txtSealNumberM']; unset($_SESSION['txtSealNumberM']);?></p><br /><br />

<!-- Location field -->
<label for="txtLocation">Location: *</label><br />
<input type="text" name="txtLocation" size="32" value="<?php 
    if(isset($_SESSION['txtLocationF'])) {
        echo $_SESSION['txtLocationF'];
        unset($_SESSION['txtLocationF']);
    }
?>" required/><p class="error-message"><?php echo $_SESSION['txtLocationM']; unset($_SESSION['txtLocationM']);?></p><br /><br />

</br></br>

<!-- Received From field -->
<label for="txtReceivedFrom">Received From /<br /> Dispatched By (FULL NAME): *</label><br />
<input type="text" name="txtReceivedFrom" size="32" value="<?php 
    if(isset($_SESSION['txtReceivedFromF'])) {
        echo $_SESSION['txtReceivedFromF'];
        unset($_SESSION['txtReceivedFromF']);
    }
?>" required/><p class="error-message"><?php echo $_SESSION['txtReceivedFromM']; unset($_SESSION['txtReceivedFromM']);?></p><br /><br />

<!-- Received From Rank field -->
<label for="txtReceivedFromRank">Received From /<br /> Dispatched By (RANK / TITLE): *</label><br />
                 <input type="text" name="txtReceivedFromRank" size="32" value="<?php 
    if(isset($_SESSION['txtReceivedFromRankF'])) {
        echo $_SESSION['txtReceivedFromRankF'];
        unset($_SESSION['txtReceivedFromRankF']);
    }
?>" required/><p class="error-message"><?php echo $_SESSION['txtReceivedFromRankM']; unset($_SESSION['txtReceivedFromRankM']);?></p><br /><br />

Received From /<br /> Dispatched By Timestamp: <?php echo ($_SESSION['timestampDisplay']);?></br></br>

<!-- Signature Canvas for Received From -->
<label for="signature">Received From /<br /> Dispatched By Signature: *</label><br>
<canvas id="signature-canvas-from" class="signature-box"></canvas><br>
<button type="button" id="clear-btn-from">Clear</button><br><br>
<!-- Hidden field to store signature data -->
<input type="hidden" name="signature_data_from" id="signature-data-from">

<!-- Received From Company field -->
<label for="txtReceivedFromCompany">Received From /<br /> Dispatched By Company: *</label><br />
<input type="text" name="txtReceivedFromCompany" size="32" value="<?php 
    if(isset($_SESSION['txtReceivedFromCompanyF'])) {
        echo $_SESSION['txtReceivedFromCompanyF'];
        unset($_SESSION['txtReceivedFromCompanyF']);
    }
?>" required/><p class="error-message"><?php echo $_SESSION['txtReceivedFromCompanyM']; unset($_SESSION['txtReceivedFromCompanyM']);?></p><br /><br />

<!-- Received From Email field -->
<label for="txtDispatchByEmail">Email destination for LBU02 form:</label><br />
<input type="text" name="txtDispatchByEmail" size="32" value="<?php 
    if(isset($_SESSION['txtDispatchByEmailF'])) {
        echo $_SESSION['txtDispatchByEmailF'];
        unset($_SESSION['txtDispatchByEmailF']);
    }
?>"/><p class="error-message"><?php echo $_SESSION['txtDispatchByEmailM']; unset($_SESSION['txtDispatchByEmailM']);?></p> [For External Dispatcher only]<br /><br />

</br></br>

Received By Name: <?php echo($byName); ?> </br></br>

Received By Rank/Title: <?php echo($byRank); ?></br></br>

Received By Timestamp: <?php echo ($_SESSION['timestampDisplay']);?></br></br>

<!-- Signature Canvas for Received By -->
<label for="signature">Received By Signature: *</label><br>
<canvas id="signature-canvas-by" class="signature-box"></canvas><br>
<button type="button" id="clear-btn-by">Clear</button><br><br>
<!-- Hidden field to store signature data -->
<input type="hidden" name="signature_data_by" id="signature-data-by">

Received By Company: <?php echo($byCompany); ?></br></br></br></br>

