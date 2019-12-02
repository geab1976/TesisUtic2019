<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$id = $_POST["id"];
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
$tarifa = $_POST["tarifa"];
//$fecha_inicio = explode("-", $fecha_inicio)[0] . "-" . explode("-", $fecha_inicio)[1] . "-" . explode("-", $fecha_inicio)[0];
//$fecha_fin = explode("-", $fecha_fin)[0] . "-" . explode("-", $fecha_fin)[1] . "-" . explode("-", $fecha_fin)[0];
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
            "   id_tarifa_agua," .
            //"   strftime('%d/%m/%Y',fecha_inicio) as fecha_inicio," .
            "   fecha_inicio," .
            //"   strftime('%d/%m/%Y',fecha_fin) as fecha_fin," .
            "   fecha_fin," .
            "   tarifa " .
            "FROM " .
            "   tarifas_agua" . ($opcion == 1 ? ";" : " WHERE id_tarifa_agua=" . $id . ";");
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        //echo $sql;
        echo json_encode($datos);
        break;
    case "3": //Actualizar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $ban = 0;
        $sql = "" .
            "SELECT " .
            "   id_tarifa_agua " .
            "FROM " .
            "   tarifas_agua " .
            "WHERE " .
            "   (date('" . $fecha_inicio . "')>=fecha_inicio AND date('" . $fecha_inicio . "')<=fecha_fin) OR " .
            "   (date('" . $fecha_fin . "')>=fecha_inicio AND date('" . $fecha_fin . "')<=fecha_fin)"; //date('2019-01-01')>=date('2019-02-01') AND date('2019-03-01')<=date('2019-12-31')
        $results = $db->query($sql);
        if ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $ban = 1;
        }
        $sql = "" .
            "SELECT " .
            "   id_tarifa_agua " .
            "FROM " .
            "   tarifas_agua " .
            "WHERE " .
            "   (date('" . $fecha_inicio . "')=fecha_inicio AND date('" . $fecha_fin . "')=fecha_fin)"; //date('2019-01-01')>=date('2019-02-01') AND date('2019-03-01')<=date('2019-12-31')
        $results = $db->query($sql);
        if ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $ban = 0;
        }
        if ($ban == 0) {
            $db->exec('BEGIN;');
            $sql = "UPDATE tarifas_agua SET" .
                " fecha_inicio='" . $fecha_inicio .
                "',fecha_fin='" . $fecha_fin .
                "',tarifa='" . $tarifa .
                "' WHERE id_tarifa_agua=" . $id . ";" .
                "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
                "datetime('" . $fecha_hora . "')," .
                "'20'," .
                "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ") Operación: Modificación');";
            $query = $db->exec($sql);
            if ($query) {
                $db->exec('COMMIT;');
                echo 'Registro Actualizado!!!';
            } else {
                $db->exec('ROLLBACK;');
                echo "Error: " . $sql;
            }
        } else {
            echo 'El rango seleccionado no es factible!!!';
        }
        break;
    case "4": //Insertar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $ban = 0;
        $sql = "" .
            "SELECT " .
            "   id_tarifa_agua " .
            "FROM " .
            "   tarifas_agua " .
            "WHERE " .
            "   (date('" . $fecha_inicio . "')>=fecha_inicio AND date('" . $fecha_inicio . "')<=fecha_fin) OR " .
            "   (date('" . $fecha_fin . "')>=fecha_inicio AND date('" . $fecha_fin . "')<=fecha_fin)"; //date('2019-01-01')>=date('2019-02-01') AND date('2019-03-01')<=date('2019-12-31')
        $results = $db->query($sql);
        if ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $ban = 1;
        }
        if ($ban == 0) {
            $db->exec('BEGIN;');
            $sql = "INSERT INTO tarifas_agua (fecha_inicio, fecha_fin, tarifa) VALUES(" .
                "'" . $fecha_inicio . "'," .
                "'" . $fecha_fin . "'," .
                "'" . $tarifa . "');" .
                "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
                "datetime('" . $fecha_hora . "')," .
                "'20'," .
                "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ") Operación: Inserción');";
            $query = $db->exec($sql);
            if ($query) {
                $db->exec('COMMIT;');
                echo 'Registro Agregado!!!';
            } else {
                $db->exec('ROLLBACK;');
                echo "Error: " . $sql;
            }
        } else {
            echo 'El rango seleccionado no es factible!!!';
        }
        break;
    case "5": //Borrar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $ban = 0;
        $db->exec('BEGIN;');
        $sql = "DELETE FROM tarifas_agua WHERE id_tarifa_agua=" . $id . ";" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'20'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ") Operación: Borrado');";
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
