<?php

class EventController extends BaseController{
  public static function index(){
    $events = Event::all();
      
    View::make('event/index.html', array('events' => $events));
  }

  public static function show($id){
    $event = Event::find($id);

    View::make('event/show.html', array('event' => $event));
  }

  public static function create(){
    View::make('event/new.html');
  }

  public static function store(){
    $params = $_POST;

    $attributes = array(
      'eventday' => $params['day'],
      'eventtime' => $params['time'],
      'description' => $params['description'],
      'user' => User::find(1),      // korvataan current_user metodilla
      'group' => null       // korvataan ryhmänlisäämisvaihtoehdolla lomakkeessa
    );

    $event = new Event($attributes);
    $errors = $event->errors();

    if(count($errors) == 0){
      $event->save();

      Redirect::to('/event/' . $event->id, array('message' => 'Merkintä luotu'));
    }else{
      View::make('event/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }

  public static function edit($id){
    $event = Event::find($id);
    View::make('event/edit.html', array('attributes' => $event));
  }
}
