<?php
session_start();
if (!empty($_SESSION['usuario'])) {
    $lifetime = 1800;
    setcookie(session_name(), session_id(), time() + $lifetime, "/");
    ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
            include("head_html.php");
            ?>
    <title>SistRiego - Descripción General</title>
    <meta name="viewport" content="initial-scale=1.0">
    <link rel="stylesheet" href="./css/estilo_luci.css">
    <link href="./css/skin-win7/ui.fancytree.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    .divFondo {
        background: url('images/sistriego_hardware_full.png');
        background-size: 40%;
        background-repeat: no-repeat;
        background-position: top right;
        height: 258px;
        overflow: auto;
    }

    .divItem {
        background-color: rgba(255, 255, 255, 0.9);
    }

    .divItem1 {
        background-color: rgba(245, 222, 179, 0.5);
    }

    .divItem2 {
        background-color: rgba(135, 206, 250, 0.5);
    }

    .divItem3 {
        background-color: rgba(144, 238, 144, 0.5);
    }

    .divItem4 {
        background-color: rgba(0, 191, 255, 0.5);
    }

    .divItem5 {
        background-color: rgba(255, 255, 102, 0.5);
    }

    .divItem6 {
        background-color: rgba(32, 178, 170, 0.5);
    }

    .divItem7 {
        background-color: rgba(240, 128, 128, 0.5);
    }

    .divItem8 {
        background-color: rgba(221, 160, 221, 0.5);
    }

    .divItem9 {
        background-color: rgba(204, 204, 0, 0.5);
    }

    .divItem10 {
        background-color: rgba(192, 192, 192, 0.5);
    }
    </style>
</head>

<body class="lang_esOverview">
    <div data-role="page" id="divFormulario">
        <div data-role="header" class="sr-cuentausuario" data-theme="a">
            <center>
                <span
                    style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Informaciones</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Datos
                    Configurados en el Dispositivo</span>
            </center>
        </div>
        <div data-role="main" data-theme="a" class="ui-conten" style="margin:0px 10px 10px 10px;">
            <center>
                <div id="divEspera1">
                    <img src="images/cargando_4.gif" alt="Cargando..." height="128">
                </div>
                <div id="divEspera2" style="display:none">
                    <img src="images/cargando_3.gif" alt="Cargando..." height="128">
                </div>
            </center>
            <fieldset data-role="controlgroup" style="background-color: none;border:none;padding:2px 2px 2px 2px;">
                <div data-role="tabs" id="tabs" style="display:none;">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#uno" data-ajax="false" id="lnUno">AGRUPADO</a></li>
                            <li><a href="#dos" data-ajax="false" id="lnDos">EXPANDIDO</a></li>
                        </ul>
                    </div>
                    <div id="uno" class="ui-btn-active">
                        <div id="divDispositivo" class="divFondo">
                        </div>
                    </div>
                    <div id="dos" class="ui-btn-active">
                        <div class="divFondo divItem" id="divPrincipal">
                            <div class="cbi-section">
                                <h4>Maceta</h4>
                                <div class="divItem1">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Tipo</div>
                                            <div class="td left" id="divTipoMaceta"></div>
                                        </div>
                                        <div class="tr">
                                            <div class="td left" width="33%">Volumen</div>
                                            <div class="td left" id="divVolumenMaceta"></div>
                                        </div>
                                        <div class="tr">
                                            <div class="td left" width="33%">Cantidad</div>
                                            <div class="td left" id="divCantidadMaceta"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Gotero</h4>
                                <div class="divItem2">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Caudal</div>
                                            <div class="td left" id="divCaudalGotero"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Especie Cultivada</h4>
                                <div class=" divItem3">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Nombre</div>
                                            <div class="td left" id="divNombreEspecie"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Descripcion</div>
                                            <div class="td left" id="divDescripcionEspecie"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Nesecidad Agua</div>
                                            <div class="td left" id="divAguaEspecie"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Riego</h4>
                                <div class=" divItem4">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Horario Inicio</div>
                                            <div class="td left" id="divRiegoInicio"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Horario Fin</div>
                                            <div class="td left" id="divRiegoFin"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Tiempo Activo</div>
                                            <div class="td left" id="divActivoRiego"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Tiempo Espera</div>
                                            <div class="td left" id="divEsperaRiego"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Humedad del Suelo</div>
                                            <div class="td left" id="divHSRiego"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Temperatura Ambiente</div>
                                            <div class="td left" id="divTARiego"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Iluminaci&oacute;n Ambiente</div>
                                            <div class="td left" id="divLARiego"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Cámara Web</h4>
                                <div class="divItem5">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Activo</div>
                                            <div class="td left" id="divActivarWebcam"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Resolución Fotografía</div>
                                            <div class="td left" id="divFotoWebcam"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Resolución Video</div>
                                            <div class="td left" id="divVideoWebcam"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Fotogramas por segundo</div>
                                            <div class="td left" id="divFpsWebcam"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Alertas</h4>
                                <div class="divItem6">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Activo</div>
                                            <div class="td left" id="divActivarAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Inicio Riego</div>
                                            <div class="td left" id="divRIAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Fin Riego</div>
                                            <div class="td left" id="divRFAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Humedad Suelo Mínima</div>
                                            <div class="td left" id="divHsMinAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Humedad Suelo Máxima</div>
                                            <div class="td left" id="divHsMaxAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Temperatura Ambiente Mínima</div>
                                            <div class="td left" id="divTaMinAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Temperatura Ambiente Máxima</div>
                                            <div class="td left" id="divTaMaxAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Ilumninación Máxima</div>
                                            <div class="td left" id="divLsMaxAlerta"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Lluvia</div>
                                            <div class="td left" id="divLlAlerta"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Resumen Diario</h4>
                                <div class="divItem7">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Activo</div>
                                            <div class="td left" id="divActivarResumen"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Hora Envío</div>
                                            <div class="td left" id="divHoraResumen"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Notificador (SMTP)</h4>
                                <div class="divItem8">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Activo</div>
                                            <div class="td left" id="divActivarSmtp"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Servidor</div>
                                            <div class="td left" id="divServidorSmtp"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Puerto</div>
                                            <div class="td left" id="divPuertoSmtp"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">SSL</div>
                                            <div class="td left" id="divSslSmtp"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Cuenta</div>
                                            <div class="td left" id="divUsuarioSmtp"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Usuario</h4>
                                <div class="divItem9">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Último Acceso</div>
                                            <div class="td left" id="divAccesoInfo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cbi-section">
                                <h4>Dispositivo</h4>
                                <div class="divItem10">
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Fecha/Hora Actual</div>
                                            <div class="td left" id="divFechaInfo"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Primer Inicio Registrado</div>
                                            <div class="td left" id="divPrimerInfo"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Último Inicio Activo</div>
                                            <div class="td left" id="divUltimoInfo"></div>
                                        </div>
                                    </div>
                                    <div class="table" width="100%">
                                        <div class="tr">
                                            <div class="td left" width="33%">Tiempo Activo Actual</div>
                                            <div class="td left" id="divActivoInfo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <center>
            <div style="margin: 0px 0px 0px 0px;">
                Desarrollador: <a href="#">Gustavo Eloy Alcaraz Bogado</a><br>Noviembre/2019 - v10.73
            </div>
        </center>
    </div>
    <script src="./js/jquery.fancytree.js" type="text/javascript"></script>
    <script type="text/javascript" src="./informacionesControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>