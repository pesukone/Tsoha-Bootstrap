<?php

  class Event extends BaseModel{

    public $id, $eventday, $eventtime, $description, $user, $group;

    public function __construct($attributes){
      parent::__construct($attributes);
      $this->validators = array('validate_description', 'validate_date', 'validate_time');
      $this->eventtime = date('H:i', strtotime($this->eventtime));
    }

    public static function all(){
      $query = 'SELECT * FROM Event';
      $parameters = array();
      $rows = parent::multi_row_query($query, $parameters);

      $events = array();

      foreach($rows as $row){
        $events[] = parent::parse_event_from_query($row);
      }

      return $events;
    }

    public static function find($id){
      $query = 'SELECT * FROM Event WHERE id = :id LIMIT 1';
      $parameters = array(':id' => $id);
      $row = parent::single_row_query($query, $parameters);

      if($row){
        return parent::parse_event_from_query($row);
      }

      return null;
    }

    public function save(){
      $query = 'INSERT INTO Event (eventday, eventtime, description, registered_id, eventgroup_id) VALUES (:day, :time, :description, :user_id, :group_id) RETURNING id';
      $parameters = array(
        'day' => $this->eventday, 
        'time' => $this->eventtime, 
        'description' => $this->description, 
        'user_id' => is_null($this->user) ? null : $this->user->id,
        'group_id' => is_null($this->group) ? null : $this->group->id
      );

      $row = parent::single_row_query($query, $parameters);

      $this->id = $row['id'];
    }

    public function update(){
      $query = 'UPDATE Event SET eventday = :day, eventtime = :time, description = :description, eventgroup_id = :group_id WHERE id = :id';
      $parameters = array(
        'day' => $this->eventday,
        'time' => $this->eventtime,
        'description' => $this->description,
        'group_id' => is_null($this->group) ? null : $this->group->id,
        'id' => $this->id
      );

      parent::update_query($query, $parameters);
    }

    public function destroy(){
      $query = 'DELETE FROM Event WHERE id = :id';
      $parameters = array('id' => $this->id);
      parent::update_query($query, $parameters);
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

    public function validate_date(){
      $errors = array();

      if(!parent::validate_not_null($this->eventday)){
        $errors[] = 'Päivämäärä ei saa olla tyhjä!';
        return $errors;
      }
      if(!parent::validate_date_format($this->eventday)){
        $errors[] = 'Virheellinen päivämäärä!';
      }

      return $errors;
    }

    public function validate_time(){
      $errors = array();

      if(!parent::validate_not_null($this->eventtime)){
        $errors[] = 'Kellonaika ei saa olla tyhjä!';
      }
      if(!parent::validate_time_format($this->eventtime)){
        $errors[] = 'Virheellinen kellonaika!';
      }

      return $errors;
    }

    public function validate_user(){
      $errors = array();

      if(!parent::validate_not_null($this->user)){
        $errors[] = 'Virheellinen käyttäjä!';
      }

      return $errors;
    }
  }
