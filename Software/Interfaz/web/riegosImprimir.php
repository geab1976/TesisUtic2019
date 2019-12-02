<?php
$id_especie = $_POST["id_especie_enviar"];
$agrupado = $_POST["agrupar_enviar"];
$consulta = $_POST["consulta_enviar"];
$suministro = $_POST["suministro_enviar"];
$filtro_suministro = ">=";
if (strcmp($suministro, "1") === 0) {
   $filtro_suministro = ">";
}
if (strcmp($suministro, "2") === 0) {
   $filtro_suministro = "=";
}
if (strcmp($suministro, "3") === 0) {
   $filtro_suministro = ">=";
}
//echo $consulta;
$filtro_agrupacion = "";
$filtro_tipo = "";
if (strcmp($agrupado, "1") === 0) {
   $filtro_tipo = "D&iacute;a/Mes/A&ntilde;o";
   $filtro_agrupacion = "%Y %m %d";
}
if (strcmp($agrupado, "2") === 0) {
   $filtro_tipo = "Mes/A&ntilde;o";
   $filtro_agrupacion = "%Y %m";
}
if (strcmp($agrupado, "3") === 0) {
   $filtro_tipo = "A&ntilde;o";
   $filtro_agrupacion = "%Y";
}
if (strcmp($agrupado, "4") === 0) {
   $filtro_tipo = "Sin Agrupar";
}
$fecha_desde = $_POST["fecha_desde_enviar"];
$fecha_hasta = $_POST["fecha_hasta_enviar"];
$fecha_desde2 = $fecha_desde;
$fecha_hasta2 = $fecha_hasta;
if (strcmp($consulta, "2") === 0) {
   header("Pragma: public");
   header("Expires: 0");
   $filename = "Consulta_Riego.xls";
   if (stristr($_SERVER['HTTP_USER_AGENT'], 'ipad') or stristr($_SERVER['HTTP_USER_AGENT'], 'iphone') or stristr($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
      header("Content-Type: application/octet-stream");
   } else {
      header('Content-Type: application/vnd.ms-excel');
   }
   header("Content-Disposition: attachment; filename=$filename");
   header("Pragma: no-cache");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
} else {
   header('Content-Type: text/html');
}

//Parámetros enviados


//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();

$fecha1 = explode("-", $fecha_desde);
$fecha_desde2 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];
$fecha1 = explode("-", $fecha_hasta);
$fecha_hasta2 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];


//CONFIGURACION ACTUAL
$sql = "" .
   "SELECT " .
   "   c.descripcion, " .
   "   strftime('%H:%M:%S',c.riego_inicio)||' '||strftime('%H:%M:%S',c.riego_fin) as horario, " .
   "   e.riego_mililitros*c.maceta_cantidad AS necesidad, " .
   "   e.riego_frecuencia AS frecuencia, " .
   "   c.maceta_volumen AS volumen, " .
   "   c.maceta_cantidad AS cantidad, " .
   "   c.gotero_caudal AS gotero, " .
   "   c.riego_minutos_activo AS activo, " .
   "   c.riego_minutos_espera AS espera " .
   "FROM " .
   "   configuraciones c " .
   "   LEFT JOIN especies e ON e.id_especie=c.id_especie " .
   "WHERE " .
   "   c.configuracion_activar=1 AND " .
   "   c.id_especie=" . $id_especie . ";";
//echo $sql;
$results = $db->query($sql);
$tbody0 = "";
if ($row = $results->fetchArray(SQLITE3_ASSOC)) {
   $tbody0 = "<tr>" .
      "<td style='text-align:center'>" . $row["descripcion"] . "</td>" .
      "<td style='text-align:center'>" . $row["horario"] . "</td>" .
      "<td style='text-align:center'>" . $row["volumen"] . "</td>" .
      "<td style='text-align:center'>" . $row["cantidad"] . "</td>" .
      "<td style='text-align:center'>" . $row["necesidad"] . "</td>" .
      "<td style='text-align:center'>" . $row["frecuencia"] . "</td>" .
      "<td style='text-align:center'>" . $row["gotero"] . "</td>" .
      "<td style='text-align:center'>" . $row["activo"] . "</td>" .
      "<td style='text-align:center'>" . $row["espera"] . "</td>" .
      "</tr>";
}

