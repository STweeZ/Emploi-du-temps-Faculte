<?php
    function delete($bd,$tps,$deb,$end,$idT,$idS,$idM,$idE,$idP) {
        $sql=$bd->prepare('DELETE FROM Creneau WHERE temps=? AND debut=? AND fin=? AND idType=? AND idSalle=? AND idMatiere=? AND idEnseignant=? AND idPromotion=?'); // We delete if from the database

        $date=htmlspecialchars($tps);
        $debut=htmlspecialchars($deb);
        $fin=htmlspecialchars($end);
        $type=htmlspecialchars($idT);
        $salle=htmlspecialchars($idS);
        $matiere=htmlspecialchars($idM);
        $enseignant=htmlspecialchars($idE);
        $promotion=htmlspecialchars($idP);

        unset($tps,$deb,$end,$idT,$idS,$idM,$idE,$idP);

        $sql->bindParam(1,$date);
        $sql->bindParam(2,$debut);
        $sql->bindParam(3,$fin);
        $sql->bindParam(4,$type);
        $sql->bindParam(5,$salle);
        $sql->bindParam(6,$matiere);
        $sql->bindParam(7,$enseignant);
        $sql->bindParam(8,$promotion);
        $sql->execute();

        unset($date,$debut,$fin,$type,$salle,$matiere,$enseignant,$promotion);

        if($sql->rowCount()) { // It has been deleted
            unset($sql);
            $bd=null;
            return 1;
        } else { // The schedule was not in the database
            unset($sql);
            $bd=null;
            return 0;
        }
    }
?>