<?php

  class UserController extends BaseController{
    public static function get_root(){
      if(parent::check_logged_in()){
        self::show(parent::get_logged_in()->id);
      }else{
        self::login();
      }
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
      parent::check_user_id($id);

      setlocale(LC_TIME, 'fi_FI');

      if(!empty($_POST)) {
        $time = $_POST['time'];
        $user = User::find($id);

        $month = date('n', strtotime($time));
        $year = date('Y', strtotime($time));
        $text = strftime('%B', strtotime($time));
        $days = date('t', strtotime($time));
      }else{
        $user = User::find($id);
        $month = date('n');
        $year = date('Y');
        $text = strftime('%B');
        $days = date('t');
      }

      View::make('user/show.html', array('user' => $user, 'monthtext' => $text, 'days' => $days, 'year' => $year, 'month' => $month));
    }

    public static function events_for_day($id, $date){
      parent::check_user_id($id);

      $user = User::find($id);
      $events = $user->events_for_day($date);

      usort($events, 'cmp_time');

      View::make('user/event_day.html', array('user' => $user, 'events' => $events, 'date' => $date));
    }

    public static function create(){
      View::make('user/new.html');
    }

    public static function store(){
      $user = self::parse_post_attributes($_POST);
      $errors = $user->errors();

      if(count($errors) == 0){
        $user->save();

        Redirect::to('/user/' . $user->id, array('message' => 'Käyttäjätunnus luotu'));
      }else{
        View::make('user/new.html', array('errors' => $errors, 'attributes' => $attributes));
      }
    }

    private static function parse_post_attributes($params){
      $attributes = array(
        'name' => $params['name'],
        'password' => $params['password']
      );

      return new User($attributes);
    }
  }

  function cmp_time($a, $b){
    if($a->eventtime == $b->eventtime){
      return 0;
    }
    return ($a->eventtime < $b->eventtime) ? -1 : 1;
  }
