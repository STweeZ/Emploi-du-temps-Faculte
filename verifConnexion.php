<?php
    if(isset($_POST['mail']) && isset($_POST['mdp'])) { // Check if the values of connexion are right
		session_start();
        require_once("openBD.php");
        $bd=openBD();

        $sql=$bd->prepare('SELECT id,mot_de_passe FROM Utilisateur WHERE mail=?'); // Select the user by its email
        $mail=htmlspecialchars($_POST['mail']);
        $sql->bindParam(1,$mail);
        $sql->execute();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            if(password_verify(htmlspecialchars($_POST['mdp']),$row['mot_de_passe'])) $_SESSION['user']=$row['id']; // Verify if the password is right
        }
        unset($sql,$mail,$row);
        $bd=null;
        if(!isset($_SESSION['user'])) {
            header("Location: index.php");
		    exit();
        } else {
            header("Location: edt.php");
            exit();
        }
	} else {
        header("Location: edt.php");
		exit();
    }
?>