//RESUMEN
$sql = "" .
   "SELECT " .
   "   er.id_especie," .
   "   e.nombre||' - '||e.descripcion||'<br>Agua Requerida por maceta/d&iacute;a: '||e.riego_mililitros||' ml' AS especie," .
   "   min(strftime('%d/%m/%Y %H:%M:%S', er.fecha_hora_inicio)) AS inicio," .
   "   max(strftime('%d/%m/%Y %H:%M:%S', er.fecha_hora_fin)) AS fin," .
   "   count(id_especie_riego) as riegos," .
   "   time(sum(er.total_duracion),'unixepoch') as duracion," .
   "   sum(er.total_agua_suministrada*er.cantidad_maceta) as agua," .
   "   sum(round(COALESCE(ta.tarifa,0)*er.total_agua_suministrada*er.cantidad_maceta,0)) as importe," .
   "   min(er.hs_inicio) AS hs_min," .
   "   max(er.hs_fin) AS hs_max," .
   "   min(er.ta_inicio) AS ta_min," .
   "   max(er.ta_fin) AS ta_max," .
   "   min(er.ls_inicio) AS ls_min," .
   "   max(er.ls_fin) AS ls_max," .
   "   CASE WHEN sum(er.lluvia_detectada)>0 THEN 'SI' ELSE 'NO' END AS lluvia " .
   "FROM " .
   "   especies_riegos er " .
   "   LEFT JOIN especies e ON e.id_especie=er.id_especie " .
   "   LEFT JOIN ( " .
   "     SELECT " .
   "        fecha_inicio, " .
   "        fecha_fin, " .
   "        tarifa*0.000001 as tarifa " .
   "     FROM " .
   "        tarifas_agua " .
   "   ) ta ON date(er.fecha_hora_inicio)>= date(ta.fecha_inicio) and date(er.fecha_hora_inicio) <= date(ta.fecha_fin) " .
   "WHERE " .
   "   date(er.fecha_hora_inicio)>=date('" . $fecha_desde . "') AND " .
   "   date(er.fecha_hora_actualizacion)<=date('" . $fecha_hasta . "') AND " .
   "   er.id_especie=" . $id_especie . " AND " .
   "   er.total_agua_suministrada" . $filtro_suministro . "0 ";
"GROUP BY " .
   "   er.id_especie;";
//echo $sql;
$results = $db->query($sql);
$tbody = "";
$especie = "";
if ($row = $results->fetchArray(SQLITE3_ASSOC)) {
   $especie = $row["especie"];
   $tbody = "<tr>" .
      "<td style='text-align:center'>" . $row["inicio"] . "</td>" .
      "<td style='text-align:center'>" . $row["fin"] . "</td>" .
      "<td style='text-align:center'>" . $row["riegos"] . "</td>" .
      "<td style='text-align:center'>" . $row["duracion"] . "</td>" .
      "<td style='text-align:center'>" . $row["agua"] . "</td>" .
      "<td style='text-align:center'>" . $row["importe"] . "</td>" .
      "<td style='text-align:center'>" . $row["hs_min"] . "</td>" .
      "<td style='text-align:center'>" . $row["hs_max"] . "</td>" .
      "<td style='text-align:center'>" . $row["ta_min"] . "</td>" .
      "<td style='text-align:center'>" . $row["ta_max"] . "</td>" .
      "<td style='text-align:center'>" . $row["ls_min"] . "</td>" .
      "<td style='text-align:center'>" . $row["ls_max"] . "</td>" .
      "<td style='text-align:center'>" . $row["lluvia"] . "</td>" .
      "</tr>";
}

