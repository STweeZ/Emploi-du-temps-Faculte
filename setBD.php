<?php
    function setBD($bd,$salle,$matiere,$utilisateur,$creneau) { // Set values of the database
        $stmt = $bd->prepare("INSERT INTO Role (intitule) VALUES (?)");
        $stmt->execute(array("Etudiant"));
        $stmt->execute(array("Enseignant"));
        $stmt->execute(array("Administratif"));
        $stmt = $bd->prepare("INSERT INTO Filiere (intitule) VALUES (?)");
        $stmt->execute(array("Informatique"));
        $stmt->execute(array("Mathematiques"));
        $stmt->execute(array("SVT"));
        $stmt->execute(array("Chimie"));
        $stmt = $bd->prepare("INSERT INTO Type (intitule) VALUES (?)");
        $stmt->execute(array("CM"));
        $stmt->execute(array("TD"));
        $stmt->execute(array("TP"));
        $sql = "SELECT id FROM Filiere";
        $result = $bd->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$stmt = $bd->prepare("INSERT INTO Promotion (intitule,idFiliere) VALUES (?,?)");
            $stmt->execute(array("Licence 1",$row['id']));
            $stmt->execute(array("Licence 2",$row['id']));
            $stmt->execute(array("Licence 3",$row['id']));
            $stmt->execute(array("Master 1",$row['id']));
            $stmt->execute(array("Master 2",$row['id']));
		}
        $stmt = $bd->prepare("INSERT INTO Salle (intitule,capacite) VALUES (?,?)");
        foreach($salle as $s) {
            $stmt->execute(array($s['intitule'],$s['capacite']));
        }
        $stmt = $bd->prepare("INSERT INTO Matiere (intitule) VALUES (?)");
        foreach($matiere as $m) {
            $stmt->execute(array($m));
        }
        $stmt = $bd->prepare("INSERT INTO Utilisateur (idRole,nom,prenom,mail,mot_de_passe,idPromotion) VALUES (?,?,?,?,?,?)");
        foreach($utilisateur as $e) {
            $stmt->execute(array($e['idRole'],$e['nom'],$e['prenom'],$e['mail'],$e['mot_de_passe'],$e['idPromotion']));
        }
        $stmt = $bd->prepare("INSERT INTO Creneau (temps,debut,fin,idType,idSalle,idMatiere,idEnseignant,idPromotion) VALUES (?,?,?,?,?,?,?,?)");
        foreach($creneau as $c) {
            $stmt->execute(array($c['temps'],$c['debut'],$c['fin'],$c['idType'],$c['idSalle'],$c['idMatiere'],$c['idEnseignant'],$c['idPromotion']));
        }
    }
?>