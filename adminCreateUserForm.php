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
            <span id="username">Username: <?php echo $_SESSION['userId']; ?></span>
            <span id="role">Role: <?php echo $_SESSION['userRole']; ?></span>
            <a href="logoutFunction.php" id="logout-button">Logout</a>
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
                    echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Create New User</td> </tr>";
                    echo "</table>";
                    echo "<br/>";    
                ?>

                <form method="post" action="adminCreateUserInsert.php">
                <fieldset class="field-set width">
                    <legend>
                    Enter new user details
                    </legend>
                    </br>

                    <label for="txtUsername">Username: *</label><br />
                    <input type="text" name="txtUsername" value="<?php 
                        if(isset($_SESSION['txtUsernameF'])) {
                            echo $_SESSION['txtUsernameF'];
                            unset($_SESSION['txtUsernameF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtusernameExistsM'])) {
                            echo $_SESSION['txtusernameExistsM'];
                            unset($_SESSION['txtusernameExistsM']);
                        }
                        if(isset($_SESSION['txtUsernameM'])) {
                            echo $_SESSION['txtUsernameM']; 
                            unset($_SESSION['txtUsernameM']);
                        }
                    ?> </p><br /><br />

                    <label for="txtFirstName">First Name: *</label><br />
                    <input type="text" name="txtFirstName" value="<?php 
                        if(isset($_SESSION['txtFirstNameF'])) {
                            echo $_SESSION['txtFirstNameF'];
                            unset($_SESSION['txtFirstNameF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtFirstNameM'])) {
                            echo $_SESSION['txtFirstNameM']; 
                            unset($_SESSION['txtFirstNameM']);
                        }
                    ?> </p><br /><br />

                    <label for="txtLastName">Last Name: *</label><br />
                    <input type="text" name="txtLastName" value="<?php 
                        if(isset($_SESSION['txtLastNameF'])) {
                            echo $_SESSION['txtLastNameF'];
                            unset($_SESSION['txtLastNameF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtLastNameM'])) {
                            echo $_SESSION['txtLastNameM']; 
                            unset($_SESSION['txtLastNameM']);
                        }
                    ?> </p><br /><br />

                    <label for="txtCompany">Company: *</label><br />
                    <input type="text" name="txtCompany" value="<?php 
                        if(isset($_SESSION['txtCompanyF'])) {
                            echo $_SESSION['txtCompanyF'];
                            unset($_SESSION['txtCompanyF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtCompanyM'])) {
                            echo $_SESSION['txtCompanyM']; 
                            unset($_SESSION['txtCompanyM']);
                        }
                    ?> </p><br /><br />

                    <label for="txtRank">Rank: *</label><br />
                    <input type="text" name="txtRank" value="<?php 
                        if(isset($_SESSION['txtRankF'])) {
                            echo $_SESSION['txtRankF'];
                            unset($_SESSION['txtRankF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtRankM'])) {
                            echo $_SESSION['txtRankM']; 
                            unset($_SESSION['txtRankM']);
                        }
                    ?> </p><br /><br />

                    <label for="txtSOCONumber">SOCO Number: *</label><br />
                    <input type="text" name="txtSOCONumber" value="<?php 
                        if(isset($_SESSION['txtSOCONumberF'])) {
                            echo $_SESSION['txtSOCONumberF'];
                            unset($_SESSION['txtSOCONumberF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtSOCONumberExistsM'])) {
                            echo $_SESSION['txtSOCONumberExistsM'];
                            unset($_SESSION['txtSOCONumberExistsM']);
                        }
                        if(isset($_SESSION['txtSOCONumberM'])) {
                            echo $_SESSION['txtSOCONumberM']; 
                            unset($_SESSION['txtSOCONumberM']);
                        }
                    ?> </p><br /><br />

                    <label for="role">Select User Role: *</label><br />
                    <select name="role" id="role" required>
                        <option value="" disabled selected>Select a Role</option>
                        <option value="Investigator">Investigator</option>
                        <option value="Lead-Investigator">Lead-Investigator</option>
                    </select><br /><br />

                    <label for="txtPassword">Password: *</label><br />
                    <input type="password" name="txtPassword" value="<?php 
                        if(isset($_SESSION['txtPasswordF'])) {
                            echo $_SESSION['txtPasswordF'];
                            unset($_SESSION['txtPasswordF']);
                        }
                    ?>" required/><p class="error-message"><?php 
                        if(isset($_SESSION['txtPasswordM'])) {
                            echo $_SESSION['txtPasswordM']; 
                            unset($_SESSION['txtPasswordM']);
                        }
                    ?> </p><br /><br />

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />
                
                </form>
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>