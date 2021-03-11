<!doctype html>
<html lang="fr">
<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    if(isset($_POST['date'])) { // If the form had been filled
        $bd=openBD();
        require_once("insertionBis.php");
        switch(insertionBis($bd,$_POST['date'],$_POST['timeDebut'].":00",$_POST['timeFin'].":00",$_POST['type'],$_POST['salle'],$_POST['matiere'],$_POST['enseignant'],$_POST['promotion'])) {
            case 0 :
                ?><script>alert("L'insertion n'est pas possible.");window.location.href = "edt.php";</script><?php
                break;
            case 1 :
                ?><script>alert("L'insertion a été effectuée.");window.location.href = "edt.php";</script><?php
                break;
            case 2 :
                ?><script>alert("Les créneaux fonctionnent par tranche de 15 minutes.");window.location.href = "insertion.php";</script><?php
                break;
        }
        $bd=null;
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
            require_once("openBD.php");
            $bd=openBD();
            if(getRole($bd) != "Enseignant") { // Reserved for teachers
                $bd=null;
                header("Location: edt.php");
                exit();
            }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!-- Form for insertion -->
            <label for="date"><b>Date</b></label>
            <br>
            <input type="date" id="date" class="form-control" name="date" value="<?php echo date("Y-m-d",time()); ?>" required>
            <label for="timeDebut"><b>Heure de début</b></label>
            <br>
            <input type="time" id="timeDebut" class="form-control" name="timeDebut" min="08:00" max="20:00" required>
            <label for="timeFin"><b>Heure de fin</b></label>
            <br>
            <input type="time" id="timeFin" class="form-control" name="timeFin" min="08:00" max="20:00" required>
            <label for="type"><b>Type de créneau</b></label>
            <br>
            <select name="type" class="form-control" required> <!-- The list of class types -->
                <?php
                    $stmt=$bd->prepare('SELECT id,intitule FROM Type');
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value=".$row['id'].">".$row['intitule']."</option>";
                    unset($stmt,$row);
                ?>
            </select>
            <label for="salle"><b>Salle</b></label>
            <br>
            <select name="salle" class="form-control" required> <!-- The list of classrooms -->
                <?php
                    $stmt=$bd->prepare('SELECT id,intitule,capacite FROM Salle');
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value=".$row['id'].">".$row['intitule']." (".$row['capacite'].")"."</option>";
                    unset($stmt,$row);
                ?>
            </select>
            <label for="matiere"><b>Matière</b></label>
            <br>
            <select name="matiere" class="form-control" required> <!-- The list of subjects -->
                <?php
                    $stmt=$bd->prepare('SELECT id,intitule FROM Matiere');
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value=".$row['id'].">".$row['intitule']."</option>";
                    unset($stmt,$row);
                ?>
            </select>
            <label for="enseignant"><b>Enseignant</b></label>
            <br>
            <select name="enseignant" class="form-control" required> <!-- The list of teachers -->
                <?php
                    $getID=$bd->prepare('SELECT id FROM Role WHERE intitule = ?');
                    $role="Enseignant";
                    $getID->bindParam(1,$role);
                    $getID->execute();
                    $id=($getID->fetchAll())[0]['id'];

                    $stmt=$bd->prepare('SELECT id,nom,prenom FROM Utilisateur WHERE idRole = ?');
                    $stmt->bindParam(1,$id);
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value=".$row['id'].">".$row['nom']." ".$row['prenom']."</option>";
                    unset($getID,$role,$id,$stmt,$row);
                ?>
            </select>
            <label for="promotion"><b>Promotion</b></label>
            <br>
            <select name="promotion" class="form-control" required> <!-- The list of promotions -->
                <?php
                    $filiere=$bd->prepare('SELECT id,intitule FROM Filiere');
                    $filiere->execute();

                    $promotion=$bd->prepare('SELECT id,intitule FROM Promotion WHERE idFiliere = ?');

                    while($row = $filiere->fetch(PDO::FETCH_ASSOC)) {
                        $promotion->bindParam(1,$row['id']);
                        $promotion->execute();
                        while($rowBis = $promotion->fetch(PDO::FETCH_ASSOC)) echo "<option value=".$rowBis['id'].">".$rowBis['intitule']." ".$row['intitule']."</option>";
                    }
                    unset($filiere,$row,$promotion,$rowBis);
                ?>
            </select>
            <br>
            <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Insérer</button>
            <br>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>