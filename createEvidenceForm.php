<!DOCTYPE html>
<html>
<?php
session_start();
if(!isset($_SESSION['userId'])){ // Doesn't allow unauthenticated user access
    header ('location:loginForm.php');
}

$identifier = intval($_GET['identifier']);
?>
 

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Create Evidence</title>

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
            <a href="<?php echo "createEvidenceForm.php?identifier=$identifier" ?>" id="navcase-button">Computers & Laptops</a>
            <a href="<?php echo "createEvidenceFormMobile.php?identifier=$identifier" ?>" id="navcase-button">Mobile Devices</a>
            <a href="<?php echo "createEvidenceFormExternal.php?identifier=$identifier" ?>" id="navcase-button">External Storage</a>
            <a href="<?php echo "createEvidenceFormNetwork.php?identifier=$identifier" ?>" id="navcase-button">Network Devices</a>
        </div>

        <section id="content">

            <h2>Create a Computers & Laptops Evidence Exhibit</h2>

            

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>