<?php
/**
 * @copyright dominique.aigroz@kadeo.net 2003-2016
 */


/**
 * Classe d'exemple d'un utilisateur
 * @author doa
 *
 */

class EEtudiant implements JsonSerializable{

    /**
     *
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize() {
        return get_object_vars($this);
    }

    /**
     * @brief	Class Constructor
     */
    public function __construct($InId = 0, $InNom = "", $InPrenom = 0, $InSexe = 0, $InIDGroup = ""){
        $this->id=$InId;
        $this->nom=$InNom;
        $this->prenom=$InPrenom;
        $this->sexe=$InSexe;
        $this->IDGroupe=$InIDGroup;
    }

    /**
     * @brief	On ne laisse pas cloner un user
     */
    private function __clone() {}


    /**
     * @brief	Est-ce que cet objet est valide
     * @return  True si valide, autrement false
     */
    public function getID(){
        return $this->id;
    }

    /**
     * @brief	Est-ce que cet objet est valide
     * @return  True si valide, autrement false
     */
    public function getNom(){
        return $this->nom;
    }

    /**
     * @brief	Est-ce que cet objet est valide
     * @return  True si valide, autrement false
     */
    public function getPrenom(){
        return $this->prenom;
    }
    /**
     * @brief	Est-ce que cet objet est valide
     * @return  True si valide, autrement false
     */
    public function getSexe(){
        return $this->sexe;
    }

    /**
     * @brief	Est-ce que cet objet est valide
     * @return  True si valide, autrement false
     */
    public function getIDGroupe(){
        return $this->IDGroupe;
    }

    /** @brief L'identifiant unique provenant de la base de données */
    private $id;
    private $nom;
    private $prenom;
    private $sexe;
    private $IDGroupe;
}

