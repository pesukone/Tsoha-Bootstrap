<?php

  class Group extends BaseModel{

    public $id, $name, $description, $members;

    public function __construct($attributes){
      parent::__construct($attributes);
      $this->validators = array('validate_name');
    }

    public static function all(){
      $query = 'SELECT * FROM Eventgroup';
      $rows = parent::multi_row_query($query, array());
      $groups = array();

      foreach($rows as $row){
        $groups[] = parent::parse_group_from_query($row);
      }

      return $groups;
    }

    public static function find($id){
      $query = 'SELECT * FROM Eventgroup WHERE id = :id LIMIT 1';
      $parameters = array(':id' => $id);
      $row = parent::single_row_query($query, $parameters);

      if($row){
        return parent::parse_group_from_query($row);
      }

      return null;
    }

    public function add_member($user){
      $query = 'INSERT INTO Membership (registered_id, eventgroup_id) VALUES (:user_id, :group_id)';
      $parameters = array(
        'user_id' => $user->id,
        'group_id' => $this->id
      );
      parent::update_query($query, $parameters);

      $this->members[] = $user;
    }

    public function remove_member($user){
      $query = 'DELETE FROM Membership WHERE registered_id = :user_id AND eventgroup_id = :group_id';
      $parameters = array(array(
        'user_id' => $user->id,
        'group_id' => $this->id
      ));
      parent::update_query($query, $parameters);

      $this->members = $this->get_members();

      if(empty($this->members)){
        $this->destroy();
      }
    }

    public function get_members(){
      $query = 'SELECT Registered.id, Registered.name, Registered.password_digest FROM Eventgroup INNER JOIN Membership on Eventgroup.id = Membership.eventgroup_id INNER JOIN Registered on registered_id = registered.id WHERE eventgroup.id = :id';
      $parameters = array('id' => $this->id);
      $rows = parent::multi_row_query($query, $parameters);
      $members = array();

      foreach($rows as $row){
        $members[] = parent::parse_user_from_query($row);
      }

      return $members;
    }

    public function save(){
      $query = 'INSERT INTO Eventgroup (name, description) VALUES (:name, :description) RETURNING id';
      $parameters = array(
        'name' => $this->name,
        'description' => $this->description
      );
      $row = parent::single_row_query($query, $parameters);

      $this->id = $row['id'];
    }

    public function update(){
      $query = 'UPDATE Eventgroup SET name = :name, description = :description WHERE id = :id';
      $parameters = array(
        'name' => $this->name,
        'description' => $this->description,
        'id' => $this->id
      );
      parent::update_query($query, $parameters);
    }

    public function destroy(){
      $query = 'DELETE FROM Eventgroup WHERE id = :id';
      $parameters = array('id' => $this->id);
      parent::update_query($query, $parameters);
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
