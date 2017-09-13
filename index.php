<?php
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Methods: *");
    require_once "LoginApi.php";    
    $peopleAPI = new LoginAPI();
    $peopleAPI->API();
?>