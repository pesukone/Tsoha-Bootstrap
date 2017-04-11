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

    $user = User::authenticate($params['name'], $params['password']);

    if(!$user){
      View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'name' => $params['name']));
    }else{
      $_SESSION['user'] = $user->id;

      Redirect::to('/user/'. $user->id, array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
    }
  }

  public static function logout(){
    $_SESSION['user'] = null;
    Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
  }

  public static function show($id){
    $user = User::find($id);

    View::make('user/show.html', array('user' => $user));
  }

  public static function list_events($id, $date){
    $user = User::find($id);
    $events = User::list_events($user->id, $date);

    View::make('user/event_day.html', array('user' => $user, 'events' => $events, 'date' => $date));
  }

  public static function create(){
    View::make('user/new.html');
  }

  public static function store(){
    $params = $_POST;

    $attributes = array(
      'name' => $params['name'],
      'password' => $params['password']
    );

    $user = new User($attributes);
    $errors = $user->errors();

    if(count($errors) == 0){
      $user->save();

      Redirect::to('/user/' . $user->id, array('message' => 'Käyttäjätunnus luotu'));
    }else{
      View::make('user/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }
}
