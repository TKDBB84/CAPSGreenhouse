<?php
require_once __DIR__.'/DBconnection.php';

/**
 * Description of chamber
 *
 * @author jeffrey
 * @property PDO $pdo_dbh
 */

class Chamber implements JsonSerializable {
    
    private $pdo_dbh;
    

    public $chamber_id;
    private $total_space;
    private $name;
     
    
    function __construct($id = -1){
        
        if(!isset($this->pdo_dbh)){
            $this->pdo_dbh = DBconnection::getFactory()->getConnection();
        }

        $this->chamber_id = $id;
        
        if($this->chamber_id == -1){
            $this->setName("Unnamed Chamber");
            @$this->setTotalSpace(0);
        }else{
            $stmt_restore_this = $this->pdo_dbh->prepare("SELECT `name`,`total_space` FROM `chambers` WHERE `chamber_id` = :id");
            $stmt_restore_this->bindValue(':id',$this->chamber_id,PDO::PARAM_INT);
            $stmt_restore_this->execute();
            $result = $stmt_restore_this->fetch(PDO::FETCH_ASSOC);
            $this->setName($result['chamber_name']);
            $this->setTotalSpace($result['total_space']);
            unset($result);
            $stmt_restore_this->closeCursor();
        }
    }
        
    
    public function getMaintenance($since = 0, $to = -1){
        if($to == -1) $to = time();
        //pull all releated fields
    }
    
    
    public function getRelatedPeriods($from = 0, $to = 2147483647){
        $all_periods = array();
        if($this->chamber_id != -1){
            $stmt_related_periods = $this->pdo_dbh->prepare("SELECT `period_id` FROM `periods`
                                                             WHERE `fk_chamber_id` = :chamber_id
                                                               AND DATE(`start_date`) >= DATE(FROM_UNIXTIME(:from))
                                                               AND DATE(`end_date`) <= DATE(FROM_UNIXTIME(:to))");
            $stmt_related_periods->bindValue(':chamber_id', $this->chamber_id,PDO::PARAM_INT);
            $stmt_related_periods->bindValue(':from',$from,PDO::PARAM_INT);
            $stmt_related_periods->bindValue(':to',$to,PDO::PARAM_INT);
            $stmt_related_periods->execute();
            while($row = $stmt_related_periods->fetch(PDO::FETCH_ASSOC)){
                $all_periods[] = new Period($row['peroid_id']);
            }
        }
        return $all_periods;
    }
    
    
    public function commitToDB(){
        $success = false;
        if($this->chamber_id == -1){
            $stmt_add_this = $this->pdo_dbh->prepare("INSERT INTO `chambers` (`name`,`total_space`) VALUES (:name,:space)");
            $stmt_add_this->bindValue(':space',$this->total_space,PDO::PARAM_INT);
            $stmt_add_this->bindValue(':name',$this->name,PDO::PARAM_STR);
            $success = $stmt_add_this->execute();
            $this->chamber_id = intval($this->pdo_dbh->lastInsertId());
        }else{
            $stmt_update_this = $this->pdo_dbh->prepare("UPDATE `chambers` SET `name` = :name, `total_space` = :space WHERE `chamber_id` = :id");
            $stmt_update_this->bindValue(':id',$this->chamber_id,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':space',$this->total_space,PDO::PARAM_INT);
            $stmt_update_this->bindValue(':name',$this->name,PDO::PARAM_STR);
            $success = $stmt_update_this->execute();
        }
        
        if(!$success)
            trigger_error("Unsuccessful Statment",E_USER_WARNING);
        
        return $success;
    }
    
        
    public function restoreThis(){
        return new Chamber($this->chamber_id);
    }
    
    
    public function getTotalSpace(){
        return $this->total_space;
    }
    
    public function setTotalSpace($space){
        $space = intval($space);
        if($space === 0){
            trigger_error("Total Space Set To 0",E_USER_WARNING);
        }
        $this->total_space = intval($space);
    }
    
    
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }




    public function jsonSerialize() {
        return [
            'chamber_id' => $this->chamber_id,
            'name' => $this->getName(),
            'space' => $this->getTotalSpace()
        ];
    }

    //***********************STATIC METHODS**********************************
    /** @returns Chamber[] */
    public static function getAllChambers(){
        $stmt_allChambers  = DBconnection::getFactory()->getConnection()->query("SELECT * FROM `Chambers`");
        $all_chambers = array();
        $result = $stmt_allChambers->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $chamber){
            $tmp_chamber = new Chamber();
            $tmp_chamber->chamber_id = $chamber['chamber_id'];
            $tmp_chamber->setName($chamber['chamber_name']);
            $tmp_chamber->setTotalSpace($chamber['total_space']);
            $all_chambers[] = $tmp_chamber;
        }
        return $all_chambers;
    }


    public static function numChambers(){
        $stmt_allChambers  = DBconnection::getFactory()->getConnection()->query("SELECT count(`chamber_id`) as num FROM `Chambers`");
        $all_chambers = array();
        $result = $stmt_allChambers->fetchColumn();
        $stmt_allChambers->closeCursor();
        return $result;
    }

    //***********************************************************************
}

?>
