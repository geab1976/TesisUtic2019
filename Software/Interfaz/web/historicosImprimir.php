<?php
//Parámetros enviados
$id_historico_motivo = $_POST["id_historico_motivo_enviar"];
$accion = $_POST["accion_enviar"];
$filtro = $_POST["filtro_enviar"];
//echo $consulta;
$fecha_desde = $_POST["fecha_desde_enviar"];
$fecha_hasta = $_POST["fecha_hasta_enviar"];
$fecha_desde2 = $fecha_desde;
$fecha_hasta2 = $fecha_hasta;
if (strcmp($accion, "2") === 0) {
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

//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();

$fecha1 = explode("-", $fecha_desde);
$fecha_desde2 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];
$fecha1 = explode("-", $fecha_hasta);
$fecha_hasta2 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];

//DETALLE
$sql = "" .
   "SELECT\r" .
   "  h.id_historico,\r" .
   "  h.id_historico_motivo,\r" .
   "  hm.descripcion,\r" .
   "  h.fecha_hora,\r" .
   "  h.detalle\r" .
   "FROM\r" .
   "  historicos h\r" .
   "  LEFT JOIN historicos_motivos hm ON hm.id_historico_motivo=h.id_historico_motivo\r" .
   "WHERE\r" .
   "  date(h.fecha_hora)>=date('" . $fecha_desde . "') AND\r" .
   "  date(h.fecha_hora)<=date('" . $fecha_hasta . "') AND\r" .
   " " . ($id_historico_motivo == "0" ? "h.id_historico_motivo>0" : "h.id_historico_motivo=" . $id_historico_motivo) . " AND\r" .
   " " . (strlen(trim($filtro)) == 0 ? "h.id_historico_motivo>0" : "h.detalle LIKE '%" . $filtro . "%'") . ";\r";
//echo $sql;

$results = $db->query($sql);
$tbody = "";
$motivo = "";
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
   $motivo = $row['descripcion'];
   $tbody = $tbody .
      "<tr>" .
      "  <td style='text-align:center'>" . $row["fecha_hora"] . "</td>\r" .
      "  <td style='text-align:left'>" . $row["descripcion"] . "</td>\r" .
      "  <td style='text-align:left'>" . $row["detalle"] . "</td>\r" .
      "</tr>\r";
}
//echo $tbody;

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
    <img src="images/riego_sol.png" height="30" width="19">
    <span
        style="color:red;font-size: 20pt; margin-top: 0px;text-shadow: white 1px 1px 0, blue 2px 2px 0,white 0.1em 0.1em 0.2em;">
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
    <h2>Motivo: <?php echo ($id_historico_motivo == "0" ? "TODOS" : $motivo); ?></h2>
    <h2>Fecha Desde: <?php echo $fecha_desde2; ?></h2>
    <h2>Fecha Hasta: <?php echo $fecha_hasta2; ?></h2>
    <h2>Filtro: <?php echo (strlen(trim($filtro)) > 0 ? trim($filtro) : "---"); ?></h2>
    <hr style="color: #0056b2;width=75%" width="710px" size="1" />
    <h3>Registros de Hist&oacute;ricos del Dispositivo</h3>
    <table border="0" cellspacing="1">
        <thead>
            <tr>
                <th style="background-color: lightcoral;width: 100px;">Fecha/Hora</th>
                <th style="background-color: lightcoral;width: 250px;">Motivo<br></th>
                <th style="background-color: lightcoral;width: 360px;">Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $tbody; ?>
        </tbody>
    </table>
    <hr style="color: #0056b2;width=75%" width="710px" size="1" />
</center>