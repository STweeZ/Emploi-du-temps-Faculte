<!doctype html>
<html lang="fr">
<?php
    session_start();
    if(isset($_SESSION['user'])) { // The user is already connected
        header("Location: edt.php");
        exit();
    }
    /*
    require_once("openBD.php");
    $bd=openBD();
    require_once("setBD.php");
    $salle=array(array("intitule" => "S23", "capacite" => 100),
        array("intitule" => "S24", "capacite" => 150),
        array("intitule" => "S25", "capacite" => 200),
        array("intitule" => "E13", "capacite" => 25),
        array("intitule" => "E14", "capacite" => 30),
        array("intitule" => "E15", "capacite" => 25),
        array("intitule" => "G007", "capacite" => 20),
        array("intitule" => "G008", "capacite" => 30),
        array("intitule" => "G009", "capacite" => 30),
        array("intitule" => "G310", "capacite" => 30),
        array("intitule" => "G311", "capacite" => 40),
        array("intitule" => "D005", "capacite" => 25),
        array("intitule" => "D006", "capacite" => 30),
        array("intitule" => "D007", "capacite" => 25));
    $matiere=["Réseaux","TEC","LCPF","COO","CPA","Programmation Web","Java","Javascript","Python","C","Unix et Shell","Anglais","Allemand","Espagnol"];
    $utilisateur=array(array("idRole" => 1,"nom"=> "Einstein","prenom"=> "Albert","mail"=> "einstein_albert@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> 3),
        array("idRole" => 2,"nom"=> "Koitka","prenom"=> "Johan","mail"=> "koitka_johan@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Tabia","prenom"=> "Karim","mail"=> "tabia_karim@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "De Lima","prenom"=> "Tiago","mail"=> "delima_tiago@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Vincent","prenom"=> "Catherine","mail"=> "vincent_catherine@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Lietard","prenom"=> "Thibault","mail"=> "lietard_thibault@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Le Berre","prenom"=> "Daniel","mail"=> "leberre_daniel@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Pino Perez","prenom"=> "Ramon","mail"=> "pinoperez_ramon@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Deboissy","prenom"=> "Helene","mail"=> "deboissy_helene@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Jabbour","prenom"=> "Said","mail"=> "jabour_said@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Klipfel","prenom"=> "Arthur","mail"=> "klipfel_arthur@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 2,"nom"=> "Caron Boilly","prenom"=> "Julien","mail"=> "caronboilly_julien@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL),
        array("idRole" => 3,"nom"=> "Delacroix","prenom"=> "Gregoire","mail"=> "delacroix_gregoire@ens.univ-artois.fr","mot_de_passe"=> password_hash("123mdp",PASSWORD_DEFAULT),"idPromotion"=> NULL));
    $creneau=array(array("temps"=> "2021-03-01","debut"=> "2021-03-01 10:15:00","fin"=> "2021-03-01 11:15:00","idType"=> 1,"idSalle"=> 3,"idMatiere"=> 2,"idEnseignant"=> 3,"idPromotion"=> 3),
        array("temps"=> "2021-03-01","debut"=> "2021-03-01 11:15:00","fin"=> "2021-03-01 12:15:00","idType"=> 2,"idSalle"=> 3,"idMatiere"=> 2,"idEnseignant"=> 3,"idPromotion"=> 3),
        array("temps"=> "2021-03-01","debut"=> "2021-03-01 14:00:00","fin"=> "2021-03-01 15:30:00","idType"=> 1,"idSalle"=> 1,"idMatiere"=> 3,"idEnseignant"=> 4,"idPromotion"=> 3),
        array("temps"=> "2021-03-02","debut"=> "2021-03-02 09:30:00","fin"=> "2021-03-02 10:30:00","idType"=> 2,"idSalle"=> 11,"idMatiere"=> 6,"idEnseignant"=> 2,"idPromotion"=> 3),
        array("temps"=> "2021-03-02","debut"=> "2021-03-02 10:45:00","fin"=> "2021-03-02 12:45:00","idType"=> 2,"idSalle"=> 6,"idMatiere"=> 12,"idEnseignant"=> 5,"idPromotion"=> 3),
        array("temps"=> "2021-03-02","debut"=> "2021-03-02 14:00:00","fin"=> "2021-03-02 15:30:00","idType"=> 1,"idSalle"=> 1,"idMatiere"=> 6,"idEnseignant"=> 2,"idPromotion"=> 3),
        array("temps"=> "2021-03-02","debut"=> "2021-03-02 15:45:00","fin"=> "2021-03-02 17:30:00","idType"=> 2,"idSalle"=> 5,"idMatiere"=> 4,"idEnseignant"=> 7,"idPromotion"=> 3),
        array("temps"=> "2021-03-03","debut"=> "2021-03-03 08:45:00","fin"=> "2021-03-03 10:30:00","idType"=> 3,"idSalle"=> 7,"idMatiere"=> 2,"idEnseignant"=> 3,"idPromotion"=> 3),
        array("temps"=> "2021-03-03","debut"=> "2021-03-03 13:45:00","fin"=> "2021-03-03 15:45:00","idType"=> 3,"idSalle"=> 7,"idMatiere"=> 3,"idEnseignant"=> 6,"idPromotion"=> 3),
        array("temps"=> "2021-03-03","debut"=> "2021-03-03 16:00:00","fin"=> "2021-03-03 18:00:00","idType"=> 3,"idSalle"=> 8,"idMatiere"=> 4,"idEnseignant"=> 6,"idPromotion"=> 3),
        array("temps"=> "2021-03-04","debut"=> "2021-03-04 09:00:00","fin"=> "2021-03-04 10:30:00","idType"=> 2,"idSalle"=> 10,"idMatiere"=> 3,"idEnseignant"=> 6,"idPromotion"=> 3),
        array("temps"=> "2021-03-04","debut"=> "2021-03-04 13:45:00","fin"=> "2021-03-04 15:45:00","idType"=> 3,"idSalle"=> 9,"idMatiere"=> 6,"idEnseignant"=> 2,"idPromotion"=> 3));
    setBD($bd,$salle,$matiere,$utilisateur,$creneau);
    $bd=null;
    */
