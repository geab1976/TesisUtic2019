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
    <script type="text/javascript" src="./js/jquery.number.min.js"></script>
    <script type="text/javascript">
    var id_acceso = "<?php echo $_SESSION['id'] ?>";
    </script>
</head>

<body>
    <div data-role="page" data-theme="a" id="divGrilla">
        <div data-role="header" class="sr-especies">
            <center>
                <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Tarifas
                    del Suministro de Agua</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Mantener Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-content">
            <center>
                <table id="tblDatos" data-role="table" data-mode="columntoggle" class="ui-responsive ui-shadow">
                    <thead>
                        <tr>
                            <th style="text-align:center">Item</th>
                            <th style="text-align:center">Fecha Inicio </th>
                            <th style="text-align:center" data-priority="1">Fecha Fin</th>
                            <th style="text-align:center" data-priority="2">Tarifa Gs./m3</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
                <div data-role="navbar" class="ui-responsive ui-shadow">
                    <ul>
                        <li><a href="#divFormulario" data-icon="plus" data-transition="flip" id="lnAgregar"></a></li>
                        <li><a href="#divFormulario" data-icon="edit" data-transition="flip" id="lnEditar"></a></li>
                        <li><a href="#" data-icon="delete" data-transition="flip" id="lnBorrar"></a>
                        </li>
                    </ul>
                </div>
            </center>
        </div>
    </div>
    <div data-role="page" data-theme="b" id="divFormulario">
        <div data-role="header" class="sr-especies" data-theme="a">
            <center>
                <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Tarifas
                    del Suministro de Agua</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Mantener Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos de la Tarifa</b></legend>
                <div data-role="fieldcontain">
                    <div data-role="fieldcontain" class="form-group">
                        <label class="control-label" for="fecha_inicio">Fecha Inicio:</label>
                        <input class="form-control" name="fecha_inicio" type="date" id="fecha_inicio" data-mini="true">
                    </div>

                    <div data-role="fieldcontain" class="form-group">
                        <label class="control-label" for="fecha_fin">Fecha Fin:</label>
                        <input class="form-control" name="fecha_fin" type="date" id="fecha_fin" data-mini="true" >
                    </div>

                    <div data-role="fieldcontain">
                        <label for="tarifa">Tarifa (Gs./m3 Agua):</label>
                        <input type="number" name="tarifa" id="tarifa" value="0" placeholder="0,00">
                    </div>
                </div>
            </fieldset>

            <div data-role="navbar" class="ui-responsive ui-shadow" id="divNavFormA"
                style="margin: 2px 50px 50px 50px;">
                <ul>
                    <li><a href="#divGrilla" data-icon="plus" data-transition="flip" id="lnInsertar" data-theme="a"
                            title="INSERTAR"></a>
                    </li>
                    <li><a href="#divGrilla" data-icon="forward" data-transition="flip" id="lnCancelar" data-theme="a"
                            title="CANCELAR"></a></li>
                </ul>
            </div>

            <div data-role="navbar" class="ui-responsive ui-shadow" id="divNavFormM"
                style="margin: 2px 50px 50px 50px;">
                <ul>
                    <li><a href="#" data-icon="edit" data-transition="flip" id="lnActualizar" data-theme="a"
                            title="ACTUALIZAR"></a></li>
                    <li><a href="#divGrilla" data-icon="forward" data-transition="flip" id="lnCancelar" data-theme="a"
                            title="CANCELAR"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./tarifasControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>