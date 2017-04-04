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

  public function save(){
    $query = DB::connection()->prepare('INSERT INTO Event (eventday, eventtime, description, registered_id, eventgroup_id) VALUES (:day, :time, :description, :user_id, :group_id) RETURNING id');
    $query->execute(array(
      'day' => $this->eventday, 
      'time' => $this->eventtime, 
      'description' => $this->description, 
      'user_id' => is_null($this->user) ? null : $this->user->id,
      'group_id' => is_null($this->group) ? null : $this->group->id
    ));

    $row = $query->fetch();

    $this->id = $row['id'];
  }
}
