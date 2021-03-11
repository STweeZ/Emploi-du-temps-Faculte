<?php
    require_once("verifConnected.php");
    $bd=openBD();
    if(!isset($_SESSION['seeSpec'])) { // Set the calendar print by promotion
        if(getRole($bd) == "Etudiant") {
            $sql=$bd->prepare('SELECT idPromotion FROM Utilisateur WHERE id = ?');
            $id=htmlspecialchars($_SESSION['user']);
            $sql->bindParam(1,$id);
            $sql->execute();
            $id=($sql->fetchAll())[0]['idPromotion'];
            $_SESSION['seeSpec'] = "promotion_".$id['id'];
            unset($sql,$result,$id);
        } else {
            $sql="SELECT id FROM Promotion LIMIT 1";
            $result = $bd->query($sql);
            $id = $result->fetch(PDO::FETCH_ASSOC);
            $_SESSION['seeSpec'] = "promotion_".$id['id'];
            unset($sql,$result,$id);
        }
        header("Location: edt.php");
        exit();
    }
?>
<div class="card shadow-sm">
    <h3 class="card-header monthYear">
        <span>
        <?php
            $seeSpec=explode("_",htmlspecialchars($_SESSION['seeSpec']));
            switch($seeSpec[0]) { // Print an indication for the type of printing for the calendar
                case "salle":
                    $sql='SELECT intitule FROM Salle WHERE id=?';
                    $result=$bd->prepare($sql);
                    $result->bindParam(1,$seeSpec[1]);
                    $result->execute();
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $salle=$row['intitule'];
                    echo "Affichage : Salle ".$salle;
                    unset($sql,$result,$row,$salle);
                    break;
                case "enseignant":
                    $sql='SELECT nom,prenom FROM Utilisateur WHERE id=?';
                    $result=$bd->prepare($sql);
                    $result->bindParam(1,$seeSpec[1]);
                    $result->execute();
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $enseignant=$row['nom']." ".$row['prenom'];
                    echo "Affichage : ".$enseignant;
                    unset($sql,$result,$row,$enseignant);
                    break;
                case "promotion":
                    $sql='SELECT intitule,idFiliere FROM Promotion WHERE id=?';
                    $result=$bd->prepare($sql);
                    $result->bindParam(1,$seeSpec[1]);
                    $result->execute();
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $promotion=$row['intitule'];
                    $idFiliere=$row['idFiliere'];
                    unset($sql,$result,$id,$row);
                    $sql='SELECT intitule FROM Filiere WHERE id=?';
                    $result=$bd->prepare($sql);
                    $result->bindParam(1,$idFiliere);
                    $result->execute();
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $filiere=$row['intitule'];
                    echo "Affichage : ".$promotion." ".$filiere;
                    unset($sql,$result,$row,$idFiliere,$promotion,$filiere);
                    break;
            }
            unset($seeSpec);
            $bd=null;
        ?>
        </span>
    </h3>
</div><br>