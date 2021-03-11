<?php

    $months = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"); // List of the months in french

    if(!isset($_SESSION['date'])) { // Set the date by the actual date
        $timestamp = time();
        $day = date('w')-1;
        $actualDate=date('d/m/y', strtotime('-'.$day.' days'));
        $m = date('m', strtotime('-'.$day.' days'));
        $d = date('d', strtotime('-'.$day.' days'));
        $y = date('Y', strtotime('-'.$day.' days'));
        $_SESSION['date']=$y."-".$m."-".$d;
        unset($timestamp,$day,$actualDate,$m,$d,$y);
        header("Location: edt.php");
    } else { // We want to print a specific date
        $d = date('d', strtotime(htmlspecialchars($_SESSION['date'])));
        $m = date('m', strtotime(htmlspecialchars($_SESSION['date'])));
        $y = date('Y', strtotime(htmlspecialchars($_SESSION['date'])));
    }

    if(isset($_POST['numberWeek'])) { // Click on precedent week
        if(htmlspecialchars($_POST['numberWeek']) == "-1") {
            $_SESSION['date'] = date('Y-m-d',strtotime(htmlspecialchars($_SESSION['date']).' - 7 days'));
            header("Location: edt.php");
        } else { // Click on next week
            $_SESSION['date'] = date('Y-m-d',strtotime(htmlspecialchars($_SESSION['date']).' + 7 days'));
            header("Location: edt.php");
        }
    }
    $tab="";
    for($i=0; $i<5; $i++) { // The array of the days of the week
        $tab=$tab.date('Y-m-d',strtotime(htmlspecialchars($_SESSION['date']).' + '.$i.' days'))." ";
    }
    $tab=$tab.date('Y-m-d',strtotime(htmlspecialchars($_SESSION['date']).' + 5 days'));
    $_SESSION['days']="\"".$tab."\"";
    unset($tab);

?>
<div class="card shadow-sm"> <!-- The buttons for the selection of the week to print -->
    <h3 class="card-header monthYear"><span id="monthAndYear" onclick="selectAWeek()">
        <?php
            echo "Lundi ".$d.' '.$months[(int)($m-1)].' '.$y;
            unset($months,$m,$d,$y);
        ?>
    </span></h3>
    <div class="form-inline">
        <form style="margin-top:20px; width: 23.2rem;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" id="numberWeek" name="numberWeek" value="-1">
            <button style="width: 100%;" class="btn btn-outline-info btn-lg" type="submit">Précédent</button>
        </form>
    </div>
    <div class="form-inline">
        <form style="margin-top:20px; width: 23.2rem;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" id="numberWeek" name="numberWeek" value="+1">
            <button style="width: 100%;" class="btn btn-outline-info btn-lg" type="submit">Suivant</button>
        </form>
    </div>
</div>
<script>

window.onload = function () { 
    selectAWeek();
}

let months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]; // List of the months in french
let monthsEN = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; // List of the months in english
let days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"]; // List of the days in french
let hours = ["8 H", "9 H", "10 H", "11 H", "12 H", "13 H", "14 H", "15 H", "16 H", "17 H", "18 H", "19 H"]; // List of the schedules
let selectedWeek = []; // Array of the days of the week selected

function pad(d) { // Transform a one-item digit to a two-item digit
    return (d < 10) ? '0' + d.toString() : d.toString();
}

function printSelectedDay(week,selectedDay) {

    let table = document.createElement("table");
    constructEdt(week, table); // Construction of the agenda skeleton
    finishEdt(week, table, selectedDay); // Add the schedule to the agenda skeleton
    return table;
}

function selectAWeek() { // Action when we select a week
    selectedWeek = []; // Array with the days of the week as content

    jours=(<?php echo $_SESSION['days']; ?>).split(' ');
    joursBrut=[[]];
    for(let i = 0; i < 6; i++) {
        joursBrut[i]=jours[i].split('-');
    }
    delete(jours);
    for(let i = 0; i < 6; i++) { // For each cell of the row
        selectedWeek.push(pad(parseInt(joursBrut[i][0]))+"-"+pad(parseInt(joursBrut[i][1]))+"-"+pad(parseInt(joursBrut[i][2]))); // Push the exact date of each day in the row
    }
    delete(joursBrut);
    let tbl = document.getElementById("selectedWeek"); // The location where we will show the agenda week
    tbl.innerHTML = ""; // Reset the location where we will show the agenda week
    tbl.appendChild(printSelectedWeek(selectedWeek)); // We continue the action in an other function, we going to show it on the HTML page
    delete(selectedWeek);
}

