var contenido_arbol = "";
var consulta_exitosa = 0;
var dato_obtenido1 = [];
var dato_obtenido2 = [];

function cargarDatos(opcion) {
    consulta_exitosa = 0;
    $.ajax({
        url: "informacionesDatos.php",
        data: {
            opcion: opcion
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 1) {
                contenido_arbol = "";
                dato_obtenido1 = data;
                consulta_exitosa = 1;
            }
            if (opcion === 2) {
                dato_obtenido2 = data;
                consulta_exitosa = 1;
            }
        },
        complete: function(data) {
            if (opcion === 1 && consulta_exitosa === 1) {
                $.each(dato_obtenido1, function(id, dato) {
                    //Maceta
                    $("#divTipoMaceta").html("<b>" + $.trim(dato.tipo_maceta) + "</b>");
                    $("#divVolumenMaceta").html("<b>" + $.trim(dato.volumen_maceta) + " mililitros (ml)</b>");
                    $("#divCantidadMaceta").html("<b>" + $.trim(dato.cantidad_maceta) + " unidade/s</b>");
                    contenido_arbol += "" +
                        "   <li id='id3' class= 'folder'> <b>Macetas</b>" +
                        "       <ul class= 'divItem1'>" +
                        "           <li id='id3.1' class= 'divItem1'> Tipo<br><b>" + $.trim(dato.tipo_maceta) + " </b><br></li>" +
                        "           <li id='id3.1' class= 'divItem1'> Volumen<br><b>" + $.trim(dato.volumen_maceta) + " mililitros (ml)</b><br></li>" +
                        "           <li id='id3.1' class= 'divItem1'> Cantidad<br><b>" + $.trim(dato.cantidad_maceta) + " unidade/s</b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divCaudalGotero").html("<b>" + $.trim(dato.caudal_gotero) + " mililitros/minutos (ml/min)</b>");
                    contenido_arbol += "" +
                        "   <li id='id4' class= 'folder'> <b>Gotero</b>" +
                        "       <ul class= 'folder divItem2'>" +
                        "           <li id='id4.1' class= 'folder divItem2'> Caudal<br><b>" + $.trim(dato.caudal_gotero) + " mililitros/minutos (ml/min)</b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divNombreEspecie").html("<b>" + $.trim(dato.nombre_especie) + " </b>");
                    $("#divDescripcionEspecie").html("<b>" + $.trim(dato.descripcion_especie) + " </b>");
                    $("#divAguaEspecie").html("<b>" + $.trim(dato.agua_especie) + " mililitros (ml) por maceta al día</b>");
                    contenido_arbol += "" +
                        "   <li id='id5' class= 'folder'> <b>Especie Cultivada</b>" +
                        "       <ul class='divItem3'>" +
                        "           <li id='id5.1' class='divItem3'> Nombre<br><b>" + $.trim(dato.nombre_especie) + " </b><br></li>" +
                        "           <li id='id5.2' class='divItem3'> Descripción<br><b>" + $.trim(dato.descripcion_especie) + " </b><br></li>" +
                        "           <li id='id5.3' class='divItem3'> Necesidad Agua<br><b>" + $.trim(dato.agua_especie) + " mililitros (ml) por maceta al día</b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divRiegoInicio").html("<b>" + $.trim(dato.riego_inicio) + " </b>");
                    $("#divRiegoFin").html("<b>" + $.trim(dato.riego_fin) + " </b>");
                    $("#divActivoRiego").html("<b>" + $.trim(dato.activo_riego) + " minuto/s</b>");
                    $("#divEsperaRiego").html("<b>" + $.trim(dato.espera_riego) + " minuto/s</b>");
                    $("#divHSRiego").html("<b>" + $.trim(dato.hs_riego) + " </b>");
                    $("#divTARiego").html("<b>" + $.trim(dato.ta_riego) + " </b>");
                    $("#divLARiego").html("<b>" + $.trim(dato.la_riego) + " </b>");
                    contenido_arbol += "" +
                        "   <li id='id6' class= 'folder'> <b>Riego</b>" +
                        "       <ul class='divItem4'>" +
                        "           <li id='id6.1' class='divItem4'> Horario Inicio<br><b>" + $.trim(dato.riego_inicio) + "</b><br></li>" +
                        "           <li id='id6.2' class='divItem4'> Horario Fin<br><b>" + $.trim(dato.riego_fin) + "</b><br></li>" +
                        "           <li id='id6.3' class='divItem4'> Duración<br><b>" + $.trim(dato.activo_riego) + " minuto/s</b><br></li>" +
                        "           <li id='id6.4' class='divItem4'> Espera<br><b>" + $.trim(dato.espera_riego) + " minuto/s</b><br></li>" +
                        "           <li id='id6.5' class='divItem4'> Humedad del Suelo<br><b>" + $.trim(dato.hs_riego) + " </b><br></li>" +
                        "           <li id='id6.6' class='divItem4'> Temperatura Ambiente<br><b>" + $.trim(dato.ta_riego) + " </b><br></li>" +
                        "           <li id='id6.7' class='divItem4'> Iluminación Ambiente<br><b>" + $.trim(dato.la_riego) + " </b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divActivarWebcam").html("<b>" + $.trim(dato.activar_webcam) + " </b>");
                    $("#divFotoWebcam").html("<b>" + $.trim(dato.rf_webcam) + " pixeles</b>");
                    $("#divVideoWebcam").html("<b>" + $.trim(dato.rv_webcam) + " pixeles</b>");
                    $("#divFpsWebcam").html("<b>" + $.trim(dato.fps_webcam) + " </b>");
                    contenido_arbol += "" +
                        "   <li id='id7' class= 'folder'> <b>Cámara Web</b>" +
                        "       <ul class='divItem5'>" +
                        "           <li id='id7.1' class='divItem5'> Activo<br><b>" + $.trim(dato.activar_webcam) + " </b><br></li>" +
                        "           <li id='id7.2' class='divItem5'> Resolución Fotografía<br><b>" + $.trim(dato.rf_webcam) + " </b><br></li>" +
                        "           <li id='id7.3' class='divItem5'> Resolución Vídeo<br><b>" + $.trim(dato.rv_webcam) + " </b><br></li>" +
                        "           <li id='id7.4' class='divItem5'> Fotogramas por segundo<br><b>" + $.trim(dato.fps_webcam) + " fps</b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divActivarResumen").html("<b>" + $.trim(dato.activar_resumen) + " </b>");
                    $("#divHoraResumen").html("<b>" + $.trim(dato.hora_resumen) + " </b>");
                    contenido_arbol += "" +
                        "   <li id='id8' class= 'folder'> <b>Resumen Diario</b>" +
                        "       <ul class='divItem6'>" +
                        "           <li id='id8.1' class='divItem6'> Activo<br><b>" + $.trim(dato.activar_resumen) + " </b><br></li>" +
                        "           <li id='id8.2' class='divItem6'> Hora de Envío<br><b>" + $.trim(dato.hora_resumen) + " </b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divActivarAlerta").html("<b>" + $.trim(dato.activar_alerta) + " </b>");
                    $("#divRIAlerta").html("<b>" + $.trim(dato.ri_alerta) + " </b>");
                    $("#divRFAlerta").html("<b>" + $.trim(dato.rf_alerta) + " </b>");
                    $("#divHsMinAlerta").html("<b>" + $.trim(dato.hsmin_alerta) + " </b>");
                    $("#divHsMaxAlerta").html("<b>" + $.trim(dato.hsmax_alerta) + " </b>");
                    $("#divTaMinAlerta").html("<b>" + $.trim(dato.tamin_alerta) + " </b>");
                    $("#divTaMaxAlerta").html("<b>" + $.trim(dato.tamax_alerta) + " </b>");
                    $("#divLsMaxAlerta").html("<b>" + $.trim(dato.lsmax_alerta) + " </b>");
                    $("#divLlAlerta").html("<b>" + $.trim(dato.ll_alerta) + " </b>");
                    contenido_arbol += "" +
                        "   <li id='id9' class= 'folder'> <b>Alertas</b>" +
                        "       <ul class='divItem7'>" +
                        "           <li id='id9.1' class='divItem7'> Activo<br><b>" + $.trim(dato.activar_alerta) + " </b><br></li>" +
                        "           <li id='id9.2' class='divItem7'> Inicio Riego<br><b>" + $.trim(dato.ri_alerta) + " </b><br></li>" +
                        "           <li id='id9.3' class='divItem7'> Fin Riego<br><b>" + $.trim(dato.rf_alerta) + " </b><br></li>" +
                        "           <li id='id9.4' class='divItem7'> Fin Riego<br><b>" + $.trim(dato.hsmin_alerta) + " </b><br></li>" +
                        "           <li id='id9.5' class='divItem7'> Humedad Suelo Mínima<br><b>" + $.trim(dato.hsmax_alerta) + " </b><br></li>" +
                        "           <li id='id9.6' class='divItem7'> Humedad Suelo Máxima<br><b>" + $.trim(dato.tamin_alerta) + " </b><br></li>" +
                        "           <li id='id9.7' class='divItem7'> Temperatura Ambiente Mínima<br><b>" + $.trim(dato.tamax_alerta) + " </b><br></li>" +
                        "           <li id='id9.8' class='divItem7'> Temperatura Ambiente Máxima<br><b>" + $.trim(dato.lsmax_alerta) + " </b><br></li>" +
                        "           <li id='id9.9' class='divItem7'> Iluminación Ambiente Máxima<br><b>" + $.trim(dato.ll_alerta) + " </b><br></li>" +
                        "       </ul>" +
                        "   </li>";

                    $("#divActivarSmtp").html("<b>" + $.trim(dato.activar_smtp) + " </b>");
                    $("#divServidorSmtp").html("<b>" + $.trim(dato.servidor_smtp) + " </b>");
                    $("#divPuertoSmtp").html("<b>" + $.trim(dato.puerto_smtp) + " </b>");
                    $("#divSslSmtp").html("<b>" + $.trim(dato.ssl_smtp) + " </b>");
                    $("#divUsuarioSmtp").html("<b>" + $.trim(dato.usuario_smtp) + " </b>");
                    contenido_arbol += "" +
                        "   <li id='id10' class= 'folder'> <b>Notificador (SMTP)</b>" +
                        "       <ul class='divItem8'>" +
                        "           <li id='id10.1' class='divItem8'> Activo<br><b>" + $.trim(dato.activar_smtp) + " </b><br></li>" +
                        "           <li id='id10.2' class='divItem8'> Servidor<br><b>" + $.trim(dato.servidor_smtp) + " </b><br></li>" +
                        "           <li id='id10.3' class='divItem8'> Puerto<br><b>" + $.trim(dato.puerto_smtp) + " </b><br></li>" +
                        "           <li id='id10.4' class='divItem8'> SSL<br><b>" + $.trim(dato.ssl_smtp) + " </b><br></li>" +
                        "           <li id='id10.5' class='divItem8'> Cuenta<br><b>" + $.trim(dato.usuario_smtp) + " </b><br></li>" +
                        "       </ul>" +
                        "   </li>";
                });
                cargarDatos(2);
                $("#divEspera1").hide();
                $("#divEspera2").show();
            }
            if (opcion === 2 && consulta_exitosa === 1) {
                $.each(dato_obtenido2, function(id, dato) {
                    //Adicional
                    $("#divFechaInfo").html("<b>" + $.trim(dato.fh_actual) + " </b>");
                    $("#divAccesoInfo").html("<b>" + $.trim(dato.ultimo_acceso) + " </b>");
                    $("#divUltimoInfo").html("<b>" + $.trim(dato.fhu_inicio) + " </b>");
                    $("#divActivoInfo").html("<b>" + $.trim(dato.tiempo_transcurrido) + " </b>");
                    $("#divPrimerInfo").html("<b>" + $.trim(dato.primer_inicio) + " </b>");
                    contenido_arbol += "" +
                        "   <li id='id1' class= 'folder'> <b>Usuario</b>" +
                        "       <ul class='divItem9'>" +
                        "           <li id='id1.1' class='divItem9'> Último Acceso<br><b>" + $.trim(dato.ultimo_acceso) + " </b><br></li>" +
                        "       </ul>" +
                        "   </li>" +
                        "   <li id='id2' class= 'folder'> <b>Dispositivo</b>" +
                        "       <ul class='divItem10'>" +
                        "           <li id='id2.1' class='divItem10'> Fecha/Hora Actual<br><b>" + $.trim(dato.fh_actual) + " </b><br></li>" +
                        "           <li id='id2.2' class='divItem10'> Primer Inicio Registrado<br><b>" + $.trim(dato.primer_inicio) + " </b><br></li>" +
                        "           <li id='id2.3' class='divItem10'> Último Inicio Activo<br><b>" + $.trim(dato.fhu_inicio) + " </b><br></li>" +
                        "           <li id='id2.4' class='divItem10'> Tiempo Activo<br><b>" + $.trim(dato.tiempo_transcurrido) + " </b><br></li>" +
                        "       </ul>" +
                        "   </li>";
                    $("#divDispositivo").html("<ul class='divFondo'>" + contenido_arbol + "</ul>");
                    $("#divDispositivo").fancytree({
                        activate: function(e, data) {
                            $("#echoActive").text(data.node.title);
                            //              alert(node.getKeyPath());
                            if (data.node.url)
                                window.open(data.node.url, data.node.target);
                        },
                        deactivate: function(e, data) {
                            $("#echoSelected").text("-");
                        },
                        focus: function(e, data) {
                            $("#echoFocused").text(data.node.title);
                        },
                        blur: function(e, data) {
                            $("#echoFocused").text("-");
                        },
                        lazyread: function(e, data) {
                            var fakeJsonResult = [
                                { title: 'Lazy node 1', lazy: true },
                                { title: 'Simple node 2', select: true }
                            ];
                            //              alert ("Let's pretend we're using this AJAX response to load the branch:\n " + jsonResult);
                            function fakeAjaxResponse() {
                                return function() {
                                    node.addChild(fakeJsonResult);
                                    // Remove the 'loading...' status:
                                    node.setLazyNodeStatus(DTNodeStatus_Ok);
                                };
                            }
                            window.setTimeout(fakeAjaxResponse(), 1500);
                        }
                    });
                    $("#divEspera2").hide();
                    $("#tabs").show();
                });
            }
        },
        error: function(msg) {
            //alert("3");
            console.log(msg);
        }
    });
}
cargarDatos(1);
$(function() {
    // Initialize the tree inside the <div>element.
    // The tree structure is read from the contained <ul> tag.

});
/*$(function() {
    // Initialize the tree inside the <div>element.
    // The tree structure is read from the contained <ul> tag.
    $("#tree").fancytree({
        activate: function(e, data) {
            $("#echoActive").text(data.node.title);
            //              alert(node.getKeyPath());
            if (data.node.url)
                window.open(data.node.url, data.node.target);
        },
        deactivate: function(e, data) {
            $("#echoSelected").text("-");
        },
        focus: function(e, data) {
            $("#echoFocused").text(data.node.title);
        },
        blur: function(e, data) {
            $("#echoFocused").text("-");
        },
        lazyread: function(e, data) {
            var fakeJsonResult = [
                { title: 'Lazy node 1', lazy: true },
                { title: 'Simple node 2', select: true }
            ];
            //              alert ("Let's pretend we're using this AJAX response to load the branch:\n " + jsonResult);
            function fakeAjaxResponse() {
                return function() {
                    node.addChild(fakeJsonResult);
                    // Remove the 'loading...' status:
                    node.setLazyNodeStatus(DTNodeStatus_Ok);
                };
            }
            window.setTimeout(fakeAjaxResponse(), 1500);
        }
    });
});*/