<?php
require_once 'php/fonction.php';

$professeurs = getProfessors();

if (isset($_POST['professor']) && isset($_POST['trimestre'])) {
    $date = $_POST['trimestre'];

    $array = explode('-', $_POST['professor']);

    $dates = getDates($array[0]);

    if(count($dates) > $date){
        $horaires = horaireByProfessor($array[0], $dates[$date]['dateDebut'], $dates[$date]['dateFin']);
    }

    function displayData($horaires, $i){
        if ($horaires[$i]['nomCours'] == "Trav.Perso.") {
            ?>
            <p style="margin-bottom: 0px;"><?= $horaires[$i]['nomCours'] ?></p>
            <p style="margin-bottom: 0px;"><?= $horaires[$i]['nomSalle'] ?></p>
            <?php
        } else {
            ?>
            <p style="margin-bottom: 0px;" class="font-weight-bold"><?= $horaires[$i]['nomCours'] ?></p>
            <p style="margin-bottom: 0px; font-size: 9pt;" class="font-italic "><?= $horaires[$i]['nomProfesseur'] ?></p>
            <p style="margin-bottom: 0px;" class="font-weight-bold"><?= $horaires[$i]['nomSalle'] ?></p>
            <?php
        }
    }

    function displayHoraire($horaires, $jour, $heure){
        for ($i = 0; $i < count($horaires); $i++) {
            if ($horaires[$i]['jour'] == $jour && $horaires[$i]['heure'] == $heure) {
                displayData($horaires, $i);
                break;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/personal/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"
            integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/"
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"
            integrity="sha256-my/qJggBjG+JoaR9MUSkYM+EpxVkxZRNn3KODs+el74=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.js"
            integrity="sha256-vZy89JbbMLTO6cMnTZgZKvZ+h4EFdvPFupTQGyiVYZg=" crossorigin="anonymous"></script>

    <style>

        body {
            -webkit-print-color-adjust: exact !important;
            font-family: 'Montserrat', sans-serif;
        }

        tr td {
            width: 20%;
        }

        @media screen {
            body * {
                -webkit-print-color-adjust: exact;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.05) !important;
            }

            .table-dark.table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(255, 255, 255, 0.05) !important;
            }
        }

        @media print {
            body * {
                visibility: hidden;
                -webkit-print-color-adjust: exact;
            }

            html,body {
                margin: 0;
            }

            @page { margin: 0; }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.05) !important;
            }
            .table-dark.table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(255, 255, 255, 0.05) !important;
            }

            #section-to-print, #section-to-print * {
                visibility: visible;
            }

            #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>

</head>
<body>
<div class="container align-content-center align-items-center text-center mt-5">
    <h2 class="h2-responsive">Liste des professeurs</h2>
    <a href="index.php"> Liste des étudiants</a>
    <hr>
    <form action="teachers.php" method="post">
        <div class="col-md-8 d-inline-block">
            <select class="mdb-select md-form" id="mySelect" name="professor">
                <option value="" disabled selected>Choix du professeur</option>
                <?php



                for($i = 0; $i < count($professeurs); $i++){
                    ?>
                    <option value="<?= $professeurs[$i]['idProfesseur'] . '-' . $professeurs[$i]['nomProfesseur']; ?>"><?= $professeurs[$i]['nomProfesseur'] ?></option>
                   <?php
                }

                ?>
            </select>
        </div>
        <div class="col-md-3 d-inline-block">
            <select class="mdb-select md-form" id="selectTrimestre" name="trimestre">
                <option value="" disabled selected>Choix de la periode</option>
                <option value="0">Premier trimestre</option>
                <option value="1">Deuxième trimestre</option>
                <option value="2">Troisième trimestre</option>
                <option value="3">Quatrième trimestre</option>
            </select>
        </div>
        <input type="submit" id="submit" class="btn blue-gradient" value="Afficher l'horaire">
    </form>

    <div id="section-to-print" class="container">


        <?php

        if (isset($horaires)) {
            ?>
            <br><br>
            <h1><?= $array[1]?></h1>
            <h6 class="font-italic">Date de début : <?= date("d-m-Y", strtotime($horaires[0]['dateDebut'])) ?> | Date de
                fin : <?= date("d-m-Y", strtotime($horaires[0]['dateFin'])) ?> </h6>

            <table class="table table-striped text-center my-5">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col" class="border-left border-right border-top">Lundi</th>
                    <th scope="col" class="border-left border-right border-top">Mardi</th>
                    <th scope="col" class="border-left border-right border-top">Mercredi</th>
                    <th scope="col" class="border-left border-right border-top">Jeudi</th>
                    <th scope="col" class="border-left border-right border-top">Vendredi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row" class="border-right border-left">8:15 <br/> - <br/> 9:00</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H1");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H1");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H1");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H1");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H1");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">9:05 <br/> - <br/>  9:50</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H2");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H2");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H2");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H2");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H2");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">10:10 - 10:55</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H3");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H3");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H3");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H3");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H3");

                    ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">11:00 - 11:45</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H4");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H4");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H4");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H4");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H4");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">11:50 - 12:35</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H5");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H5");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H5");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H5");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H5");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">12:40 - 13:25</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H6");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H6");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H6");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H6");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H6");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">13:45 - 14:30</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H7");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H7");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H7");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H7");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H7");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">14:35 - 15:20</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H8");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H8");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H8");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H8");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H8");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">15:35 - 16:20</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H9");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H9");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H9");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H9");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H9");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left">16:25 - 17:10</th>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "lundi", "H10");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mardi", "H10");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "mercredi", "H10");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "jeudi", "H10");

                        ?></td>
                    <td class="border-right"><?php

                        displayHoraire($horaires, "vendredi", "H10");

                        ?></td>
                </tr>
                <tr>
                    <th scope="row" class="border-right border-left border-bottom">17:15 - 18:00</th>
                    <td class="border-right border-bottom"><?php

                        displayHoraire($horaires, "lundi", "H11");

                        ?></td>
                    <td class="border-right border-bottom"><?php

                        displayHoraire($horaires, "mardi", "H11");

                        ?></td>
                    <td class="border-right border-bottom"><?php

                        displayHoraire($horaires, "mercredi", "H11");

                        ?></td>
                    <td class="border-right border-bottom"><?php

                        displayHoraire($horaires, "jeudi", "H11");

                        ?></td>
                    <td class="border-right border-bottom"><?php

                        displayHoraire($horaires, "vendredi", "H11");

                        ?></td>
                </tr>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</div>
</body>
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<!-- Tooltips -->
<script type="text/javascript" src="js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>

<script>
    // Material Select Initialization
    $(document).ready(function () {
        $('.mdb-select').materialSelect();

        $('#submit').prop('disabled', true);

        $('#mySelect').one('change', function () {
            $('#selectTrimestre').one('change', function () {
                $('#submit').prop('disabled', false);
            })
        });

        $('#selectTrimestre').one('change', function () {
            $('#mySelect').one('change', function () {
                $('#submit').prop('disabled', false);
            })
        });
    });
</script>
</html>