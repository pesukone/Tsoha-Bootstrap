<?php

class UserController extends BaseController{
  public static function index(){
    $users = User::all();

    View::make('user/index.html', array('users' => $users));
  }

  public static function login(){
    View::make('user/login.html');
  }

  public static function handle_login(){
    $params = $_POST;

    $user = User::authenticate($params['username'], $params['password']);

    if(!$user){
      View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
    }else{
      $_SESSION['user'] = $user->id;

      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
    }

  public static function list_events($id, $date){
    $user = User::find($id);
    $events = User::list_events($user->id, $date);

    View::make('user/event_day.html', array('user' => $user, 'events' => $events, 'date' => $date));
  }

  public static function create(){
    View::make('user/new.html');
  }
}
