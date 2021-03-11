<?php
    function openBD() { // Open the database and return it
        try {
                $bd = new PDO('sqlite:edt.sqlite3');
                $bd->exec("CREATE TABLE IF NOT EXISTS Filiere (id INTEGER PRIMARY KEY, intitule VARCHAR(50) NOT NULL)");
                $bd->exec("CREATE TABLE IF NOT EXISTS Promotion (id INTEGER PRIMARY KEY, intitule VARCHAR(50) NOT NULL, idFiliere INTEGER NOT NULL, FOREIGN KEY(idFiliere) REFERENCES Filiere(id))");
                $bd->exec("CREATE TABLE IF NOT EXISTS Role (id INTEGER PRIMARY KEY, intitule VARCHAR(50))");
                $bd->exec("CREATE TABLE IF NOT EXISTS Utilisateur (id INTEGER PRIMARY KEY, idRole INTEGER NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, mail VARCHAR(60) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, idPromotion INTEGER, FOREIGN KEY(idRole) REFERENCES Role(id), FOREIGN KEY(idPromotion) REFERENCES Promotion(id))");
                $bd->exec("CREATE TABLE IF NOT EXISTS Salle (id INTEGER PRIMARY KEY, intitule VARCHAR(50) NOT NULL, capacite INTEGER NOT NULL)");
                $bd->exec("CREATE TABLE IF NOT EXISTS Matiere (id INTEGER PRIMARY KEY, intitule VARCHAR(50) NOT NULL)");
                $bd->exec("CREATE TABLE IF NOT EXISTS Type (id INTEGER PRIMARY KEY, intitule VARCHAR(50) NOT NULL)");
                $bd->exec("CREATE TABLE IF NOT EXISTS Creneau (id INTEGER PRIMARY KEY, temps VARCHAR(50) NOT NULL, debut VARCHAR(50) NOT NULL, fin VARCHAR(50) NOT NULL, idType INTEGER NOT NULL, idSalle INTEGER NOT NULL, idMatiere INTEGER NOT NULL, idEnseignant INTEGER NOT NULL, idPromotion INTEGER NOT NULL, FOREIGN KEY(idType) REFERENCES Type(id), FOREIGN KEY(idSalle) REFERENCES Salle(id), FOREIGN KEY(idMatiere) REFERENCES Matiere(id), FOREIGN KEY(idEnseignant) REFERENCES Utilisateur(id), FOREIGN KEY(idPromotion) REFERENCES Promotion(id))");
                return $bd;
            } catch(PDOException $except){
                echo "Échec de la connexion",$except->getMessage();
                return FALSE;
                exit();
            }
    }
?>