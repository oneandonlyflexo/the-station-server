<?php
require_once("dbconnection.php");
require_once("editors.php");
require_once("games.php");
require_once("return_package.php");

require_once("requirements.php");
require_once("instances.php");

class triggers extends dbconnection
{
    //Takes in trigger JSON, all fields optional except user_id + key
    public static function createTrigger($pack)
    {
        $pack->auth->game_id = $pack->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        $pack->trigger_id = dbconnection::queryInsert(
            "INSERT INTO triggers (".
            "game_id,".
            (isset($pack->instance_id)                 ? "instance_id,"                 : "").
            (isset($pack->scene_id)                    ? "scene_id,"                    : "").
            (isset($pack->requirement_root_package_id) ? "requirement_root_package_id," : "").
            (isset($pack->type)                        ? "type,"                        : "").
            (isset($pack->name)                        ? "name,"                        : "").
            (isset($pack->title)                       ? "title,"                       : "").
            (isset($pack->icon_media_id)               ? "icon_media_id,"               : "").
            (isset($pack->latitude)                    ? "latitude,"                    : "").
            (isset($pack->longitude)                   ? "longitude,"                   : "").
            (isset($pack->distance)                    ? "distance,"                    : "").
            (isset($pack->infinite_distance)           ? "infinite_distance,"           : "").
            (isset($pack->wiggle)                      ? "wiggle,"                      : "").
            (isset($pack->show_title)                  ? "show_title,"                  : "").
            (isset($pack->hidden)                      ? "hidden,"                      : "").
            (isset($pack->trigger_on_enter)            ? "trigger_on_enter,"            : "").
            (isset($pack->qr_code)                     ? "qr_code,"                     : "").
            (isset($pack->seconds)                     ? "seconds,"                     : "").
            (isset($pack->ar_target_id)                ? "ar_target_id,"                : "").
            (isset($pack->ar_target_img_scale_x)       ? "ar_target_img_scale_x,"       : "").
            (isset($pack->ar_target_img_scale_y)       ? "ar_target_img_scale_y,"       : "").
            (isset($pack->beacon_uuid)                 ? "beacon_uuid,"                 : "").
            (isset($pack->beacon_major)                ? "beacon_major,"                : "").
            (isset($pack->beacon_minor)                ? "beacon_minor,"                : "").
            "created".
            ") VALUES (".
            "'".$pack->game_id."',".
            (isset($pack->instance_id)                 ? "'".addslashes($pack->instance_id)."',"                 : "").
            (isset($pack->scene_id)                    ? "'".addslashes($pack->scene_id)."',"                    : "").
            (isset($pack->requirement_root_package_id) ? "'".addslashes($pack->requirement_root_package_id)."'," : "").
            (isset($pack->type)                        ? "'".addslashes($pack->type)."',"                        : "").
            (isset($pack->name)                        ? "'".addslashes($pack->name)."',"                        : "").
            (isset($pack->title)                       ? "'".addslashes($pack->title)."',"                       : "").
            (isset($pack->icon_media_id)               ? "'".addslashes($pack->icon_media_id)."',"               : "").
            (isset($pack->latitude)                    ? "'".addslashes($pack->latitude)."',"                    : "").
            (isset($pack->longitude)                   ? "'".addslashes($pack->longitude)."',"                   : "").
            (isset($pack->distance)                    ? "'".addslashes($pack->distance)."',"                    : "").
            (isset($pack->infinite_distance)           ? "'".addslashes($pack->infinite_distance)."',"           : "").
            (isset($pack->wiggle)                      ? "'".addslashes($pack->wiggle)."',"                      : "").
            (isset($pack->show_title)                  ? "'".addslashes($pack->show_title)."',"                  : "").
            (isset($pack->hidden)                      ? "'".addslashes($pack->hidden)."',"                      : "").
            (isset($pack->trigger_on_enter)            ? "'".addslashes($pack->trigger_on_enter)."',"            : "").
            (isset($pack->qr_code)                     ? "'".addslashes($pack->qr_code)."',"                     : "").
            (isset($pack->seconds)                     ? "'".addslashes($pack->seconds)."',"                     : "").
            (isset($pack->ar_target_id)                ? "'".addslashes($pack->ar_target_id)."',"                : "").
            (isset($pack->ar_target_img_scale_x)       ? "'".addslashes($pack->ar_target_img_scale_x)."',"       : "").
            (isset($pack->ar_target_img_scale_y)       ? "'".addslashes($pack->ar_target_img_scale_y)."',"       : "").
            (isset($pack->beacon_uuid)                 ? "'".addslashes($pack->beacon_uuid)."',"                 : "").
            (isset($pack->beacon_major)                ? "'".addslashes($pack->beacon_major)."',"                : "").
            (isset($pack->beacon_minor)                ? "'".addslashes($pack->beacon_minor)."',"                : "").
            "CURRENT_TIMESTAMP".
            ")"
        );

        games::bumpGameVersion($pack);
        return triggers::getTrigger($pack);
    }

