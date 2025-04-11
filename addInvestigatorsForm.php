<?php
include 'sqlConnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
    exit();
}

if (!isset($_GET['identifier'])) {
    header("Location: index.php");
    exit();
}

$identifier = intval($_GET['identifier']);  // Sanitize the input to prevent SQL injection

$sql = "SELECT LeadInvestigator FROM cases WHERE Identifier = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$stmt->bind_result($leadInvestigator);
$stmt->fetch();
mysqli_stmt_close($stmt);

$admin = "admin";

if ($_SESSION['userId'] != $leadInvestigator) {
    header ('location:viewCase.php?identifier=' . $identifier);
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
                <a href="index.php" class="logout-button">← Cases</a>
                <a href="<?php echo "viewCase.php?identifier=" . $identifier ?>" class="logout-button">← Case</a>
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
            <a href="<?php echo "viewCase.php?identifier=$identifier" ?>" id="navcase-button">Case Overview</a>
            <a href="<?php echo "caseAdmin.php?identifier=$identifier" ?>" id="navcase-button">Case Admin</a>
            <a href="<?php echo "addInvestigatorsForm.php?identifier=$identifier" ?>" id="navcase-button">Add Investigators</a>
        </div>

        <section id="content">

            <p>
                <?php
                
                    $query = "SELECT CaseReference, Investigator, LeadInvestigator FROM cases WHERE Identifier = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("i", $identifier);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $case = $result->fetch_assoc();
                    $stmt->close();

                    if (!$case) {
                        header('location:index.php');
                    }

                    $leadInvestigator = $case['LeadInvestigator'];

                    echo "<h2>" . $case['CaseReference'] . " - Add Investigators</h2>";

                    $investigators = !empty($case['Investigator']) ? explode(', ', $case['Investigator']) : [];

                    $user_query = "SELECT Username, FullName FROM users WHERE Username != ? AND Username != ?";
                    $stmt = $connection->prepare($user_query);
                    $stmt->bind_param("ss", $leadInvestigator, $admin);
                    $stmt->execute();
                    $user_result = $stmt->get_result();
                    ?>

                    <form action="investigatorsInsert.php?identifier=<?php echo "$identifier"?>" method="post">
                        <h3>Select Investigators:</h3>

                        <?php
                        if ($user_result->num_rows > 0) {
                            while ($user = $user_result->fetch_assoc()) {
                                $checked = in_array($user["Username"], $investigators) ? 'checked' : '';
                                echo '<label>';
                                echo '<input type="checkbox" name="users[]" value="' . $user["Username"] . '" ' . $checked . '>';
                                echo $user["FullName"] . " (" . $user["Username"] . ")";
                                echo '</label><br>';
                            }
                        } else {
                            echo "<p>No investigators available.</p>";
                        }
                        ?>

                        <br>
                        <button type="submit">Submit</button>
                    </form>
               

            
            </p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>