?>
<head>
    <meta charset="utf-8">
    <title>Projet Programmation Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<form method="post" action="verifConnexion.php"> <!-- Form of connection -->
        <legend>Connectez-vous : </legend>
        <div class="mb-3">
            <label for="mail" class="form-label">Adresse mail</label>
            <input type="email" class="form-control" id="mail" name="mail" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
	</form>
    <div class="card-body">
        <h1 class="card-title text-center">Bienvenue sur l'emploi du temps de la Faculté des Sciences de Jean Perrin</h1><br>
        <div class="text-center"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalScrollable">Fonctionnalités</button></div>
        <div class="modal fade text-dark" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Fonctionnalités</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        3 rôles :<br><br>
                        Etudiant (Mail : einstein_albert@ens.univ-artois.fr | Mot de passe : 123mdp)<br>
                        Enseignant (Mail : koitka_johan@ens.univ-artois.fr | Mot de passe : 123mdp)<br>
                        Administratif (Mail : delacroix_gregoire@ens.univ-artois.fr | Mot de passe : 123mdp)<br><br>
                        Tout le monde peut observer l'emploi du temps. L'étudiant est limité à son propre emploi du temps.<br>
                        Changement de semaine via le menu présent à gauche du semainier.<br>
                        Les enseignants et administratifs peuvent observer tous les emplois du temps, par promotion, par salle ou par enseignant via le menu présent sur la barre de navigation.<br><br>
                        Seuls les enseignants ont la possibilité d'insérer un nouveau créneau, d'en supprimer un ou d'en modifier un :<br>
                        Insertion via le bouton présent à gauche du semainier.<br>
                        Modification et suppression lors d'un clic gauche sur un créneau.<br><br>
                        Seuls les administratifs ont la possibilité d'ajouter un nouvel étudiant, enseignant ou administratif, ainsi qu'une salle ou une matière :<br>
                        Ajout d'un utilisateur via les boutons présents à gauche du semainier.<br>
                        Ajout d'une salle ou d'une matière via les boutons présents à gauche du semainier.<br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>
