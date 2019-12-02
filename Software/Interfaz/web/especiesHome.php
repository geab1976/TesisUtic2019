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
                <span
                    style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Especies</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Mantener Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-content">
            <center>
                <table id="tblDatos" data-role="table" data-mode="columntoggle" class="ui-responsive ui-shadow">
                    <thead>
                        <tr>
                            <th style="text-align:center">Item</th>
                            <th style="text-align:center">Especie > Nombre | Descripción </th>
                            <th style="text-align:center" data-priority="1">Riego > Cant. | Frec.</th>
                            <th style="text-align:center" data-priority="2">Sensores > Temp. | Hm. | Ilum.</th>
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
                <span
                    style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Especies</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Mantener Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos del Cultivo</b></legend>
                <div data-role="fieldcontain">
                    <label for="nombre" ">Nombre:</label>
                    <input type=" text" name="nombre" id="nombre" value="" placeholder="Nombre del cultivo"
                        data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="descripcion" ">Descripción:</label>
                    <input type=" text" name="descripcion" id="descripcion" value=""
                        placeholder="Familia de la especie" data-clear-btn="true" required />
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos del Riego</b></legend>
                <div data-role="fieldcontain">
                    <label for="riego_mililitros">
                        <a href="#ayudaNecesidad" data-rel="popup" data-transition="pop" class="my-tooltip-btn"
                            title="Ayuda adicional">Necesidad</a>:
                    </label>
                    <input type="range" name="riego_mililitros" id="riego_mililitros" step="100" min="500" max="10000"
                        value="500" data-highlight="true">
                    <div data-role="popup" id="ayudaNecesidad" class="ui-content"
                        style="max-width:350px;background-color: #d6eaf8;">
                        Seleccione la cantidad de agua por día del cultivo en mililitros.<br>
                        Ejemplo:<br>
                        1 litro (l) = 1.000 mililitros (ml)
                    </div>
                </div>

                <div data-role="fieldcontain">
                    <label for="riego_frecuencia">
                        <a href="#ayudaFrecuencia" data-rel="popup" data-transition="pop" class="my-tooltip-btn"
                            title="Ayuda adicional">Frecuencia</a>:
                    </label>
                    <input type="range" name="riego_frecuencia" id="riego_frecuencia" step="1" min="1" max="60"
                        value="3" data-highlight="true">
                    <div data-role="popup" id="ayudaFrecuencia" class="ui-content"
                        style="max-width:350px;background-color: #d6eaf8;">
                        Seleccione la veces en el día que se va ha activar el riego.
                    </div>
                </div>

            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Mínimos y Máximos<br>para los Sensores</b></legend>
                <div class="ui-field-contain">
                    <div data-role="rangeslider" data-mini="false">
                        <label for="ta_min">Temperatura:</label>
                        <input type="range" name="ta_min" id="ta_min" min="1" max="40" value="4">
                        <label for="ta_max">Temperatura:</label>
                        <input type="range" name="ta_max" id="ta_max" min="1" max="40" value="35">
                    </div>
                </div>

                <div class="ui-field-contain">
                    <div data-role="rangeslider" data-mini="false">
                        <label for="hs_min">Humedad Suelo:</label>
                        <input type="range" name="hs_min" id="hs_min" min="1" max="100" value="20">
                        <label for="hs_max">Humedad Suelo:</label>
                        <input type="range" name="hs_max" id="hs_max" min="1" max="100" value="70">
                    </div>
                </div>

                <div class="ui-field-contain">
                    <div data-role="rangeslider" data-mini="false">
                        <label for="ls_min">Iluminación:</label>
                        <input type="range" name="ls_min" id="ls_min" min="0" max="100" value="50">
                        <label for="ls_max">Iluminación:</label>
                        <input type="range" name="ls_max" id="ls_max" min="0" max="100" value="90">
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
    <script type="text/javascript" src="./especiesControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>