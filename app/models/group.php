<?php

  class Group extends BaseModel{

    public $name, $description;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public static function all(){
      $query = DB::connection()->prepare('SELECT * FROM Eventgroup');
      $query->execute();
      $rows = $query->fetchAll();
      $groups = array();

      foreach($rows as $row){
        $groups[] = new Group(array(
	        'id' => $row['id'],
	        'name' => $row['name'],
	        'description' => $row['description']
	      ));
      }

      return $groups;
    }

    public static function find($id){
      $query = DB::connection()->prepare('SELECT * FROM Eventgroup WHERE id = :id LIMIT 1');
      $query->execute(array(':id' => $id));
      $row = $query->fetch();

      if($row){
        $group = new Group(array(
	        'name' => $row['name'],
	        'description' => $row['description']
	      ));

	      return $group;
      }

      return null;
    }


  }
