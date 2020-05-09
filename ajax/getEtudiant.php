<?php
require_once '../php/inc.all.php';

// Nécessaire lorsqu'on retourne du json
header("Content-Type: application/json;");

// Je récupère le filtre

    $newEtudiant = EtudiantManager::getInstance()->getStudent();

    if ($newEtudiant !== false)
    {
        $jsn = json_encode($newEtudiant);

        if ($jsn === FALSE)
        {
            $code = json_last_error();
            echo '{ "ReturnCode": 3, "Message": "Un problème de d\'encodage json (' . $code . '"}';
            exit();
        }
        echo '{ "ReturnCode": 0, "Data": ' . $jsn . '}';
        exit();
}
// erreur
echo '{ "ReturnCode": 1, "Message": "Un problème est survenu" }';