    //Takes in game JSON, all fields optional except user_id + key
    public static function updateTrigger($pack)
    {
        $pack->auth->game_id = dbconnection::queryObject("SELECT * FROM triggers WHERE trigger_id = '{$pack->trigger_id}'")->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        dbconnection::query(
            "UPDATE triggers SET ".
            (isset($pack->instance_id)                 ? "instance_id                 = '".addslashes($pack->instance_id)."', "                 : "").
            (isset($pack->scene_id)                    ? "scene_id                    = '".addslashes($pack->scene_id)."', "                    : "").
            (isset($pack->requirement_root_package_id) ? "requirement_root_package_id = '".addslashes($pack->requirement_root_package_id)."', " : "").
            (isset($pack->type)                        ? "type                        = '".addslashes($pack->type)."', "                        : "").
            (isset($pack->name)                        ? "name                        = '".addslashes($pack->name)."', "                        : "").
            (isset($pack->title)                       ? "title                       = '".addslashes($pack->title)."', "                       : "").
            (isset($pack->icon_media_id)               ? "icon_media_id               = '".addslashes($pack->icon_media_id)."', "               : "").
            (isset($pack->latitude)                    ? "latitude                    = '".addslashes($pack->latitude)."', "                    : "").
            (isset($pack->longitude)                   ? "longitude                   = '".addslashes($pack->longitude)."', "                   : "").
            (isset($pack->distance)                    ? "distance                    = '".addslashes($pack->distance)."', "                    : "").
            (isset($pack->infinite_distance)           ? "infinite_distance           = '".addslashes($pack->infinite_distance)."', "           : "").
            (isset($pack->wiggle)                      ? "wiggle                      = '".addslashes($pack->wiggle)."', "                      : "").
            (isset($pack->show_title)                  ? "show_title                  = '".addslashes($pack->show_title)."', "                  : "").
            (isset($pack->hidden)                      ? "hidden                      = '".addslashes($pack->hidden)."', "                      : "").
            (isset($pack->trigger_on_enter)            ? "trigger_on_enter            = '".addslashes($pack->trigger_on_enter)."', "            : "").
            (isset($pack->qr_code)                     ? "qr_code                     = '".addslashes($pack->qr_code)."', "                     : "").
            (isset($pack->seconds)                     ? "seconds                     = '".addslashes($pack->seconds)."', "                     : "").
            (isset($pack->ar_target_id)                ? "ar_target_id                = '".addslashes($pack->ar_target_id)."', "                : "").
            (isset($pack->ar_target_img_scale_x)       ? "ar_target_img_scale_x       = '".addslashes($pack->ar_target_img_scale_x)."', "       : "").
            (isset($pack->ar_target_img_scale_y)       ? "ar_target_img_scale_y       = '".addslashes($pack->ar_target_img_scale_y)."', "       : "").
            (isset($pack->beacon_uuid)                 ? "beacon_uuid                 = '".addslashes($pack->beacon_uuid)."', "                 : "").
            (isset($pack->beacon_major)                ? "beacon_major                = '".addslashes($pack->beacon_major)."', "                : "").
            (isset($pack->beacon_minor)                ? "beacon_minor                = '".addslashes($pack->beacon_minor)."', "                : "").
            "last_active = CURRENT_TIMESTAMP ".
            "WHERE trigger_id = '{$pack->trigger_id}'"
        );

        games::bumpGameVersion($pack);
        return triggers::getTrigger($pack);
    }

