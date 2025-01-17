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
$currentDate = date('d-m-Y');

// Get the date 5 years from now
$maxDate = date('d-m-Y', strtotime('+5 years'));

// Removes array key errors
if (!isset($_SESSION['message'])) {
    $_SESSION['message']='';
}
if (!isset($_SESSION['txtCaseReferenceR'])) {
    $_SESSION['txtCaseReferenceR']='';
}
if (!isset($_SESSION['txtCaseNameR'])) {
    $_SESSION['txtCaseNameR']='';
}
if (!isset($_SESSION['dateDeadlineR'])) {
    $_SESSION['dateDeadlineR']='';
}
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
                    <input type="text" name="txtCaseReference" value="<?php 
                        if(isset($_SESSION['txtCaseReferenceF'])) {
                            echo $_SESSION['txtCaseReferenceF'];
                            unset($_SESSION['txtCaseReferenceF']);
                        } // Autofill remembered field data for failed form submittion
                    ?>"/>
                    <?php echo $_SESSION['txtCaseReferenceR']; unset($_SESSION['txtCaseReferenceR']);?><br /><br /><!-- Displays error message -->

                    <!-- Case name field -->
                    <label for="txtCaseName">Case Name: </label><br />
                    <input type="text" name="txtCaseName" value="<?php 
                        if(isset($_SESSION['txtCaseNameF'])) {
                            echo $_SESSION['txtCaseNameF'];
                            unset($_SESSION['txtCaseNameF']);
                        } // Autofill remembered field data for failed form submittion
                    ?>"/>
                    <?php echo $_SESSION['txtCaseNameR']; unset($_SESSION['txtCaseNameR']);?><br /><br /><!-- Displays error message -->

                    <!-- Case deadline date field -->
                    <label for="dateDeadline">Deadline date:</label><br />
                    <input type="date" id="dateDeadline" name="dateDeadline" min="<?php echo $currentDate; ?>" max="<?php echo $maxDate; ?>" value="<?php 
                        if(isset($_SESSION['dateDeadlineF'])) {
                            echo htmlspecialchars($_SESSION['dateDeadlineF'], ENT_QUOTES, 'UTF-8');
                            unset($_SESSION['dateDeadlineF']);
                        } // Autofill remembered field data for failed form submittion
                    ?>">
                    <?php echo $_SESSION['dateDeadlineR']; unset($_SESSION['dateDeadlineR']);?><br /><br /><!-- Displays error message -->

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />

                </fieldset>
                
            </form>
            
            <?php
            // Removes submittion error message
            echo $_SESSION['message'];
            $_SESSION['message']='';
            ?>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>