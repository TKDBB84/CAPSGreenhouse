<?php
require_once __DIR__.'DBconnection.php';
require_once __DIR__.'Chamber.php';
require_once __DIR__.'Setting.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of period
 *
 * @author jeffrey
 * 
 * 
 * @property PDO $pdo_dbh
 */
class Period{
    
    private $pdo_dbh;
    
    private $peroid_chamber_id;
    private $peroid_setting_id;
    private $peroid_id;
    private $start_date;
    private $end_date;
    private $final_plant_date;
    private $final_harvest_date;
    
    
    public function __construct($id = -1) {
        
         if(!isset($this->pdo_dbh)){
            $this->pdo_dbh = DBconnection::getFactory()->getConnection();
        }
        
        $this->peroid_id = $id;
        if($this->peroid_id == -1){
            //new growth period
            $this->peroid_chamber_id = -1;
            $this->peroid_setting_id = -1;
        }else{
            $stmt_get_chamber_and_settings = $this->pdo_dbh->prepare("SELECT `fk_chamber_id`,`fk_setting_id`,
                UNIX_TIMESTAMP(`start_date`) as `start_date`,UNIX_TIMESTAMP(`end_date`) as `end_date`,
                UNIX_TIMESTAMP(`final_plant_date`) as `final_plant_date`,UNIX_TIMESTAMP(`final_harvest_date`) as `final_harvest_date` 
                FROM `periods` WHERE period_id = :id");
            $stmt_get_chamber_and_settings->bindValue(':id',$this->peroid_id,PDO::PARAM_INT);
            $stmt_get_chamber_and_settings->execute();
            $result = $stmt_get_chamber_and_settings->fetch(PDO::FETCH_ASSOC);
            $this->setFinalHarvestTime($result['final_harvest_date']);
            $this->setFinalPlantTime($result['final_plant_date']);
            $this->setPeriodEndTime($result['end_date']);
            $this->setPeriodStartTime($result['start_date']);
            $this->changeChamber($result['fk_chamber_id']);
            $this->changeSettings($result['fk_setting_id']);
            unset($result);
            $stmt_get_chamber_and_settings->closeCursor();
        }
    }
    
    
    public function commitToDB(){
        $success = false;
        $error = '';
        if($this->peroid_setting_id == -1){
            $error = 'Cannot Commit Peroid With Unsaved Settings';
        }
        if($this->peroid_chamber_id == -1){
            $error .= PHP_EOL.'Cannot Commit Peroid With Unsaved Chamber';
        }
        
        if($error === ''){
            if($this->peroid_id == -1){
                $stmt_insert_peroid = $this->pdo_dbh->prepare("INSERT INTO `periods` (`fk_chamber_id`,`fk_setting_id`,
                                                               `start_date`,`end_date`,`final_plant_date`,
                                                               `final_harvest_date`) VALUES (:fk_chamber_id,:fk_setting_id,
                                                               FROM_UNIXTIME(:start_date),FROM_UNIXTIME(:end_date),
                                                               FROM_UNIXTIME(:final_plant_date),FROM_UNIXTIME(:final_harvest_date))");

                $stmt_insert_peroid->bindValue(':fk_chamber_id',$this->peroid_chamber_id ,PDO::PARAM_INT);
                $stmt_insert_peroid->bindValue(':fk_setting_id',$this->peroid_setting_id ,PDO::PARAM_INT);
                $stmt_insert_peroid->bindValue(':start_date',$this->start_date ,PDO::PARAM_INT);
                $stmt_insert_peroid->bindValue(':end_date',$this->end_date ,PDO::PARAM_INT);
                $stmt_insert_peroid->bindValue(':final_plant_date',$this->final_plant_date ,PDO::PARAM_INT);
                $stmt_insert_peroid->bindValue(':final_harvest_date',$this->final_harvest_date ,PDO::PARAM_INT);

                $success = $stmt_insert_peroid->execute();
                $this->peroid_id = intval($this->pdo_dbh->lastInsertId());
            }else{
                $stmt_update_period = $this->pdo_dbh->prepare('UPDATE `periods` SET `fk_chamber_id` = :fk_chamber_id,`fk_setting_id` = :fk_setting_id,
                                                                   `start_date` = :start_date,`end_date` = :end_date,`final_plant_date` = :final_plant_date,
                                                                   `final_harvest_date` = :final_harvest_date WHERE `period_id` = :period_id');
                
                $stmt_update_period->bindValue(':fk_chamber_id',$this->peroid_chamber_id ,PDO::PARAM_INT);
                $stmt_update_period->bindValue(':fk_setting_id',$this->peroid_setting_id ,PDO::PARAM_INT);
                $stmt_update_period->bindValue(':start_date',$this->start_date ,PDO::PARAM_INT);
                $stmt_update_period->bindValue(':end_date',$this->end_date ,PDO::PARAM_INT);
                $stmt_update_period->bindValue(':final_plant_date',$this->final_plant_date ,PDO::PARAM_INT);
                $stmt_update_period->bindValue(':final_harvest_date',$this->final_harvest_date ,PDO::PARAM_INT);
                $stmt_update_period->bindValue(':period_id',$this->peroid_id,PDO::PARAM_INT);
                $success = $stmt_update_period->execute();
            }
        }
        if(!$success){
            if($error === '')
                trigger_error("Unsuccessful Statment Execution",E_USER_WARNING);
            else
                trigger_error($error,E_USER_ERROR);
        }
        return $success;
    }
    
    
    public function restoreThis(){
        return new Period($this->peroid_id);
    }
    
    public function changeChamber($chamber_id){
        if($chamber_id === 0 || $chamber_id === '0'){
            $chamber_id = 0;
        }else{
            $chamber_id = intval($chamber_id);
            if($chamber_id == 0){
                $chamber_id = -1;
            }
        }
        $stmt_chk_chamber = $this->pdo_dbh->prepare("SELECT 1 FROM `chambers` WHERE `chamber_id` = :id");
        $stmt_chk_chamber->bindValue(':id', $chamber_id, PDO::PARAM_INT);
        $stmt_chk_chamber->execute();
        $result = $stmt_chk_chamber->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            trigger_error("Assigned Unsaved Chamber",E_USER_WARNING);
        }
        $this->peroid_chamber_id = $chamber_id;
    }
    
    public function getChamber(){
        return new Chamber($this->peroid_chamber_id);
    }
    
    
    
    
    public function changeSettings($setting_id){
        if($setting_id === 0 || $setting_id === '0'){
            $setting_id = 0;
        }else{
            $setting_id = intval($setting_id);
            if($setting_id === 0){
                $setting_id = -1;
            }
        }
        $stmt_chk_setting = $this->pdo_dbh->prepare("SELECT 1 FROM `settings` WHERE `setting_id` = :id");
        $stmt_chk_setting->bindValue(':id', $setting_id, PDO::PARAM_INT);
        $stmt_chk_setting->execute();
        $result = $stmt_chk_setting->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            trigger_error("Assigned Unsaved Settings",E_USER_WARNING);
        }
        $this->peroid_setting_id = $setting_id;
    }
    
    public function getSetting(){
        return new Setting($this->peroid_setting_id);
    }
    
    
    
    
    public function setPeriodStartTime($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->start_date = $time;
    }
    
    public function getPeroidStartTime(){
        return $this->start_date;
    }
    
    public function getPeroidStartDate(){
        return date('F jS, Y',$this->start_date);
    }
    
    
    
    public function setPeriodEndTime($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->end_date = $time;
    }
    
    public function getPeroidEndTime(){
        return $this->end_date;
    }
    
    public function getPeroidEndDate(){
        return date('F jS, Y',$this->end_date);
    }
    
    
    
    
    public function setFinalHarvestTime($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->final_harvest_date = $time;
    }
    
    public function getFinalHarvestTime(){
        return $this->final_harvest_date;
    }
    
    public function getFinalHarvestDate(){
        return date('F jS, Y',$this->final_harvest_date);
    }
    
    
    
    
    public function setFinalPlantTime($time){
        $time = intval($time);
        if($time === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->final_plant_date = $time;
    }
    
    public function getFinalPlantTime(){
        return $this->final_plant_date;
    }
    
    public function getFinalPlantDate(){
        return date('F jS, Y',$this->final_plant_date);
    }
}

?>
