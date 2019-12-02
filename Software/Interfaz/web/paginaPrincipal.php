<?php
session_start();
if (!empty($_SESSION['usuario'])) {
    /*
     * La funci n empty() devuelve verdadero si el argumento posee un valor usuario o,
     * al usar ! Empty() devuelve verdadero no solo si la variable fue declarada sino
     * ademas si contiene alg n valor no nulo.
     */
    echo 'Te haz logueado como :' . $_SESSION['usuario'];
    echo 'Haz logrado el acceso a una pagina segura';
    ?>
<!DOCTYPE html>
<html class="ui-mobile">

<head>
    <?php
            include("head_html.php");
            ?>
    <style type="text/css">
    .txtUsuario {
        font-size: 5pt;
        margin-top: 0px;
        color: white;
        text-shadow: black 0.1em 0.2em 0.4em;
    }
    </style>
</head>

<body class="ui-mobile-viewport ui-overlay-a">
    <center>
        <div id="visorPrincipal" style="width: 501px">
            <div data-role="page" class="jqm-demos ui-responsive-panel ui-page-active" id="panel-responsive-page1"
                data-title="SistRiego v10.73" data-url="panel-responsive-page1" tabindex="0">
                <div class="ui-panel-wrapper"
                    style="margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;background-color: white;">
                    <div data-role="header" role="banner">
                        <center>
                            <img src="images/riego_sol.png" height="30" width="19">
                            <span
                                style="color:red;font-size: 20pt; margin-top: 0px;text-shadow: white 1px 1px 0, blue 2px 2px 0,white 0.1em 0.1em 0.2em;">
                                <b>SistRiego</b>
                            </span><br>
                            <span class="txtUsuario">
                                <?php
                                            echo "<b>" . $_SESSION["nombres"] . " " . $_SESSION["apellidos"] . "</b> (" . $_SESSION["usuario"] . ")";
                                            ?>
                            </span>
                            <a href="#nav-panel1" data-icon="bars" data-iconpos="notext"
                                class="ui-btn-left ui-btn ui-icon-bars  ui-shadow ui-corner-all" data-role="button"
                                role="button" style="font-size: 8pt;"> MENU </a>
                            <a href="#nav-panel2" data-icon="bars" data-iconpos="notext"
                                class="ui-btn-right ui-btn ui-icon-bars  ui-shadow ui-corner-all" data-role="button"
                                role="button" style="font-size: 8pt;"> SESI&Oacute;N </a>
                        </center>
                    </div>
                    <center>
                        <iframe id="framePrincipal" src="" class="ui-responsive ui-shadow" scrolling="auto"
                            style="width: 100%;height:550px;border: none;padding: 2px 2px 2px 2px;"></iframe>
                    </center>
                </div>
                <div data-role="panel" data-display="overlay" data-theme="a" id="nav-panel1"
                    class="ui-panel ui-panel-position-left ui-panel-display-push ui-body-a ui-panel-animate ui-panel-closed"
                    data-tolerance="0,0">
                    <div class="ui-panel-inner">
                        <ul data-role="listview" class="ui-listview" style="font-size: 5px;">
                            <li data-icon="delete" class="ui-first-child"><a href="#" data-rel="close"
                                    class="ui-btn ui-btn-icon-left ui-icon-delete">
                                    Cerrar Menu </a></li>

                            <li><a class="ui-btn ui-btn-icon-left ui-icon-home" id="aPrincipal" data-rel="close">
                                    Informaciones</a></li>

                            <?php
                                    if ($_SESSION['administrador'] == '1') {
                                        ?>
                            <li><a class="ui-btn ui-btn-icon-left ui-icon-star" id="aEspecies" data-rel="close">
                                    Especies </a></li>
                            <li><a class="ui-btn ui-btn-icon-left ui-icon-gear" id="aConfiguraciones" data-rel="close">
                                    Configuraciones</a></li>
                            <?php
                                    }
                                    ?>

                            <li><a class="ui-btn ui-btn-icon-left ui-icon-eye" id="aSupervision" data-rel="close">
                                    Supervisi&oacute;n</a></li>

                            <li><a class="ui-btn ui-btn-icon-left ui-icon-check" id="aRiego" data-rel="close">
                                    Riegos</a></li>

                            <li><a class="ui-btn ui-btn-icon-left ui-icon-info" id="aMineria" data-rel="close">
                                    Minería Datos</a></li>

                            <li><a class="ui-btn ui-btn-icon-left ui-icon-bars" id="aTarifa" data-rel="close">
                                    Tarifas Agua</a></li>

                            <?php
                                    if ($_SESSION['administrador'] == '1') {
                                        ?>
                            <li><a class="ui-btn ui-btn-icon-left ui-icon-calendar" id="aLog" data-rel="close">
                                    Hist&oacute;ricos</a></li>
                            <li class="ui-last-child"><a class="ui-btn ui-btn-icon-left ui-icon-grid" id="aUsuario"
                                    data-rel="close"> Usuarios </a></li>
                            <li><a class="ui-btn ui-btn-icon-left ui-icon-clock" id="aCambiarFechaHora"
                                    data-rel="close">
                                    Fecha/Hora</a></li>
                            <?php
                                    }
                                    ?>


                        </ul>
                        <!-- /page -->
                    </div>
                </div>
                <div data-role="panel" data-theme="a" id="nav-panel2" data-display="overlay"
                    class="ui-panel ui-panel-position-right ui-panel-display-push ui-body-a ui-panel-animate ui-panel-closed"
                    data-tolerance="0,0" data-position="right">
                    <div class="ui-panel-inner">
                        <ul data-role="listview" class="ui-listview" style="font-size: 5px;">
                            <li data-icon="delete" class="ui-first-child"><a href="#" data-rel="close"
                                    class="ui-btn ui-btn-icon-left ui-icon-delete">
                                    Cerrar Menu </a></li>

                            <li><a class="ui-btn ui-btn-icon-left ui-icon-user" id="aMiPerfil" data-rel="close">
                                    Perfil</a></li>

                            <li class="ui-last-child"><a class="ui-btn ui-btn-icon-left ui-icon-power"
                                    id="aCerrarSesion" data-rel="close" href="cerrarSesion.php">
                                    Cerrar Sesi&oacute;n</a></li>
                        </ul>
                        <!-- /page -->
                    </div>
                </div>
                <!-- /panel -->
                <div class="ui-loader ui-corner-all ui-body-a ui-loader-default">
                    <span class="ui-icon-loading"></span>
                    <h1>loading</h1>
                </div>
                <div class="ui-panel-dismiss" style=""></div>
                <div class="ui-panel-dismiss" style=""></div>
                <script type="text/javascript">
                //$("#framePrincipal").attr("src", "InicioHome.php");
                $("#aPrincipal").click(function() {
                    $("#framePrincipal").attr("src", "informacionesHome.php");
                });

                $("#aEspecies").click(function() {
                    $("#framePrincipal").attr("src", "especiesHome.php");
                });

                $("#aConfiguraciones").click(function() {
                    $("#framePrincipal").attr("src", "configuracionesHome.php");
                    mediaSize();
                });

                $("#aRiego").click(function() {
                    $("#framePrincipal").attr("src", "riegosHome.php");
                });

                $("#aMineria").click(function() {
                    $("#framePrincipal").attr("src", "datamineHome.php");
                });

                $("#aTarifa").click(function() {
                    $("#framePrincipal").attr("src", "tarifasHome.php");
                });

                $("#aSupervision").click(function() {
                    $("#framePrincipal").attr("src", "supervisionHome.php");
                });

                $("#aCambiarFechaHora").click(function() {
                    $("#framePrincipal").attr("src", "fechaHoraHome.php");
                });

                $("#aLog").click(function() {
                    $("#framePrincipal").attr("src", "historicosHome.php");
                });

                $("#aUsuario").click(function() {
                    $("#framePrincipal").attr("src", "usuariosHome.php");
                });

                $("#aMiPerfil").click(function() {
                    $("#framePrincipal").attr("src", "cuentaUsuarioHome.php");
                });

                /*
                 * We need to turn it into a function.
                 * To apply the changes both on document ready and when we resize the browser.
                 */
                function mediaSize() {
                    /* Set the matchMedia */
                    if (window.matchMedia('(min-width: 801px)').matches) {
                        /* Changes when we reach the min-width  */
                        $('#framePrincipal').css('width', '800px');
                    } else {
                        /* Reset for CSS changes – Still need a better way to do this! */
                        $('#framePrincipal').css('width', '100%');
                    }
                };

                /* Call the function */
                mediaSize();
                /* Attach the function to the resize event listener */
                window.addEventListener('resize', mediaSize, false);
                </script>
            </div>
        </div>
    </center>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>