<?
  session_start();
  unset($_SESSION["id"]); 
  unset($_SESSION["usuario"]);
  unset($_SESSION["nombres"]);
  unset($_SESSION["apellidos"]);
  unset($_SESSION["email"]);
  unset($_SESSION["administrador"]);
  session_destroy();
  header("Location: cuentaUsuarioHome.php");
  exit;
?>