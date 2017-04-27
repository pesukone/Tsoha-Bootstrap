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
      setlocale(LC_TIME, 'fi_FI');

      if(!empty($_POST)) {
        $time = $_POST['time'];
        $user = User::find($id);

        $month = date('m', strtotime($time));
        $year = date('Y', strtotime($time));
        $text = strftime('%B', strtotime($time));
        $days = date('t', strtotime($time));
      }else{
        $user = User::find($id);
        $month = date('m');
        $year = date('Y');
        $text = strftime('%B');
        $days = date('t');
      }

      View::make('user/show.html', array('user' => $user, 'monthtext' => $text, 'days' => $days, 'year' => $year, 'month' => $month));
    }

    public static function events_for_day($id, $date){
      $user = User::find($id);
      $events = $user->events_for_day($date);

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
