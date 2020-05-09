<?php

session_start();

define('DB_HOST', "localhost");
define('DB_NAME', "horaire");
define('DB_USER', "root");
define('DB_PASS', "");

function getConnexion()
{
    static $dbb = null;
    if ($dbb === null) {
        try {
            $connectionString = 'mysql:host=' . DB_HOST . ';port=3308;dbname=' . DB_NAME . '';
            $dbb = new PDO($connectionString, DB_USER, DB_PASS);
            $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbb->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    return $dbb;
}

function clearDatabase(){
    try {
        $request = getConnexion()->prepare("SET FOREIGN_KEY_CHECKS = 0");
        $request->execute();

        $request = getConnexion()->prepare("DELETE FROM `Etudiant`");
        $request->execute();

        $request = getConnexion()->prepare("TRUNCATE TABLE `Etudiant`");
        $request->execute();

        $request = getConnexion()->prepare("DELETE FROM `Groupe`");
        $request->execute();

        $request = getConnexion()->prepare("TRUNCATE TABLE `Groupe`");
        $request->execute();

        $request = getConnexion()->prepare("DELETE FROM `Cours`");
        $request->execute();

        $request = getConnexion()->prepare("TRUNCATE TABLE `Cours`");
        $request->execute();

        $request = getConnexion()->prepare("DELETE FROM `Salle`");
        $request->execute();

        $request = getConnexion()->prepare("TRUNCATE TABLE `Salle`");
        $request->execute();

        $request = getConnexion()->prepare("DELETE FROM `Professeur`");
        $request->execute();

        $request = getConnexion()->prepare("TRUNCATE TABLE `Professeur`");
        $request->execute();

        $request = getConnexion()->prepare("DELETE FROM `Horaire`");
        $request->execute();

        $request = getConnexion()->prepare("TRUNCATE TABLE `Horaire`");
        $request->execute();

        $request = getConnexion()->prepare("SET FOREIGN_KEY_CHECKS = 1");
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function ifGroupExist($name)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * from `groupe` WHERE `nomGroupe` = :nom");
        $request->bindParam(':nom', $name, PDO::PARAM_STR);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function newGroup($name)
{
    try {
        $request = getConnexion()->prepare("INSERT INTO `groupe` (`nomGroupe`) VALUES (:name)");
        $request->bindParam('name', $name, PDO::PARAM_STR);
        $request->execute();

        return getConnexion()->lastInsertId();
    } catch (PDOException $e) {
        throw $e;
    }
}

function newStudent($name, $firstName, $gender, $idGroup)
{
    try {
        $request = getConnexion()->prepare("INSERT INTO `etudiant` (`nomEtudiant`, `prenomEtudiant`, `sexe`, `idGroupe`) VALUES (:name, :firstName, :gender, :idGroup)");
        $request->bindParam('name', $name, PDO::PARAM_STR);
        $request->bindParam('firstName', $firstName, PDO::PARAM_STR);
        $request->bindParam('gender', $gender, PDO::PARAM_STR);
        $request->bindParam('idGroup', $idGroup, PDO::PARAM_INT);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function ifCoursExist($name)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * from `cours` WHERE `nomCours` = :nom");
        $request->bindParam(':nom', $name, PDO::PARAM_STR);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function newCours($name)
{
    try {
        $request = getConnexion()->prepare("INSERT INTO `cours` (`nomCours`) VALUES (:name)");
        $request->bindParam('name', $name, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function ifSalleExist($name)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * from `salle` WHERE `nomSalle` = :nom");
        $request->bindParam(':nom', $name, PDO::PARAM_STR);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function newSalle($name)
{
    try {
        $request = getConnexion()->prepare("INSERT INTO `salle` (`nomSalle`) VALUES (:name)");
        $request->bindParam('name', $name, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function ifProfExist($name)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * from `professeur` WHERE `nomProfesseur` = :nom");
        $request->bindParam(':nom', $name, PDO::PARAM_STR);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function newProf($name)
{
    try {
        $request = getConnexion()->prepare("INSERT INTO `professeur` (`nomProfesseur`) VALUES (:name)");
        $request->bindParam('name', $name, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function newHoraire($idGroupe, $dateDebut, $dateFin, $jour, $heure, $idProf, $idCours, $idSalle)
{
    try {
        $request = getConnexion()->prepare("INSERT INTO `horaire` (`idGroupe`, `idProfesseur`, `idSalle`, `idCours`, `dateDebut`, `dateFin`, `jour`, `heure`) VALUES (:idGroupe, :idProfesseur, :idSalle, :idCours, :dateDebut, :dateFin, :jour, :heure)");
        $request->bindParam('idGroupe', $idGroupe, PDO::PARAM_INT);
        $request->bindParam('idProfesseur', $idProf, PDO::PARAM_STR);
        $request->bindParam('idSalle', $idSalle, PDO::PARAM_STR);
        $request->bindParam('idCours', $idCours, PDO::PARAM_STR);
        $request->bindParam('dateDebut', $dateDebut, PDO::PARAM_STR);
        $request->bindParam('dateFin', $dateFin, PDO::PARAM_STR);
        $request->bindParam('jour', $jour, PDO::PARAM_STR);
        $request->bindParam('heure', $heure, PDO::PARAM_STR);
        $request->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

function horaireByStudent($id, $dateDebut, $dateFin)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT groupe.nomGroupe, horaire.dateDebut, horaire.dateFin, horaire.jour, horaire.heure, professeur.nomProfesseur, cours.nomCours, salle.nomSalle from horaire JOIN groupe on groupe.idGroupe = horaire.idGroupe JOIN etudiant on etudiant.idGroupe = groupe.idGroupe left join professeur on professeur.idProfesseur = horaire.idProfesseur JOIN cours on cours.idCours = horaire.idCours LEFT JOIN salle on salle.idSalle = horaire.idSalle where etudiant.idEtudiant = :id and horaire.dateDebut = :dateDebut and horaire.dateFin = :dateFin");
        $request->bindParam(':id', $id, PDO::PARAM_INT);
        $request->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
        $request->bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function getDatesStudent($idStudent)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT DISTINCT horaire.dateDebut, horaire.dateFin FROM horaire WHERE horaire.idGroupe = :idStudent");
        $request->bindParam(':idStudent', $idStudent, PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function getDates($idProf)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT DISTINCT horaire.dateDebut, horaire.dateFin FROM horaire WHERE horaire.idProfesseur = :idProf");
        $request->bindParam(':idProf', $idProf, PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function horaireByProfessor($id, $dateDebut, $dateFin)
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT DISTINCT groupe.nomGroupe, horaire.dateDebut, horaire.dateFin, horaire.jour, horaire.heure, professeur.nomProfesseur, cours.nomCours, salle.nomSalle from horaire LEFT JOIN groupe on groupe.idGroupe = horaire.idGroupe LEFT JOIN etudiant on etudiant.idGroupe = groupe.idGroupe LEFT JOIN professeur on professeur.idProfesseur = horaire.idProfesseur LEFT JOIN cours on cours.idCours = horaire.idCours LEFT JOIN salle on salle.idSalle = horaire.idSalle where horaire.idProfesseur = :idProf and horaire.dateDebut = :dateDebut and horaire.dateFin = :dateFin");
        $request->bindParam(':idProf', $id, PDO::PARAM_INT);
        $request->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
        $request->bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}

function getProfessors()
{
    try {
        $connexion = getConnexion();
        $request = $connexion->prepare("SELECT * FROM professeur ORDER BY nomProfesseur ASC");
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw $e;
    }
}