//DETALLE
if (strcmp($agrupado, "4") === 0) {
   $sql = "" .
      "SELECT " .
      "   strftime('%d/%m/%Y %H:%M:%S', er.fecha_hora_inicio) AS inicio, " .
      "   strftime('%d/%m/%Y %H:%M:%S', er.fecha_hora_fin) AS fin, " .
      "   time(er.total_duracion,'unixepoch') as duracion, " .
      "   er.total_agua_suministrada*er.cantidad_maceta as agua, " .
      "   round(COALESCE(ta.tarifa,0)*er.total_agua_suministrada*er.cantidad_maceta,0) as importe, " .
      "   er.hs_inicio AS hsi, " .
      "   er.hs_fin AS hsf, " .
      "   er.ta_inicio AS tai, " .
      "   er.ta_fin AS taf, " .
      "   er.ls_inicio AS lsi, " .
      "   er.ls_fin AS lsf, " .
      "   CASE WHEN er.lluvia_detectada=1 THEN 'SI' ELSE 'NO' END AS lluvia " .
      "FROM " .
      "   especies_riegos er " .
      "   LEFT JOIN especies e ON e.id_especie=er.id_especie " .
      "   LEFT JOIN ( " .
      "     SELECT " .
      "        fecha_inicio, " .
      "        fecha_fin, " .
      "        tarifa*0.000001 as tarifa " .
      "     FROM " .
      "        tarifas_agua " .
      "   ) ta ON date(er.fecha_hora_inicio)>= date(ta.fecha_inicio) and date(er.fecha_hora_inicio) <= date(ta.fecha_fin) " .
      "WHERE " .
      "   date(er.fecha_hora_inicio)>=date('" . $fecha_desde . "') AND" .
      "   date(er.fecha_hora_actualizacion)<=date('" . $fecha_hasta . "') AND " .
      "   er.id_especie=" . $id_especie . " AND " .
      "   er.total_agua_suministrada" . $filtro_suministro . "0 ";
} else {
   $sql = "" .
      "SELECT " .
      "  er.id_especie, " .
      "  min(strftime('%d/%m/%Y %H:%M:%S', er.fecha_hora_inicio)) AS inicio, " .
      "  max(strftime('%d/%m/%Y %H:%M:%S', er.fecha_hora_fin)) AS fin, " .
      "  count(id_especie_riego) as riegos, " .
      "  time(sum(er.total_duracion),'unixepoch') as duracion, " .
      "  sum(er.total_agua_suministrada*er.cantidad_maceta) as agua, " .
      "  sum(round(COALESCE(ta.tarifa,0)*er.total_agua_suministrada*er.cantidad_maceta,0)) as importe, " .
      "  min(er.hs_inicio) AS hsi, " .
      "  max(er.hs_fin) AS hsf, " .
      "  min(er.ta_inicio) AS tai, " .
      "  max(er.ta_fin) AS taf, " .
      "  min(er.ls_inicio) AS lsi, " .
      "  max(er.ls_fin) AS lsf, " .
      "  CASE WHEN sum(er.lluvia_detectada)>0 THEN 'SI' ELSE 'NO' END AS lluvia " .
      "FROM " .
      "  especies_riegos er " .
      "  LEFT JOIN especies e ON e.id_especie=er.id_especie " .
      "  LEFT JOIN ( " .
      "    SELECT " .
      "       fecha_inicio, " .
      "       fecha_fin, " .
      "       tarifa*0.000001 as tarifa " .
      "    FROM " .
      "       tarifas_agua " .
      "  ) ta ON date(er.fecha_hora_inicio)>= date(ta.fecha_inicio) and date(er.fecha_hora_inicio) <= date(ta.fecha_fin) " .
      "WHERE " .
      "  date(er.fecha_hora_inicio)>=date('" . $fecha_desde . "') AND" .
      "  date(er.fecha_hora_actualizacion)<=date('" . $fecha_hasta . "') AND " .
      "  er.id_especie=" . $id_especie . " AND " .
      "  er.total_agua_suministrada" . $filtro_suministro . "0 " .
      "GROUP BY " .
      "  er.id_especie," .
      "  strftime('" . $filtro_agrupacion . "',er.fecha_hora_inicio) ";
}

