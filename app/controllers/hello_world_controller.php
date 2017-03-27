<?php

  require 'app/models/registered.php';
  require 'app/models/event.php';
  class HelloWorldController extends BaseController{

    public static function index() {
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      //View::make('home.html');
      echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
      $pekka = Registered::find(1);
      $events = Event::all();
      $users = Registered::all();

      Knit::dump($pekka);
      Knit::dump($events);
      Knit::dump($users);
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

    public static function registered_new() {
      View::make('suunnitelmat/registration_new.html');
    }
  }
