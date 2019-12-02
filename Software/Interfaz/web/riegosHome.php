<?php
session_start();
if (!empty($_SESSION['usuario'])) {
    $lifetime = 1800;
    setcookie(session_name(), session_id(), time() + $lifetime, "/");
    ?>
<!DOCTYPE html>
<html class="ui-mobile">

<head>
    <?php
        include("head_html.php");
        ?>
    <link href="css/estilo_tabla.css" rel="stylesheet" />
    <script type="text/javascript">
        var id_acceso = "<?php echo $_SESSION['id'] ?>";
    </script>
</head>

<body>
    <div data-role="page" data-theme="a" id="divFormulario" style="background-color: white">
        <div data-role="header" class="sr-cuentausuario" data-theme="a">
            <center>
                <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Riegos</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Históricos de Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="background-color: none;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Complete los datos de consulta</b></legend>
                <div data-role="fieldcontain">
                    <label for="id_especie">Especie:</label>
                    <select name="id_especie" id="id_especie">
                    </select>
                </div>

                <div data-role="fieldcontain" class="form-group">
                    <label class="control-label" for="fecha_desde">Fecha Desde:</label>
                    <input class="form-control" name="fecha_desde" type="date" id="fecha_desde" data-mini="true">
                </div>
                <div data-role="fieldcontain" class="form-group">
                    <label class="control-label" for="fecha_hasta">Fecha Hasta:</label>
                    <input class="form-control" name="fecha_hasta" type="date" id="fecha_hasta" data-mini="true">
                </div>

                <div class="ui-field-contain">
                    <label for="agrupar">Agrupar por:</label>
                    <select name="agrupar" id="agrupar">
                        <option value="1">Día/Mes/Año</option>
                        <option value="2">Mes/Año</option>
                        <option value="3">Año</option>
                        <option value="4">Sin agrupar</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="suministro">Agua Suministrada:</label>
                    <select name="suministro" id="suministro">
                        <option value="1">Mayor a Cero</option>
                        <option value="2">Igual a Cero</option>
                        <option value="3">Mayor e Igual a Cero</option>
                    </select>
                </div>
            </fieldset>

            <div data-role="navbar" class="ui-responsive ui-shadow" id="divNavFormM" style="margin: 2px 50px 50px 50px;">
                <ul>
                    <li> <a onclick="enviarReporte(1);" data-icon="grid" data-transition="flip" id="idReporte" data-theme="a" title="Visualizar Reporte">Visualizar</a></li>
                    <li> <a onclick="enviarReporte(2);" data-icon="action" data-transition="flip" id="idReporte" data-theme="a" title="Exportar Reporte">Exportar</a></li>
                </ul>
            </div>
        </div>
    </div>
    <form id="frmImprimir" action="riegosImprimir.php" target="_blank" method="post">
        <input type="hidden" id="id_especie_enviar" name="id_especie_enviar">
        <input type="hidden" id="fecha_desde_enviar" name="fecha_desde_enviar">
        <input type="hidden" id="fecha_hasta_enviar" name="fecha_hasta_enviar">
        <input type="hidden" id="agrupar_enviar" name="agrupar_enviar">
        <input type="hidden" id="consulta_enviar" name="consulta_enviar">
        <input type="hidden" id="suministro_enviar" name="suministro_enviar">
    </form>
    <script type="text/javascript" src="./riegosControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>