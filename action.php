<?php

session_start();
require_once 'classes/Route.php';



$route = new formController();

 $route->handleForm();

