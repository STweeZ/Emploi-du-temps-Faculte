<!doctype html>
<html lang="fr">
<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    require_once("suppression.php");
    require_once("insertionBis.php");

    $temps;
    $debut;
    $fin;
    $idType;
    $idSalle;
    $idMatiere;
    $idEnseignant;
    $idPromotion;

    if(isset($_GET['date']) && isset($_GET['debut']) && isset($_GET['promotion'])) {
        $bd=openBD();
        $sql=$bd->prepare('SELECT * FROM Creneau WHERE temps = ? AND debut = ? AND idPromotion = ?');
        $temps=htmlspecialchars($_GET['date']);
        $sql->bindParam(1,$temps);
        $debut=htmlspecialchars($_GET['date'])." ".htmlspecialchars($_GET['debut']);
        $sql->bindParam(2,$debut);
        $idPromotion=htmlspecialchars($_GET['promotion']);
        $sql->bindParam(3,$idPromotion);
        $sql->execute();
        $result = ($sql->fetchAll())[0];

        $temps=$result['temps'];
        $debut=explode(" ",$result['debut'])[1];
        $fin=explode(" ",$result['fin'])[1];
        $idType=$result['idType'];
        $idSalle=$result['idSalle'];
        $idMatiere=$result['idMatiere'];
        $idEnseignant=$result['idEnseignant'];
        $idPromotion=$result['idPromotion'];
        
        unset($sql,$result);
        $bd=null;

        $_SESSION["pathObject"]=array("temps"=>$temps, "debut"=>$debut,"fin"=>$fin,"idType"=>$idType,"idSalle"=>$idSalle,"idMatiere"=>$idMatiere,"idEnseignant"=>$idEnseignant,"idPromotion"=>$idPromotion);

    } else if(isset($_POST['delete']) && isset($_SESSION["pathObject"])){
        $bd=openBD();
        $date=htmlspecialchars($_SESSION["pathObject"]['temps']);
        if(delete($bd,$_SESSION["pathObject"]['temps'],$date." ".$_SESSION["pathObject"]['debut'],$date." ".$_SESSION["pathObject"]['fin'],$_SESSION["pathObject"]['idType'],$_SESSION["pathObject"]['idSalle'],$_SESSION["pathObject"]['idMatiere'],$_SESSION["pathObject"]['idEnseignant'],$_SESSION["pathObject"]['idPromotion'])) {
            ?><script>alert("La suppression a été effectuée.");window.location.href = "edt.php";</script><?php
        } else {
            ?><script>alert("Le créneau choisi n'existe pas.");window.location.href = "edt.php";</script><?php
        }
        $bd=null;
    } else if(isset($_POST['insert']) && isset($_POST['date']) && isset($_POST['timeDebut']) && isset($_POST['timeFin']) && isset($_POST['type']) && isset($_POST['salle']) && isset($_POST['matiere']) && isset($_POST['enseignant']) && isset($_POST['promotion'])) {

        $postDebut=explode(":",$_POST['timeDebut'])[0].":".explode(":",$_POST['timeDebut'])[1].":00";
        $postFin=explode(":",$_POST['timeFin'])[0].":".explode(":",$_POST['timeFin'])[1].":00";

        $bd=openBD();
        $correct=1;
        $date=$_POST['date'];
        $minutesD=explode(":",$postDebut)[1];
        $minutesF=explode(":",$postFin)[1];

        if(($minutesD != "00" && $minutesD != "15" && $minutesD != "30" && $minutesD != "45") || ($minutesF != "00" && $minutesF != "15" && $minutesF != "30" && $minutesF != "45")) { // Course by 15 minutes
            unset($minutesD,$minutesF);
            $correct = false;
            ?><script>alert("Les créneaux fonctionnent par tranche de 15 minutes.");window.location.href = "edt.php";</script><?php
        }
        unset($minutesD,$minutesF);

        $debut=$date." ".$postDebut; // Schedule given
        $fin=$date." ".$postFin;
        $timeDebut=date($debut);
        $timeFin=date($fin);
        $dateDebut=date($date." 08:00:00"); // Schedule min and max possible
        $dateFin=date($date." 20:00:00");

        if((($timeFin <= $timeDebut) || ($timeDebut < $dateDebut || $timeFin <= $dateDebut || ($timeFin > $dateFin || $dateDebut >= $dateFin)))) $correct = false; // The schedule in not well formed

        if($correct) { // Check if the schedule given is not in collision with an other schedule in the database
            $sql=$bd->prepare('SELECT * FROM Creneau WHERE temps = ? AND (idSalle = ? OR idEnseignant = ? OR idPromotion = ?)');
            $sql->bindParam(1,$date);
            $salle=htmlspecialchars($_POST['salle']);
            $sql->bindParam(2,$salle);
            $enseignant=htmlspecialchars($_POST['enseignant']);
            $sql->bindParam(3,$enseignant);
            $promotion=htmlspecialchars($_POST['promotion']);
            $sql->bindParam(4,$promotion);

            $tps=htmlspecialchars($_SESSION["pathObject"]['temps']);
            $d=htmlspecialchars($tps." ".$_SESSION["pathObject"]['debut']);
            $f=htmlspecialchars($tps." ".$_SESSION["pathObject"]['fin']);
            $t=htmlspecialchars($_SESSION["pathObject"]['idType']);
            $s=htmlspecialchars($_SESSION["pathObject"]['idSalle']);
            $m=htmlspecialchars($_SESSION["pathObject"]['idMatiere']);
            $e=htmlspecialchars($_SESSION["pathObject"]['idEnseignant']);
            $p=htmlspecialchars($_SESSION["pathObject"]['idPromotion']);
            
            $sql->execute();
            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                if($row['temps'] != $tps || $row['debut'] != $d || $row['fin'] != $f || $row['idType'] != $t || $row['idSalle'] != $s || $row['idMatiere'] != $m || $row['idEnseignant'] != $e || $row['idPromotion'] != $p) {
                    $dateDebut=date($row['debut']);
                    $dateFin=date($row['fin']);
                    if((($timeDebut <= $dateDebut) && ($dateDebut < $timeFin)) || (($timeDebut < $dateFin) && ($dateFin < $timeFin)) || (($dateDebut <= $timeDebut) && ($timeDebut < $dateFin)) || (($dateDebut < $timeFin) && ($timeFin < $dateFin))) {
                        $correct = false;
                        break;
                    } else if((($dateDebut <= $timeDebut) && ($timeDebut < $dateFin)) || (($dateDebut < $timeFin) && ($timeFin < $dateFin)) || (($timeDebut <= $dateDebut) && ($dateDebut < $timeFin)) || (($timeDebut < $dateFin) && ($dateFin < $timeFin))) {
                        $correct = false;
                        break;
                    }
                    unset($dateDebut,$dateFin);
                }
            }
            unset($row,$sql,$salle,$enseignant,$promotion);
        }
        unset($timeDebut,$timeFin);

        if($correct) {
            if(delete($bd,$_SESSION["pathObject"]['temps'],$_SESSION["pathObject"]['temps']." ".$_SESSION["pathObject"]['debut'],$_SESSION["pathObject"]['temps']." ".$_SESSION["pathObject"]['fin'],$_SESSION["pathObject"]['idType'],$_SESSION["pathObject"]['idSalle'],$_SESSION["pathObject"]['idMatiere'],$_SESSION["pathObject"]['idEnseignant'],$_SESSION["pathObject"]['idPromotion'])) {
                switch(insertionBis($bd,$date,$postDebut,$postFin,$_POST['type'],$_POST['salle'],$_POST['matiere'],$_POST['enseignant'],$_POST['promotion'])) {
                    case 0 :
                        ?><script>alert("La modification n'est pas possible.");window.location.href = "edt.php";</script><?php
                        break;
                    case 1 :
                        ?><script>alert("La modification a été effectuée.");window.location.href = "edt.php";</script><?php
                        break;
                    case 2 :
                        ?><script>alert("Les créneaux fonctionnent par tranche de 15 minutes.");window.location.href = "edt.php";</script><?php
                        break;
                }
            } else {
                ?><script>alert("Le créneau choisi n'existe pas.");window.location.href = "edt.php";</script><?php
            }
        } else {
            ?><script>alert("La modification n'est pas possible.");window.location.href = "edt.php";</script><?php
        }
    } else {
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
    <div id="main"> <!-- The main page -->
        <?php
            $bd=openBD();
            if(getRole($bd) != "Enseignant") { // Reserved for teachers
                $bd=null;
                header("Location: edt.php");
                exit();
            }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> <!-- Form for modification -->
            <label for="date"><b>Date</b></label>
            <br>
            <input type="date" id="date" class="form-control" name="date" value="<?php echo explode("-",$temps)[0]."-".explode("-",$temps)[1]."-".explode("-",$temps)[2]; ?>" required>
            <label for="timeDebut"><b>Heure de début</b></label>
            <br>
            <input type="time" id="timeDebut" class="form-control" name="timeDebut" min="08:00" max="20:00" value="<?php echo $debut; ?>" required>
            <label for="timeFin"><b>Heure de fin</b></label>
            <br>
            <input type="time" id="timeFin" class="form-control" name="timeFin" min="08:00" max="20:00" value="<?php echo $fin; ?>" required>
            <label for="type"><b>Type de créneau</b></label>
            <br>
            <select name="type" class="form-control" required> <!-- The list of class types -->
                <?php
                    $stmt=$bd->prepare('SELECT id,intitule FROM Type');
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if($row['id'] == $idType) echo "<option value=".$row['id']." selected=\"selected\">".$row['intitule']."</option>";
                        else echo "<option value=".$row['id'].">".$row['intitule']."</option>";
                    }
                    unset($stmt,$row);
                ?>
            </select>
            <label for="salle"><b>Salle</b></label>
            <br>
            <select name="salle" class="form-control" required> <!-- The list of classrooms -->
                <?php
                    $stmt=$bd->prepare('SELECT id,intitule,capacite FROM Salle');
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if($row['id'] == $idSalle) echo "<option value=".$row['id']." selected=\"selected\">".$row['intitule']." (".$row['capacite'].")"."</option>";
                        else echo "<option value=".$row['id'].">".$row['intitule']." (".$row['capacite'].")"."</option>";
                    }
                    unset($stmt,$row);
                ?>
            </select>
            <label for="matiere"><b>Matière</b></label>
            <br>
            <select name="matiere" class="form-control" required> <!-- The list of subjects -->
                <?php
                    $stmt=$bd->prepare('SELECT id,intitule FROM Matiere');
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if($row['id'] == $idMatiere) echo "<option value=".$row['id']." selected=\"selected\">".$row['intitule']."</option>";
                        else echo "<option value=".$row['id'].">".$row['intitule']."</option>";
                    }
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
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if($row['id'] == $idEnseignant) echo "<option value=".$row['id']." selected=\"selected\">".$row['nom']." ".$row['prenom']."</option>";
                        else echo "<option value=".$row['id'].">".$row['nom']." ".$row['prenom']."</option>";
                    }
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
                        while($rowBis = $promotion->fetch(PDO::FETCH_ASSOC)) {
                            if($rowBis['id'] == $idPromotion) echo "<option value=".$rowBis['id']." selected=\"selected\">".$rowBis['intitule']." ".$row['intitule']."</option>";
                            else echo "<option value=".$rowBis['id'].">".$rowBis['intitule']." ".$row['intitule']."</option>";
                        }
                    }
                    unset($filiere,$row,$promotion,$rowBis);
                ?>
            </select>
            <br>
            <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit" name="insert">Modifier</button>
            <br>
        </form>
        <br>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> <!-- Form for deletion -->
            <button style="width: 100%;" class="btn btn-outline-danger btn-lg" type="submit" name="delete">Supprimer</button>
            <br>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>