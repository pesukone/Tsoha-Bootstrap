<?php

  class User extends BaseModel{

    public $id, $name, $password_digest, $groups;

    public function __construct($attributes){
      parent::__construct($attributes);
      $this->validators = array('validate_name');

      if(array_key_exists('password_digest', $attributes)){
        $this->password_digest = $attributes['password_digest'];
      }else{
        self::validate_password($attributes['password']);

        // ei näin!
        $this->password_digest = crypt($attributes['password']);
      }

      $this->groups = $this->find_groups();
    }

    public static function all(){
      $query = 'SELECT * FROM Registered';
      $rows = parent::multi_row_query($query, array());

      foreach($rows as $row){
        $users[] = parent::parse_user_from_query($row);
      }

      return $users;
    }

    public static function find($id){
      $query = 'SELECT * FROM Registered WHERE id = :id LIMIT 1';
      $parameters = array(':id' => $id);
      $row = parent::single_row_query($query, $parameters);

      if($row){
        return parent::parse_user_from_query($row);
      }

      return null;
    }

    public static function find_by_name($name){
      $query = 'SELECT * FROM Registered WHERE name = :name';
      $parameters = array(':name' => $name);
      $row = parent::single_row_query($query, $parameters);

      if($row){
        return parent::parse_user_from_query($row);
      }

      return null;
    }

    public function find_groups(){
      $query = 'SELECT Eventgroup.id, Eventgroup.name, Eventgroup.description FROM Registered INNER JOIN Membership ON id = registered_id INNER JOIN Eventgroup ON Eventgroup.id = eventgroup_id WHERE Registered.id = :user_id';
      $parameters = array(':user_id' => $this->id);
      $rows = parent::multi_row_query($query, $parameters);
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
      $query = 'SELECT Event.id, Event.eventday, Event.eventtime, Event.description, Event.registered_id, Event.eventgroup_id FROM Membership INNER JOIN Eventgroup ON Membership.eventgroup_id = Eventgroup.id RIGHT JOIN Event ON Eventgroup.id = Event.eventgroup_id WHERE Event.eventday = :date AND (Membership.registered_id = :user_id or (Membership.registered_id IS NULL AND Event.registered_id = :user_id)) ORDER BY Event.eventtime';
      $parameters = array(
        ':user_id' => $this->id,
        ':date' => $date
      );
      $rows = parent::multi_row_query($query, $parameters);
      $events = array();

      foreach($rows as $row){
        $events[] = parent::parse_event_from_query($row);
      }

      return $events;
    }

    public function save(){
      $query = 'INSERT INTO Registered (name, password_digest) VALUES (:name, :digest) RETURNING id';
      $parameters = array(
        'name' => $this->name,
        'digest' => $this->password_digest
      );
      $row = parent::single_row_query($query, $parameters);

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

      if(!is_null(User::find_by_name($this->name))){
        $errors[] = 'Käyttäjänimi on varattu!';
      }

      return $errors;
    }

    public function validate_password($password){
      if(!parent::validate_string_min_length($password, 8)){
        Redirect::to('/user/new', array('username' => $this->name, 'errors' => array('Salasanan on oltava vähintään 8 merkkiä pitkä!')));
      }
    }
  }
