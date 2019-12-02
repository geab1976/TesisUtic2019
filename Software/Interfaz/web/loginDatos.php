<?php
$db = new SQLite3('/mnt/sda1/SistRiego/sqlite/sistriego_db.db');
assert($db);
$usuario = $_POST["usuario"];
$clave = $_POST["clave"];
$stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario=:user AND clave=:password AND activo=1;");
//$stmt = $dbh->prepare("SELECT * FROM REGISTRY where name LIKE '%?%'");
$stmt->bindParam(':user', $usuario);
$stmt->bindParam(':password', $clave);

$results = $stmt->execute();
$dato = '0';

if ($row = $results->fetchArray()) {
    $dato = '1';
    ini_set("session.cookie_lifetime", 1800);
    session_start();
    $_SESSION["id"] = $row["id_usuario"];
    $_SESSION["usuario"] = $row["usuario"];
    $_SESSION["nombres"] = $row["nombres"];
    $_SESSION["apellidos"] = $row["apellidos"];
    $_SESSION["email"] = $row["email"];
    $_SESSION["administrador"] = $row["administrador"];
    $fecha_hora = file_get_contents("http://localhost:8080/fecha_hora");
    $db->exec('BEGIN;');
    $sql = "INSERT INTO historicos (fecha_hora, id_historico_motivo, detalle) VALUES(" .
        "datetime('" . $fecha_hora . "')," .
        "'2'," .
        "'Usuario: " . $row["nombres"] . " " . $row["apellidos"] . " (" . $row["usuario"] . ")');";
    $query = $db->exec($sql);
    if ($query) {
        $db->exec('COMMIT;');
    } else {
        $db->exec('ROLLBACK;');
    }
}
echo $dato;
$db->close();
