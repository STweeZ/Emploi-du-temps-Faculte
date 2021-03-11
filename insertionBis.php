<?php

function insertionBis($bd,$date,$tBegin,$tEnd,$t,$s,$m,$e,$p) {
    $correct=true; // We can insert

    $debut=$date." ".$tBegin; // Schedule given
    $fin=$date." ".$tEnd;

    $timeDebut=date($debut);
    $timeFin=date($fin);
    $dateDebut=date($date." 08:00:00"); // Schedule min and max possible
    $dateFin=date($date." 20:00:00");

    $minutesD=explode(":",$tBegin)[1];
    $minutesF=explode(":",$tEnd)[1];

    if(($minutesD != "00" && $minutesD != "15" && $minutesD != "30" && $minutesD != "45") || ($minutesF != "00" && $minutesF != "15" && $minutesF != "30" && $minutesF != "45")) { // Course by 15 minutes
        unset($date,$correct,$debut,$fin,$timeDebut,$timeFin,$minutesD,$minutesF);
        return 2;
    }

    unset($minutesD,$minutesF);

    if((($timeFin <= $timeDebut) || ($timeDebut < $dateDebut || $timeFin <= $dateDebut || ($timeFin > $dateFin || $dateDebut >= $dateFin)))) $correct = false; // The schedule in not well formed

    if($correct) { // Check if the schedule given is not in collision with an other schedule in the database
        $sql=$bd->prepare('SELECT * FROM Creneau WHERE temps = ? AND (idSalle = ? OR idEnseignant = ? OR idPromotion = ? )');
        $sql->bindParam(1,$date);
        $salle=htmlspecialchars($s);
        $sql->bindParam(2,$salle);
        $enseignant=htmlspecialchars($e);
        $sql->bindParam(3,$enseignant);
        $promotion=htmlspecialchars($p);
        $sql->bindParam(4,$promotion);
        $sql->execute();
        while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
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
        unset($row,$sql,$salle,$enseignant,$promotion);
    }
    unset($timeDebut,$timeFin);

    if($correct) { // We finally can insert the schedule in the database
        $bd=openBD();
        $sql=$bd->prepare("INSERT INTO Creneau (temps,debut,fin,idType,idSalle,idMatiere,idEnseignant,idPromotion) VALUES (?,?,?,?,?,?,?,?);");

        $debut=htmlspecialchars($debut);
        $fin=htmlspecialchars($fin);
        $type=htmlspecialchars($t);
        $salle=htmlspecialchars($s);
        $matiere=htmlspecialchars($m);
        $enseignant=htmlspecialchars($e);
        $promotion=htmlspecialchars($p);

        $sql->bindParam(1,$date);
        $sql->bindParam(2,$debut);
        $sql->bindParam(3,$fin);
        $sql->bindParam(4,$type);
        $sql->bindParam(5,$salle);
        $sql->bindParam(6,$matiere);
        $sql->bindParam(7,$enseignant);
        $sql->bindParam(8,$promotion);
        $sql->execute();
        unset($date,$debut,$fin,$type,$sql,$salle,$matiere,$enseignant,$promotion);
        $bd=null;
    }
    unset($tBegin,$tEnd,$t,$s,$m,$e,$p);
    return $correct;
}

?>