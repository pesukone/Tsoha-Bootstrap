<?php

  class Group extends BaseModel{

    public $id, $name, $description;

    public function __construct($attributes){
      parent::__construct($attributes);
      $this->validators = array();
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

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO Eventgroup (name, description) VALUES (:name, :description) RETURNING id');
      $query->execute(array(
        'name' => $this->name,
        'description' => $this->description
      ));

      $row = $query->fetch();

      $this->id = $row['id'];
    }

  }
