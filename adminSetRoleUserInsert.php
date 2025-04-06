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
    $role=$_POST['role'];
    $rank=$_POST['txtRank'];
    print $role;
    print $rank;

    //if both set
    if (isset($rank) && !empty($rank) && isset($role) && ($role == "Investigator" || $role == "Lead Investigator")) {
        if (preg_match('/^[a-zA-Z]{2,4}$/', $rank)) {
        
            $sql = "UPDATE users SET Rank = ? WHERE ID = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $rank, $user);
            if ($stmt->execute()) {
                $_SESSION['adminPageMessage2'] = "Rank Successfully Updated";
            } else {
                $_SESSION['adminPageMessage2'] = "Failed to Update Rank";
            }
    
            $sql = "UPDATE users SET Role = ? WHERE ID = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $role, $user);
            if ($stmt->execute()) {
                $_SESSION['adminPageMessage'] = "Role Successfully Updated";
            } else {
                $_SESSION['adminPageMessage'] = "Role Update was Unsuccessful";
            }
    
            header('Location: adminpage.php');
            exit();
        } else {
            $_SESSION['txtRankM'] = 'Must only contain alpha characters and 2-4 character length';
            header('Location: adminSetRoleUserList.php?user=' . urlencode($user));
            exit();
        }
    }
    

if (isset($role) && ($role == "Investigator" || $role == "Lead Investigator")) {
    
    $sql = "UPDATE users SET Role = ? WHERE ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $role, $user);
    $stmt->execute();
    if ($stmt->execute()) {
        $_SESSION['adminPageMessage'] = "Role Successfully Updated";
    } else {
        $_SESSION['adminPageMessage'] = "Role Update was Unsuccessful";
    }
    
    header('Location: adminpage.php');
    exit();
}

    //if just rank set
    if (isset($rank) && !empty($rank)) {
        if (preg_match('/^[a-zA-Z]{2,4}$/', $rank)) {
            $sql = "UPDATE users SET Rank = ? WHERE ID = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $rank, $user);
            $stmt->execute();
            if ($stmt->execute()) {
                $_SESSION['adminPageMessage'] = "Rank Successfully Updated";
            } else {
                $_SESSION['adminPageMessage'] = "Rank Update was Unsuccessful";
            }
            header('Location: adminpage.php');
            exit();
        } else {
            $_SESSION['txtRankM'] = 'Must only contain alpha characters and 2-4 character length';
            header('Location: adminSetRoleUserList.php?user=' . urlencode($user));
            exit();
        }
    }
    

}

?> 

