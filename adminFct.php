<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    $bd=openBD();
    if(getRole($bd) == "Administratif") { // Just for the administrators
?>
        <div class="form-inline"> <!-- Three buttons -> Add a user, add a classroom, add a subject -->
            <form style="margin-top:20px; width: 23.2rem;" method="post" action="ajoutEtudiant.php">
                <input type="hidden" id="insertion" name="insertion">
                <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Ajout Etudiant</button>
            </form>
            <form style="margin-top:20px; width: 23.2rem;" method="post" action="ajoutEnseignant.php">
                <input type="hidden" id="insertion" name="insertion">
                <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Ajout Enseignant</button>
            </form>
            <form style="margin-top:20px; width: 23.2rem;" method="post" action="ajoutSalle.php">
                <input type="hidden" id="deletion" name="deletion">
                <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Ajout Salle</button>
            </form>
            <form style="margin-top:20px; width: 23.2rem;" method="post" action="ajoutMatiere.php">
                <input type="hidden" id="deletion" name="deletion">
                <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Ajout Matière</button>
            </form>
        </div>
<?php
    }
    $bd=null;
?>