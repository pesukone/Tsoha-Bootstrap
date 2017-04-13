<?php

  class Group extends BaseModel{

    public $id, $name, $description, $members;

    public function __construct($attributes){
      parent::__construct($attributes);
      $this->members = array();
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
          'id' => $row['id'],
	        'name' => $row['name'],
	        'description' => $row['description']
	      ));

	      return $group;
      }

      return null;
    }

    public function add_member($user){
      $query = DB::connection()->prepare('INSERT INTO Membership (registered_id, eventgroup_id) VALUES (:user_id, :group_id)');
      $query->execute(array(
        'user_id' => $user->id,
        'group_id' => $this->id
      ));
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

    public function destroy(){
      $query = DB::connection()->prepare('DELETE FROM Eventgroup WHERE id = :id');
      $query->execute(array(
        'id' => $this->id
      ));
    }
  }