function printSelectedWeek(week) { // Print the week selected
    let div = document.createElement("div"); // The div that will contains the week
    div.classList.add("oneWeek");

    let container = document.createElement("div"); // The principal container
    container.classList.add("container-fluid");

    let div2 = document.createElement("div"); // The div that will contains the agenda
    div2.classList.add("card");

    let title = document.createElement("h3"); // The title that is the exact date of each day
    title.classList.add("card-header");
    title.classList.add("dayOfWeek");

    datesOfWeek = printDatesOfWeek(week[0]); // Array with each element of the day's date
    for(let i = 0; i < monthsEN.length; i++) { // Set the english month to the french month
        if (monthsEN[i] === datesOfWeek[2]) {
            datesOfWeek[2] = months[i];
            break;
        }
    }
    let text = document.createTextNode(datesOfWeek[0] + " " + datesOfWeek[1] + " " + datesOfWeek[2] + " " + datesOfWeek[3]); // The exact date of the day i
    delete(datesOfWeek);

    title.appendChild(text); // Add the text to the title location
    div2.appendChild(title); // Add this title to the div

    div2.appendChild(printSelectedDay(week)); // Add the table to the div
    container.appendChild(div2);

    div.appendChild(container);

    return div;
}

function printDatesOfWeek(o) { // Return the complete date of the i day in the week
    let y = o.substring(0,4); // The year
    let m = monthsEN[o.substring(5,7) - 1]; // The month
    let d = o.substring(8,10); // The day
    let j = (new Date(m + " " + d + ", " + y).getDay()); // The day of the week
    return [days[j - 1], d, m, y];
}

function constructEdt(week,table) { // Construct the agenda skeleton
    table.classList.add("table", "table-bordered", "table-responsive-lg");
    table.id = "edt";

    let thead = document.createElement("thead"); // The head of the agenda
    let tr = document.createElement("tr"); // The first row

    let th = document.createElement("th"); // The first th cell
    th.classList.add("edtTopColumn");
    tr.appendChild(th); // Add the cell to the row

    for(let i = 0; i < week.length; i++) { // For each day of school week
        let th = document.createElement("th"); // We create a cell that contains the day
        th.classList.add("edtRowSup");

        datesOfWeek = printDatesOfWeek(week[i]); // Array with each element of the day's date
        for(let i = 0; i < monthsEN.length; i++) { // Set the english month to the french month
            if (monthsEN[i] === datesOfWeek[2]) {
                datesOfWeek[2] = months[i];
                break;
            }
        }
        let text = document.createTextNode(datesOfWeek[0] + " " + datesOfWeek[1] + " " + datesOfWeek[2] + " " + datesOfWeek[3]); // The exact date of the day i
        delete(datesOfWeek);

        th.appendChild(text); // Add the text to the cell
        tr.appendChild(th); // Add the cell to the row
    }
    thead.appendChild(tr); // We add the row to the head of the agenda
    table.appendChild(thead); // We add this head to the agenda
}

function diff_minutes(dt2, dt1) { // Return the difference of time between two dates dt1 and dt2
    var diff =(dt2.getTime() - dt1.getTime()) / 1000;
    diff /= 60;
    return Math.abs(Math.round(diff));
}

