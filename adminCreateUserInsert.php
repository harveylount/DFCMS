<?php
include 'SqlConnection.php';

$file = "encryptionkey.txt";
if (!file_exists($file)) {
    echo "No encryption key file!";
    exit();

} else {

    $encryptionKey = file_get_contents($file);

    if (isset($_POST['subEvent'])) {
        $username=$_POST['txtUsername'];
        $firstName=$_POST['txtFirstName'];
        $lastName=$_POST['txtLastName'];
        $company=$_POST['txtCompany'];
        $rank=$_POST['txtRank'];
        $socoNumber=$_POST['txtSOCONumber'];
        $role=$_POST['role'];
        $password=$_POST['txtPassword'];

        $_SESSION['txtUsernameF']=$username;
        $_SESSION['txtFirstNameF']=$firstName;
        $_SESSION['txtLastNameF']=$lastName;
        $_SESSION['txtCompanyF']=$company;
        $_SESSION['txtRankF']=$rank;
        $_SESSION['txtSOCONumberF']=$socoNumber;
        $_SESSION['txtPasswordF']=$password;

        $sql = "SELECT * FROM users WHERE Username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $_SESSION['txtusernameExistsM'] = 'Username already exists';
            $usernameExists = "true";
        }

        $sql = "SELECT * FROM users WHERE SocoNumber = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $socoNumber);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $_SESSION['txtSOCONumberExistsM'] = 'SOCO Number already exists';
            $socoExists = "true";
        }

        if ($socoExists === "true") {
            header('location:adminCreateUserForm.php');
            exit();
        }
        if ($usernameExists === "true") {
            header('location:adminCreateUserForm.php');
            exit();
        }



        if (preg_match('/^[a-z-]{6,30}$/', $username)) {
            $usernameCheck = true;
        } else {
            $usernameCheck = false;
            $_SESSION['txtUsernameM']=' Must only contain lower case a-z, "-" characters and 6-30 character length';
        }

        if (preg_match('/^[a-zA-Z]{3,24}$/', $firstName)) {
            $firstNameCheck = true;
        } else {
            $firstNameCheck = false;
            $_SESSION['txtFirstNameM']=' Must only contain alpha characters and 3-24 character length';
        }

        if (preg_match('/^[a-zA-Z]{3,24}$/', $lastName)) {
            $lastNameCheck = true;
        } else {
            $lastNameCheck = false;
            $_SESSION['txtLastNameM']=' Must only contain alpha characters and 3-24 character length';
        }

        if (preg_match('/^[a-zA-Z0-9 ]{3,50}$/', $company)) {
            $companyCheck = true;
        } else {
            $companyCheck = false;
            $_SESSION['txtCompanyM']=' Must only contain alpha and 0-9 characters and 3-50 character length';
        }

        if (preg_match('/^[a-zA-Z]{2,4}$/', $rank)) {
            $rankCheck = true;
        } else {
            $rankCheck = false;
            $_SESSION['txtRankM']=' Must only contain alpha characters and 2-4 character length';
        }

        if (preg_match('/^[0-9]{4}$/', $socoNumber)) {
            $socoNumberCheck = true;
        } else {
            $socoNumberCheck = false;
            $_SESSION['txtSOCONumberM']=' Must only contain 0-9 characters and 4 character length';
        }

        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?])[\w!@#$%^&*()_\-+=<>?]{8,32}$/', $password)) {
            $passwordCheck = true;
        } else {
            $passwordCheck = false;
            $_SESSION['txtPasswordM']=' Must only contain atleast: 1 uppercase letter, lowercase letter, number, special character. Character length 8-32';
        }

        if ($usernameCheck && $firstNameCheck && $lastNameCheck && $rankCheck && $companyCheck && $socoNumberCheck && $passwordCheck) {

            unset($_SESSION['txtUsernameF']);
            unset($_SESSION['txtFirstNameF']);
            unset($_SESSION['txtLastNameF']);
            unset($_SESSION['txtCompanyF']);
            unset($_SESSION['txtRankF']);
            unset($_SESSION['txtSOCONumberF']);
            unset($_SESSION['txtPasswordF']);

            $fullName = $firstName . ' ' . $lastName;

            $encryptedPass = openssl_encrypt($password, 'aes-256-cbc', $encryptionKey, 0, $encryptionKey);

            $query = "INSERT INTO users 
                (Username, UserPass, FullName, Company, Rank, Role, SocoNumber)
                VALUES
                (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssss", $username, $encryptedPass, $fullName, $company, $rank, $role, $socoNumber);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['adminPageMessage']="User created successfully!";

            header('location:adminPage.php');

            exit();

        } else {
            header('location:adminCreateUserForm.php');
            exit();
        }
        
    }

}


?>