//echo $sql;
$results = $db->query($sql);
$tbody2 = "";
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
   $tbody2 = $tbody2 .
      "<tr>" .
      "<td style='text-align:center'>" . $row["inicio"] . "</td>" .
      "<td style='text-align:center'>" . $row["fin"] . "</td>" . (strcmp($agrupado, "4") !== 0 ? "<td style='text-align:center'>" . $row["riegos"] . "</td>" : "") .
      "<td style='text-align:center'>" . $row["duracion"] . "</td>" .
      "<td style='text-align:center'>" . $row["agua"] . "</td>" .
      "<td style='text-align:center'>" . $row["importe"] . "</td>" .
      "<td style='text-align:center'>" . $row["hsi"] . "</td>" .
      "<td style='text-align:center'>" . $row["hsf"] . "</td>" .
      "<td style='text-align:center'>" . $row["tai"] . "</td>" .
      "<td style='text-align:center'>" . $row["taf"] . "</td>" .
      "<td style='text-align:center'>" . $row["lsi"] . "</td>" .
      "<td style='text-align:center'>" . $row["lsf"] . "</td>" .
      "<td style='text-align:center'>" . $row["lluvia"] . "</td>" .
      "</tr>";
}
//echo "Id_especie: " . $id_especie . " | fecha_desde:" . $fecha_desde . " | fecha_hasta:" . $fecha_hasta;

?>
<style type="text/css">
   tr:nth-child(even) {
      background-color: whitesmoke;
   }

   tr:nth-child(odd) {
      background-color: lightsteelblue;
   }

   td,
   th {
      padding: 2px 2px 2px 2px;
   }
