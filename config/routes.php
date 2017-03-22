<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/suunnitelmat/calendar', function() {
    HelloWorldController::calendar_show();
  });

  $routes->get('/suunnitelmat/event/new', function() {
    HelloWorldController::event_new();
  });

  $routes->get('/suunnitelmat/event/1', function() {
    HelloWorldController::event_show();
  });
