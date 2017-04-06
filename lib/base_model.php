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

  public function validate_datetime_format($string){
    if(!strtotime($string)){
      return false;
    }

    return true;
  }
}
