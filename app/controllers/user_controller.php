<?php

  class UserController extends BaseController{
    public static function index(){
      $users = User::all();

      View::make('user/index.html', array('users' => $users));
    }
  }
