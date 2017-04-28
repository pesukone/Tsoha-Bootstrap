<?php

  class Group extends BaseModel{

    public $id, $name, $description, $members;

    public function __construct($attributes){
      parent::__construct($attributes);
      #$this->members = $this->get_members();
      $this->validators = array('validate_name');
    }

    public static function all(){
      $query = DB::connection()->prepare('SELECT * FROM Eventgroup');
      $query->execute();
      $rows = $query->fetchAll();
      $groups = array();

      foreach($rows as $row){
        $group = new Group(array(
	        'id' => $row['id'],
	        'name' => $row['name'],
	        'description' => $row['description']
	      ));

        $group->members = $group->get_members();

        $groups[] = $group;
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

        $group->members = $group->get_members();

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

      $this->members[] = $user;
    }

    public function remove_member($user){
      $query = DB::connection()->prepare('DELETE FROM Membership WHERE registered_id = :user_id AND eventgroup_id = :group_id');
      $query->execute(array(
        'user_id' => $user->id,
        'group_id' => $this->id
      ));

      $this->members = $this->get_members();

      if(empty($this->members)){
        $this->destroy();
      }
    }

    public function get_members(){
      $query = DB::connection()->prepare('SELECT Registered.id, Registered.name, Registered.password_digest FROM Eventgroup INNER JOIN Membership on Eventgroup.id = Membership.eventgroup_id INNER JOIN Registered on registered_id = registered.id WHERE eventgroup.id = :id');
      $query->execute(array(
        'id' => $this->id
      ));

      $rows = $query->fetchAll();
      $members = array();

      foreach($rows as $row){
        $members[] = new User(array(
          'id' => $row['id'],
          'name' => $row['name'],
          'password_digest' => $row['password_digest']
        ));
      }

      return $members;
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

    public function validate_name(){
      $errors = array();

      if(!parent::validate_not_null($this->name)){
        $errors[] = 'Nimi ei saa olla tyhjä!';
      }

      if(!parent::validate_string_min_length($this->name, 3)){
        $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
      }

      return $errors;
    }

    public function validate_description(){
      $errors = array();

      if(!parent::validate_not_null($this->description)){
        $errors[] = 'Kuvaus ei saa olla tyhjä!';
      }

      if(!parent::validate_string_max_length($this->description, 200)){
        $errors[] = 'Kuvaus ei saa olla yli 200 merkkiä pitkä!';
      }

      return $errors;
    }
  }
