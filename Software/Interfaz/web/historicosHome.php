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
                <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Históricos</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Datos de Registros del Sistema</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="background-color: none;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Complete los datos de consulta</b></legend>
                <div data-role="fieldcontain">
                    <label for="id_historico_motivo">Motivo:</label>
                    <select name="id_historico_motivo" id="id_historico_motivo">
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

                <div data-role="fieldcontain" class="form-group">
                    <label class="control-label" for="filtro">Filtro:</label>
                    <input type="text" name="filtro" id="filtro" value="" placeholder="Ingrese palabra para filtro" data-clear-btn="true"/>
                </div>

                <div class="ui-field-contain">
                    <label for="accion">Acción:</label>
                    <select name="accion" id="accion">
                        <option value="1">Visualizar</option>
                        <option value="2">Exportar XLS</option>
                        <option value="3">Borrar según Consulta</option>
                        <option value="4">Borrar todos los registros</option>
                    </select>
                </div>
            </fieldset>

            <div data-role="navbar" class="ui-responsive ui-shadow" id="divNavFormM" style="margin: 2px 50px 50px 50px;">
                <ul>
                    <li> <a onclick="ejecutarAccion();" data-icon="grid" data-transition="flip" id="btnEjecutar" data-theme="a" title="Realizar la acción seleccionada">Realizar</a></li>
                </ul>
            </div>
        </div>
    </div>
    <form id="frmImprimir" action="historicosImprimir.php" target="_blank" method="post">
        <input type="hidden" id="id_historico_motivo_enviar" name="id_historico_motivo_enviar">
        <input type="hidden" id="fecha_desde_enviar" name="fecha_desde_enviar">
        <input type="hidden" id="fecha_hasta_enviar" name="fecha_hasta_enviar">
        <input type="hidden" id="filtro_enviar" name="filtro_enviar">
        <input type="hidden" id="accion_enviar" name="accion_enviar">
    </form>
    <script type="text/javascript" src="./historicosControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>