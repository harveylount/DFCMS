<?php
include 'sqlConnection.php';
session_start();

$file = "encryptionkey.txt";
if (file_exists($file)) {
    $encryptionKey = file_get_contents($file);

    if (isset($_POST['subEvent'])) {
        $username=$_POST['txtUsername'];

        $password=$_POST['txtPassword'];

        if (!empty($username)) {
            $_SESSION['txtUsernameF']=$_POST['txtUsername'];
            
            if (preg_match('/^[a-zA-Z-]+$/', $username)) {
                $usernameCheck = true;
            } else {
                $usernameCheck = false;
                $_SESSION['txtUsernameR']=' Must only contain alpha characters';
            }

        } else {
            $_SESSION['txtUsernameR']=' Username Required';
        }

        if (!empty($password)) {
            $_SESSION['txtPasswordF']=$_POST['txtPassword'];
            $passwordCheck = true;

            if ($usernameCheck && $passwordCheck) {
                
                $encryptedPass = openssl_encrypt($password, 'aes-256-cbc', $encryptionKey, 0, $encryptionKey);
                
                $query="SELECT * FROM users WHERE Username='$username' AND UserPass='$encryptedPass'";
                $result=mysqli_query($connection, $query);
                

                if ($row = mysqli_fetch_assoc($result)) {
                    $_SESSION['userId']=$username;
                    unset($_SESSION['txtUsernameF']);
                    unset($_SESSION['txtPasswordF']);

                    $query="SELECT role FROM users WHERE Username = '$username';";
                    $result=mysqli_query($connection, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result); // Fetch the result as an associative array
                    
                        if ($row) { // Check if the row contains data
                            $_SESSION['userRole'] = $row['role']; // Store the role in the session
                    
                        } else {
                            header('location:loginForm.php');// Handle case where no user is found

                        }
                    }

                    header('location:index.php');
                    exit();

                } else {
                    $_SESSION['message']='Login failed.';
                    header('location:loginForm.php');
                    exit();
                }
        
            }

        } else {
            $_SESSION['txtPasswordR']=' Password Required';
        }
                            
        header('location:loginForm.php');
        $_SESSION['message']='There were errors you have not been logged in!';
    }
}
?>