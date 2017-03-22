<?php

  class HelloWorldController extends BaseController{

    public static function index() {
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      //View::make('home.html');
      echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
      // Testaa koodiasi täällä
      View::make('helloworld.html');
    }

    public static function calendar_show() {
      View::make('suunnitelmat/calendar_show.html');
    }

    public static function event_new() {
      View::make('suunnitelmat/event_new.html');
    }

    public static function event_show() {
      View::make('suunnitelmat/event_show.html');
    }
  }
