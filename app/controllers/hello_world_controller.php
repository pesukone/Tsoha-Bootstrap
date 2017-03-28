<?php

  class HelloWorldController extends BaseController{

    public static function index() {
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      //View::make('home.html');
      echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
      $events = Event::all();
      $users = Registered::all();

      $user_find = Registered::find(1);
      $event_find = Event::find(1);


      Kint::dump($events);
      Kint::dump($users);

      Kint::dump($user_find);
      Kint::dump($event_find);
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
