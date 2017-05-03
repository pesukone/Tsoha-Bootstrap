<?php

  class BaseModel{
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      $errors = array();

      foreach($this->validators as $validator){
        $errors = array_merge($errors, $this->{$validator}());
      }

      return $errors;
    }

    protected static function single_row_query($querytext, $parameters){
      $query = DB::connection()->prepare($querytext);
      $query->execute($parameters);
      $row = $query->fetch();

      return $row;
    }

    protected static function multi_row_query($querytext, $parameters){
      $query = DB::connection()->prepare($querytext);
      $query->execute($parameters);
      $rows = $query->fetchAll();

      return $rows;
    }

    protected static function update_query($querytext, $parameters){
      $query = DB::connection()->prepare($querytext);
      $query->execute($parameters);
    }

    protected static function parse_event_from_query($row){
      $event = new Event(array(
        'id' => $row['id'],
        'eventday' => $row['eventday'],
        'eventtime' => $row['eventtime'],
        'description' => $row['description'],
        'user' => User::find($row['registered_id']),
        'group' => Group::find($row['eventgroup_id'])
      ));

      return $event;
    }

    protected static function parse_group_from_query($row){
      $group = new Group(array(
        'id' => $row['id'],
        'name' => $row['name'],
        'description' => $row['description']
      ));

      $group->members = $group->get_members();

      return $group;
    }

    protected static function parse_user_from_query($row){
      $user = new User(array(
        'id' => $row['id'],
        'name' => $row['name'],
        'password_digest' => $row['password_digest']
      ));

      $user->groups = $user->find_groups();

      return $user;
    }

    public function validate_string_max_length($string, $length){
      if(strlen($string) > $length){
        return false;
      }else{
        return true;
      }
    }

    public function validate_string_min_length($string, $length){
      if(strlen($string) < $length){
        return false;
      }else{
        return true;
      }
    }

    public function validate_not_null($string){
      if($string == '' || $string == null){
        return false;
      }

      return true;
    }

    public function validate_date_format($string){
      if(!strtotime($string)){
        return false;
      }

      $date = date_parse($string);

      if(!checkdate($date["month"], $date["day"], $date["year"])){
        return false;
      }

      return true;
    }

    public function validate_time_format($string){
      return preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9]/', $string);
    }
  }
