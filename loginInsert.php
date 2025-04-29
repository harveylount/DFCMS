<?php
include 'sqlConnection.php';
date_default_timezone_set("Europe/London");
$timestamp = date('Y-m-d H:i:s');

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

                    $query="SELECT role, FullName, SocoNumber FROM users WHERE Username = '$username';";
                    $result=mysqli_query($connection, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result); // Fetch the result as an associative array
                    
                        if ($row) { 
                            $_SESSION['userRole'] = $row['role']; 
                            $_SESSION['fullName'] = $row['FullName'];
                            $_SESSION['socoNumber'] = $row['SocoNumber'];
                    
                        } else {
                            header('location:loginForm.php');
                            exit();
                        }

                    $sql = "SELECT ID FROM users WHERE Username = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("s", $_SESSION['userId']);
                    $stmt->execute();
                    $stmt->bind_result($userId);
                    $stmt->fetch();
                    mysqli_stmt_close($stmt);
                    
                    // Audit Log
                    $fullName = $_SESSION['fullName'];
                    $username = $_SESSION['userId'];
                    $action = "User logged in. Full Name: " . $fullName . ". Username: " . $username . ". User ID: " . $userId;
                    $type = "Auth";

                    $query = "INSERT INTO auditlog 
                        (UserID, EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
                        VALUES
                        (?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "ssssss", $userId, $type, $timestamp, $fullName, $username, $action);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    }

                    header('location:index.php');
                    exit();

                } else {
                    $_SESSION['message']='Login failed.';

                    // Audit Log
                    $action = "Login failed.";
                    $type = "AuthFailed";
                    $fullName = $_POST['txtUsername'];
                    $username = $_POST['txtUsername'];

                    $query = "INSERT INTO auditlog 
                        (EntryType, Timestamp, ActionerFullName, ActionerUsername, Action)
                        VALUES
                        (?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "sssss", $type, $timestamp, $fullName, $username, $action);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

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