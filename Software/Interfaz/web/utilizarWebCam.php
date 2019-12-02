
<?php
$opcion = $_POST["opcion"];
$fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
$comando="python /mnt/sda1/SistRiego/python/camara_web.py " . $opcion . " " . $fecha_hora;
$salida = shell_exec($comando);
echo $comando; 
if ($salida !== NULL) {
    echo "FOTO TOMADA";
} else {
    echo "ERROR DE CAPTURA DE FOTO!";
}
?>