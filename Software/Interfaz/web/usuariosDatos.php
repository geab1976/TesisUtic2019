<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$id = $_POST["id"];
$nombres = $_POST["nombres"];
$apellidos = $_POST["apellidos"];
$email = $_POST["email"];
$administrador = $_POST["administrador"];
$activo = $_POST["activo"];
$usuario = $_POST["usuario"];
$clave = $_POST["clave"];
//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();
//Selector de casos
switch ($opcion) {
    case "1": //Consultar todos los Datos 
    case "2": //Obtener Datos de un registro
        $sql = "SELECT id_usuario, usuario, nombres, apellidos, email, administrador, activo FROM usuarios" . ($opcion == 1 ? ";" : " WHERE id_usuario=" . $id . ";");
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
        $sql = "UPDATE usuarios SET" .
            " nombres='" . $nombres .
            "',apellidos='" . $apellidos .
            "',email='" . $email .
            "',usuario='" . $usuario .
            "',clave='" . md5($clave) .
            "',administrador='" . $administrador .
            "',activo='" . $activo .
            "' WHERE id_usuario=" . $id . ";" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'6'," .
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
        $sql = "INSERT INTO usuarios (nombres, apellidos, email, administrador, usuario, clave, activo) VALUES(" .
            "'" . $nombres . "'," .
            "'" . $apellidos . "'," .
            "'" . $email . "'," .
            "'" . $administrador . "'," .
            "'" . $usuario . "'," .
            "'" . md5($clave) . "'," .
            "'" . $activo . "');" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'6'," .
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
        $db->exec('BEGIN;');
        $sql = "DELETE FROM usuarios WHERE id_usuario=" . $id . ";" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'6'," .
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
