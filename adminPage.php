<?php
include 'sqlConnection.php'; 

if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
    exit();
}

$username = $_SESSION['userId'];

$sql = "SELECT Role FROM users WHERE Username = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($roleCheck);
$stmt->fetch();
mysqli_stmt_close($stmt);

if ($roleCheck != "Administrator") {
    header ('location:index.php');
    exit();
}

?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Case Admin</title>

</head>

<body>

    <div id="pagewrap">

        <div id="logout-bar">
            <div class="left-group">
            </div>
            <div class="right-group">
                <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
                <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
                <a href="logoutFunction.php" class="logout-button">Logout</a>
            </div>
        </div>

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        <div id="navcase-bar">
            <a href="<?php echo "adminCreateUserForm.php" ?>" id="navcase-button">Create User</a>
            <a href="<?php echo "adminDeleteUserForm.php" ?>" id="navcase-button">Delete User</a>
            <a href="<?php echo "adminSetRoleUserForm.php" ?>" id="navcase-button">Set User Role & Rank</a>
            <a href="<?php echo "adminChangePassUserForm.php" ?>" id="navcase-button">Change User Password</a>
        </div>

        <section id="LBU">

            <p>
                <?php
                    echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Admin Page</td> </tr>";
                    echo "</table>";
                    echo "<br/></br>";
                    
                    if(isset($_SESSION['adminPageMessage'])) {
                        echo $_SESSION['adminPageMessage'] . "</br>";
                        unset($_SESSION['adminPageMessage']);
                    }
                    if(isset($_SESSION['adminPageMessage2'])) {
                        echo $_SESSION['adminPageMessage2'];
                        unset($_SESSION['adminPageMessage2']);
                    }

                ?>

                
                </form>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>