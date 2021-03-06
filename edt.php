<!doctype html>
<html lang="fr">
<?php
    require_once("verifConnected.php");
?>
<head>
    <meta charset="utf-8">
    <title>Projet Programmation Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body id="body">
    <?php
        include("navBar.php");
    ?>
    <div id="main"> <!-- The main page -->
        <div id="containerLeft" class="container col-sm-4 col-md-7" style="float: left; top: 0; width: 23.2rem;"> <!-- The left container  -->
            <?php
                include("printSpecial.php");
            ?>
            <?php
                include("changeWeek.php");
            ?>
            <?php
                include("enseignantFct.php");
                include("adminFct.php");
            ?>
        </div>
        <div id="selectedWeek" class="flex-row flex-nowrap"> <!-- The main section that contains the calendar -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>