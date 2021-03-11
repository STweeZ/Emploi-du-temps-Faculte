<!doctype html>
<html lang="fr">
<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    if(isset($_POST['salle'])) { // Put in the session variable the id of the classroom concerned
        $_SESSION['seeSpec']="salle_".htmlspecialchars($_POST['salle']);
        header("Location: edt.php");
        exit();
    }
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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <select name="salle" class="form-control" required> <!-- List of the classrooms -->
            <?php
                $bd=openBD();
                $stmt=$bd->prepare('SELECT id,intitule,capacite FROM Salle');
                $stmt->execute();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value=".$row['id'].">".$row['intitule']." (".$row['capacite'].")"."</option>";
                unset($stmt,$row);
                $bd=null;
            ?>
        </select>
        <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Séléctionner</button>
        <br>
    </form>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>