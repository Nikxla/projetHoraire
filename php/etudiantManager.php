<?php
require_once 'database.php';
require_once 'etudiant.php';

/**
 * @brief	Helper class pour gérer les EUser
 * @author 	dominique.aigroz@edu.ge.ch
 * @remark
 */

class EtudiantManager {
    private static $objInstance;
    /**
     * @brief	Class Constructor - Create a new EUserManager if one doesn't exist
     * 			Set to private so no-one can create a new instance via ' = new EUserManager();'
     */
    private function __construct() {
        $this->student = array();
    }
    /**
     * @brief	Like the constructor, we make __clone private so nobody can clone the instance
     */
    private function __clone() {}
    /**
     * @brief	Retourne notre instance ou la crée
     * @return $objInstance;
     */
    public static function getInstance() {
        if(!self::$objInstance){
            try{
                self::$objInstance = new EtudiantManager();
            }catch(Exception  $e ){
                echo "EtudiantManager Error: ".$e;
            }
        }
        return self::$objInstance;
    } # end method

    public function getStudent(){
        $this->student = array();

        $sql = "SELECT * FROM etudiant ORDER BY nomEtudiant ASC";

        try{
            $stmt = database::prepare($sql,array(PDO::ATTR_CURSOR,PDO::CURSOR_SCROLL));
            $stmt->execute();

            while($row=$stmt->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)){
                $f = new EEtudiant($row['idEtudiant'], $row['nomEtudiant'], $row['prenomEtudiant'], $row['sexe'], $row['idGroupe']);
                array_push($this->student,$f);
            }

        }catch(PDOException  $e ){
            echo "ArticleManager:getArticleByMarque Error: ".$e->getMessage();
            return false;
        }

        return $this->student;
    }

    /** @var Contient le tableau des EField */
    private $student;
}