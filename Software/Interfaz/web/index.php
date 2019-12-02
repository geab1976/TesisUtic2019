<?php
include("crearSesion.php");
?>
<!DOCTYPE html>
<html class="ui-mobile">

<head>
    <?php
    include("head_html.php");
    ?>
    <style type="text/css">
        .spTitulo {
            font-weight: 700;
            text-align: center;
            margin: 0 auto;
            font-size: 20pt;
            color: red;
            text-shadow: white 1px 1px 0, blue 2px 2px 0, white 0.1em 0.1em 0.2em;
        }

        .stTitulo2 {
            background-image: url('images/tomates_index.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 280px;
            text-shadow: gray 0.1em 0.1em 0.5em;
        }

        .imgTitulo {
            -webkit-filter: drop-shadow(3px 3px 3px white);
            filter: drop-shadow(3px 3px 3px white);
        }

        .txtTitulo {
            font-size: 14pt;
            margin-top: 0px;
            margin-bottom: 0px;
            color: #1503ba;
            text-shadow: black 0.0em 0.0em 0.0em;
        }

        .txtTitulo2 {
            font-size: 12pt;
            margin-top: 0px;
            color: black;
            text-shadow: black 0.1em 0.1em 0.1em;
        }

        .txtSubtitulo {
            font-size: 10pt;
            margin-top: 0px;
            color: black;
            text-shadow: black 0.0em 0.0em 0.0em;
        }

        .txtSubtitulo2 {
            font-size: 8pt;
            margin-top: 0px;
            color: black;
            text-shadow: gray 0.0em 0.2em 0.3em;
        }

        hr {
            display: block;
            margin-top: 0.1em;
            margin-bottom: 0.1em;
            margin-left: auto;
            margin-right: auto;
            border-style: solid;
            width: 300px;
            border-color: cornflowerblue;
            border-width: 1px;
        }
    </style>
</head>

<body>
    <center>
        <div data-role="page" data-theme="b" style="background-color: white;padding: 15px 5px 5px 5px;">
            <center>
                <!-- /header -->
                <img src="images/riego_sol.png" height="40" class="imgTitulo"><br>
                <span class="spTitulo">
                    <b>SistRiego</b>
                </span>
                <div style="margin: 20px 0px 0px 0px;" class="txtSubtitulo2">
                    <br>
                    <span style="font-size:11pt;">
                        <b>SISTEMA DE CONTROL<br>AUTOM&Aacute;TICO PROGRAMABLE</b>
                    </span><br>
                    <span style="font-size:9pt;">
                        para el Riego por Goteo de un <br>
                        Huerto Urbano en Macetas</b>
                    </span><br><br><br>
                </div>
                <section class="container">
                    <div role="main" class="box ui-content" style="width: 250px;">
                        <center>
                            <a href="loginHome.php" class="ui-btn ui-btn-a ui-corner-all stTitulo2" 
                            data-transition="flip" 
                            style="color:white;font-size:12pt;width:200px;height:70px;text-shadow: black 1.0em 1.0em 2.0em;">
                                <br><br><br>E N T R A R
                            </a>
                        </center>
                    </div>
                </section>
                <hr>
                <center>
                    <div style="margin: 10px 0px 0px 0px;" class="txtSubtitulo2">
                        <span style="font-size:10pt;"><b>Gustavo Eloy Alcaráz Bogado</b></span><br>
                        Desarrollador del Dispositivo<br>
                        Noviembre/2019<br>
                        v10.73
                    </div>
                </center>
            </center>
        </div>
    </center>
</body>

</html>