function finishEdt(week, table, selectedDay) { // Finish to build the agenda
    let tbody = document.createElement("tbody"); // The body of the agenda
    tbody.classList.add("edt-body");

    actualSdl = new Date(week[0]+"T08:00:00"); // Used for comparaison

    weekSelected = <?php
        require_once("openBD.php");
        $bd=openBD();
        $spec=[];
        if(isset($_SESSION['seeSpec'])) { // Get the type of agenda we want to see
            $spec=explode("_",htmlspecialchars($_SESSION['seeSpec']));
        }
        $tab=explode(" ",htmlspecialchars($_SESSION['days']));
        $sql="SELECT temps,debut,fin,idType,idSalle,idMatiere,idEnseignant,idPromotion FROM Creneau WHERE";
        if($spec!=[]) {
            switch($spec[0]) {
                case "promotion":
                    $sql = $sql." idPromotion = ".$spec[1]." AND";
                    break;
                case "enseignant":
                    $sql = $sql." idEnseignant = ".$spec[1]." AND";
                    break;
                case "salle":
                    $sql = $sql." idSalle = ".$spec[1]." AND";
                    break;
            }
        }
        unset($idPromotion,$spec);
        $sql = $sql." (temps=? OR temps=? OR temps=? OR temps=? OR temps=? OR temps=?)";
        $result=$bd->prepare($sql);
        $valOne=substr($tab[0],6);
        $result->bindParam(1,$valOne);
        $result->bindParam(2,$tab[1]);
        $result->bindParam(3,$tab[2]);
        $result->bindParam(4,$tab[3]);
        $result->bindParam(5,$tab[4]);
        $result->bindParam(6,$tab[5]);
        $result->execute();
        unset($valOne,$tab,$sql);
        $resultat="\"";
        $start=0;
        while($row = $result->fetch(PDO::FETCH_ASSOC)) { // Search the schedules concerned in the database
            if($start==1) $resultat = $resultat.";";
            else $start = 1;

            $type=($bd->query("SELECT intitule FROM Type WHERE id LIKE '%".$row['idType']."%'"))->fetch(PDO::FETCH_ASSOC);
            $salle=($bd->query("SELECT intitule FROM Salle WHERE id LIKE '%".$row['idSalle']."%'"))->fetch(PDO::FETCH_ASSOC);
            $matiere=($bd->query("SELECT intitule FROM Matiere WHERE id LIKE '%".$row['idMatiere']."%'"))->fetch(PDO::FETCH_ASSOC);
            $enseignant=($bd->query("SELECT nom,prenom FROM Utilisateur WHERE id LIKE '%".$row['idEnseignant']."%'"))->fetch(PDO::FETCH_ASSOC);
            $promotion=($bd->query("SELECT intitule FROM Promotion WHERE id LIKE '%".$row['idPromotion']."%'"))->fetch(PDO::FETCH_ASSOC);

            $resultat = $resultat.$row['temps'].",".$row['debut'].",".$row['fin'].",".$type['intitule'].",".$salle['intitule'].",".$matiere['intitule'].",".$enseignant['nom'].",".$enseignant['prenom'].",".$row['idPromotion'].",".$promotion['intitule'];
            unset($type,$salle,$matiere,$enseignant,$promotion);
        }
        $resultat = $resultat."\"";
        echo $resultat;
        unset($result,$resultat,$start);
        $bd=null;
    ?>; // The week the schedule existing in the database
    temp = weekSelected.split(";");
    weekSelected = [];
    temp.forEach(element => weekSelected.push(element.split(","))); // Schedules of the week
    delete(temp);

    week = (<?php echo $_SESSION['days'];?>).split(" "); // Days of the week

    for(let palier=0; palier <= 47; palier++) { // For each schedule
        let tr = document.createElement("tr"); // Create a row
        let td;
        if (pad(actualSdl.getMinutes().toString()) === "00") { // Left column -> Hours for indication
            td = document.createElement("td"); // Create a cell
            td.classList.add("edtLeftColumn");
            td.rowSpan = "4";
            let text = document.createTextNode(pad(actualSdl.getHours())+"h"+pad(actualSdl.getMinutes()));
            td.appendChild(text); // Add the group word to the cell
            tr.appendChild(td); // Add the cell to the row
        }
        let nbCell = 0; // The count of row's cell

        while(nbCell <= 5) { // For each day
            let haveDone = false;
            td = document.createElement("td");
            let truc="<?php echo htmlspecialchars($_SESSION['seeSpec']);?>";
            td.id = week[nbCell]+"/"+pad(actualSdl.getHours().toString())+":"+pad(actualSdl.getMinutes().toString())+":"+pad(actualSdl.getSeconds().toString());

            for(let i=0; i<weekSelected.length; i++) { // Search if a schedule is already inserted
                timeDepart = new Date(weekSelected[i][1]);
                timeEnd = new Date(weekSelected[i][2]);

                dateDebut=new Date(week[nbCell]+"T"+pad(actualSdl.getHours()).toString()+":"+pad(actualSdl.getMinutes()).toString()+":00");
                dateFin=new Date(week[nbCell]+"T"+pad(actualSdl.getHours()).toString()+":"+pad(actualSdl.getMinutes()+15).toString()+":00");

                th=timeDepart.getHours();
                tm=timeDepart.getMinutes();
                dh=dateDebut.getHours();
                dm=dateDebut.getMinutes();

                if(((th != dh && tm != dm) || (th == dh && tm != dm) || (th != dh && tm == dm)) && ((timeDepart < dateDebut && timeEnd > dateDebut) || (timeDepart < dateFin && timeDepart > dateDebut && timeEnd >= dateDebut))) { // There is a collision between the two schedules
                    haveDone = true;
                    break;
                }
                delete(timeDepart,timeEnd,dateDebut,dateFin,th,tm,dh,dm);
            }
            if(!haveDone) { // We can show the cell concerning a schedule
                for(let i=0; i<weekSelected.length; i++) { // Search the schedule to insert
                    timeDepart = new Date(weekSelected[i][1]);
                    if(weekSelected[i][0] == week[nbCell] && timeDepart.getMinutes() == actualSdl.getMinutes() && timeDepart.getHours() == actualSdl.getHours()) { // There is a schedule to print
                        td.classList.add("agendaCellFill");
                        timeEnd = new Date(weekSelected[i][2]);
                        duree = diff_minutes(timeEnd,timeDepart);
                        td.rowSpan = (duree/15).toString();
                        delete(duree);
                        
                        let id = "date="+week[nbCell]+"&debut="+pad(actualSdl.getHours().toString())+":"+pad(actualSdl.getMinutes().toString())+":"+pad(actualSdl.getSeconds().toString())+"&promotion="+weekSelected[i][8];
                        td.addEventListener("click", function() { // Click on this cell will send you to a new window reserved for modifications
                            window.location.replace("modification.php?"+id);
                        });

                        let span = document.createElement("span");
                        span.classList.add("cours", "infoBulle");
                        let text = document.createTextNode(pad(timeDepart.getHours()) + "h" + pad(timeDepart.getMinutes()) + " - " + pad(timeEnd.getHours()) + "h" + pad(timeEnd.getMinutes())); // First line of the cell text -> the hours
                        span.setAttribute("aria-label", pad(timeDepart.getHours()) + "h" + pad(timeDepart.getMinutes()) + " - " + pad(timeEnd.getHours()) + "h" + pad(timeEnd.getMinutes()));
                        span.appendChild(text);
                        td.appendChild(span);
                        td.appendChild(document.createElement("br"));
                        delete(timeEnd);

                        t = weekSelected[i][3];
                        td.classList.add(t.toLowerCase());
                        m = weekSelected[i][5];
                        p = weekSelected[i][6];
                        p2 = weekSelected[i][7];
                        
                        span = document.createElement("span");
                        span.classList.add("cours", "infoBulle");
                        text = document.createTextNode(t + " " + m + " " + p + " " + p2); // Second line of the cell text -> the type of the school subject, the school subject, the teacher
                        span.setAttribute("aria-label", t + " " + m + " " + p + " " + p2)
                        span.appendChild(text);
                        td.appendChild(span);
                        td.appendChild(document.createElement("br"));

                        s = weekSelected[i][4];
                        g = weekSelected[i][9];

                        span = document.createElement("span");
                        span.classList.add("details", "infoBulle");
                        text = document.createTextNode(s + " " + g); // Third line of the cell text -> the classroom and the promotion
                        span.setAttribute("aria-label", s + " " + g)
                        span.appendChild(text);
                        td.appendChild(span);

                        tr.appendChild(td);

                        haveDone = true;
                    }
                    delete(timeDepart);
                }
            }
            if(!haveDone) { // We have to insert an empty cell
                td.appendChild(document.createElement("p"));
                td.appendChild(document.createElement("p"));
                td.classList.add("cellAgendaEmpty");
                if (pad(actualSdl.getMinutes().toString()) === "00") td.classList.add("agendaCellSplit");
                else td.classList.add("agendaCell");
                tr.appendChild(td);
            }
            nbCell++;
        }
        tbody.appendChild(tr); // We add the row to the agenda body
        actualSdl.setMinutes(actualSdl.getMinutes() + 15); // Add the duration
    }
    delete(actualSdl);
    delete(weekSelected);
    delete(week);
    table.appendChild(tbody); // We add this body to the agenda
}

</script>