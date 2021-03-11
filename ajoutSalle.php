<!doctype html>
<html lang="fr">
<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    if(isset($_POST['salle'])) { // If the form is filled
        $bd=openBD();
        $sql=$bd->prepare('SELECT COUNT(*) as count FROM Salle WHERE intitule=?');

        $salle=htmlspecialchars($_POST['salle']);

        $sql->bindParam(1,$salle);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        unset($sql);

        if($row['count']) { // The classroom is already existing in the database
            unset($salle,$row);
            $bd=null;
            ?><script>alert("La salle existe déjà.");window.location.href = "ajoutSalle.php";</script><?php
        } else { // Insert it in the database
            $sql=$bd->prepare('INSERT INTO Salle (intitule,capacite) VALUES (?,?)');
            $sql->bindParam(1,$salle);
            $capacite=htmlspecialchars($_POST['capacite']);
            $sql->bindParam(2,$capacite);
            $sql->execute();
            unset($salle,$capacite,$sql,$row);
            $bd=null;
            ?><script>alert("Ajout effectué.");window.location.href = "edt.php";</script><?php
        }
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
    <div id="main"> <!-- The main page -->
        <?php
            $bd=openBD();
            if(getRole($bd) != "Administratif") { // A changer
                $bd=null;
                header("Location: edt.php");
                exit();
            }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!-- Form to add a classroom -->
            <label for="salle"><b>Salle</b></label>
            <br>
            <input type="text" id="salle" class="form-control" name="salle" required>
            <label for="capacite"><b>Capacité</b></label>
            <br>
            <input type="number" id="capacite" class="form-control" name="capacite" required>
            <br>
            <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Ajouter</button>
            <br>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>