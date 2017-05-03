<?php

  $routes->get('/', function() {
    UserController::get_root();
  });

  $routes->post('/event', function(){
    EventController::store();
  });

  $routes->get('/event/new', function(){
    EventController::create();
  });

  $routes->get('/event/:id', function($id){
    EventController::show($id);
  });

  $routes->get('/event/:id/edit', function($id){
    EventController::edit($id);
  });

  $routes->post('/event/:id/edit', function($id){
    EventController::update($id);
  });

  $routes->post('/event/:id/destroy', function($id){
    EventController::destroy($id);
  });

  $routes->get('/user/new', function(){
    UserController::create();
  });

  $routes->post('/user/:id', function($id){
    UserController::show($id);
  });

  $routes->post('/user', function(){
    UserController::store();
  });

  $routes->get('/login', function(){
    UserController::login();
  });

  $routes->post('/login', function(){
    UserController::handle_login();
  });

  $routes->post('/logout', function(){
    UserController::logout();
  });

  $routes->get('/user/:id', function($id){
    UserController::show($id);
  });

  $routes->get('/user/:id/:year-:month-:day', function($id, $year, $month, $day){
    UserController::events_for_day($id, $day . "." . $month . "." . $year);
  });

  $routes->get('/group', function(){
    GroupController::index();
  });

  $routes->get('/group/new', function(){
    GroupController::create();
  });

  $routes->get('/group/:id/edit', function($id){
    GroupController::edit($id);
  });

  $routes->get('/group/:id', function($id){
    GroupController::show($id);
  });

  $routes->post('/group/:id/destroy', function($id){
    GroupController::destroy($id);
  });

  $routes->post('/group/:id/join', function($id){
    GroupController::add_member($id);
  });

  $routes->post('/group/:id/leave', function($id){
    GroupController::remove_member($id);
  });

  $routes->post('/group/:id/edit', function($id){
    GroupController::update($id);
  });

  $routes->post('/group', function(){
    GroupController::store();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
