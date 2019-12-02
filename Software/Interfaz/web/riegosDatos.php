<?php
//Parámetros enviados
$opcion = $_POST["opcion"];
//Conexión a la base de datos SQLite3
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$sql = "";
$datos = array();
//Selector de casos
switch ($opcion) {
    case "1": //Obtener Datos de especies (FK)
    $sql = "" .
        "SELECT " .
        "   id_especie," .
        "   nombre||' - '||descripcion as especie " .
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