<?php

class BaseModel{
  // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
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
    // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
    $errors = array();

    $errors = $this->validate_name();

    return $errors;

    foreach($this->$validators as $validator){
      // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
      $errors = array_merge($errors, $this->{$validator}());
    }

    return $errors;
  }

  public function validate_string_length($string, $length){
    if(strlen($string) < $length){
      return false;
    }

    return true;
  }

  public function validate_not_null($string){
    if($string == '' || $string == null){
      return false;
    }

    return true;
  }
}
