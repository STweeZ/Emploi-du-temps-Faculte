<!doctype html>
<html lang="fr">
<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    if(isset($_POST['nom'])) { // If the form is filled
        $bd=openBD();
        $sql=$bd->prepare('SELECT COUNT(*) as count FROM Utilisateur WHERE mail=?');

        $mail=htmlspecialchars($_POST['mail']);

        $sql->bindParam(1,$mail);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        unset($sql);

        if($row['count']) { // If the user is already existing in the database
            unset($mail,$row);
            $bd=null;
            ?><script>alert("L'utilisateur existe déjà.");window.location.href = "ajoutUtilisateur.php";</script><?php
        } else { // Insert it in the database
            $getRoleID=$bd->prepare('SELECT id FROM Role WHERE intitule = ?');
            $role="Enseignant";
            $getRoleID->bindParam(1,$role);
            $getRoleID->execute();
            $idRole=($getRoleID->fetchAll())[0]['id'];
            unset($getRoleID,$id);

            $sql=$bd->prepare('INSERT INTO Utilisateur (idRole,nom,prenom,mail,mot_de_passe) VALUES (?,?,?,?,?)');
            $nom=htmlspecialchars($_POST['nom']);
            $prenom=htmlspecialchars($_POST['prenom']);
            $sql->bindParam(1,$idRole);
            $sql->bindParam(2,$nom);
            $sql->bindParam(3,$prenom);
            $sql->bindParam(4,$mail);
            $mdp=password_hash(htmlspecialchars($_POST['mdp']),PASSWORD_DEFAULT);
            $sql->bindParam(5,$mdp);
            $sql->execute();
            unset($idRole,$nom,$prenom,$mail,$mdp,$sql,$row);
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!-- Form to add a user -->
            <label for="nom"><b>Nom</b></label>
            <br>
            <input type="text" id="nom" class="form-control" name="nom" required>
            <label for="prenom"><b>Prénom</b></label>
            <br>
            <input type="text" id="prenom" class="form-control" name="prenom" required>
            <label for="mail"><b>Adresse mail</b></label>
            <br>
            <input type="email" id="mail" class="form-control" name="mail" placeholder="mail@exemple.com" required>
            <label for="mdp"><b>Mot de passe</b></label>
            <br>
            <input type="password" id="mdp" class="form-control" name="mdp" required>
            <br>
            <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Ajouter</button>
            <br>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>