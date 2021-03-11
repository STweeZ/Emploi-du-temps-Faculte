<?php
    require_once("verifConnected.php");
    require_once("openBD.php"); // The lastname and the firstname of the actual user
    require_once("getRole.php");
    $bd=openBD();
?>
<header id="headerNav"> <!-- The navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand header-link menulink" href="edt.php">Gestion de l'emploi du temps</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <?php if(getRole($bd) != "Etudiant") { // Just for the administrators ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélection</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item menulink" href="seePromotion.php">Promotion</a>
                            <a class="dropdown-item menulink" href="seeEnseignant.php">Enseignant</a>
                            <a class="dropdown-item menulink" href="seeSalle.php">Salle</a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <p style="color: white;">
            <?php
                $sql = $bd->prepare('SELECT nom,prenom FROM Utilisateur WHERE id=?');
                $session=htmlspecialchars($_SESSION['user']);
                $sql->bindParam(1,$session);
                $sql->execute();
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    echo $row['nom']." ".$row['prenom'];
                }
                unset($sql,$session,$row);
                $bd=null;
            ?>
            <a class="btn btn-primary btn btn-outline-danger" role="button" href="deconnexion.php">X</a>
        </p>
    </nav>
</header>