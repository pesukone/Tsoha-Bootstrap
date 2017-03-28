<?php

  class EventsController extends BaseController{
    public static function index(){
      $events = Event::all();
      
      View::make('event/index.html', array('events' => $events));
    }

    public static function show($id){
      $event = Event::find($id);

      View::make('event/show.html', array('event' => $event);
    }
  }
