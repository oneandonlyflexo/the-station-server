<?php
require_once("dbconnection.php");
require_once("users.php");
require_once("editors.php");
require_once("return_package.php");

class quests extends dbconnection
{	
    //Takes in quest JSON, all fields optional except user_id + key
    public static function createQuest($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return quests::createQuestPack($glob); }
    public static function createQuestPack($pack)
    {
        $pack->auth->game_id = $pack->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        $pack->quest_id = dbconnection::queryInsert(
            "INSERT INTO quests (".
            ($pack->game_id                                   ? "game_id,"                              : "").
            ($pack->name                                      ? "name,"                                 : "").
            ($pack->description                               ? "description,"                          : "").
            ($pack->active_icon_media_id                      ? "active_icon_media_id,"                 : "").
            ($pack->active_media_id                           ? "active_media_id,"                      : "").
            ($pack->active_description                        ? "active_description,"                   : "").
            ($pack->active_notification_type                  ? "active_notification_type,"             : "").
            ($pack->active_function                           ? "active_function,"                      : "").
            ($pack->active_requirement_package_id             ? "active_requirement_package_id,"        : "").
            ($pack->complete_icon_media_id                    ? "complete_icon_media_id,"               : "").
            ($pack->complete_media_id                         ? "complete_media_id,"                    : "").
            ($pack->complete_description                      ? "complete_description,"                 : "").
            ($pack->complete_notification_type                ? "complete_notification_type,"           : "").
            ($pack->complete_function                         ? "complete_function,"                    : "").
            ($pack->complete_requirement_package_id           ? "complete_requirement_package_id,"      : "").
            ($pack->sort_index                                ? "sort_index,"                           : "").
            "created".
            ") VALUES (".
            ($pack->game_id                                   ? "'".addslashes($pack->game_id)."',"                         : "").
            ($pack->name                                      ? "'".addslashes($pack->name)."',"                            : "").
            ($pack->description                               ? "'".addslashes($pack->description)."',"                     : "").
            ($pack->active_icon_media_id                      ? "'".addslashes($pack->active_icon_media_id)."',"            : "").
            ($pack->active_media_id                           ? "'".addslashes($pack->active_media_id)."',"                 : "").
            ($pack->active_description                        ? "'".addslashes($pack->active_description)."',"              : "").
            ($pack->active_notification_type                  ? "'".addslashes($pack->active_notification_type)."',"        : "").
            ($pack->active_function                           ? "'".addslashes($pack->active_function)."',"                 : "").
            ($pack->active_requirement_package_id             ? "'".addslashes($pack->active_requirement_package_id)."',"   : "").
            ($pack->complete_icon_media_id                    ? "'".addslashes($pack->complete_icon_media_id)."',"          : "").
            ($pack->complete_media_id                         ? "'".addslashes($pack->complete_media_id)."',"               : "").
            ($pack->complete_description                      ? "'".addslashes($pack->complete_description)."',"            : "").
            ($pack->complete_notification_type                ? "'".addslashes($pack->complete_notification_type)."',"      : "").
            ($pack->complete_function                         ? "'".addslashes($pack->complete_function)."',"               : "").
            ($pack->complete_requirement_package_id           ? "'".addslashes($pack->complete_requirement_package_id)."'," : "").
            ($pack->sort_index                                ? "'".addslashes($pack->sort_index)."',"                      : "").
            "CURRENT_TIMESTAMP".
            ")"
        );

        return quests::getQuestPack($pack);
    }

