<?php
include 'sqlconnection.php'; 
if(!isset($_SESSION['userId'])){
    header ('location:loginForm.php');
}
?> 

<!DOCTYPE html>

<html>

<head>

    <link href="./index.css" rel="stylesheet" type="text/css" />

    <title>Case List</title>

</head>

<body>

    <div id="pagewrap">

        <header>

            <h1>DFCMS</h1>

            <h2> a Digital Forensics Case Management System </h2>

        </header>

        


        <section id="content">

            <h2>Cases</h2>

            <p>Cases displayed here</p>

        </section>

        

        <footer>

            <h4>Harvey Lount c3654483</h4>

        </footer>

    </div>

</body>

</html>