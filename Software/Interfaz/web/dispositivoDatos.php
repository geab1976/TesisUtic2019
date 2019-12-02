<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
error_reporting(0);
$data = file_get_contents("http://localhost:8080/json");

if($data===FALSE){
   echo "[{\"In\":\"Error\"}]";
}else{
   echo $data;
}
?>