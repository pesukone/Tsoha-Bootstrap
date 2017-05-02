<?php

  class HelloWorldController extends BaseController{

    public static function sandbox() {
      $events = Event::all();
      $event_find = Event::find(3);

      $users = User::all();
      $user_find = User::find(3);
      $user_find_by_name = User::find_by_name("Pekka");
      $list_events = $user_find->events_for_day('2017-03-23');
      $user_groups = $user_find->find_groups();

      $groups = Group::all();
      $group = Group::find(3);
      $members = $group->get_members();

      Kint::dump($events);
      Kint::dump($event_find);
      Kint::dump($list_events);
      
      Kint::dump($users);
      Kint::dump($user_find);
      Kint::dump($user_groups);

      Kint::dump($groups);

      Kint::dump($event_find->errors());
      Kint::dump($group);
      Kint::dump($members);
    }
  }
