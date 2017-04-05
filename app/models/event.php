<?php

class Event extends BaseModel{

  public $id, $eventday, $eventtime, $description, $user, $group;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array('validate_description', 'validate_date', 'validate_time');
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

  public function update(){
    $query = DB::connection()->prepare('UPDATE Event SET eventday = :day, eventtime = :time, description = :description WHERE id = :id');
    $query->execute(array(
      'day' => $this->eventday,
      'time' => $this->eventtime,
      'description' => $this->description,
      //'group' => is_null($this->group) ? null : $this->group->id
      'id' => $this->id
    ));
  }

  public function destroy(){
    $query = DB::connection()->prepare('DELETE FROM Event WHERE id = :id');
    $query->execute(array(
      'id' => $this->id
    ));
  }

  public function validate_description(){
    $errors = array();

    if(!parent::validate_not_null($this->description)){
      $errors[] = 'Kuvaus ei saa olla tyhjä!';
    }
    if(!parent::validate_string_length($this->description, 200)){
      $errors[] = 'Kuvaus ei saa olla yli 200 merkkiä pitkä!';
    }

    return $errors;
  }

  public function validate_date(){
    $errors = array();

    if(!parent::validate_not_null($this->eventday)){
      $errors[] = 'Päivämäärä ei saa olla tyhjä!';
    }
    if(!parent::validate_datetime_format($this->eventday)){
      $errors[] = 'Virheellinen päivämäärä!';
    }

    return $errors;
  }

  public function validate_time(){
    $errors = array();

    if(!parent::validate_not_null($this->eventtime)){
      $errors[] = 'Kellonaika ei saa olla tyhjä!';
    }
    if(!parent::validate_datetime_format($this->eventtime)){
      $errors[] = 'Virheellinen kellonaika!';
    }

    return $errors;
  }

  public function validate_user(){
    $errors = array();

    if(!parent::validate_not_null($this->user)){
      $errors[] = 'Virheellinen käyttäjä!';
    }

    return $errors;
  }
}
