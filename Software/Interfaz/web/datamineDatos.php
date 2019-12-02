<?php
session_start();
$lifetime = 1800;
setcookie(session_name(), session_id(), time() + $lifetime, "/");
//Parámetros enviados
$opcion = $_POST["opcion"];
$id_especie = $_POST["id_especie"];
$fecha_desde = $_POST["fecha_desde"];
$fecha_hasta = $_POST["fecha_hasta"];

/*$fecha1 = explode("/", $fecha_desde);
$fecha_desde = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
$fecha1 = explode("/", $fecha_hasta);
$fecha_hasta = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];*/

//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$column = "";
$where = "";
$group = "";
$datos = array();
$ban = 0;

//Selector de casos
switch ($opcion) {
    case "1":
        $column = "" .
            //"--Diario\r" .
            "   strftime('%d/%m/%Y',er.fecha_hora_inicio) AS eje_x,\r";
        $where = "" .
            //"--Diario\r" .
            "   strftime('%Y/%m/%d',er.fecha_hora_inicio)>=strftime('%Y/%m/%d',datetime('" . $fecha_desde . "')) AND\r" .
            "   strftime('%Y/%m/%d',er.fecha_hora_inicio)<=strftime('%Y/%m/%d',datetime('" . $fecha_hasta . "'))\r";
        $group = "" .
            //"--Diario\r" .
            "   strftime('%d/%m/%Y',er.fecha_hora_inicio)\r";
        $ban = 1;
        break;
    case "2":
        $column = "" .
            //"  --Semanal\r" .
            "  strftime('%W %Y',er.fecha_hora_inicio) AS eje_x,\r";
        $where = "" .
            //"--Semanal\r" .
            "   strftime('%W %Y',er.fecha_hora_inicio)>=strftime('%W %Y','" . $fecha_desde . "') AND\r" .
            "   strftime('%W %Y',er.fecha_hora_inicio)<=strftime('%W %Y','" . $fecha_hasta . "')\r";
        $group = "" .
            //"--Semanal\r" .
            "   strftime('%W %Y',er.fecha_hora_inicio)\r";
        $ban = 1;
        break;
    case "3":
        $column = "" .
            //"--Mensual\r" .
            "   strftime('%m/%Y',er.fecha_hora_inicio) AS eje_x,\r";
        $where = "" .
            //"--Mensual\r" .
            "   strftime('%m/%Y',er.fecha_hora_inicio)>=strftime('%m/%Y','" . $fecha_desde . "') AND\r" .
            "   strftime('%m/%Y',er.fecha_hora_inicio)<=strftime('%m/%Y','" . $fecha_hasta . "')\r";
        $group = "" .
            //"--Mensual\r" .
            "   strftime('%m/%Y',er.fecha_hora_inicio)\r";
        $ban = 1;
        break;
    case "4":
        $column = "" .
            //"--Anual\r" .
            "   strftime('%Y',er.fecha_hora_inicio) AS eje_x,\r";
        $where = "" .
            //"--Anual\r" .
            "   strftime('%Y',er.fecha_hora_inicio)>=strftime('%Y','" . $fecha_desde . "') AND\r" .
            "   strftime('%Y',er.fecha_hora_inicio)<=strftime('%Y','" . $fecha_hasta . "')\r";
        $group = "" .
            //"--Anual\r" .
            "   strftime('%Y',er.fecha_hora_inicio)\r";
        $ban = 1;
        break;

    case "5": //Obtener Datos de especies (FK)
        $sql = "" .
            "SELECT\r" .
            "   e.id_especie as id_especie,\r" .
            "   e.nombre||' - '||e.descripcion as especie\r" .
            "FROM\r" .
            "   especies e\r";
        //echo $sql;
        $results = $db->query($sql);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }
        echo json_encode($datos);
        break;
}

if ($ban == 1) {
    $sql = "" .
        "SELECT\r" .
        "   er.id_especie,\r" .
        " " . $column .
        "   sum(total_agua_suministrada*cantidad_maceta*0.001) as agua,\r" .    
        "   sum(round(COALESCE(ta.tarifa,0)*er.total_agua_suministrada*er.cantidad_maceta,0)) as importe, " .
        "   count(er.id_especie) as veces,\r" .

        "   round(avg(er.hs_inicio),1) as hsi,\r" .
        "   min(er.hs_inicio) as hsi_min,\r" .
        "   max(er.hs_inicio) as hsi_max,\r" .  

        "   round(avg(er.hs_fin),1) as hsf,\r" .
        "   min(er.hs_fin) as hsf_min,\r" .
        "   max(er.hs_fin) as hsf_max,\r" .

        "   round(avg(er.ta_inicio),1) as tai,\r" .
        "   min(er.ta_inicio) as tai_min,\r" .
        "   max(er.ta_inicio) as tai_max,\r" .

        "   round(avg(er.ta_fin),1) as taf,\r" .
        "   min(er.ta_fin) as taf_min,\r" .
        "   max(er.ta_fin) as taf_max,\r" .

        "   round(avg(er.ls_inicio),1) as lsi,\r" .
        "   min(er.ls_inicio) as lsi_min,\r" .
        "   max(er.ls_inicio) as lsi_max,\r" .

        "   round(avg(er.ls_fin),1) as lsf,\r" .
        "   min(er.ls_fin) as lsf_min,\r" .
        "   max(er.ls_fin) as lsf_max,\r" .

        "   sum(er.lluvia_detectada) as ll\r" .
        "FROM\r" .
        "   especies_riegos er\r" .
        "   LEFT JOIN ( " .
        "     SELECT " .
        "        fecha_inicio, " .
        "        fecha_fin, " .
        "        tarifa*0.000001 as tarifa " .
        "     FROM " .
        "        tarifas_agua " .
        "   ) ta ON date(er.fecha_hora_inicio)>= date(ta.fecha_inicio) and date(er.fecha_hora_inicio) <= date(ta.fecha_fin) " .
        "WHERE\r" .
        "   er.id_especie=" . $id_especie . " AND \r" .
        "   er.total_agua_suministrada>0 AND \r" .
        "   " . $where .
        "GROUP BY\r" .
        "   er.id_especie,\r" .
        " " . $group."\r".
        "ORDER BY\r".
        "   er.fecha_hora_inicio\r";
    //echo $sql;
    $results = $db->query($sql);
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $datos[] = $row;
    }
    echo json_encode($datos);
}

$db->close();
