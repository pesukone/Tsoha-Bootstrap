<?php

  class EventsController extends BaseController{
    public static function show($id){
      $event = Event::find($id);

      View::make('event/show.html', array('event' => $event);
    }
  }
