<?php
    require_once("verifConnected.php");
    function getRole($bd) { // Return the role of the user connected
        $getRoleID=$bd->prepare('SELECT idRole FROM Utilisateur WHERE id = ?');
        $id=htmlspecialchars($_SESSION['user']);
        $getRoleID->bindParam(1,$id);
        $getRoleID->execute();
        $idRole=($getRoleID->fetchAll())[0]['idRole'];

        $getRoleName=$bd->prepare('SELECT intitule FROM Role WHERE id = ?');
        $getRoleName->bindParam(1,$idRole);
        $getRoleName->execute();
        $getRoleName=($getRoleName->fetchAll())[0]['intitule'];
        unset($getRoleID,$id,$idRole);
        return $getRoleName;
    }
?>