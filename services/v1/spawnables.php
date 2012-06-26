<?php
require_once("module.php");

class Spawnables extends Module
{
  public static function createSpawnable($gameId, $type, $typeId, $amount, $area, $amountRestriction, $locationBoundType, $lat, $lon, $spawnProbability, $spawnRate, $deleteWhenViewed, $timeToLive, $errorRange, $forceView, $hidden, $allowQuickTravel, $wiggle)
  {
    if($spawnableId = Spawnables::hasSpawnable($gameId, $type, $typeId))
      $query = "UPDATE spawnables SET active = 1 WHERE game_id = $gameId AND type = '$type' AND type_id = $typeId";
    else
      $query = "INSERT INTO spawnables (game_id, type, type_id, amount, area, amount_restriction, location_bound_type, latitude, longitude, spawn_probability, spawn_rate, delete_when_viewed, time_to_live, error_range, force_view, hidden, allow_quick_travel, wiggle, active) VALUES ($gameId, '{$type}', $typeId, $amount, $area, '{$amountRestriction}', '{$locationBoundType}', $lat, $lon, $spawnProbability, $spawnRate, $deleteWhenViewed, $timeToLive, $errorRange, $forceView, $hidden, $allowQuickTravel, $wiggle, 1);";
      
    mysql_query($query);
    $spawnableId = mysql_insert_id();
    return new returnData(0,$spawnableId);
  }

  public static function hasActiveSpawnable($gameId, $type, $typeId)
  {
    $query = "SELECT * FROM spawnables WHERE game_id = $gameId AND type = '$type' AND type_id = $typeId AND active = 1"; 
    $result = mysql_query($query);
    if($obj = mysql_fetch_object($result)) return $obj->spawnable_id;
    else return false;
  }

  public static function hasSpawnable($gameId, $type, $typeId)
  {
    $query = "SELECT * FROM spawnables WHERE game_id = $gameId AND type = '$type' AND type_id = $typeId"; 
    $result = mysql_query($query);
    if($obj = mysql_fetch_object($result)) return $obj->spawnable_id;
    else return false;
  }

  public static function deleteSpawnable($spawnableId)
  {
    $query = "UPDATE spawnables SET active = 0 WHERE spawnable_id = $spawnableId";
    mysql_query($query);
    /*
    //This does a hard delete
    $query = "SELECT * FROM spawnables WHERE spawnable_id = $spawnableId";
    $result = mysql_query($query);
    $obj = mysql_fetch_object($result);
    if($obj)
    {
      $query = "DELETE FROM spawnables WHERE spawnable_id = $spawnableId";
      mysql_query($query);
      $query = "DELETE FROM ".$obj->game_id."_requirements WHERE content_type = 'Spawnable' AND content_id = $spawnableId";
      mysql_query($query);
    }
    */
    return new returnData(0);
  }

  public static function deleteSpawnablesOfObject($gameId, $type, $typeId)
  {

    if($spawnableId = Spawnables::hasSpawnable($gameId, $type, $typeId))
      Spawnables::deleteSpawnable($spawnableId);
    return new returnData(0);
  }

  //Optionally by spawnableId or by gameId, type, and typeId
  public static function updateSpawnable($spawnableId = 0, $gameId, $type, $typeId, $amount, $area, $amountRestriction, $locationBoundType, $lat, $lon, $spawnProbability, $spawnRate, $deleteWhenViewed, $timeToLive, $errorRange, $forceView, $hidden, $allowQuickTravel, $wiggle, $active = 1)
  {
    if($spawnableId == 0)
      $query = "UPDATE spawnables SET amount = $amount, area = $area, amount_restriction = '{$amountRestriction}', location_bound_type = '{$locationBoundType}', latitude = $lat, longitude = $lon, spawn_probability = $spawnProbability, spawn_rate = $spawnRate, delete_when_viewed = $deleteWhenViewed, time_to_live = $timeToLive, error_range = $errorRange, force_view = $forceView, hidden = $hidden, allow_quick_travel = $allowQuickTravel, wiggle = $wiggle, active = $active WHERE game_id = $gameId AND type = '{$type}' AND type_id = $typeId";
    else
      $query = "UPDATE spawnables SET game_id = $gameId, type = '$type', type_id = $typeId, amount = $amount, area = $area, amount_restriction = '{$amountRestriction}', location_bound_type = '{$locationBoundType}', latitude = $lat, longitude = $lon, spawn_probability = $spawnProbability, spawn_rate = $spawnRate, delete_when_viewed = $deleteWhenViewed, time_to_live = $timeToLive, error_range = $errorRange, force_view = $forceView, hidden = $hidden, allow_quick_travel = $allowQuickTravel, wiggle = $wiggle, active = $active WHERE spawnable_id = $spawnableId";
    mysql_query($query);
    return new returnData(0);
  }

  public static function createSpawnableForObject($gameId, $type, $typeId)
  {
    Spawnables::createSpawnable($gameId, $type, $typeId, 0, 10, 'PER_PLAYER', 'PLAYER', 0, 0, 50, 10, 0, 100, 20, 0, 0, 0, 1);
    return Spawnables::getSpawnableForObject($gameId, $type, $typeId);
  }

  public static function getSpawnableForObject($gameId, $type, $typeId)
  {
    $query = "SELECT * FROM spawnables WHERE game_id = $gameId AND type = '".$type."' AND type_id = '".$typeId."' AND active = 1 LIMIT 1";
    $result = mysql_query($query);
    $obj = mysql_fetch_object($result);
    if($obj) return new returnData(0, $obj);
    else return new returnData(1, "No Spawnables For Object");
  }

  public static function getSpawnablesForGame($gameId)
  {
    $query = "SELECT * FROM spawnables WHERE game_id = $gameId AND active = 1";
    $result = mysql_query($query);
    $spawnables = array();
    while($obj = mysql_fetch_object($result))
    {
      $spawnables[] = $obj;
    }
    return new returnData(0, $spawnables);
  }
}
