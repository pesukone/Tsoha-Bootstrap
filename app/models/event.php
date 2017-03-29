<?php

  class Event extends BaseModel{

    public $id, $eventday, $eventtime, $description, $user, $group;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public static function all(){
      $query = DB::connection()->prepare('SELECT * FROM Event');
      $query->execute();
      $rows = $query->fetchAll();
      $events = array();

      foreach($rows as $row){
        $events[] = new Event(array(
	  'id' => $row['id'],
	  'eventday' => $row['eventday'],
	  'eventtime' => $row['eventtime'],
	  'description' => $row['description'],
	  'user' => User::find($row['registered_id']),
	  'group' => Group::find($row['eventgroup_id'])
	));
      }

      return $events;
    }

    public static function find($id){
      $query = DB::connection()->prepare('SELECT * FROM Event WHERE id = :id LIMIT 1');
      $query->execute(array(':id' => $id));
      $row = $query->fetch();

      if($row){
	$event = new Event(array(
	  'id' => $row['id'],
	  'eventday' => $row['eventday'],
	  'eventtime' => $row['eventtime'],
	  'description' => $row['description'],
	  'user' => User::find($row['registered_id']),
	  'group' => Group::find($row['eventgroup_id'])
	));

	return $event;
      }

      return null;
    }

    public static function list_events($user_id, $date){
      $query = DB::connection()->prepare('SELECT * FROM Event WHERE registered_id = :user_id AND eventday = :date');
      $query->execute(array(':user_id' => $user_id, ':date' => $date));
      $row = $query->fetchAll();

      if($row){
        $events[] = new User(array(
	  'id' => $row['id'],
	  'eventday' => $row['eventday'],
	  'eventtime' => $row['eventtime'],
	  'description' => $row['description'],
	  'user' => User::find($row['registered_id']),
	  'group' => Group::find($row['eventgroup_id'])
	));

	return $events;
      }

      return null;
    }
  }
