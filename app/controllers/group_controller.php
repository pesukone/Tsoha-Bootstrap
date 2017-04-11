<?php

class GroupController extends BaseController{
  public static function index(){
    $groups = Group::all();

    View::make('group/index.html', array('groups' => $groups));
  }

  public static function create(){
    View::make('group/new.html');
  }

  public static function store(){
    $params = $_POST;

    $attributes = array(
      'name' => $params['name'],
      'description' => $params['description']
    );

    $group = new Group($attributes);
    $errors = $group->errors();

    if(count($errors) == 0){
      $group->save();

      Redirect::to('/group/' . $group->id, array('message' => 'Ryhmä luotu'));
    }else{
      View::make('group/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }
}
