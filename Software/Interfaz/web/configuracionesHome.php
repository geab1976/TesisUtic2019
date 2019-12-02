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
    <script src="./js/jtsage-datebox.min.js" type="text/javascript"></script>
    <script src="./js/jtsage-datebox.i18n.es-ES.utf8.min.js" type="text/javascript"></script>
</head>

<body>
    <div data-role="page" data-theme="a" id="divGrilla">
        <div data-role="header" class="sr-configuraciones">
            <center>
                <span
                    style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Configuraciones</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Mantener
                    Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-content">
            <center>
                <table id="tblDatos" data-role="table" data-mode="columntoggle" class="ui-responsive ui-shadow">
                    <thead>
                        <tr>
                            <th style="text-align:center">Item</th>
                            <th style="text-align:center">Configuración> Desc. | Esp. | Disp. | Est.</th>
                            <th style="text-align:center" data-priority="1">Riego> Cant. | Frec. | Caudal Gotero |
                                H. Inicio | H. Fin | Activo | Espera</th>
                            <th style="text-align:center" data-priority="2">Maceta> Tipo | Vol. | Cant.</th>
                            <th style="text-align:center" data-priority="3">Resumen Diario> Estado | Hora Envío</th>
                            <th style="text-align:center" data-priority="4">Alertas> Est. | R. Ini. | R. Fin. | Tp.
                                min. | Tp. max | Hm. min. | Hm. max. | Ls. max. | Lluv.</th>
                            <th style="text-align:center" data-priority="5">WebCam> Est. | Rs. Imagen | Rs. Vídeo |
                                Fps
                                Video</th>
                            <th style="text-align:center" data-priority="6">Email> Est. | Smtp | Puerto | Ssl |
                                Usuario
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
                <div data-role="navbar" class="ui-responsive ui-shadow">
                    <ul>
                        <li><a href="#divFormulario" data-icon="plus" data-transition="flip" id="lnAgregar"></a>
                        </li>
                        <li><a href="#divFormulario" data-icon="edit" data-transition="flip" id="lnEditar"></a></li>
                        <li><a href="#" data-icon="delete" data-transition="flip" id="lnBorrar"></a>
                        </li>
                    </ul>
                </div>
            </center>
        </div>
    </div>
    <div data-role="page" data-theme="b" id="divFormulario">
        <div data-role="header" class="sr-configuraciones" data-theme="a">
            <center>
                <span
                    style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Configuraciones</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Mantener
                    Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Generales</b></legend>
                <div data-role="fieldcontain">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" name="descripcion" id="descripcion" value=""
                        placeholder="Nombre para la configuración" data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="id_especie">Especie:</label>
                    <select name="id_especie" id="id_especie">
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="dispositivo_activar">Activar Dispositivo:</label>
                    <select name="dispositivo_activar" id="dispositivo_activar" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="configuracion_activar">Activar Configuración:</label>
                    <select name="configuracion_activar" id="configuracion_activar" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Maceta</b></legend>
                <div data-role="fieldcontain">
                    <label for="maceta_tipo">Tipo:</label>
                    <select name="maceta_tipo" id="maceta_tipo">
                        <option value="1">Paralepípedo</option>
                        <option value="2">Pirámide Truncada</option>
                        <option value="3">Cono Truncado</option>
                        <option value="4">Cilindro</option>
                    </select>
                    <label for="formula_volumen"></label>
                    <input type="text" name="formula_volumen" id="formula_volumen" value="Volumen = l.a.h"
                        placeholder="" disabled />
                </div>

                <div data-role="fieldcontain">
                    <label for="maceta_largo" id="lblLargo">Lado A:</label>
                    <input type="range" name="maceta_largo" id="maceta_largo" step="1" min="10" max="50" value="10"
                        data-highlight="true">
                </div>

                <div data-role="fieldcontain">
                    <label for="maceta_ancho" id="lblAncho">Lado B:</label>
                    <input type="range" name="maceta_ancho" id="maceta_ancho" step="1" min="10" max="50" value="10"
                        data-highlight="true">
                </div>

                <div data-role="fieldcontain">
                    <label for="maceta_alto" id="lblAlto">Lado C:</label>
                    <input type="range" name="maceta_alto" id="maceta_alto" step="1" min="10" max="50" value="10"
                        data-highlight="true">
                </div>

                <div data-role="fieldcontain">
                    <label for="maceta_volumen">Volumen (mililitros):</label>
                    <input type="number" name="maceta_volumen" id="maceta_volumen" value="0" disabled>
                </div>

                <div data-role="fieldcontain">
                    <label for="maceta_cantidad">Cantidad:</label>
                    <input type="range" name="maceta_cantidad" id="maceta_cantidad" step="1" min="2" max="20" value="2"
                        data-highlight="true">
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Riego</b></legend>
                <div data-role="fieldcontain">
                    <label for="riego_inicio">Horario Inicio:</label>
                    <input type="time" name="riego_inicio" id="riego_inicio" value="" placeholder="--:--"
                        data-clear-btn="false" required>
                </div>

                <div data-role="fieldcontain">
                    <label for="riego_fin">Horario Fin:</label>
                    <input type="time" name="riego_fin" id="riego_fin" value="" placeholder="--:--"
                        data-clear-btn="false" required>
                </div>

                <div data-role="fieldcontain">
                    <label for="riego_mililitros">Necesidad (agua):</label>
                    <input type="number" name="riego_mililitros" id="riego_mililitros" value="0" disabled>
                </div>

                <div data-role="fieldcontain">
                    <label for="riego_frecuencia">Frecuencia:</label>
                    <input type="number" name="riego_frecuencia" id="riego_frecuencia" value="0" disabled>
                </div>

                <div data-role="fieldcontain">
                    <label for="gotero_caudal">Caudal Gotero (mililitros/minuto):</label>
                    <input type="range" name="gotero_caudal" id="gotero_caudal" step="1" min="100" max="10000"
                        value="100" data-highlight="true" title="">
                </div>

                <div data-role="fieldcontain">
                    <label for="riego_minutos_activo">Minutos Activo:</label>
                    <input type="number" name="riego_minutos_activo" id="riego_minutos_activo" value="0" disabled>
                </div>

                <div data-role="fieldcontain">
                    <label for="riego_minutos_espera">Minutos Espera:</label>
                    <input type="range" name="riego_minutos_espera" id="riego_minutos_espera" step="1" min="1" max="120"
                        value="1" data-highlight="true">
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Resumen Diario</b></legend>
                <div class="ui-field-contain">
                    <label for="resumen_activar">Activar:</label>
                    <select name="resumen_activar" id="resumen_activar" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="resumen_hora_envio">Hora Envío:</label>
                    <input type="time" data-clear-btn="true" name="resumen_hora_envio" id="resumen_hora_envio"
                        value="15:00">
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Alertas</b></legend>
                <div class="ui-field-contain">
                    <label for="alerta_activar">Activar:</label>
                    <select name="alerta_activar" id="alerta_activar" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_riego_inicio">Inicio Riego:</label>
                    <select name="alerta_riego_inicio" id="alerta_riego_inicio" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_riego_fin">Fin Riego:</label>
                    <select name="alerta_riego_fin" id="alerta_riego_fin" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_hs_min">Hum. Suelo Min.:</label>
                    <select name="alerta_hs_min" id="alerta_hs_min" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_hs_max">Hum. Suelo Max.:</label>
                    <select name="alerta_hs_max" id="alerta_hs_max" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_ta_min">Temp. Min.:</label>
                    <select name="alerta_ta_min" id="alerta_ta_min" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_ta_max">Temp. Max.:</label>
                    <select name="alerta_ta_max" id="alerta_ta_max" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_ls_max">Ilum. Max.:</label>
                    <select name="alerta_ls_max" id="alerta_ls_max" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="alerta_lluvia">Lluvia:</label>
                    <select name="alerta_lluvia" id="alerta_lluvia" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos Cámara Web</b></legend>
                <div class="ui-field-contain">
                    <label for="webcam_activar">Activar:</label>
                    <select name="webcam_activar" id="webcam_activar" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="webcam_tamanio_imagen">Foto Tamaño:</label>
                    <select name="webcam_tamanio_imagen" id="webcam_tamanio_imagen">
                        <option value="160×120">QQVGA (160×120)</option>
                        <option value="176×144">QCIF (176×144)</option>
                        <option value="192×144">QCIF (192×144)</option>
                        <option value="240×160">HQVGA (240×160)</option>
                        <option value="320×240">QVGA (320×240)</option>
                        <option value="352×240">Video CD NTSC (352×240)</option>
                        <option value="352×288">Video CD PAL (352×288)</option>
                        <option value="384×288">xCIF (384×288)</option>
                        <option value="480×360" selected>360p (480×360)</option>
                        <option value="640×360">nHD (640×360)</option>
                        <option value="640×480">VGA (640×480)</option>
                        <option value="704×480">SD (704×480)</option>
                        <option value="720×480">DVD NTSC (720×480)</option>
                        <option value="800×480">WGA (800×480)</option>
                        <option value="800×600">SVGA (800×600)</option>
                        <option value="960×720">DVCPRO HD (960×720)</option>
                        <option value="1024×768">XGA (1024×768)</option>
                        <option value="1280×720">HD (1280×720)</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="webcam_tamanio_video">Vídeo Tamaño:</label>
                    <select name="webcam_tamanio_video" id="webcam_tamanio_video">
                        <option value="160X120">QQVGA (160×120)</option>
                        <option value="176X144">QCIF (176×144)</option>
                        <option value="192X144">QCIF (192×144)</option>
                        <option value="240X160">HQVGA (240×160)</option>
                        <option value="320X240">QVGA (320×240)</option>
                        <option value="352X240">Video CD NTSC (352×240)</option>
                        <option value="352X288" selected>Video CD PAL (352×288)</option>
                        <option value="384X288">xCIF (384×288)</option>
                        <option value="480X360">360p (480×360)</option>
                        <option value="640X360">nHD (640×360)</option>
                        <option value="640X480">VGA (640×480)</option>
                        <option value="704X480">SD (704×480)</option>
                        <option value="720X480">DVD NTSC (720×480)</option>
                        <option value="800X480">WGA (800×480)</option>
                        <option value="800X600">SVGA (800×600)</option>
                        <option value="960X720">DVCPRO HD (960×720)</option>
                        <option value="1024X768">XGA (1024×768)</option>
                        <option value="1280X720">HD (1280×720)</option>
                    </select>
                </div>

                <div data-role="fieldcontain">
                    <label for="webcam_fps_video">Fotogramas por segundo (fps):</label>
                    <input type="range" name="webcam_fps_video" id="webcam_fps_video" step="1" min="1" max="30"
                        value="14" data-highlight="true">
                </div>
            </fieldset>

            <fieldset data-role="controlgroup" style="background-color: #505050;padding:5px 5px 5px 5px;">
                <legend style="text-align:center;"><b>Datos SMTP Correo Electrónico</b></legend>
                <div class="ui-field-contain">
                    <label for="email_smtp_activar">Activar:</label>
                    <select name="email_smtp_activar" id="email_smtp_activar" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div data-role="fieldcontain">
                    <label for="email_smtp_servidor">Servidor:</label>
                    <input type="text" name="email_smtp_servidor" id="email_smtp_servidor" value=""
                        placeholder="Dirección del servidor SMTP" data-clear-btn="true" required />
                </div>

                <div class="ui-field-contain">
                    <label for="email_smtp_puerto">Puerto:</label>
                    <select name="email_smtp_puerto" id="email_smtp_puerto">
                        <option value="25">25</option>
                        <option value="465">465</option>
                        <option value="587">587</option>
                        <option value="2525">2525</option>
                        <option value="25025">25025</option>
                    </select>
                </div>

                <div class="ui-field-contain">
                    <label for="email_smtp_ssl">SSL:</label>
                    <select name="email_smtp_ssl" id="email_smtp_ssl" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div data-role="fieldcontain">
                    <label for="email_smtp_usuario">Usuario:</label>
                    <input type="email" name="email_smtp_usuario" id="email_smtp_usuario" value=""
                        placeholder="Email Cuenta Usuario SMTP" data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="email_smtp_clave">Clave:</label>
                    <input type="text" name="email_smtp_clave" id="email_smtp_clave" value=""
                        placeholder="Ingrese datos" data-clear-btn="true" required />
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
    <script type="text/javascript" src="./configuracionesControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>