<!doctype html>
<html lang="fr">
<?php
    session_start();
    session_destroy(); // Reset the user session
    header("Location: edt.php");
    exit();
?>
<head>
    <meta charset="utf-8">
    <title>Projet Programmation Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
</body>
</html>