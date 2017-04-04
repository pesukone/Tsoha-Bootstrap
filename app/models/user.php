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
      $user = new Registered(array(
	'id' => $row['id'],
	'name' => $row['name']
      ));

      return $user;
    }

    return null;
  }

  public function validate_name(){
    $errors = array();
    if(!validate_not_null($this->name)){
      $errors[] = 'Nimi ei saa olla tyhjä!';
    }
    if(!validate_string_length($this->name, 3)){
      $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
    }

    return $errors;
  }
}
