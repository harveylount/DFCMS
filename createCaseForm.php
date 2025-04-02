<!DOCTYPE html>
<html>
<?php
session_start();
if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

if (!isset($_SESSION['txtCaseReferenceM'])) {
    $_SESSION['txtCaseReferenceM']='';
}
if (!isset($_SESSION['txtCaseNameM'])) {
    $_SESSION['txtCaseNameM']='';
}
if (!isset($_SESSION['txtcaseReferenceExistsM'])) {
    $_SESSION['txtcaseReferenceExistsM']='';
}

// Returns to index page if user role is not set
if (!isset($_SESSION['userRole'])) {
    header ('location:index.php');
    exit();
}

// Returns to index page if user role is not 'Lead Investigator'
if ($_SESSION['userRole'] !== 'Lead Investigator') {
    header('Location: index.php');
    exit();
}

// Get the current date and format it as YYYY-MM-DD
$currentDate = date('Y-m-d');

// Get the date 5 years from now
$maxDate = date('Y-m-d', strtotime('+5 years'));

?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Create a case</title>

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

        <section id="content">

            <h2>Create a case</h2>

            <form method="post" action="createCaseInsert.php">
                <fieldset class="field-set width">
                    <legend>
                    Enter case details
                    </legend>

                    <!-- Case reference field -->
                    <label for="txtCaseReference">Case Reference: </label><br />
                    <input type="text" name="txtCaseReference" value="<?php 
                        if(isset($_SESSION['txtCaseReferenceF'])) {
                            echo $_SESSION['txtCaseReferenceF'];
                            unset($_SESSION['txtCaseReferenceF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtCaseReferenceM']; unset($_SESSION['txtCaseReferenceM']);?></p><br /><br />

                    <!-- Case name field -->
                    <label for="txtCaseName">Case Name: </label><br />
                    <input type="text" name="txtCaseName" value="<?php 
                        if(isset($_SESSION['txtCaseNameF'])) {
                            echo $_SESSION['txtCaseNameF'];
                            unset($_SESSION['txtCaseNameF']);
                        }
                    ?>" required/><p class="error-message"><?php echo $_SESSION['txtCaseNameM']; unset($_SESSION['txtCaseNameM']);?></p><br /><br />

                    <!-- Case deadline date field -->
                    <label for="dateDeadline">Deadline date:</label><br />
                    <input type="date" id="dateDeadline" name="dateDeadline" min="<?php echo $currentDate; ?>" max="<?php echo $maxDate; ?>" value="<?php
                    if(isset($_SESSION['txtDateDeadlineF'])) {
                        echo $_SESSION['txtDateDeadlineF'];
                        unset($_SESSION['txtDateDeadlineF']);
                    }
                    ?>" required> <br /><br />

                    <label for="timezone">Select Case Timezone:</label><br />
                    <select name="timezone" id="timezone">
                        <option value="" disabled selected>-- Select a Timezone --</option>
                        <option value="Europe/Amsterdam">Europe/Amsterdam</option>
                        <option value="Europe/Andorra">Europe/Andorra</option>
                        <option value="Europe/Astrakhan">Europe/Astrakhan</option>
                        <option value="Europe/Athens">Europe/Athens</option>
                        <option value="Europe/Belgrade">Europe/Belgrade</option>
                        <option value="Europe/Berlin">Europe/Berlin</option>
                        <option value="Europe/Bratislava">Europe/Bratislava</option>
                        <option value="Europe/Brussels">Europe/Brussels</option>
                        <option value="Europe/Bucharest">Europe/Bucharest</option>
                        <option value="Europe/Budapest">Europe/Budapest</option>
                        <option value="Europe/Busingen">Europe/Busingen</option>
                        <option value="Europe/Chisinau">Europe/Chisinau</option>
                        <option value="Europe/Copenhagen">Europe/Copenhagen</option>
                        <option value="Europe/Dublin">Europe/Dublin</option>
                        <option value="Europe/Gibraltar">Europe/Gibraltar</option>
                        <option value="Europe/Guernsey">Europe/Guernsey</option>
                        <option value="Europe/Helsinki">Europe/Helsinki</option>
                        <option value="Europe/Isle_of_Man">Europe/Isle_of_Man</option>
                        <option value="Europe/Istanbul">Europe/Istanbul</option>
                        <option value="Europe/Jersey">Europe/Jersey</option>
                        <option value="Europe/Kaliningrad">Europe/Kaliningrad</option>
                        <option value="Europe/Kiev">Europe/Kiev</option>
                        <option value="Europe/Kirov">Europe/Kirov</option>
                        <option value="Europe/Lisbon">Europe/Lisbon</option>
                        <option value="Europe/Ljubljana">Europe/Ljubljana</option>
                        <option value="Europe/London">Europe/London</option>
                        <option value="Europe/Luxembourg">Europe/Luxembourg</option>
                        <option value="Europe/Madrid">Europe/Madrid</option>
                        <option value="Europe/Malta">Europe/Malta</option>
                        <option value="Europe/Mariehamn">Europe/Mariehamn</option>
                        <option value="Europe/Minsk">Europe/Minsk</option>
                        <option value="Europe/Monaco">Europe/Monaco</option>
                        <option value="Europe/Moscow">Europe/Moscow</option>
                        <option value="Europe/Oslo">Europe/Oslo</option>
                        <option value="Europe/Paris">Europe/Paris</option>
                        <option value="Europe/Podgorica">Europe/Podgorica</option>
                        <option value="Europe/Prague">Europe/Prague</option>
                        <option value="Europe/Riga">Europe/Riga</option>
                        <option value="Europe/Rome">Europe/Rome</option>
                        <option value="Europe/Samara">Europe/Samara</option>
                        <option value="Europe/San_Marino">Europe/San_Marino</option>
                        <option value="Europe/Sarajevo">Europe/Sarajevo</option>
                        <option value="Europe/Saratov">Europe/Saratov</option>
                        <option value="Europe/Simferopol">Europe/Simferopol</option>
                        <option value="Europe/Skopje">Europe/Skopje</option>
                        <option value="Europe/Sofia">Europe/Sofia</option>
                        <option value="Europe/Stockholm">Europe/Stockholm</option>
                        <option value="Europe/Tallinn">Europe/Tallinn</option>
                        <option value="Europe/Tirane">Europe/Tirane</option>
                        <option value="Europe/Ulyanovsk">Europe/Ulyanovsk</option>
                        <option value="Europe/Uzhgorod">Europe/Uzhgorod</option>
                        <option value="Europe/Vaduz">Europe/Vaduz</option>
                        <option value="Europe/Vatican">Europe/Vatican</option>
                        <option value="Europe/Vienna">Europe/Vienna</option>
                        <option value="Europe/Vilnius">Europe/Vilnius</option>
                        <option value="Europe/Volgograd">Europe/Volgograd</option>
                        <option value="Europe/Warsaw">Europe/Warsaw</option>
                        <option value="Europe/Zagreb">Europe/Zagreb</option>
                        <option value="Europe/Zaporozhye">Europe/Zaporozhye</option>
                        <option value="Europe/Zurich">Europe/Zurich</option>
                    </select>
                    </br></br>

                    <p class="error-message">
                        <?php echo $_SESSION['txtcaseReferenceExistsM']; unset($_SESSION['txtcaseReferenceExistsM']);?>
                    </p> </br></br>

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />

                </fieldset>
                
            </form>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>