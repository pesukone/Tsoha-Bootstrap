<?php

  class Registered extends BaseModel{

    public $id, $name;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public function all(){
      $query = DB::connection()->prepare('SELECT * FROM Registered');
      $query->execute();
      $rows = $query->fetchAll();
      $users = array();

      foreach($rows as $row){
        $events[] = new Registered(array(
	  'id' => $row['id'],
	  'name' => $row['name']
	));
      }

      return $users;
    }

    public function find($id){
      $query = DB::connection()->prepare('SELECT * FROM Registered WHERE id = :id LIMIT 1');
      $query->execute(array('id' => $id));
      $row = $query->fetch();

      if($row){
        $user = new Registered(array(
	  'id' => $row['id'],
	  'name' => $row['id']
	));

	return $user;
      }

      return null;
    }
  }
