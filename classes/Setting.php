<?php
require_once __DIR__.'/DBconnection.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of setting
 *
 * @author jeffrey
 * @property PDO $pdo_dbh
 */
class Setting implements JsonSerializable {

    private $pdo_dbh;
    
    public $setting_id;
    
    private $temperature;
    private $name;
    private $first_lights_on;
    private $humidity;
    private $night_length;
    private $light_length;
    
     
    
    function __construct($id = -1){
        
        if(!isset($this->pdo_dbh)){
            $this->pdo_dbh = DBconnection::getFactory()->getConnection();
        }
        
        $this->setting_id = $id;
        
        if($this->setting_id == -1){
            $this->setName("Unnamed Setting");
            $this->setTemp(0);
            $this->setHumidity(0);
            $this->setDayLength(0);
            $this->setNightLength(0);
            @$this->setFirstLightsOn(0);
        }else{
            $stmt_restore_this = $this->pdo_dbh->prepare("SELECT `name`,`temperature`,`night_length`,`light_length`,`humidity`,UNIX_TIMESTAMP(`first_lights_on`) as `first_lights_on` FROM `Settings` WHERE `setting_id` = :id");
            $stmt_restore_this->bindValue(':id',$this->chamber_id,PDO::PARAM_INT);
            $stmt_restore_this->execute();
            $result = $stmt_restore_this->fetch(PDO::FETCH_ASSOC);
            $this->setName($result['name']);
            $this->setTemp($result['temperature']);
            $this->setHumidity($result['humidity']);
            $this->setDayLength($result['light_length']);
            $this->setNightLength($result['night_length']);
            @$this->setFirstLightsOn($result['first_lights_on']);            
            unset($result);
            $stmt_restore_this->closeCursor();
        }
        
        
    }
    
    
    public function getRelatedPeriods($after = 0){
        $all_periods = array();
        if($this->chamber_id != -1){
            $stmt_related_periods = $this->pdo_dbh->prepare("SELECT `period_id` FROM `periods` WHERE `fk_setting_id` = :setting_id AND `start_date` > FROM_UNIXTIME(:time)");
            $stmt_related_periods->bindValue(':setting_id', $this->setting_id,PDO::PARAM_INT);
            $stmt_related_periods->bindValue(':time',$after,PDO::PARAM_INT);
            $stmt_related_periods->execute();
            while($row = $stmt_related_periods->fetch(PDO::FETCH_ASSOC)){
                $all_periods[] = new Period($row['peroid_id']);
            }
        }
        return $all_periods;
    }
    
    
    public function commitToDB(){
        $success = false;
        if($this->setting_id == -1){
            $stmt_add_this = $this->pdo_dbh->prepare("INSERT INTO `settings` (`name`,`temperature`,`daylight`,`humidity`) VALUES (:name,:temp,:daylight,:humid)");
            $stmt_add_this->bindValue(':temp',$this->temperature,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':daylight',$this->daylight,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':humid',$this->humidity,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':name',$this->name,PDO::PARAM_STR);
            $success = $stmt_add_this->execute();
            $this->chamber_id = intval($this->pdo_dbh->lastInsertId());
        }else{
            $stmt_update_this = $this->pdo_dbh->prepare("UPDATE `chambers` SET `name` = :name, `temperature` = :temp, `daylight` = :daylight, `humidity` = :humid WHERE `chamber_id` = :id");
            $stmt_update_this->bindValue(':id',$this->chamber_id,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':temp',$this->temperature,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':daylight',$this->daylight,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':humid',$this->humidity,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':name',$this->name,PDO::PARAM_STR);
            $success = $stmt_update_this->execute();
        }
        
        if(!$success)
            trigger_error("Unsuccessful Statment Execution",E_USER_WARNING);
        
        return $success;
    }
    
        
    public function restoreThis(){
       return new Setting($this->setting_id);
    }
    
    
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    
    
    public function getTemp(){
        return $this->temperature;
    }
    
    public function setTemp($temp){
        $this->temperature = intval($temp);
    }
    
    
    
    public function getHumidity(){
        return $this->humidity;
    }
    
    public function setHumidity($humidity){
        $this->humidity = intval($humidity);
    }
    
 
    
    public function getDayLength(){
        return $this->light_length;
    }
    
    public function setDayLength($hhmmss){
        $this->light_length = intval($hhmmss);
    }
    
    
    
    public function getNightLength(){
        return $this->night_length;
    }
    public function setNightLength($hhmmss){
        $this->night_length = $hhmmss;
    }
    
    
    
    public function getFirstLightsOn(){
        return date('F jS, Y',$this->first_lights_on);
    }
    
    public function setFirstLightsOn($timestamp){
        $timestamp = intval($timestamp);
        if($timestamp === 0){
            trigger_error("Start Time Set To 0",E_USER_WARNING);
        }
        $this->first_lights_on = $timestamp;
    }


    public function jsonSerialize() {
        return [
            'setting_id' => $this->chamber_id,
            'name' => $this->getName(),
            'temperature' => $this->getTemp(),
            'humidity' => $this->getHumidity(),
            'light_length' => $this->getDayLength(),
            'night_length' => $this->getNightLength(),
            'first_lights_on' => $this->getFirstLightsOn()
        ];
    }

    //***********************************STATICS***************************************

    public static function getAllSettings(){
        $stmt_allSettings  = DBconnection::getFactory()->getConnection()->query("SELECT `name`,`temperature`,`night_length`,`light_length`,`humidity`,UNIX_TIMESTAMP(`first_lights_on`) as `first_lights_on`, setting_id FROM `Settings`");
        $all_settings = array();
        $result = $stmt_allSettings->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $setting){
            $tmp_setting = new Setting();
            $tmp_setting->setting_id = $setting['setting_id'];
            $tmp_setting->setName($setting['name']);
            $tmp_setting->setTemp($setting['temperature']);
            $tmp_setting->setHumidity($setting['humidity']);
            $tmp_setting->setDayLength($setting['light_length']);
            $tmp_setting->setNightLength($setting['night_length']);
            $tmp_setting->setFirstLightsOn($setting['first_lights_on']);
            $all_settings[] = $tmp_setting;
        }
        return $all_settings;
    }


    //*********************************************************************************

}

?>
