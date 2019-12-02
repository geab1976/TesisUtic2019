<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$opcion = $_POST["opcion"];
$fecha = $_POST["fecha"];
$hora = $_POST["hora"];
switch ($opcion) {
    case "1": //Consultar todos los Datos 
    case "2": //Obtener Datos de un registro
        $data = file_get_contents("http://localhost:8080/fecha_hora");
        //list($anio, $mes, $dia, $hora, $minuto, $segundo) = split('[/.-: ]', $data);
        list($fecha, $hora) = explode(' ', $data);
        list($anio, $mes, $dia) = explode('-', $fecha);
        //list($hs, $min, $seg) = explode(':', $hora);
        echo '[{"fecha":"' . $anio . '-' . $mes . '-' . $dia . '","hora":"' . $hora . '"}]';
        break;
    case "3":
        session_start();
        $db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
        assert($db);
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'3'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ")');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
        } else {
            $db->exec('ROLLBACK;');
        }
        break;
}
