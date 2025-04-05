<?php
include 'sqlConnection.php'; 

if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
    exit();
}

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
    $confirm=$_POST['txtConfirm'];

    if ($confirm === strtolower("yes")) {

        $sql = "DELETE FROM users WHERE ID = ?";
        $stmt = $connection->prepare($sql);

        $stmt->bind_param("s", $user);

        if ($stmt->execute()) {
            $_SESSION['adminPageMessage']="User Successfully Deleted!";
        } else {
            $_SESSION['adminPageMessage']="Error Deleting User!";
        }

        $stmt->close();
        header('location:adminPage.php');
        exit();
    } elseif ($confirm === strtolower("no")) {
        $_SESSION['adminPageMessage']="User Not Deleted!";
        header('location:adminPage.php');
        exit();
    } else {
        $_SESSION['txtConfirmM']="Answer must be Yes or No";
        header('location:adminDeleteUserCheck.php?user=' . urlencode($user));
        exit();
    }


    
}

?> 