</style>
<center>
   <!--img src="images/riego_sol.png" height="30" width="19"-->
   <span style="color:red;font-size: 20pt; margin-top: 0px;text-shadow: white 1px 1px 0, blue 2px 2px 0,white 0.1em 0.1em 0.2em;">
      <b>SistRiego</b>
   </span><br>
   <span style="font-size: 16pt; margin-top: 0px;color: gold;text-shadow: black 0.1em 0.1em 0.1em;">
      <b>Sistema de Control Autom&aacute;tico Programable
   </span><br>
   <span style="font-size: 10pt; margin-top: 0px;color: black;">para el Riego por Goteo de un Huerto
      Urbano en Macetas</b>
   </span>
   <hr style="color: #0056b2;width=75%" width="710px" size="1" />
   <h1>Consulta Generada</h1>
   <h2>Cultivo: <?php echo $especie; ?></h2>
   <h2>Fecha Desde: <?php echo $fecha_desde2; ?></h2>
   <h2>Fecha Hasta: <?php echo $fecha_hasta2; ?></h2>
   <hr style="color: #0056b2;width=75%" width="710px" size="1" />
   <h3>Configuraci&oacute;n Actual del Dispositivo</h3>
   <table border="0" cellspacing="1">
      <thead>
         <tr>
            <th rowspan="2" style="background-color: lightcoral;width: 100px;">Descripcion</th>
            <th rowspan="2" style="background-color: lightcoral;width: 100px;">Horario Riego</th>
            <th colspan="2" style="background-color: lightcoral;width: 120px;">Maceta<br></th>
            <th rowspan="2" style="background-color: lightcoral;width: 100px;">Agua<br>Requerida<br>(ml)</th>
            <th rowspan="2" style="background-color: lightcoral;width: 100px;">Frecuencia<br>Riego</th>
            <th rowspan="2" style="background-color: lightcoral;width: 90px;">Gotero<br>Caudal<br>(ml/h)</th>
            <th rowspan="2" style="background-color: lightcoral;width: 90px;">Activo<br>(minutos)</th>
            <th rowspan="2" style="background-color: lightcoral;width: 90px;">Espera<br>(minutos)</th>
         </tr>
         <tr>
            <th style="background-color: lightcoral;width: 60px;">Volumen<br>(ml)</th>
            <th style="background-color: lightcoral;width: 60px;">Cantidad<br>(unidad)</th>
         </tr>
      </thead>
      <tbody>
         <?php echo $tbody0; ?>
      </tbody>
   </table>
   <h3>Resumen del Riego</h3>
   <table border="0" cellspacing="1">
      <thead>
         <tr>
            <th rowspan="2" style="background-color: lemonchiffon;width: 90px;">Inicio</th>
            <th rowspan="2" style="background-color: lemonchiffon;width: 90px;">Fin</th>
            <th rowspan="2" style="background-color: lemonchiffon;width: 50px;">Ciclos</th>
            <th rowspan="2" style="background-color: lemonchiffon;width: 50px;">Duraci&oacute;n</th>
            <th rowspan="2" style="background-color: lemonchiffon;width: 50px;">Agua<br>(ml)</th>
            <th rowspan="2" style="background-color: lemonchiffon;width: 50px;">Importe<br>(Gs)</th>
            <th colspan="2" style="background-color: lemonchiffon;width: 80px;">Humedad<br>Suelo<br>(%)</th>
            <th colspan="2" style="background-color: lemonchiffon;width: 80px;">Temperatura<br>Ambiente<br>(&#176;C)</th>
            <th colspan="2" style="background-color: lemonchiffon;width: 80px;">Luz<br>Ambiente<br>(%)</th>
            <th rowspan="2" style="background-color: lemonchiffon;width: 80px;">Lluvia</th>
         </tr>
         <tr>
            <th style="background-color: lemonchiffon;width: 40px;">min</th>
            <th style="background-color: lemonchiffon;width: 40px;">max</th>
            <th style="background-color: lemonchiffon;width: 40px;">min</th>
            <th style="background-color: lemonchiffon;width: 40px;">max</th>
            <th style="background-color: lemonchiffon;width: 40px;">min</th>
            <th style="background-color: lemonchiffon;width: 40px;">max</th>
         </tr>
      </thead>
      <tbody>
         <?php echo $tbody; ?>
      </tbody>
   </table>
   <h3>Detalle del Riego(<?php echo $filtro_tipo; ?>)</h3>
   <table border="1" cellspacing="0">
      <thead>
         <tr>
            <th rowspan="2" style="background-color: lightgreen;width: 90px;">Inicio</th>
            <th rowspan="2" style="background-color: lightgreen;width: 90px;">Fin</th>
            <?php if (strcmp($agrupado, "4") !== 0) {
               echo "<th rowspan='2' style='background-color: lightgreen;width: 50px;'>Ciclos</th>";
            } ?>
            <th rowspan="2" style="background-color: lightgreen;width: 50px;">Duraci&oacute;n</th>
            <th rowspan="2" style="background-color: lightgreen;width: 50px;">Agua<br>(ml)</th>
            <th rowspan="2" style="background-color: lightgreen;width: 50px;">Importe<br>(Gs)</th>
            <th colspan="2" style="background-color: lightgreen;width: 80px;">Humedad<br>Suelo<br>(%)</th>
            <th colspan="2" style="background-color: lightgreen;width: 80px;">Temperatura<br>Ambiente<br>(&#176;C)</th>
            <th colspan="2" style="background-color: lightgreen;width: 80px;">Luz<br>Ambiente<br>(%)</th>
            <th rowspan="2" style="background-color: lightgreen;width: 80px;">Lluvia</th>
         </tr>
         <tr>
            <th style="background-color: lightgreen;width: 40px;">Inicio</th>
            <th style="background-color: lightgreen;width: 40px;">Fin</th>
            <th style="background-color: lightgreen;width: 40px;">Inicio</th>
            <th style="background-color: lightgreen;width: 40px;">Fin</th>
            <th style="background-color: lightgreen;width: 40px;">Inicio</th>
            <th style="background-color: lightgreen;width: 40px;">Fin</th>
         </tr>
      </thead>
      <tbody>
         <?php
            echo $tbody2;
         ?>
      </tbody>
   </table>
   <hr style="color: #0056b2;width=75%" width="710px" size="1" />
</center>