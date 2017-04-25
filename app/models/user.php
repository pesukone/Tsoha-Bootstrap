<?php

  class User extends BaseModel{

    public $id, $name, $password_digest, $groups;

    public function __construct($attributes){
      parent::__construct($attributes);
      $this->validators = array('validate_name');

      if(array_key_exists('password_digest', $attributes)){
        $this->password_digest = $attributes['password_digest'];
      }else{
        // ei näin!
        $this->password_digest = crypt($attributes['password']);
      }

      $this->groups = $this->find_groups();
    }

    public static function all(){
      $query = DB::connection()->prepare('SELECT * FROM Registered');
      $query->execute();
      $rows = $query->fetchAll();
      $users = array();

      foreach($rows as $row){
        $users[] = new Registered(array(
       	  'id' => $row['id'],
      	  'name' => $row['name'],
          'password_digest' => $row['name']
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
      	  'name' => $row['name'],
          'password_digest' => $row['password_digest']
        ));

        return $user;
      }

      return null;
    }

    public static function find_by_name($name){
      $query = DB::connection()->prepare('SELECT * FROM Registered WHERE name = :name');
      $query->execute(array(':name' => $name));
      $row = $query->fetch();

      if($row){
        $user = new User(array(
          'id' => $row['id'],
          'name' => $row['name'],
          'password_digest' => $row['password_digest']
        ));

        return $user;
      }

      return null;
    }

    public function find_groups(){
      $query = DB::connection()->prepare('SELECT Eventgroup.id, Eventgroup.name, Eventgroup.description FROM Registered INNER JOIN Membership ON id = registered_id INNER JOIN Eventgroup ON Eventgroup.id = eventgroup_id WHERE Registered.id = :user_id');
      $query->execute(array(':user_id' => $this->id));
      $rows = $query->fetchAll();
      $groups = array();


      foreach($rows as $row){
        $group = new Group(array(
          'id' => $row['id'],
          'name' => $row['name'],
          'description' => $row['description']
        ));

        $groups[] = $group;
      }

      return $groups;
    }

    public function events_for_day($date){
      $query = DB::connection()->prepare('SELECT * FROM Event WHERE registered_id = :user_id AND eventday = :date');
      $query->execute(array(':user_id' => $this->id, ':date' => $date));
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

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO Registered (name, password_digest) VALUES (:name, :digest) RETURNING id');
      $query->execute(array(
        'name' => $this->name,
        'digest' => $this->password_digest
      ));

      $row = $query->fetch();

      $this->id = $row['id'];
    }

    public function authenticate($name, $password){
      $user = User::find_by_name($name);

      if($user == null){
        return null;
      }

      // ei ei ei!
      if(crypt($password, $user->password_digest) == $user->password_digest){
        return $user;
      }else{
        return null;
      }
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
  }
