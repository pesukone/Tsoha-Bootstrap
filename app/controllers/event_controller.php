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

  public static function store(){
    $params = $_POST;

    $event = new Event(array(
      'eventday' => $params['day'],
      'eventtime' => $params['time'],
      'description' => $params['description'],
      'user' => User::find(1),      // korvataan current_user metodilla
      'group' => null       // korvataan ryhmänlisäämisvaihtoehdolla lomakkeessa
    ));

    $event->save();

    Redirect::to('/', array('message' => 'Tapahtuma luotu'));
  }
}
