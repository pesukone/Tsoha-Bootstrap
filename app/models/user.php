<?php

class User extends BaseModel{

  public $id, $name;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array('validate_name');
  }

  public static function all(){
    $query = DB::connection()->prepare('SELECT * FROM Registered');
    $query->execute();
    $rows = $query->fetchAll();
    $users = array();

    foreach($rows as $row){
      $users[] = new Registered(array(
       	'id' => $row['id'],
      	'name' => $row['name']
      ));
    }

    return $users;
  }

  public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM Registered WHERE id = :id LIMIT 1');
    $query->execute(array(':id' => $id));
    $row = $query->fetch();

    if($row){
      $user = new User(array(
      	'id' => $row['id'],
      	'name' => $row['name']
      ));

      return $user;
    }

    return null;
  }

  public static function list_events($user_id, $date){
    $query = DB::connection()->prepare('SELECT * FROM Event WHERE registered_id = :user_id AND eventday = :date');
    $query->execute(array(':user_id' => $user_id, ':date' => $date));
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

  public function validate_name(){
    $errors = array();

    if(!parent::validate_not_null($this->name)){
      $errors[] = 'Nimi ei saa olla tyhjä!';
    }
    if(!parent::validate_string_length($this->name, 3)){
      $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
    }

    return $errors;
  }
}
