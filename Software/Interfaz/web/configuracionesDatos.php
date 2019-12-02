<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$id = $_POST["id"]; //1
$id_especie = $_POST["id_especie"]; //2
$descripcion = $_POST["descripcion"]; //3
$maceta_tipo = $_POST["maceta_tipo"]; //4
$maceta_alto = $_POST["maceta_alto"]; //5
$maceta_largo = $_POST["maceta_largo"]; //6
$maceta_ancho = $_POST["maceta_ancho"]; //7
$maceta_volumen = $_POST["maceta_volumen"]; //8
$maceta_cantidad = $_POST["maceta_cantidad"]; //9
$gotero_caudal = $_POST["gotero_caudal"]; //10
$riego_inicio = $_POST["riego_inicio"]; //11
$riego_fin = $_POST["riego_fin"]; //12
$riego_minutos_activo = $_POST["riego_minutos_activo"]; //13
$riego_minutos_espera = $_POST["riego_minutos_espera"]; //14
$resumen_activar = $_POST["resumen_activar"]; //15
$resumen_hora_envio = $_POST["resumen_hora_envio"]; //16
$alerta_activar = $_POST["alerta_activar"]; //17
$alerta_riego_inicio = $_POST["alerta_riego_inicio"]; //18
$alerta_riego_fin = $_POST["alerta_riego_fin"]; //19
$alerta_hs_min = $_POST["alerta_hs_min"]; //20
$alerta_hs_max = $_POST["alerta_hs_max"]; //21
$alerta_ta_min = $_POST["alerta_ta_min"]; //22
$alerta_ta_max = $_POST["alerta_ta_max"]; //23
$alerta_ls_max = $_POST["alerta_ls_max"]; //24
$alerta_lluvia = $_POST["alerta_lluvia"]; //25
$webcam_activar = $_POST["webcam_activar"]; //26
$webcam_tamanio_imagen = $_POST["webcam_tamanio_imagen"]; //27
$webcam_tamanio_video = $_POST["webcam_tamanio_video"]; //28
$webcam_fps_video = $_POST["webcam_fps_video"]; //29
$email_smtp_activar = $_POST["email_smtp_activar"]; //30
$email_smtp_servidor = $_POST["email_smtp_servidor"]; //31
$email_smtp_puerto = $_POST["email_smtp_puerto"]; //32
$email_smtp_ssl = $_POST["email_smtp_ssl"]; //33
$email_smtp_usuario = $_POST["email_smtp_usuario"]; //34
$email_smtp_clave = $_POST["email_smtp_clave"]; //35
$dispositivo_activar = $_POST["dispositivo_activar"]; //36
$configuracion_activar = $_POST["configuracion_activar"]; //37

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
            "   c.id_configuracion," .
            "   c.id_especie," .
            "   e.nombre||' - '||e.descripcion as especie," .
            "   e.riego_mililitros," .
            "   e.riego_frecuencia," .
            "   c.descripcion," .
            "   c.maceta_tipo," .
            "   c.maceta_alto," .
            "   c.maceta_largo," .
            "   c.maceta_ancho," .
            "   c.maceta_volumen," .
            "   c.maceta_cantidad," .
            "   c.gotero_caudal," .
            "   c.riego_inicio," .
            "   c.riego_fin," .
            "   c.riego_minutos_activo," .
            "   c.riego_minutos_espera," .
            "   c.resumen_activar," .
            "   c.resumen_hora_envio," .
            "   c.alerta_activar," .
            "   c.alerta_riego_inicio," .
            "   c.alerta_riego_fin," .
            "   c.alerta_hs_min," .
            "   c.alerta_hs_max," .
            "   c.alerta_ta_min," .
            "   c.alerta_ta_max," .
            "   c.alerta_ls_max," .
            "   c.alerta_lluvia," .
            "   c.webcam_activar," .
            "   c.webcam_tamanio_imagen," .
            "   c.webcam_tamanio_video," .
            "   c.webcam_fps_video," .
            "   c.email_smtp_activar," .
            "   c.email_smtp_servidor," .
            "   c.email_smtp_puerto," .
            "   c.email_smtp_ssl," .
            "   c.email_smtp_usuario," .
            "   c.email_smtp_clave," .
            "   c.dispositivo_activar," .
            "   c.configuracion_activar " .
            "FROM " .
            "   configuraciones c " .
            "   LEFT JOIN especies e ON e.id_especie=c.id_especie " . ($opcion == 1 ? ";" : " WHERE id_configuracion=" . $id . ";");
        //echo $sql;
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
        $sql = "" . (strcmp($configuracion_activar, "0" == 0) ? "" : "UPDATE configuraciones SET configuracion_activar = '0';") .
            "UPDATE configuraciones SET " .
            "id_especie = '" . $id_especie . "'," .
            "descripcion = '" . $descripcion . "'," .
            "maceta_tipo = '" . $maceta_tipo . "'," .
            "maceta_alto = '" . $maceta_alto . "'," .
            "maceta_largo = '" . $maceta_largo . "'," .
            "maceta_ancho = '" . $maceta_ancho . "'," .
            "maceta_volumen = '" . $maceta_volumen . "'," .
            "maceta_cantidad = '" . $maceta_cantidad . "'," .
            "gotero_caudal = '" . $gotero_caudal . "'," .
            "riego_inicio = '" . $riego_inicio . "'," .
            "riego_fin = '" . $riego_fin . "'," .
            "riego_minutos_activo = '" . $riego_minutos_activo . "'," .
            "riego_minutos_espera = '" . $riego_minutos_espera . "'," .
            "resumen_activar = '" . $resumen_activar . "'," .
            "resumen_hora_envio = '" . $resumen_hora_envio . "'," .
            "alerta_activar = '" . $alerta_activar . "'," .
            "alerta_riego_inicio = '" . $alerta_riego_inicio . "'," .
            "alerta_riego_fin = '" . $alerta_riego_fin . "'," .
            "alerta_hs_min = '" . $alerta_hs_min . "'," .
            "alerta_hs_max = '" . $alerta_hs_max . "'," .
            "alerta_ta_min = '" . $alerta_ta_min . "'," .
            "alerta_ta_max = '" . $alerta_ta_max . "'," .
            "alerta_ls_max = '" . $alerta_ls_max . "'," .
            "alerta_lluvia = '" . $alerta_lluvia . "'," .
            "webcam_activar = '" . $webcam_activar . "'," .
            "webcam_tamanio_imagen = '" . $webcam_tamanio_imagen . "'," .
            "webcam_tamanio_video = '" . $webcam_tamanio_video . "'," .
            "webcam_fps_video = '" . $webcam_fps_video . "'," .
            "email_smtp_activar = '" . $email_smtp_activar . "'," .
            "email_smtp_servidor = '" . $email_smtp_servidor . "'," .
            "email_smtp_puerto = '" . $email_smtp_puerto . "'," .
            "email_smtp_ssl = '" . $email_smtp_ssl . "'," .
            "email_smtp_usuario = '" . $email_smtp_usuario . "'," .
            "email_smtp_clave = '" . $email_smtp_clave . "'," .
            "dispositivo_activar = '" . $dispositivo_activar . "'," .
            "configuracion_activar = '" . $configuracion_activar . "' " .
            "WHERE id_configuracion = '" . $id . "';" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'4'," .
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
    case "4": //Actualizar registro
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "" . (strcmp($configuracion_activar, "0" == 0) ? "" : "UPDATE configuraciones SET configuracion_activar = '0';") .
            "INSERT INTO configuraciones (" .
            "id_especie," .
            "descripcion," .
            "maceta_tipo," .
            "maceta_alto," .
            "maceta_largo," .
            "maceta_ancho," .
            "maceta_volumen," .
            "maceta_cantidad," .
            "gotero_caudal," .
            "riego_inicio," .
            "riego_fin," .
            "riego_minutos_activo," .
            "riego_minutos_espera," .
            "resumen_activar," .
            "resumen_hora_envio," .
            "alerta_activar," .
            "alerta_riego_inicio," .
            "alerta_riego_fin," .
            "alerta_hs_min," .
            "alerta_hs_max," .
            "alerta_ta_min," .
            "alerta_ta_max," .
            "alerta_ls_max," .
            "alerta_lluvia," .
            "webcam_activar," .
            "webcam_tamanio_imagen," .
            "webcam_tamanio_video," .
            "webcam_fps_video," .
            "email_smtp_activar," .
            "email_smtp_servidor," .
            "email_smtp_puerto," .
            "email_smtp_ssl," .
            "email_smtp_usuario," .
            "email_smtp_clave," .
            "dispositivo_activar," .
            "configuracion_activar" .
            ") " .
            "VALUES (" .
            "'" . $id_especie . "'," .
            "'" . $descripcion . "'," .
            "'" . $maceta_tipo . "'," .
            "'" . $maceta_alto . "'," .
            "'" . $maceta_largo . "'," .
            "'" . $maceta_ancho . "'," .
            "'" . $maceta_volumen . "'," .
            "'" . $maceta_cantidad . "'," .
            "'" . $gotero_caudal . "'," .
            "'" . $riego_inicio . "'," .
            "'" . $riego_fin . "'," .
            "'" . $riego_minutos_activo . "'," .
            "'" . $riego_minutos_espera . "'," .
            "'" . $resumen_activar . "'," .
            "'" . $resumen_hora_envio . "'," .
            "'" . $alerta_activar . "'," .
            "'" . $alerta_riego_inicio . "'," .
            "'" . $alerta_riego_fin . "'," .
            "'" . $alerta_hs_min . "'," .
            "'" . $alerta_hs_max . "'," .
            "'" . $alerta_ta_min . "'," .
            "'" . $alerta_ta_max . "'," .
            "'" . $alerta_ls_max . "'," .
            "'" . $alerta_lluvia . "'," .
            "'" . $webcam_activar . "'," .
            "'" . $webcam_tamanio_imagen . "'," .
            "'" . $webcam_tamanio_video . "'," .
            "'" . $webcam_fps_video . "'," .
            "'" . $email_smtp_activar . "'," .
            "'" . $email_smtp_servidor . "'," .
            "'" . $email_smtp_puerto . "'," .
            "'" . $email_smtp_ssl . "'," .
            "'" . $email_smtp_usuario . "'," .
            "'" . $email_smtp_clave . "'," .
            "'" . $dispositivo_activar . "'," .
            "'" . $configuracion_activar . "'" .
            ");" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'4'," .
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
        $sql = "DELETE FROM configuraciones WHERE id_configuracion=" . $id . ";" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'4'," .
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
    case "6": //Obtener Datos de especies (FK)
        $sql = "" .
            "SELECT " .
            "   id_especie," .
            "   nombre||' - '||descripcion as especie," .
            "   riego_mililitros," .
            "   riego_frecuencia " .
            "FROM " .
            "   especies;";
        //echo $sql;
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        echo json_encode($datos);
        break;
}
$db->close();
