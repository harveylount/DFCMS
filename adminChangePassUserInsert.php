<?php
include 'sqlConnection.php'; 

if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
    exit();
}

$file = "encryptionkey.txt";
if (!file_exists($file)) {
    echo "No encryption key file!";
    exit();

} else {

    $encryptionKey = file_get_contents($file);

    $username = $_SESSION['userId'];
    $user = intval($_GET['user']);

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

    if (isset($_POST['subEvent'])) {
        $password=$_POST['txtPassword'];

        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?])[\w!@#$%^&*()_\-+=<>?]{8,32}$/', $password)) {
            $passwordCheck = true;
        } else {
            $passwordCheck = false;
            $_SESSION['txtPasswordM']=' Must only contain atleast: 1 uppercase letter, lowercase letter, number, special character. Character length 8-32';
            header('location:adminChangePassUserList.php?user=' . urlencode($user));
            exit();
        }


        if ($passwordCheck) {

            $encryptedPass = openssl_encrypt($password, 'aes-256-cbc', $encryptionKey, 0, $encryptionKey);

            $sql = "UPDATE users SET UserPass = ? WHERE ID = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $encryptedPass, $user);
            $stmt->execute();
            if ($stmt->execute()) {
                $_SESSION['adminPageMessage'] = "Password Successfully Updated";
                header('location:adminPage.php');
                exit();
            } else {
                $_SESSION['adminPageMessage'] = "Password Update was Unsuccessful";
                header('location:adminPage.php');
                exit();
            }

        }
        
    }

}

?> 

