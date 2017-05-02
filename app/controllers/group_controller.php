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
      $group = self::parse_post_attributes($_POST);
      $errors = $group->errors();

      if(count($errors) == 0){
        $group->save();
        $group->add_member($user);

        Redirect::to('/group/' . $group->id, array('message' => 'Ryhmä luotu'));
      }else{
        View::make('group/new.html', array('errors' => $errors, 'attributes' => $attributes));
      }
    }

    public static function show($id){
      $group = Group::find($id);

      View::make('group/show.html', array('group' => $group));
    }

    public static function edit($id){
      self::check_group_membership($id);
      $group = Group::find($id);

      View::make('group/edit.html', array('attributes' => $group));
    }

    public static function update($id){
      self::check_group_membership($id);

      $group = self::parse_post_attributes($_POST);
      $errors = $group->errors();

      if(count($errors) > 0){
        View::make('group/edit.html', array('errors' => $errors, 'attributes' => $attributes));
      }else{
        $group->update();

        Redirect::to('/group/' . $group->id, array('message' => 'Ryhmää muokattu onnistuneesti!'));
      }
    }

    public static function destroy($id){
      $group = new Group(array('id' => $id));
      $group->destroy();

      Redirect::to('/', array('message' => 'Ryhmä tuhottu onnistuneesti!'));
    }

    public static function add_member($id){
      $group = Group::find($id);
      $user = self::get_user_logged_in();

      $group->add_member($user);

      Redirect::to('/group/' . $group->id, array('message' => 'Liitytty ryhmään'));
    }

    public static function remove_member($id){
      $group = Group::find($id);
      $user = self::get_user_logged_in();

      $group->remove_member($user);

      Redirect::to('/group', array('message' => 'Lähdetty ryhmästä'));
    }

    private static function parse_post_attributes($params){
      $attributes = array(
        'name' => $params['name'],
        'description' => $params['description'],
      );

      return new Group($attributes);
    }
  }
