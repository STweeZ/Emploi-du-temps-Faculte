<?php
    require_once("verifConnected.php");
    require_once("openBD.php");
    $bd=openBD();
    if(getRole($bd) == "Enseignant") { // Just for the teachers
?>
        <div class="form-inline"> <!-- Two buttons -> Insertion, Deletion -->
            <form style="margin-top:20px; width: 23.2rem;" method="post" action="insertion.php">
                <input type="hidden" id="insertion" name="insertion">
                <button style="width: 100%;" class="btn btn-outline-success btn-lg" type="submit">Insertion</button>
            </form>
        </div>
<?php
    }
    $bd=null;
?>