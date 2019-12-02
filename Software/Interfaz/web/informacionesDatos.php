<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();
//Selector de casos
switch ($opcion) {
    case "1": //Obtener Datos de un registro
        $sql = "" .
            "SELECT " .
            "   CASE\r" .
            "       WHEN c.maceta_tipo=1 THEN 'Paralepípedo'\r" .
            "       WHEN c.maceta_tipo=2 THEN 'Pirámide Truncada'\r" .
            "       WHEN c.maceta_tipo=3 THEN 'Cono Truncado'\r" .
            "       WHEN c.maceta_tipo=4 THEN 'Cilindro'\r" .
            "   END AS tipo_maceta,\r" .
            "   c.maceta_volumen AS volumen_maceta,\r" .
            "   c.maceta_cantidad AS cantidad_maceta,\r" .
            "   c.gotero_caudal AS caudal_gotero,\r" .
            "   e.nombre AS nombre_especie,\r" .
            "   e.descripcion AS descripcion_especie,\r" .
            "   e.riego_mililitros AS agua_especie,\r" .
            "   c.riego_inicio,\r" .
            "   c.riego_fin,\r" .
            "   c.riego_minutos_activo AS activo_riego,\r" .
            "   c.riego_minutos_espera AS espera_riego,\r" .
            "   '['||e.hs_min||' - '||e.hs_max||'] %' AS hs_riego,\r" .
            "   '['||e.ta_min||' - '||e.ta_max||'] °C' AS ta_riego,\r" .
            "   '['||e.ls_min||' - '||e.ls_max||'] %' AS la_riego,\r" .
            "   CASE WHEN c.webcam_activar=1 THEN 'Sí' ELSE 'NO' END AS activar_webcam,\r" .
            "   c.webcam_tamanio_imagen AS rf_webcam,\r" .
            "   c.webcam_tamanio_video AS rv_webcam,\r" .
            "   c.webcam_fps_video AS fps_webcam,\r" .
            "   CASE WHEN c.resumen_activar=1 THEN 'Sí' ELSE 'NO' END AS activar_resumen,\r" .
            "   c.resumen_hora_envio AS hora_resumen,\r" .
            "   CASE WHEN c.alerta_activar=1 THEN 'Sí' ELSE 'NO' END AS activar_alerta,\r" .
            "   CASE WHEN c.alerta_riego_inicio=1 THEN 'Sí' ELSE 'NO' END AS ri_alerta,\r" .
            "   CASE WHEN c.alerta_riego_fin=1 THEN 'Sí' ELSE 'NO' END AS rf_alerta,\r" .
            "   CASE WHEN c.alerta_hs_min=1 THEN 'Sí' ELSE 'NO' END AS hsmin_alerta,\r" .
            "   CASE WHEN c.alerta_hs_max=1 THEN 'Sí' ELSE 'NO' END AS hsmax_alerta,\r" .
            "   CASE WHEN c.alerta_ta_min=1 THEN 'Sí' ELSE 'NO' END AS tamin_alerta,\r" .
            "   CASE WHEN c.alerta_ta_min=1 THEN 'Sí' ELSE 'NO' END AS tamax_alerta,\r" .
            "   CASE WHEN c.alerta_ls_max=1 THEN 'Sí' ELSE 'NO' END AS lsmax_alerta,\r" .
            "   CASE WHEN c.alerta_lluvia=1 THEN 'Sí' ELSE 'NO' END AS ll_alerta,\r" .
            "   CASE WHEN c.email_smtp_activar=1 THEN 'Sí' ELSE 'NO' END AS activar_smtp,\r" .
            "   c.email_smtp_servidor AS servidor_smtp,\r" .
            "   c.email_smtp_puerto AS puerto_smtp,\r" .
            "   CASE WHEN c.email_smtp_ssl THEN 'Sí' ELSE 'NO' END AS ssl_smtp,\r" .
            "   c.email_smtp_usuario AS usuario_smtp\r" .
            "FROM\r" .
            "   configuraciones c\r" .
            "   LEFT JOIN especies e ON e.id_especie=c.id_especie\r" .
            "WHERE\r" .
            "   c.configuracion_activar=1 AND\r" .
            "   c.dispositivo_activar=1\r" .
            ";";
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        echo json_encode($datos);
        break;
    case "2": //Obtener Datos de Históricos [{ultimo acceso}]
        session_start();
        $usuario =  $_SESSION["usuario"];
        $data = file_get_contents("http://localhost:8080/json");
        //echo $data;// Produce: Hll Wrld f PHP
        //$elementos = array("[", "]","{","}", "\"In\":","\"Ac\":","\"Hs\":","\"Ha\":","\"Ta\":","\"Lu\":","\"Ll\":","\"Ri\":","\"Ci\":");
        $elementos = array("[", "]", "{", "}", "\"", "In:", "Ac:", "Hs:", "Ha:", "Ta:", "Lu:", "Ll:", "Ri:", "Ci:");
        //{In:2019-09-09 15:45:35,Ci:87553,Ac:2019-09-12 18:44:19,Hs:1,Ha:51,Ta:22,Lu:12,Ll:NO,Ri:NO}
        //$onlyconsonants = str_replace($vowels, "", "Hello World of PHP");
        $data = str_replace($elementos, "", $data);
        $valores = explode(",", $data);
        $fecha_ultimo_inicio = $valores[0];
        $fecha_hora_actual = $valores[2];
        $sql = "" .
            "SELECT " .
            "   max(h.fecha_hora) as ultimo_acceso\r" .
            "FROM\r" .
            "   historicos h\r" .
            "WHERE\r" .
            "   h.id_historico_motivo=2 AND\r" .
            "   h.detalle LIKE '%" . $usuario . "%';\r";
        //echo $sql;
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $sql = "" .
                "SELECT " .
                "   strftime('%d/%m/%Y %H:%M:%S',datetime('" . $fecha_hora_actual . "')) AS fh_actual, " .
                "   COALESCE(strftime('%d/%m/%Y %H:%M:%S',d.primer_inicio),'No Registrado o Borrado.') AS primer_inicio, " .
                "   strftime('%d/%m/%Y %H:%M:%S',datetime('" . $fecha_ultimo_inicio . "')) AS fhu_inicio, " .
                "   CAST((strftime('%s', datetime('" . $fecha_hora_actual . "')) - strftime('%s', datetime('" . $fecha_ultimo_inicio . "')))/(60 * 60 * 24) AS TEXT)||'d '||" .
                "   CAST(((strftime('%s', datetime('" . $fecha_hora_actual . "')) - strftime('%s', datetime('" . $fecha_ultimo_inicio . "'))) % (60 * 60 * 24))/(60 * 60) AS TEXT)||'h '||" .
                "   CAST((((strftime('%s', datetime('" . $fecha_hora_actual . "')) - strftime('%s', datetime('" . $fecha_ultimo_inicio . "'))) % (60 * 60 * 24)) % (60 * 60))/60 AS TEXT)||'m' AS tiempo_transcurrido," .
                "   strftime('%d/%m/%Y %H:%M:%S',max(h.fecha_hora)) as ultimo_acceso\r" .
                "FROM\r" .
                "   historicos h\r" .
                "   LEFT JOIN(\r" .
                "       SELECT\r" .
                "           h.fecha_hora as primer_inicio\r" .
                "       FROM\r" .
                "           historicos h\r" .
                "       WHERE\r" .
                "           h.id_historico_motivo=1\r" .
                "       LIMIT 1\r" .
                "   ) d ON 1>0\r" .
                "WHERE\r" .
                "   h.id_historico_motivo=2 AND\r" .
                "   h.fecha_hora<datetime('" . $row['ultimo_acceso'] . "') AND\r" .
                "   h.detalle LIKE '%" . $usuario . "%';\r";
            //echo $sql;
            $results = $db->query($sql);
            while ($row2 = $results->fetchArray(SQLITE3_ASSOC)) {
                $datos[] = $row2;
                break;
            }
        }
        echo json_encode($datos);
        break;
    case "3": //Obtener Datos de Históricos [{ultimo acceso}]
        $sql = "" .
            "SELECT " .
            "   min(h.fecha_hora) as primer_inicio\r" .
            "FROM\r" .
            "   historicos h\r" .
            "WHERE\r" .
            "   h.id_historico_motivo=1;\r";
        //echo $sql;
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
            break;
        }
        echo json_encode($datos);
        break;
}
$db->close();
