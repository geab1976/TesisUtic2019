<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$id = $_POST["id"];
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$riego_mililitros = $_POST["riego_mililitros"];
$riego_frecuencia = $_POST["riego_frecuencia"];
$ta_min = $_POST["ta_min"];
$ta_max = $_POST["ta_max"];
$hs_min = $_POST["hs_min"];
$hs_max = $_POST["hs_max"];
$ls_min = $_POST["ls_min"];
$ls_max = $_POST["ls_max"];
//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();
//Selector de casos
switch ($opcion) {
    case "1": //Consultar todos los Datos 
    case "2": //Obtener Datos de un registro
        $sql = "" .
            "SELECT " .
            "   id_especie," .
            "   nombre," .
            "   descripcion," .
            "   riego_mililitros," .
            "   riego_frecuencia," .
            "   ta_min," .
            "   ta_max," .
            "   hs_min," .
            "   hs_max," .
            "   ls_min," .
            "   ls_max " .
            "FROM " .
            "   especies" . ($opcion == 1 ? ";" : " WHERE id_especie=" . $id . ";");
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        echo json_encode($datos);
        break;
    case "3": //Actualizar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "UPDATE especies SET" .
            " nombre='" . $nombre .
            "',descripcion='" . $descripcion .
            "',riego_mililitros='" . $riego_mililitros .
            "',riego_frecuencia='" . $riego_frecuencia .
            "',ta_min='" . $ta_min .
            "',ta_max='" . $ta_max .
            "',hs_min='" . $hs_min .
            "',hs_max='" . $hs_max .
            "',ls_min='" . $ls_min .
            "',ls_max='" . $ls_max .
            "' WHERE id_especie=" . $id . ";" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'5'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ") Operación: Modificación');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
            echo 'Registro Actualizado!!!';
        } else {
            $db->exec('ROLLBACK;');
            echo "Error: " . $sql;
        }
        break;
    case "4": //Insertar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "INSERT INTO especies (nombre, descripcion, riego_mililitros, riego_frecuencia, ta_min, ta_max, hs_min, hs_max, ls_min, ls_max) VALUES(" .
            "'" . $nombre . "'," .
            "'" . $descripcion . "'," .
            "'" . $riego_mililitros . "'," .
            "'" . $riego_frecuencia . "'," .
            "'" . $ta_min . "'," .
            "'" . $ta_max . "'," .
            "'" . $hs_min . "'," .
            "'" . $hs_max . "'," .
            "'" . $ls_min . "'," .
            "'" . $ls_max . "');" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'5'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ") Operación: Inserción');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
            echo 'Registro Agregado!!!';
        } else {
            $db->exec('ROLLBACK;');
            echo "Error: " . $sql;
        }
        break;
    case "5": //Borrar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $ban = 0;
        $sql = "" .
            "SELECT " .
            "   id_especie " .
            "FROM " .
            "   configuraciones " .
            "WHERE id_especie=" . $id . ";";
        $results = $db->query($sql);
        if ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $ban = 1;
        }
        if ($ban == 0) {
            $db->exec('BEGIN;');
            $sql = "DELETE FROM especies WHERE id_especie=" . $id . " AND " . $id . " NOT IN(SELECT id_especie FROM configuraciones);" .
                "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
                "datetime('" . $fecha_hora . "')," .
                "'5'," .
                "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ") Operación: Borrado');";
            $query = $db->exec($sql);
            if ($query) {
                $db->exec('COMMIT;');
                echo 'Registro Borrado!!!';
            } else {
                $db->exec('ROLLBACK;');
                echo "Error: " . $sql;
            }
        } else {
            echo 'Operación Cancelada, Registro utilizado!!!';
        }
        break;
}
$db->close();
