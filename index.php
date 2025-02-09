<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Case List</title>

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

            <h2>Cases</h2>

            <div id="createcase-bar">
                <?php
                if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'Lead Investigator') {
                    echo '<a href="createCaseForm.php" id="createcase-button">Create Case</a>';
                }
                ?>
            </div>

            <p>
                <?php
                
                include 'displayCases.php';

                ?>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>