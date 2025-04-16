<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}
$username = $_SESSION['userId'];

$sql = "SELECT Role FROM users WHERE Username = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($roleCheck);
$stmt->fetch();
mysqli_stmt_close($stmt);


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
        <div class="left-group">
            <!-- <a href="adminPage.php" class="logout-button">Back</a> -->
        </div>
        <div class="right-group">
            <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
            <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
            <?php
                if ($roleCheck == "Administrator") {
                    echo '<a href="adminPage.php" class="logout-button">Admin Page</a>';
                }
            ?>
            <a href="logoutFunction.php" class="logout-button">Logout</a>
        </div>
    </div>


        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        <section id="LBU">

            <?php
                echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Cases</td> 
                        <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>"; 
                echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>";
                echo "</table>";
                echo "<br/>";
        
                if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'Lead Investigator') {
                    echo '<div id="createcase-bar">
                            <a href="auditAuth.php" id="createcase-button">Authentication Audit</a>
                            <a href="createCaseForm.php" id="createcase-button">Create Case</a>
                        </div>';
                }
            ?>

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