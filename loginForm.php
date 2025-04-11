<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['message'])) {
    $_SESSION['message']='';
}
if (!isset($_SESSION['txtUsernameR'])) {
    $_SESSION['txtUsernameR']='';
}
if (!isset($_SESSION['txtPasswordR'])) {
    $_SESSION['txtPasswordR']='';
}
?>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Login Page</title>

</head>

<body>

    <div id="pagewrap">

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        

        <section id="LBU">

            <?php
            echo "<table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 2px solid #5AAAFF;'>"; 
            echo "<tr><td rowspan='2' style='font-size: 50px; font-weight: bold; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white;'>Login Page</td> 
                    <td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>"; 
            echo "<tr><td style='text-align: right; border: 2px solid #5AAAFF; background-color: #5AAAFF; color: white; font-weight: bold; font-size: 20px;'></td></tr>";
            echo "</table>";
            echo "<br/>";
            ?>

            <form method="post" action="loginInsert.php">
                <fieldset class="field-set width">
                    <legend>
                    Enter User Details
                    </legend>
                    <label for="txtUsername">Username: </label><br />
                    <input type="text" name="txtUsername" value="<?php 
                        if(isset($_SESSION['txtUsernameF'])) {
                            echo $_SESSION['txtUsernameF'];
                            unset($_SESSION['txtUsernameF']);
                        }
                    ?>"/><?php echo $_SESSION['txtUsernameR']; unset($_SESSION['txtUsernameR']);?><br /><br />

                    <label for="txtPassword">Password: </label><br />
                    <input type="password" name="txtPassword" value="<?php 
                        if(isset($_SESSION['txtPasswordF'])) {
                            echo $_SESSION['txtPasswordF'];
                            unset($_SESSION['txtPasswordF']);
                        }
                    ?>"/><?php echo $_SESSION['txtPasswordR']; unset($_SESSION['txtPasswordR']);?><br /><br />

                    <input type="submit" value="Submit" name="subEvent" />
                    <input type="reset" value="Clear" />
                </fieldset>
                
            </form>
            
            <?php
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