    public static function updateQuest($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return quests::updateQuestPack($glob); }
    public static function updateQuestPack($pack)
    {
        $pack->auth->game_id = $pack->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        dbconnection::query(
            "UPDATE quests SET ".
            ($pack->game_id                         ? "game_id                         = '".addslashes($pack->game_id)."', "                          : "").
            ($pack->name                            ? "name                            = '".addslashes($pack->name)."', "                             : "").
            ($pack->description                     ? "description                     = '".addslashes($pack->description)."', "                      : "").
            ($pack->active_icon_media_id            ? "active_icon_media_id            = '".addslashes($pack->active_icon_media_id)."', "             : "").
            ($pack->active_media_id                 ? "active_media_id                 = '".addslashes($pack->active_media_id)."', "                  : "").
            ($pack->active_description              ? "active_description              = '".addslashes($pack->active_description)."', "               : "").
            ($pack->active_notification_type        ? "active_notification_type        = '".addslashes($pack->active_notification_type)."', "         : "").
            ($pack->active_function                 ? "active_function                 = '".addslashes($pack->active_function)."', "                  : "").
            ($pack->active_requirement_package_id   ? "active_requirement_package_id   = '".addslashes($pack->active_requirement_package_id)."', "    : "").
            ($pack->complete_icon_media_id          ? "complete_icon_media_id          = '".addslashes($pack->complete_icon_media_id)."', "           : "").
            ($pack->complete_media_id               ? "complete_media_id               = '".addslashes($pack->complete_media_id)."', "                : "").
            ($pack->complete_description            ? "complete_description            = '".addslashes($pack->complete_description)."', "             : "").
            ($pack->complete_notification_type      ? "complete_notification_type      = '".addslashes($pack->complete_notification_type)."', "       : "").
            ($pack->complete_function               ? "complete_function               = '".addslashes($pack->complete_function)."', "                : "").
            ($pack->complete_requirement_package_id ? "complete_requirement_package_id = '".addslashes($pack->complete_requirement_package_id)."', "  : "").
            ($pack->sort_index                      ? "sort_index                      = '".addslashes($pack->sort_index)."', "                       : "").
            "last_active = CURRENT_TIMESTAMP ".
            "WHERE quest_id = '{$pack->quest_id}'"
        );

        return quests::getQuestPack($pack);
    }

    private static function questObjectFromSQL($sql_quest)
    {
        $quest = new stdClass();
        $quest->quest_id                         = $sql_quest->quest_id;
        $quest->game_id                          = $sql_quest->game_id;
        $quest->name                             = $sql_quest->name;
        $quest->description                      = $sql_quest->description;
        $quest->active_icon_media_id             = $sql_quest->active_icon_media_id;
        $quest->active_media_id                  = $sql_quest->active_media_id;
        $quest->active_description               = $sql_quest->active_description;
        $quest->active_notification_type         = $sql_quest->active_notification_type;
        $quest->active_function                  = $sql_quest->active_function;
        $quest->active_requirement_package_id    = $sql_quest->active_requirement_package_id;
        $quest->complete_icon_media_id           = $sql_quest->complete_icon_media_id;
        $quest->complete_media_id                = $sql_quest->complete_media_id;
        $quest->complete_description             = $sql_quest->complete_description;
        $quest->complete_notification_type       = $sql_quest->complete_notification_type;
        $quest->complete_function                = $sql_quest->complete_function;
        $quest->complete_requirement_package_id  = $sql_quest->complete_requirement_package_id;
        $quest->sort_index                       = $sql_quest->sort_index;

        return $quest;
    }

    public static function getQuest($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return quests::getQuestPack($glob); }
    public static function getQuestPack($pack)
    {
        $sql_quest = dbconnection::queryObject("SELECT * FROM quests WHERE quest_id = '{$pack->quest_id}' LIMIT 1");
        if(!$sql_quest) return new return_package(2, NULL, "The quest you've requested does not exist");
        return new return_package(0,quests::questObjectFromSQL($sql_quest));
    }

    public static function getQuestsForGame($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return quests::getQuestsForGamePack($glob); }
    public static function getQuestsForGamePack($pack)
    {
        $pack->auth->permission = "read_write";
        if(!users::authenticateUser($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        $sql_quests = dbconnection::queryArray("SELECT * FROM quests WHERE game_id = '{$pack->game_id}'");
        $quests = array();
        for($i = 0; $i < count($sql_quests); $i++)
            $quests[] = quests::questObjectFromSQL($sql_quests[$i]);

        return new return_package(0,$quests);

    }

    public static function deleteQuest($glob) { $data = file_get_contents("php://input"); $glob = json_decode($data); return quests::deleteQuestPack($glob); }
    public static function deleteQuestPack($pack)
    {
        $pack->auth->game_id = $pack->game_id;
        $pack->auth->permission = "read_write";
        if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        dbconnection::query("DELETE FROM quests WHERE quest_id = '{$pack->quest_id}' LIMIT 1");
        return new return_package(0);
    }
}
?>