<?php
require_once __DIR__.'DBconnection.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of plant
 *
 * @author jeffrey
 * 
 * @property PDO $pdo_dbh
 */
class Plant{
    
    public $set_id;
    private $species;
    private $name;
    private $ideal_temp;
    private $ideal_daylight;
    private $ideal_humidity;
    private $size;
    private $number;
    private $user_id;
    private $peroid_id;
    
    
    
    public function __construct($id = -1) {
        
         if(!isset($this->pdo_dbh)){
            $this->pdo_dbh = DBconnection::getFactory()->getConnection();
        }
        
        $this->set_id = $id;
        if($this->set_id == -1){
            $this->setName("New Plant");
            $this->setSpecies("Unknown");
        }else{
            $stmt_restore_plant = $this->pdo_dbh->prepare("SELECT `species`,`name`,`ideal_temp`,`ideal_daylight`,`ideal_humidity`,`size`,`number`
                                    FROM `plantsets` WHERE `set_id` = :id");
            $stmt_restore_plant->bindValue(':id', $this->set_id,PDO::PARAM_INT);
            $stmt_restore_plant->execute();
            $result = $stmt_restore_plant->fetch(PDO::FETCH_ASSOC);
            $this->setSpecies($result['species']);
            $this->setDaylight($result['ideal_daylight']);
            $this->setHumidity($result['ideal_humidity']);
            $this->setName($result['name']);
            $this->setQuanity($result['number']);
            $this->setSize($result['size']);
            $this->setTemp($result['ideal_temp']);
            unset($result);
            $stmt_restore_plant->closeCursor();
        }
    }




    public function commitToDB(){
        $success = false;
        if($this->set_id == -1){
            $stmt_add_this = $this->pdo_dbh->prepare("INSERT INTO `plantsets` (`species`,`name`,`ideal_temp`,
                                                     `ideal_daylight`,`ideal_humidity`,`size`,`number`,
                                                     `fk_user_id`,`fk_peroid_id`) VALUES (:species,:name,
                                                     :ideal_temp,:ideal_daylight,:ideal_humidity,:size,
                                                     :number,:fk_user_id,:fk_peroid_id)");
            
            $stmt_add_this->bindValue(':species',$this->species,PDO::PARAM_STR);
            $stmt_add_this->bindValue(':name',$this->name,PDO::PARAM_STR);
            $stmt_add_this->bindValue(':ideal_temp',$this->ideal_temp,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':ideal_daylight',$this->ideal_daylight,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':ideal_humidity',$this->ideal_humidity,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':size',$this->size,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':number',$this->number,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':fk_user_id',$this->user_id,PDO::PARAM_INT);// ?
            $stmt_add_this->bindValue(':fk_peroid_id',$this->peroid_id,PDO::PARAM_INT); // ?
            
            $success = $this->stmt_add_this->execute();
            $this->chamber_id = intval($this->pdo_dbh->lastInsertId());
        }else{
            $stmt_update_this = $this->pdo_dbh->prepare("UPDATE `plantsets` SET `species` = :species,
                        `name` = :name,`ideal_temp` = :ideal_temp,`ideal_daylight` = :ideal_daylight,
                        `ideal_humidity` = :ideal_humidity,`size` = :size,`number` = :number,
                        `fk_user_id` = :fk_user_id,`fk_peroid_id` = :fk_peroid_id WHERE `set_id` = :set_id");

            $stmt_update_this->bindValue(':species',$this->species,PDO::PARAM_STR);
            $stmt_update_this->bindValue(':name',$this->name,PDO::PARAM_STR);
            $stmt_update_this->bindValue(':ideal_temp',$this->ideal_temp,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':ideal_daylight',$this->ideal_daylight,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':ideal_humidity',$this->ideal_humidity,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':size',$this->size,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':number',$this->number,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':fk_user_id',$this->user_id,PDO::PARAM_INT);// ?
            $stmt_update_this->bindValue(':fk_peroid_id',$this->peroid_id,PDO::PARAM_INT); // ?
            $stmt_update_this->bindValue(':set_id',$this->set_id,PDO::PARAM_INT);
            $success = $stmt_update_this->execute();
        }
        if(!$success)
            trigger_error("Unsuccessful Statment",E_USER_WARNING);
        
        return $success;
    }
    



    public function restoreThis(){
        return new Plant($this->set_id);
    }
    

    //*********************************GETTERS AND SETTERS*****************************************
    public function getSpecies(){
        return $this->species;
    }
    public function setSpecies($species) {
        $this->species = $species;
    }
    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function getTemp() {
        return $this->ideal_temp;
    }
    public function setTemp($temp) {
        $temp = intval($temp);
        if($temp === 0){
            trigger_error("Ideal Tempature Set To 0",E_USER_WARNING);
        }
        $this->ideal_temp = $temp;
    }
    public function getDaylight() {
        return $this->ideal_daylight;
    }
    public function setDaylight($min){
        $this->ideal_daylight = $min;
    }
    public function getHumidity() {
        return $this->ideal_humidity;
    }
    public function setHumidity($humidity) {
        $humidity = intval($humidity);
        if($humidity === 0){
            trigger_error("Ideal Tempature Set To 0",E_USER_WARNING);
        }
        $this->ideal_humidity = $humidity;
    }
    public function getSize() {
        return $this->size;
    }
    public function setSize($size) {
        $size = intval($size);
        if($size === 0){
            trigger_error("Size Set To 0",E_USER_WARNING);
        }
        $this->size = $size;
    }
    public function getQuanity() {
        return $this->number;
    }
    public function setQuanity($number) {
        $number = intval($number);
        if($number === 0){
            trigger_error("Quanity Set To 0",E_USER_WARNING);
        }
        $this->number = $number;
    }
    //*********************************************************************************************
    
}

?>
