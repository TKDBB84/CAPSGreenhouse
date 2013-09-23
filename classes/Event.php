<?php
require_once __DIR__.'DBconnection.php';
require_once __DIR__.'Period.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of event
 *
 * @author jeffrey
 * @property PDO $pdo_dbh
 */
class Event {
    
    private $pdo_dbh;
    
    
    private $event_id;
    private $fk_period_id;
    
    private $discription;
    private $event_date;
    

    public function __construct($id = -1) {
        
         if(!isset($this->pdo_dbh)){
            $this->pdo_dbh = DBconnection::getFactory()->getConnection();
        }
        
        $this->event_id = $id;
        if($this->event_id == -1){
            //new event
            $this->fk_period_id = -1;
            
        }else{
            $stmt_get_event = $this->pdo_dbh->prepare("SELECT `fk_period_id`,`discription`,
                UNIX_TIMESTAMP(`event_date`) as `event_date` 
                FROM `events` WHERE `event_id` = :id");
            $stmt_get_event->bindValue(':id',$this->event_id,PDO::PARAM_INT);
            $stmt_get_event->execute();
            $result = $stmt_get_event->fetch(PDO::FETCH_ASSOC);
            $this->setDiscription($result['discription']);
            $this->setEventDate($result['event_date']);
            $this->changePeriod($result['fk_period_id']);
            unset($result);
            $stmt_get_event->closeCursor();
        }
    }
    
    
    
    
    
    public function changePeriod($period_id){
        if($period_id === 0 || $period_id === '0'){
            $period_id = 0;
        }else{
            $period_id = intval($period_id);
            if($period_id == 0){
                $period_id = -1;
            }
        }
        $stmt_chk_period = $this->pdo_dbh->prepare("SELECT 1 FROM `periods` WHERE `period_id` = :id");
        $stmt_chk_period->bindValue(':id', $period_id, PDO::PARAM_INT);
        $stmt_chk_period->execute();
        $result = $stmt_chk_period->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            trigger_error("Assigned Unsaved Period",E_USER_WARNING);
        }
        $this->peroid_chamber_id = $period_id;
    }
    public function getPeriod(){
        return new Period($this->fk_period_id);
    }
    
    
    public function getEventDate(){
         return date('F jS, Y',$this->event_date);
    }
    public function setEventTime($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->event_date = $time;
    }
    
    
    public function getDiscription(){
        return $this->discription;
    }
    public function setDiscription($discrpt){
        $this->discription = $discrpt;
    }
    
}

?>
