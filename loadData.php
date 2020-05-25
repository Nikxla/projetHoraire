<?php
require_once('php/fonction.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

clearDatabase();

ini_set('max_execution_time', 0);
?>
<!doctype>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
<?php

require_once "Classes/PHPExcel.php";

$tmpfname = "files/Etudiants.xlsx";
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$countSheets = $excelObj->getSheetCount();
$sheetName = $excelObj->getSheetNames();

for($k = 0; $k < count($sheetName); $k++){
    if(strpos($sheetName[$k], "ESIG-CP") !== false){
        $checkGroup = ifGroupExist($sheetName[$k]);

        if($checkGroup == null){
            $trim = trim($sheetName[$k]);
            $final = str_replace(" ", "-", $trim);
            newGroup($final);
        }
    }
}

for($i = 0; $i < $countSheets; $i++){
    $worksheet = $excelObj->getSheet($i);
    $lastRow = $worksheet->getHighestRow();

    for ($row = 1; $row <= $lastRow; $row++) {
        if($row > 3){
            if($worksheet->getCell('B'.$row)->getValue() != null){
                if($worksheet->getCell('E'.$row)->getValue() == "01" || $worksheet->getCell('E'.$row)->getValue() == "02" || $worksheet->getCell('E'.$row)->getValue() == "03" || $worksheet->getCell('E'.$row)->getValue() == "04" || $worksheet->getCell('E'.$row)->getValue() == "05"){
                    $groupe = str_replace(' ', '', $sheetName[$i]) . '/Groupe-' . substr($worksheet->getCell('E'.$row)->getValue(), -1);

                    $checkGroup = ifGroupExist($groupe);
                    if($checkGroup == null){
                        $GroupId = newGroup($groupe);
                    } else {
                        $GroupId = $checkGroup[0]['idGroupe'];
                    }

                    $name = $worksheet->getCell('B'.$row)->getValue();
                    $firstName = $worksheet->getCell('C'.$row)->getValue();
                    $gender = $worksheet->getCell('D'.$row)->getValue();

                    if($worksheet->getCell('F'.$row)->getValue() != null){
                        $email = $worksheet->getCell('F'.$row)->getValue();

                        $identifiant = generateRandomString(10);
                        $password = generateRandomString(10);

                        include_once "PHPMailer/PHPMailer.php";
                        include_once "PHPMailer/Exception.php";

                        $mail = new PHPMailer();
                        $mail->setFrom('esig@eduge.ch');
                        $mail->addAddress($email);
                        $mail->Subject = "Votre login !";
                        $mail->isHTML(true);
                        $mail->Body = "Voici les identifiants pour accéder à votre compte : <br><br>
                                       Identifiant : $identifiant <br><br>
                                       Mot de passe : $password";

                        if (!$mail->send()) {
                            echo 'Mailer Error: '. $mail->ErrorInfo;
                        } else {
                            echo 'Message sent!';
                        }
                        
                        newStudent(ucwords(strtolower($name)), ucwords(strtolower($firstName)), $gender, $GroupId, $email, $identifiant, $password);
                    } else {
                        newStudent(ucwords(strtolower($name)), ucwords(strtolower($firstName)), $gender, $GroupId, null, null, null);
                    }
                } else {
                    $name = $worksheet->getCell('B'.$row)->getValue();
                    $firstName = $worksheet->getCell('C'.$row)->getValue();
                    $gender = $worksheet->getCell('D'.$row)->getValue();

                    $trim = trim($sheetName[$i]);
                    $final = str_replace(" ", "-", $trim);

                    $idGroupe = ifGroupExist($final);

                    if($idGroupe == null){
                        newStudent(ucwords(strtolower($name)), ucwords(strtolower($firstName)), $gender, null, null, null, null);
                    } else {
                        newStudent(ucwords(strtolower($name)), ucwords(strtolower($firstName)), $gender, $idGroupe[0]['idGroupe'], null, null, null);
                    }
                }
            }
        }
    }
}

function generateRandomString($length) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

$tmpfname = "files/Horaires.xlsx";
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

for($j = 0; $j < $lastRow; $j++){
    if($j > 1){
        $cours = $worksheet->getCell('H'.$j)->getValue();

        $checkCours = ifCoursExist($cours);

        if($checkCours == null){
            newCours($cours);
        }

        if($worksheet->getCell('I'.$j)->getValue() != null){
            $salle = $worksheet->getCell('I'.$j)->getValue();

            if($salle == "finir"){
                $salle = "à définir";
            }
            $checkSalle = ifSalleExist($salle);

            if($checkSalle == null){
                newSalle($salle);
            }
        }

        if($worksheet->getCell('G'.$j)->getValue() != null){
            $prof = $worksheet->getCell('G'.$j)->getValue();

            $checkProf = ifProfExist($prof);

            if($checkProf == null){
                newProf($prof);
            }
        }

        if($worksheet->getCell('B'.$j)->getValue() != null){
            $groupe = $worksheet->getCell('B'.$j)->getValue();
            $groupeExploded = explode("/", $groupe);

            if(count($groupeExploded) > 2){
                $groupeFinal = $groupeExploded[0] . "/" . $groupeExploded[2];
            } else {
                $groupeFinal = $groupeExploded[0];
            }

            $getInfosGroupe = ifGroupExist($groupeFinal);

            if($worksheet->getCell('G'.$j)->getValue() != null){
                $profFinal = ifProfExist($worksheet->getCell('G'.$j)->getValue());
                $profFinal = $profFinal[0]['idProfesseur'];
            } else {
                $profFinal = null;
            }

            if($worksheet->getCell('H'.$j)->getValue() != null){
                $coursFinal = ifCoursExist($worksheet->getCell('H'.$j)->getValue());
                $coursFinal = $coursFinal[0]['idCours'];
            }

            if($worksheet->getCell('I'.$j)->getValue() != null){
                if($worksheet->getCell('I'.$j)->getValue() == "finir"){
                    $salle = "à définir";
                } else {
                    $salle = $worksheet->getCell('I'.$j)->getValue();
                }

                $salleFinal = ifSalleExist($salle);

                if($salleFinal != null){
                    if($salleFinal[0]['nomSalle'] == "finir"){
                        $salleFinal = "à définir";
                    } else {
                        $salleFinal = $salleFinal[0]['idSalle'];
                    }
                }
            }

            if($worksheet->getCell('C'.$j)->getFormattedValue() != null && $worksheet->getCell('D'.$j)->getFormattedValue() != null && $worksheet->getCell('C' . $j)->getValue()) {
                $currentHoraire = array(
                    "idGroupe" => $getInfosGroupe[0]['idGroupe'],
                    "date_debut" => $worksheet->getCell('C' . $j)->getFormattedValue(),
                    "date_fin" => $worksheet->getCell('D' . $j)->getFormattedValue(),
                    "jour" => $worksheet->getCell('E'. $j)->getValue(),
                    "heure" => $worksheet->getCell('F'. $j)->getValue(),
                    "idProf" => $profFinal,
                    "idCours" => $coursFinal,
                    "idSalle" => $salleFinal
                );
            }

            if($currentHoraire['idGroupe'] != null && $currentHoraire['date_debut'] != null && $currentHoraire['date_fin'] != null && $currentHoraire['jour'] != null && $currentHoraire['heure'] != null &&$currentHoraire['idCours'] != null &&$currentHoraire['idSalle'] != null ){
                newHoraire(
                        $currentHoraire['idGroupe'],
                        date("Y-m-d", strtotime($currentHoraire['date_debut'])),
                        date("Y-m-d", strtotime($currentHoraire['date_fin'])),
                        $currentHoraire['jour'],
                        $currentHoraire['heure'],
                        $currentHoraire['idProf'],
                        $currentHoraire['idCours'],
                        $currentHoraire['idSalle']
                );
            }
        }
    }
}

?>
<script>
    $( document ).ready(function() {
        alert("Le chargement des données est terminé.");
        window.location.href = "index.php";
    });
</script>
</body>
</html>