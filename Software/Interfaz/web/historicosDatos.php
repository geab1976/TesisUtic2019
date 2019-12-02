<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$id_historico_motivo = $_POST["id_historico_motivo"];
$motivo = $_POST["motivo"];
$fecha_desde = $_POST["fecha_desde"];
$fecha_hasta = $_POST["fecha_hasta"];
$filtro = $_POST["filtro"];

/*$fecha1 = explode("/", $fecha_desde);
$fecha_desde = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
$fecha1 = explode("/", $fecha_hasta);
$fecha_hasta = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
*/

//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();
//Selector de casos
switch ($opcion) {
    case "1": //Obtener Datos de especies (FK)
        $sql = "" .
            "SELECT\r" .
            "   hm.id_historico_motivo as id,\r" .
            "   hm.descripcion as motivo\r" .
            "FROM\r" .
            "   historicos_motivos hm\r";
        //echo $sql;
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        echo json_encode($datos);
        break;
    case "2": //Borrar registro según consulta
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "" .
            "DELETE FROM historicos\r" .
            "WHERE\r".
            "  date(fecha_hora)>=date('" . $fecha_desde . "') AND\r" .
            "  date(fecha_hora)<=date('" . $fecha_hasta . "') AND\r" .
            " " . ($id_historico_motivo == "0" ? "id_historico_motivo>0" : "id_historico_motivo=" . $id_historico_motivo) . " AND\r" .
            " " . (strlen(trim($filtro)) == 0 ? "id_historico_motivo>0" : "detalle LIKE '%" . $filtro . "%'") . ";\r".
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'18'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ")".
            " Operación: Borrado -> Motivo: ".$motivo.
            ", Desde: ".$fecha_desde.
            ", Hasta: ".$fecha_hasta.
            ", Filtro: ".$filtro."');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
            echo 'Registro Borrado!!!';
        } else {
            $db->exec('ROLLBACK;');
            echo "Error: " . $sql;
        }
        break;
    case "3": //Borrar registro según consulta
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "" .
            "DELETE FROM historicos;\r".
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'19'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ")".
            " Operación: Borrado Total');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
            echo 'Registro Borrado!!!';
        } else {
            $db->exec('ROLLBACK;');
            echo "Error: " . $sql;
        }
        break;
}
$db->close();
