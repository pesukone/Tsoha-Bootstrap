<?php

  class HelloWorldController extends BaseController{

    public static function index() {
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      //View::make('home.html');
      echo 'Tänne tulee kirjautumissivu sitten joskus';
    }

    public static function sandbox() {
      $events = Event::all();
      $event_find = Event::find(3);

      $users = User::all();
      $user_find = User::find(1);
      $user_find_by_name = User::find_by_name("Pekka");
      $list_events = $user_find->events_for_day('2017-03-23');

      $groups = Group::all();
      $group = Group::find(3);
      $members = $group->get_members();

      Kint::dump($events);
      Kint::dump($event_find);
      Kint::dump($list_events);
      
      Kint::dump($users);
      Kint::dump($user_find);

      Kint::dump($groups);

      Kint::dump($event_find->errors());
      Kint::dump($group);
      Kint::dump($members);
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