    private static function triggerObjectFromSQL($sql_trigger)
    {
        if(!$sql_trigger) return $sql_trigger;
        $trigger = new stdClass();
        $trigger->trigger_id                  = $sql_trigger->trigger_id;
        $trigger->game_id                     = $sql_trigger->game_id;
        $trigger->instance_id                 = $sql_trigger->instance_id;
        $trigger->scene_id                    = $sql_trigger->scene_id;
        $trigger->requirement_root_package_id = $sql_trigger->requirement_root_package_id;
        $trigger->type                        = $sql_trigger->type;
        $trigger->name                        = $sql_trigger->name;
        $trigger->title                       = $sql_trigger->title;
        $trigger->icon_media_id               = $sql_trigger->icon_media_id;
        $trigger->latitude                    = $sql_trigger->latitude;
        $trigger->longitude                   = $sql_trigger->longitude;
        $trigger->distance                    = $sql_trigger->distance;
        $trigger->infinite_distance           = $sql_trigger->infinite_distance;
        $trigger->wiggle                      = $sql_trigger->wiggle;
        $trigger->show_title                  = $sql_trigger->show_title;
        $trigger->hidden                      = $sql_trigger->hidden;
        $trigger->trigger_on_enter            = $sql_trigger->trigger_on_enter;
        $trigger->qr_code                     = $sql_trigger->qr_code;
        $trigger->seconds                     = $sql_trigger->seconds;
        $trigger->ar_target_id                = $sql_trigger->ar_target_id;
        $trigger->ar_target_img_scale_x       = $sql_trigger->ar_target_img_scale_x;
        $trigger->ar_target_img_scale_y       = $sql_trigger->ar_target_img_scale_y;
        $trigger->beacon_uuid                 = $sql_trigger->beacon_uuid;
        $trigger->beacon_major                = $sql_trigger->beacon_major;
        $trigger->beacon_minor                = $sql_trigger->beacon_minor;

        return $trigger;
    }

    public static function getTrigger($pack)
    {
        $sql_trigger = dbconnection::queryObject("SELECT * FROM triggers WHERE trigger_id = '{$pack->trigger_id}' LIMIT 1");
        return new return_package(0,triggers::triggerObjectFromSQL($sql_trigger));
    }

    public static function getTriggersForGame($pack)
    {
        $sql_triggers = dbconnection::queryArray("SELECT * FROM triggers WHERE game_id = '{$pack->game_id}'");
        $triggers = array();
        for($i = 0; $i < count($sql_triggers); $i++)
            if($ob = triggers::triggerObjectFromSQL($sql_triggers[$i])) $triggers[] = $ob;

        return new return_package(0,$triggers);
    }

    public static function getTriggersForScene($pack)
    {
        $sql_triggers = dbconnection::queryArray("SELECT * FROM triggers WHERE scene_id = '{$pack->scene_id}'");
        $triggers = array();
        for($i = 0; $i < count($sql_triggers); $i++)
            {
            $ob = triggers::triggerObjectFromSQL($sql_triggers[$i]);
            if($ob) $triggers[] = $ob;
            }

        return new return_package(0,$triggers);
    }

    public static function getTriggersForInstance($pack)
    {
        $sql_triggers = dbconnection::queryArray("SELECT * FROM triggers WHERE instance_id = '{$instanceId}'");
        $triggers = array();
        for($i = 0; $i < count($sql_triggers); $i++)
            if($ob = triggers::triggerObjectFromSQL($sql_trigger)) $triggers[] = $ob;

        return new return_package(0,$triggers);
    }

    public static function deleteTrigger($pack)
    {
        $trigger = dbconnection::queryObject("SELECT * FROM triggers WHERE trigger_id = '{$pack->trigger_id}'");
        $pack->auth->game_id = $trigger->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        games::bumpGameVersion($pack);
        return triggers::noauth_deleteTrigger($pack);
    }

    //this is a security risk...
    public static function noauth_deleteTrigger($pack)
    {
        //and this "fixes" the security risk...
        if(strpos($_SERVER['REQUEST_URI'],'noauth') !== false) return new return_package(6, NULL, "Attempt to bypass authentication externally.");

        dbconnection::query("DELETE FROM triggers WHERE trigger_id = '{$pack->trigger_id}' LIMIT 1");
        //cleanup
        $instances = dbconnection::queryArray("SELECT * FROM instances WHERE instance_id = '{$trigger->instance_id}'");
        for($i = 0; $i < count($instances); $i++)
        {
            $pack->instance_id = $instances[$i]->instance_id;
            instances::noauth_deleteInstance($pack);
        }

        $reqPack = dbconnection::queryObject("SELECT * FROM requirement_root_packages WHERE requirement_root_package_id = '{$trigger->requirement_root_package_id}'");
        if($reqPack)
        {
            $pack->requirement_root_package_id = $reqPack->requirement_root_package_id;
            requirements::noauth_deleteRequirementPackage($pack);
        }

        games::bumpGameVersion($pack);
        return new return_package(0);
    }
}
?>
