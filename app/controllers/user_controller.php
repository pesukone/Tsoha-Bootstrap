<?php

  class UserController extends BaseController{
    public static function index(){
      $users = User::all();

      View::make('user/index.html', array('users' => $users));
    }

    public static function list_events($id, $date){
      $user = User::find($id);
      $events = Event::list_events($id, $date);

      View::make('user/event_day.html', array('user' => $user, 'events' => $events));
    }
  }
