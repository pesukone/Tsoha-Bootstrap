<?php

  class BaseController{

    public static function get_user_logged_in(){
      if(isset($_SESSION['user'])){
        $user_id = $_SESSION['user'];
        $user = User::find($user_id);

        return $user;
      }
      return null;
    }

    public static function check_logged_in(){
      if(!isset($_SESSION['user'])){
        Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään!'));
      }
    }

    public static function check_event_owner($id){
      $event = Event::find($id);
      if(is_null($event)){
        Redirect::to('/', array('message' => 'Merkintää ei ole olemassa!'));
      }

      if($event->user->id != $_SESSION['user']){
        Redirect::to('/', array('errors' => array('Oikeudet eivät riitä!')));
      }
    }

    public static function check_group_membership($id){
      $group = Group::find($id);
      $user = self::get_user_logged_in();

      if(is_null($group)){
        Redirect::to('/', array('message' => 'Ryhmää ei ole olemassa!'));
      }

      $members = $group->members;
      if(empty($members)){
        Redirect::to('/', array('message' => 'Et ole ryhmän jäsen!'));
      }

      foreach($members as $member){
        if($member->id == $user->id){
          return;
        }
      }

      Redirect::to('/', array('message' => 'Et ole ryhmän jäsen!'));
    }

    public static function check_user_id($id){
      if(self::get_user_logged_in()->id != $id){
        Redirect::to('/', array('errors' => array('Oikeudet eivät riitä!')));
      }
    }
  }
