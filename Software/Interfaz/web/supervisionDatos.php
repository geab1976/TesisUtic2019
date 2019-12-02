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
    case "1": //Consultar todos los Datos 
        $sql = "" .
            "SELECT " .
            "   e.id_especie," .
            "   '<b>'||e.nombre||'</b><br>'||e.descripcion||'<br>'||(e.riego_mililitros*0.001*c.maceta_cantidad)||' ls/día' AS especie," .
            "   '<b>Riego</b><br><b>'||c.riego_inicio||' | '||c.riego_fin AS horario," .
            "   c.webcam_activar AS webcam," .
            "   c.webcam_tamanio_imagen AS resolucion_imagen," .
            "   c.webcam_tamanio_video AS resolucion_video," .
            "   c.webcam_fps_video AS fps," .
            "   e.riego_mililitros," .
            "   e.riego_frecuencia," .
            "   e.ta_min," .
            "   e.ta_max," .
            "   e.hs_min," .
            "   e.hs_max," .
            "   e.ls_min," .
            "   e.ls_max " .
            "FROM " .
            "   especies e " .
            "   LEFT JOIN configuraciones c ON c.id_especie=e.id_especie ".
            "WHERE ".
            "   c.configuracion_activar=1;";
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        echo json_encode($datos);
        break;
    case "2":
        //SUMINISTRADO %
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        /*$sql = "" .
            "SELECT " .
            "   sum(er.total_agua_suministrada*0.1) as volumen," .
            "   ROUND((sum(er.total_agua_suministrada*0.1)/max(e.riego_mililitros*0.1))*100,2) as porcentaje " .
            "FROM " .
            "   especies_riegos er " .
            "   LEFT JOIN especies e ON e.id_especie=er.id_especie " .
            "   INNER JOIN configuraciones c ON c.id_especie=e.id_especie AND configuracion_activar=1 " .
            "WHERE " .
            "   date(fecha_hora_inicio)=date('" . $fecha_hora . "') " .
            "GROUP BY " .
            "   er.id_especie;";*/
        $sql = "" .
            "SELECT " .
            "    COALESCE(strftime('%H:%M:%S',er.fecha_hora_inicio)||' - '||strftime('%H:%M:%S',er.fecha_hora_fin),'---') as nombre, " .
            "    COALESCE(round(er.total_agua_suministrada*er.cantidad_maceta*0.001,1),0) as valor, " .
            "    COALESCE(era.volumen*0.001,0) as volumen, " .
            "    COALESCE(era.porcentaje,0.0) as porcentaje, " .
            "   '<b>Riego</b><br><b>'||c.riego_inicio||' | '||c.riego_fin||'</b>' AS horario," .
            "    CASE WHEN time('" . $fecha_hora . "')>=c.riego_inicio AND time('" . $fecha_hora . "')<=c.riego_fin THEN '1' ELSE '0' END as habilitado" .
            " FROM " .
            "    configuraciones c " .
            "    LEFT JOIN especies_riegos er ON er.id_especie=c.id_especie AND ".
            "    date(er.fecha_hora_inicio)=date('" . $fecha_hora . "') AND " .
            "    er.total_agua_suministrada>0 ".
            "    LEFT JOIN ( " .
            "         SELECT " .
            "            er.id_especie, " .
            "            sum(er.total_agua_suministrada*er.cantidad_maceta) as volumen, " .
            "            ROUND((sum(er.total_agua_suministrada*0.1)/max(e.riego_mililitros*0.1))*100,2) as porcentaje " .
            "         FROM " .
            "            especies_riegos er " .
            "            LEFT JOIN especies e ON e.id_especie=er.id_especie " .
            "            INNER JOIN configuraciones c ON c.id_especie=e.id_especie AND c.configuracion_activar=1 AND c.dispositivo_activar=1 " .
            "         WHERE " .
            "            date(fecha_hora_inicio)=date('" . $fecha_hora . "') AND" .
            "            total_agua_suministrada>0 ".
            "         GROUP BY ".
            "            er.id_especie " .
            "    ) era ON era.id_especie=er.id_especie " .
            " WHERE " .
            "    c.configuracion_activar=1 AND c.dispositivo_activar=1;" ;

        //echo $sql;
        $ban=0;
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
            $json = json_encode($datos);
            $ban=1;
        }
        if($ban==0){
            $json = "[{\"nombre\":\"Desactivado\",\"valor\":0,\"volumen\":0,\"porcentaje\":0}]";
        }
        echo $json;
        break;
    case "3":
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'9'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ")');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
            echo 'Registrado';
        } else {
            $db->exec('ROLLBACK;');
            echo "Error: " . $sql;
        }
        break;
    case "4":
        session_start();
        $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
        $db->exec('BEGIN;');
        $sql = "" .
            "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
            "datetime('" . $fecha_hora . "')," .
            "'10'," .
            "'Usuario: " . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . " (" . $_SESSION["usuario"] . ")');";
        $query = $db->exec($sql);
        if ($query) {
            $db->exec('COMMIT;');
            echo 'Registrado';
        } else {
            $db->exec('ROLLBACK;');
            echo "Error: " . $sql;
        }
        break;
}
$db->close();