<?php
require_once("dbconnection.php");
require_once("editors.php");
require_once("return_package.php");

class instances extends dbconnection
{	
    //Takes in instance JSON, all fields optional except user_id + key
    public static function createInstance($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return instances::createInstancePack($glob); }
    public static function createInstancePack($pack)
    {
        $pack->auth->game_id = $pack->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");
    
        $pack->instance_id = dbconnection::queryInsert(
            "INSERT INTO instances (".
            "game_id,".
            ($pack->object_id   ? "object_id,"    : "").
            ($pack->object_type ? "object_type,"  : "").
            ($pack->factory_id  ? "factory_id,"   : "").
            "created".
            ") VALUES (".
            "'".$pack->game_id."',".
            ($pack->object_id   ? "'".addslashes($pack->object_id)."',"   : "").
            ($pack->object_type ? "'".addslashes($pack->object_type)."'," : "").
            ($pack->factory_id  ? "'".addslashes($pack->factory_id)."',"  : "").
            "CURRENT_TIMESTAMP".
            ")"
        );

        return instances::getInstancePack($pack);
    }

    //Takes in game JSON, all fields optional except user_id + key
    public static function updateInstance($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return instances::updateInstancePack($glob); }
    public static function updateInstancePack($pack)
    {
        $pack->auth->game_id = dbconnection::queryObject("SELECT * FROM instances WHERE instance_id = '{$pack->instance_id}'")->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        dbconnection::query(
            "UPDATE instances SET ".
            ($pack->object_id   ? "object_id   = '".addslashes($pack->object_id)."', "   : "").
            ($pack->object_type ? "object_type = '".addslashes($pack->object_type)."', " : "").
            ($pack->factory_id  ? "factory_id  = '".addslashes($pack->factory_id)."', "  : "").
            "last_active = CURRENT_TIMESTAMP ".
            "WHERE instance_id = '{$pack->instance_id}'"
        );

        return instances::getInstancePack($pack);
    }

    private static function instanceObjectFromSQL($sql_instance)
    {
        $instance = new stdClass();
        $instance->instance_id = $sql_instance->instance_id;
        $instance->game_id     = $sql_instance->game_id;
        $instance->object_id   = $sql_instance->object_id;
        $instance->object_type = $sql_instance->object_type;
        $instance->factory_id  = $sql_instance->factory_id;
        return $instance;
    }

    public static function getInstance($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return instances::getInstancePack($glob); }
    public static function getInstancePack($pack)
    {
        $sql_instance = dbconnection::queryObject("SELECT * FROM instances WHERE instance_id = '{$pack->instance_id}' LIMIT 1");
        return new return_package(0,instances::instanceObjectFromSQL($sql_instance));
    }

    public static function getInstancesForGame($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return instances::getInstancesForGamePack($glob); }
    public static function getInstancesForGamePack($pack)
    {
        $sql_instances = dbconnection::queryArray("SELECT * FROM instances WHERE game_id = '{$pack->game_id}'");
        $instances = array();
        for($i = 0; $i < count($sql_instances); $i++)
            $instances[] = instances::instanceObjectFromSQL($sql_instances[$i]);

        return new return_package(0,$instances);
    }

    public static function getInstancesForObject($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return instances::getInstancesForObjectPack($glob); }
    public static function getInstancesForObjectPack($pack)
    {
        $sql_instances = dbconnection::queryArray("SELECT * FROM instances WHERE object_type = '{$pack->object_type}' AND object_id = '{$pack->object_id}'");
        $instances = array();
        for($i = 0; $i < count($sql_instances); $i++)
            $instances[] = instances::instanceObjectFromSQL($sql_instances[$i]);

        return new return_package(0,$instances);
    }

    public static function deleteInstance($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return instances::deleteInstancePack($glob); }
    public static function deleteInstancePack($pack)
    {
        $pack->auth->game_id = dbconnection::queryObject("SELECT * FROM instances WHERE instance_id = '{$pack->instance_id}'")->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        dbconnection::query("DELETE FROM instances WHERE instance_id = '{$pack->instance_id}' LIMIT 1");
        return new return_package(0);
    }
}

?>
