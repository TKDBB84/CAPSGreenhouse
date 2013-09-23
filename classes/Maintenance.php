<?php
require_once __DIR__.'DBconnection.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Maintenance
 *
 * @author jeffrey
 */
class Maintenance {
    private $pdo_dbh;
    private $maintenance_id;
    
    private $complete_date;
    private $description;
    private $start_date;
    
    
    
    
    
    
    public function __construct($id = -1) {
        
         if(!isset($this->pdo_dbh)){
            $this->pdo_dbh = DBconnection::getFactory()->getConnection();
        }
        
        $this->maintenance_id = $id;
        if($this->maintenance_id == -1){
            //new event
            $this->maintenance_id = -1;
            $this->complete_date = 0;
            $this->start_date = 0;
            $this->description = '';
        }else{
            $stmt_get_maintenance = $this->pdo_dbh->prepare("SELECT `description`,
                UNIX_TIMESTAMP(`start_date`) as `start_date`,
                UNIX_TIMESTAMP(`complete_date`) as `complete_date`,
                FROM `maintenance` WHERE `maintenance_id` = :id");
            $stmt_get_maintenance->bindValue(':id',$this->maintenance_id,PDO::PARAM_INT);
            $stmt_get_maintenance->execute();
            $result = $stmt_get_maintenance->fetch(PDO::FETCH_ASSOC);
            $this->setStartDate($result['start_date']);
            $this->setCompleteDate($result['complete_date']);
            $this->setDescription($result['description']);
            unset($result);
            $stmt_get_maintenance->closeCursor();
        }
    }
    
    
    
    public function setDescription($descript){
        $this->description = $descript;
    }
    public function getDescription(){
        return $this->description;
    }
    
    
    
    public function setStartDate($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->start_date = $time;
    }
    public function getStartDate(){
        return date('F jS, Y',$this->start_date);
    }
    
    
    
    public function setCompleteDate($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Complete Time Set To 0",E_USER_WARNING);
        }
        $this->complete_date = $time;
    }
    public function getCompleteDate(){
        return date('F jS, Y',$this->complete_date);
    }
}

?>
