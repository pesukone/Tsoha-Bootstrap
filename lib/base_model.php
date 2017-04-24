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
