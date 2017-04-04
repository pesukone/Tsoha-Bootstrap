<?php

class GroupController extends BaseController{
  public static function index(){
    $groups = Group::all();

    View::make('group/index.html', array('groups' => $groups));
  }
}
