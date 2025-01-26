<!DOCTYPE html>
<html>
<?php
session_start();

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
                    <input type="text" name="txtCaseReference" required/> <br /><br />

                    <!-- Case name field -->
                    <label for="txtCaseName">Case Name: </label><br />
                    <input type="text" name="txtCaseName" required/> <br /><br />

                    <!-- Case deadline date field -->
                    <label for="dateDeadline">Deadline date:</label><br />
                    <input type="date" id="dateDeadline" name="dateDeadline" min="<?php echo $currentDate; ?>" max="<?php echo $maxDate; ?>" required> <br /><br />

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