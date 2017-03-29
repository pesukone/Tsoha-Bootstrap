<?php

  class HelloWorldController extends BaseController{

    public static function index() {
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      //View::make('home.html');
      echo 'Tänne tulee kirjautumissivu sitten joskus';
    }

    public static function sandbox() {
      $events = Event::all();
      $event_find = Event::find(1);
      $list_events = Event::list_events(1, '2017-03-23');

      $users = Registered::all();
      $user_find = Registered::find(1);


      Kint::dump($events);
      Kint::dump($event_find);
      Kint::dump($list_events);
      
      Kint::dump($users);
      Kint::dump($user_find);
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
