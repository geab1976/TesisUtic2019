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
        <script type="text/javascript" src="./js/highcharts.js"></script>
        <script type="text/javascript" src="./js/highcharts-more.js"></script>
        <script type="text/javascript" src="./js/exporting.js"></script>
        <script type="text/javascript">
            var id_acceso = "<?php echo $_SESSION['id'] ?>";
        </script>
        <link rel="stylesheet" href="./css/estilo_supervision.css">
        <style type="text/css">
            .blink1 {
                animation: blinker 3s linear infinite;
                background-color: red;
                color: white;
                font-size: 12pt;
                font-weight: normal;
                font-family: sans-serif;
                width: 100%;
            }

            .blink2 {
                animation: blinker 3s linear infinite;
                background-color: green;
                color: white;
                font-size: 12;
                font-weight: normal;
                font-family: sans-serif;
                width: 100%;
            }

            .blink3 {
                animation: blinker 3s linear infinite;
                background-color: turquoise;
                color: white;
                font-size: 12;
                font-weight: normal;
                font-family: sans-serif;
                width: 100%;
            }

            .blink4 {
                animation: blinker 3s linear infinite;
                background-color: violet;
                color: white;
                font-size: 12;
                font-weight: normal;
                font-family: sans-serif;
                width: 100%;
            }

            @keyframes blinker {
                50% {
                    opacity: 0;
                }
            }
        </style>
    </head>

    <body>
        <div data-role="page" data-theme="a" id="divFormulario" style="background-color: white;">
            <div data-role="header" class="sr-cuentausuario" data-theme="a">
                <center>
                    <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Supervisión</span><br>
                    <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Telemetría en Tiempo
                        Real</span>
                </center>
            </div>
            <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
                <fieldset data-role="controlgroup" style="background-color: none;padding:5px 5px 5px 5px;">
                    <div data-role="tabs" id="tabs" style="background: none;padding: 10px 10px 10px 10px">
                        <div data-role="navbar">
                            <ul>
                                <li><a href="#uno" data-ajax="false" id="lnUno">Básica</a></li>
                                <li><a href="#dos" data-ajax="false" id="lnDos">Extendida</a></li>
                                <li><a href="#tres" data-ajax="false" id="lnTres">Visual</a></li>
                            </ul>
                        </div>
                        <div id="uno" class="ui-btn-active" style="background: none;padding: 10px 10px 10px 10px">
                            <center>
                                <div id="divEspera1" style="background-color: white;width: 100%;height: 100%;"></div>
                                <table id='tblSensores' border="0" cellspacing="0">
                                </table>
                            </center>
                        </div>
                        <div id="dos">
                            <div id="divEspera2" style="background-color: white;width: 100%;height: 100%;"></div>
                            <div class="ui-grid-b">
                                <div id="divSensores" style="background-color: white;width: 100%">
                                    <center>
                                        <fieldset style="height:150px;width:200px;margin:auto;background-color: white;">
                                            <div id="clockdate">
                                                <div class="clockdate-wrapper">
                                                    <div id="date" style="margin-bottom: 0px"></div>
                                                    <div id="clock"></div>
                                                </div>
                                            </div>
                                            <div id="divHorario"></div>
                                            <div id="divLed" style="width: 100%;">
                                                <center>
                                                    <div class="blink1" id="spEnEspera" style="display: none;">En Espera</div>
                                                    <div class="blink2" id="spActivo" style="display: none;">Activo</div>
                                                    <div class="blink3" id="spFueraHorario" style="display: none;">Fuera de Horario</div>
                                                    <div class="blink4" id="spDesactivado" style="display: none;">Desactivado</div>
                                                </center>
                                            </div><br>
                                        </fieldset>
                                        <fieldset style="height:150px;width:200px;margin:auto;background-color: white;">
                                            <div id="lblCultivo"></div>
                                            <img alt="Electrovalvula" src="" id="imgElectrovalvula" height="40" style="margin-top: 0px">
                                            <img alt="Tiempo" src="" id="imgTiempo" height="40" style="margin-top: 0px">
                                        </fieldset>
                                        <fieldset style="height:200px;width:200px;margin:auto;background-color: white;">
                                            <div id="divVolumen"></div>
                                        </fieldset>
                                        <fieldset style="height:200px;width:200px;margin:auto;background-color: white;">
                                            <div id="divCA">CA</div>
                                        </fieldset>
                                        <fieldset style="height:200px;width:200px;margin:auto;background-color: white;">
                                            <div id="divHR">HR</div>
                                        </fieldset>
                                        <fieldset style="height:200px;width:200px;margin:auto;background-color: white;">
                                            <div id="divHS">HS</div>
                                        </fieldset>
                                        <fieldset style="height:200px;width:200px;margin:auto;background-color: white;">
                                            <div id="divTA">TA</div>
                                        </fieldset>
                                        <fieldset style="height:200px;width:200px;margin:auto;background-color: white;">
                                            <div id="divLS">LS</div>
                                        </fieldset>
                                    </center>
                                </div>
                            </div><!-- /grid-c -->
                        </div>
                        <div id="tres">
                            <center>
                                <fieldset data-role="controlgroup" data-type="horizontal" data-theme="a">
                                    <a href="#popupInfo1" data-rel="popup" data-transition="pop" data-position-to="window" name="btnWebcam" class="my-tooltip-btn ui-btn ui-alt-icon ui-nodisc-icon ui-btn-inline ui-icon-info ui-btn-icon-notext" title="Info" style="width: 5px">Info</a>
                                    <div data-role="popup" id="popupInfo1" data-theme="b" style="max-width:350px;">
                                        <label id="lblFotoDatos" style="text-align: center;"></label>
                                    </div>
                                    <button id="btnImagen" name="btnWebcam" class="ui-btn ui-btn-inline" title="" style="margin-right: 5px;width: 60px" disabled>
                                        FOTO
                                    </button>

                                    <button id="btnVideo" name="btnWebcam" class="ui-btn ui-btn-inline" title="" style="width: 60px" disabled>
                                        VIDEO
                                    </button>
                                    <a href="#popupInfo2" data-rel="popup" data-transition="pop" data-position-to="window" name="btnWebcam" class="my-tooltip-btn ui-btn ui-alt-icon ui-nodisc-icon ui-btn-inline ui-icon-info ui-btn-icon-notext" title="Info" style="width: 5px">Info</a>
                                    <div data-role="popup" id="popupInfo2" data-theme="b" style="max-width:350px;">
                                        <label id="lblVideoDatos" style="text-align: center;"></label>
                                    </div>

                                    <button id="btnVideoApagar" class="ui-btn ui-btn-inline" title="Apagar emisión de Video" style="width: 70px;display:none">
                                        CERRAR
                                    </button>
                                </fieldset>
                                <img src='imagen.png' height='300' id="imgFoto" style="display: none">
                                <div id="divIframe" class="iframe-16-9" style="display:none;border:none">
                                    <iframe src="webcam_server.php" height="auto" width="auto" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" id="frmVideo"></iframe>
                                </div>
                                <div id="divEspera3" style="background-color: white;width: 100%;height: 100%;display:none">
                                </div>
                            </center>
                        </div>
                    </div>
            </div>
            </fieldset>
        </div>
        <script type="text/javascript" src="./supervisionControl.js"></script>
    </body>

    </html>
<?php
} else {
    include('accesoRestringido.php');
}
?>