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
                <span
                    style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Fecha/Hora
                    del Dispositivo</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Actualizar Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Fecha y Hora Actual del Dispositivo</b></legend>
                <div data-role="fieldcontain" class="form-group">
                    <label class="control-label" for="fecha">Fecha:</label>
                    <input class="form-control" name="fecha" type="date" id="fecha" data-mini="true">
                </div>

                <div data-role="fieldcontain">
                    <label for="hora">Hora:</label>
                    <input type="time" name="hora" id="hora" value="" placeholder="--:--:--" data-clear-btn="false"
                        required>
                </div>
            </fieldset>

            <div data-role="navbar" class="ui-responsive ui-shadow" id="divNavFormM"
                style="margin: 2px 50px 50px 50px;">
                <ul>
                    <li><a href="#divFormulario" data-icon="edit" data-transition="flip" id="lnActualizar"
                            data-theme="a" title="ACTUALIZAR"></a></li>
                    <li><a href="#divFormulario" data-icon="forward" data-transition="flip" id="lnCancelar"
                            data-theme="a" title="REFRESCAR DATOS"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./fechaHoraControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>