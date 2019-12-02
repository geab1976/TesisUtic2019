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
        <div data-role="page" id="divFormulario">
            <div data-role="header" class="sr-cuentausuario" data-theme="a">
                <center>
                    <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Minería
                        de Datos</span><br>
                    <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Generador de
                        Gráficos Estadísticos</span>
                </center>
            </div>
            <div data-role="main" data-theme="a" class="ui-conten" style="margin:0px 10px 10px 10px;">
                <!--center>
                <div id="divEspera1">
                    <img src="images/cargando_4.gif" alt="Cargando..." height="128">
                </div>
                <div id="divEspera2" style="display:none">
                    <img src="images/cargando_3.gif" alt="Cargando..." height="128">
                </div>
            </center-->
                <fieldset data-role="controlgroup" style="background-color: none;border:none;padding:2px 2px 2px 2px;">
                    <div data-role="tabs" id="tabs">
                        <div data-role="navbar">
                            <ul>
                                <li><a href="#uno" data-ajax="false" id="lnUno">CONSULTA</a></li>
                                <li><a href="#dos" data-ajax="false" id="lnDos">GRÁFICO</a></li>
                            </ul>
                        </div>
                        <div id="uno" class="ui-btn-active">
                            <fieldset data-role="controlgroup" style="background-color: none;padding:5px 5px 5px 5px;">
                                <legend style="text-align:center;"><b>Complete los datos de consulta</b></legend>
                                <div data-role="fieldcontain">
                                    <div data-role="fieldcontain" class="form-group">
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
                                        <label for="accion">Agrupación:</label>
                                        <select name="accion" id="accion">
                                            <option value="1">Diaria</option>
                                            <option value="2">Semanal</option>
                                            <option value="3">Mensual</option>
                                            <option value="4">Anual</option>
                                        </select>
                                    </div>

                            </fieldset>
                        </div>
                        <div id="dos">
                            <center>
                                <div id="divEspera1" style="margin-top: 20%">
                                    <img src="images/cargando_3.gif" alt="Cargando..." height="128">
                                </div>
                            </center>
                            <div data-role="tabs" id="tabs2" style="display: none">
                                <div data-role="navbar">
                                    <ul>
                                        <li><a href="#divSuministro" data-ajax="false" id="lnAgua">AGUA - LLUVIA</a></li>
                                        <li><a href="#divHS" data-ajax="false" id="lnHumedad">HUMEDAD</a></li>
                                        <li><a href="#divTA" data-ajax="false" id="lnTemperatura">TEMPERATURA</a></li>
                                        <li><a href="#divLS" data-ajax="false" id="lnIluminacion">ILUMINACIÓN</a></li>
                                    </ul>
                                </div>
                                <div id="divSuministro">
                                    <div id="divAgua"></div>
                                    <hr>
                                    <div id="divSuministrado"></div>
                                    <hr>
                                    <div id="divLluvias"></div>
                                    <hr>
                                    <div id="divRiegos"></div>
                                    <hr>
                                    <div id="divImportes"></div>
                                </div>
                                <div id="divHS"></div>
                                <div id="divTA"></div>
                                <div id="divLS"></div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <script type="text/javascript" src="./js/highcharts.js"></script>
        <script type="text/javascript" src="./js/highcharts-more.js"></script>
        <script type="text/javascript" src="./js/exporting.js"></script>
        <script type="text/javascript" src="./datamineControl.js"></script>
    </body>

    </html>
<?php
} else {
    include('accesoRestringido.php');
}
?>