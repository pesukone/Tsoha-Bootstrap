<?php

  class Event extends BaseModel{

    public $id, $eventday, $eventtime, $description, $registered_id, $eventgroup_id;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public function events_of($owner){
      $query = DB::connection()->prepare('SELECT * FROM Event');
      $query->execute();
      $rows = $query->fetchAll();
      $events = array();

      foreach($rows as $row){
        $events[] = new Game(array(
	  'id' => $row['id'],
	  'eventday' => $row['eventday'],
	  'eventtime' => $row['eventtime'],
	  'description' => $row['description'],
	  'registered_id' => $row['registered_id'],
	  'eventgroup_id' => $row['eventgroup_id']
	));
      }
    }
  }
