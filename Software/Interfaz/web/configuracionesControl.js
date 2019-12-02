function cargarDatos(opcion) {
    $.ajax({
        url: "configuracionesDatos.php",
        data: {
            opcion: opcion,
            id: opcion === 1 ? "0" : $("[name=optDato]:checked").val(),
            id_especie: opcion === 1 ? "0" : $("#id_especie").val(),
            descripcion: opcion === 3 || opcion === 4 ? $("#descripcion").val() : "",
            maceta_tipo: opcion === 3 || opcion === 4 ? $("#maceta_tipo").val() : "",
            maceta_alto: opcion === 3 || opcion === 4 ? $("#maceta_alto").val() : "",
            maceta_largo: opcion === 3 || opcion === 4 ? $("#maceta_largo").val() : "",
            maceta_ancho: opcion === 3 || opcion === 4 ? $("#maceta_ancho").val() : "",
            maceta_volumen: opcion === 3 || opcion === 4 ? $("#maceta_volumen").val() : "",
            maceta_cantidad: opcion === 3 || opcion === 4 ? $("#maceta_cantidad").val() : "",
            gotero_caudal: opcion === 3 || opcion === 4 ? $("#gotero_caudal").val() : "",
            riego_minutos_activo: opcion === 3 || opcion === 4 ? $("#riego_minutos_activo").val() : "",
            riego_inicio: opcion === 3 || opcion === 4 ? $("#riego_inicio").val() : "",
            //gotero_caudal: opcion === 3 || opcion === 4 ? $("#gotero_caudal").val() : "",
            riego_fin: opcion === 3 || opcion === 4 ? $("#riego_fin").val() : "",
            riego_minutos_espera: opcion === 3 || opcion === 4 ? $("#riego_minutos_espera").val() : "",
            resumen_activar: opcion === 3 || opcion === 4 ? $("#resumen_activar").val() : "",
            resumen_hora_envio: opcion === 3 || opcion === 4 ? $("#resumen_hora_envio").val() : "",
            alerta_activar: opcion === 3 || opcion === 4 ? $("#alerta_activar").val() : "",
            alerta_riego_inicio: opcion === 3 || opcion === 4 ? $("#alerta_riego_inicio").val() : "",
            alerta_riego_fin: opcion === 3 || opcion === 4 ? $("#alerta_riego_fin").val() : "",
            alerta_hs_min: opcion === 3 || opcion === 4 ? $("#alerta_hs_min").val() : "",
            alerta_hs_max: opcion === 3 || opcion === 4 ? $("#alerta_hs_max").val() : "",
            alerta_ta_min: opcion === 3 || opcion === 4 ? $("#alerta_ta_min").val() : "",
            alerta_ta_max: opcion === 3 || opcion === 4 ? $("#alerta_ta_max").val() : "",
            alerta_ls_max: opcion === 3 || opcion === 4 ? $("#alerta_ls_max").val() : "",
            alerta_lluvia: opcion === 3 || opcion === 4 ? $("#alerta_lluvia").val() : "",
            webcam_activar: opcion === 3 || opcion === 4 ? $("#webcam_activar").val() : "",
            webcam_tamanio_imagen: opcion === 3 || opcion === 4 ? $("#webcam_tamanio_imagen").val() : "",
            webcam_tamanio_video: opcion === 3 || opcion === 4 ? $("#webcam_tamanio_video").val() : "",
            webcam_fps_video: opcion === 3 || opcion === 4 ? $("#webcam_fps_video").val() : "",
            email_smtp_activar: opcion === 3 || opcion === 4 ? $("#email_smtp_activar").val() : "",
            email_smtp_servidor: opcion === 3 || opcion === 4 ? $("#email_smtp_servidor").val() : "",
            email_smtp_puerto: opcion === 3 || opcion === 4 ? $("#email_smtp_puerto").val() : "",
            email_smtp_ssl: opcion === 3 || opcion === 4 ? $("#email_smtp_ssl").val() : "",
            email_smtp_usuario: opcion === 3 || opcion === 4 ? $("#email_smtp_usuario").val() : "",
            email_smtp_clave: opcion === 3 || opcion === 4 ? $("#email_smtp_clave").val() : "",
            dispositivo_activar: opcion === 3 || opcion === 4 ? $("#dispositivo_activar").val() : "",
            configuracion_activar: opcion === 3 || opcion === 4 ? $("#configuracion_activar").val() : ""
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            //alert("1");
            if (opcion === 1) {
                var tbl = $("#tblDatos tbody").html("");
                var maceta_tipo = ["Paralepípedo", "Pirámide Truncada", "Cono Truncado", "Cilindro"];
                var estado = ["Inactivo", "Activo"];
                var activar = ["No", "Sí"];
                $.each(data, function(id, dato) {
                    //alert(activar[(dato.resumen_activar)*1-1]);
                    tbl.append("" +
                            "<tr>" +
                            "<td><input type='radio' value='" + dato.id_configuracion + "' name='optDato'></td>" +
                            "<td>" + dato.descripcion + " | " + dato.especie + " | " +
                            estado[dato.dispositivo_activar] + " | " + estado[dato.configuracion_activar] + "</td>" +
                            "<td>" + dato.riego_mililitros + " ml | " + dato.riego_frecuencia + " | " +
                            dato.gotero_caudal + " ml/hs | " + dato.riego_inicio + " | " + dato.riego_fin + " | " +
                            dato.riego_minutos_activo + " min. | " + dato.riego_minutos_espera + " min.</td>" +
                            "<td>" + maceta_tipo[(dato.maceta_tipo * 1) - 1] + " | " + dato.maceta_volumen + " ml | " + dato.maceta_cantidad + "</td>" +
                            "<td>" + estado[dato.resumen_activar] + " | " + dato.resumen_hora_envio + "</td>" +
                            "<td>" + estado[dato.alerta_activar] + " | " + activar[dato.alerta_riego_inicio] +
                            " | " + activar[dato.alerta_riego_fin] + " | " + activar[dato.alerta_ta_min] +
                            " | " + activar[dato.alerta_ta_max] + " | " + activar[dato.alerta_hs_min] +
                            " | " + activar[dato.alerta_hs_max] + " | " + activar[dato.alerta_ls_max] + " | " + activar[dato.alerta_lluvia] + "</td>" +
                            "<td>" + estado[dato.webcam_activar] + " | " + dato.webcam_tamanio_imagen + " pixeles | " +
                            dato.webcam_tamanio_video + " pixeles | " + dato.webcam_fps_video + "</td>" +
                            "<td>" + estado[dato.email_smtp_activar] + " | " + dato.email_smtp_servidor + " | " + dato.email_smtp_puerto +
                            " | " + activar[dato.email_smtp_ssl] + " | " + dato.email_smtp_usuario + "</td>" +
                            "</tr>"
                        )
                        .parents("table").table("refresh");
                });
            }
            if (opcion === 2) {
                $.each(data, function(id, dato) {
                    $("#descripcion").val(dato.descripcion);
                    $("#id_especie").val(dato.id_especie).selectmenu("refresh");
                    $("#maceta_tipo").val($.trim(dato.maceta_tipo)).selectmenu("refresh");
                    $("#maceta_tipo").change();
                    $("#maceta_alto").val($.trim(dato.maceta_alto)).slider("refresh");
                    $("#maceta_largo").val($.trim(dato.maceta_largo)).slider("refresh");
                    $("#maceta_ancho").val($.trim(dato.maceta_ancho)).slider("refresh");
                    $("#maceta_volumen").val($.trim(dato.maceta_volumen));
                    $("#maceta_cantidad").val($.trim(dato.maceta_cantidad)).slider("refresh");
                    $("#gotero_caudal").val($.trim(dato.gotero_caudal)).slider("refresh");
                    $("#riego_inicio").val($.trim(dato.riego_inicio));
                    $("#riego_fin").val($.trim(dato.riego_fin));
                    $("#riego_mililitros").val(dato.riego_mililitros);
                    $("#riego_frecuencia").val(dato.riego_frecuencia);
                    $("#riego_minutos_activo").val($.trim(dato.riego_minutos_activo));
                    calcularTiempo();
                    $("#riego_minutos_espera").val($.trim(dato.riego_minutos_espera)).slider("refresh");
                    $("#resumen_activar").val($.trim(dato.resumen_activar)).slider("refresh");
                    $("#resumen_hora_envio").val($.trim(dato.resumen_hora_envio));
                    $("#alerta_activar").val($.trim(dato.alerta_activar)).slider("refresh");
                    $("#alerta_riego_inicio").val($.trim(dato.alerta_riego_inicio)).slider("refresh");
                    $("#alerta_riego_fin").val($.trim(dato.alerta_riego_fin)).slider("refresh");
                    $("#alerta_hs_min").val($.trim(dato.alerta_hs_min)).slider("refresh");
                    $("#alerta_hs_max").val($.trim(dato.alerta_hs_max)).slider("refresh");
                    $("#alerta_ta_min").val($.trim(dato.alerta_ta_min)).slider("refresh");
                    $("#alerta_ta_max").val($.trim(dato.alerta_ta_max)).slider("refresh");
                    $("#alerta_ls_max").val($.trim(dato.alerta_ls_max)).slider("refresh");
                    $("#alerta_lluvia").val($.trim(dato.alerta_lluvia)).slider("refresh");
                    $("#webcam_activar").val($.trim(dato.webcam_activar)).slider("refresh");
                    $("#webcam_tamanio_imagen").val($.trim(dato.webcam_tamanio_imagen)).selectmenu("refresh");
                    $("#webcam_tamanio_video").val($.trim(dato.webcam_tamanio_video)).selectmenu("refresh");
                    $("#webcam_fps_video").val($.trim(dato.webcam_fps_video)).slider("refresh");
                    $("#email_smtp_activar").val($.trim(dato.email_smtp_activar)).slider("refresh");
                    $("#email_smtp_servidor").val($.trim(dato.email_smtp_servidor));
                    $("#email_smtp_puerto").val($.trim(dato.email_smtp_puerto)).selectmenu("refresh");
                    $("#email_smtp_ssl").val($.trim(dato.email_smtp_ssl)).slider("refresh");
                    $("#email_smtp_usuario").val($.trim(dato.email_smtp_usuario));
                    $("#email_smtp_clave").val($.trim(dato.email_smtp_clave));
                    $("#dispositivo_activar").val($.trim(dato.dispositivo_activar)).slider("refresh");
                    $("#configuracion_activar").val($.trim(dato.configuracion_activar)).slider("refresh");
                });
            }
            if (opcion === 6) {
                var lista = $("#id_especie").html("");
                $.each(data, function(id, dato) {
                    //alert(activar[(dato.resumen_activar)*1-1]);
                    lista.append("" +
                        "<option value='" + dato.id_especie + "'>" + dato.especie + "|" + dato.riego_mililitros + "|" + dato.riego_frecuencia + "</option>"
                    );
                });
                lista.selectmenu('refresh', true);
            }
        },
        complete: function(data) {
            if (opcion === 3 || opcion === 4) {
                $("#lnCancelar").click();
            }
        },
        error: function(msg) {
            if (opcion === 3 || opcion === 4 || opcion === 5) {
                alert($.trim(msg.responseText));
                cargarDatos(1);
            }
            console.log(msg);
        }
    });
}

$("#tblDatos>tbody").on("click", "tr", function() {
    $(this).find("input").prop("checked", true);
    $("#lnEditar").attr("disabled", false);
    $("#lnBorrar").attr("disabled", false);
});

cargarDatos(1);
$("#lnAgregar").click(function() {
    limpiarCampos();
    cargarDatos(6);
    $("#divNavFormA").show();
    $("#divNavFormM").hide();
});

$("#lnEditar").click(function() {
    $("#divNavFormA").hide();
    $("#divNavFormM").show();
});

$('#lnEditar').click(function(e) {
    if ($("[name=optDato]").is(":checked")) {
        limpiarCampos();
        cargarDatos(6);
        cargarDatos(2);
        return true;
    } else {
        alert("Seleccione un registro!");
        return false;
    }
});

$('#lnBorrar').click(function(e) {
    if ($("[name=optDato]").is(":checked")) {
        var ban = 0;
        $("#tblDatos>tbody>tr").each(function(i) {
            if ($(this).find("td").eq(0).find("input").is(":checked")) {
                if ($.trim(($(this).find("td").eq(1).text()).split(" | ")[3]) == "Inactivo") {
                    ban = 1;
                    return false;
                }
            }
        });
        if (ban === 1) {
            if (confirm("Desea Borrar el Registro Seleccionado?")) {
                cargarDatos(5);
            }
            return true;
        } else {
            alert("El Registro seleccionado está Activo!!!");
        }
    } else {
        alert("Seleccione un Registro!!!");
        return false;
    }
});

$('#lnActualizar').click(function(e) {
    return validarCampos(3);
});

$('#lnInsertar').click(function(e) {
    return validarCampos(4);
});

function validarCampos(opcion) {
    var ban = 0;
    if ($("#descripcion").val().length === 0 ||
        $("#email_smtp_servidor").val().length === 0 ||
        $("#email_smtp_usuario").val().length === 0 ||
        $("#email_smtp_clave").val().length === 0) {
        alert("Complete los campos requeridos!!!");
        ban = 1
    }
    if (ban === 1) {
        return false;
    } else {
        cargarDatos(opcion);
        return true;
    }
}

function limpiarCampos() {
    $("#descripcion").val("");
    $("#email_smtp_servidor").val("");
    $("#email_smtp_usuario").val("");
    $("#email_smtp_clave").val("");
    $("#riego_inicio").val("00:00:00");
    $("#riego_fin").val("23:59:00");
}

$("#riego_inicio").on("focusout", function() {
    var inicio = ($("#riego_inicio").val()).split(":");
    var fin = ($("#riego_fin").val()).split(":");
    var tamanho = inicio.length;
    var t1 = inicio[0] * 60 * 60 + inicio[1] * 60 + (tamanho === 3 ? inicio[2] * 1 : 0);
    var t2 = fin[0] * 60 * 60 + fin[1] * 60 + (tamanho === 3 ? fin[2] * 1 : 0);
    if (t1 > t2) {
        $("#riego_fin").val($("#riego_inicio").val());
    }
    calcularTiempo();
});

$("#riego_fin").on("focusout", function() { //.bind( "change",
    var inicio = ($("#riego_inicio").val()).split(":");
    var fin = ($("#riego_fin").val()).split(":");
    var tamanho = inicio.length;
    var t1 = inicio[0] * 60 * 60 + inicio[1] * 60 + (tamanho === 3 ? inicio[2] * 1 : 0);
    var t2 = fin[0] * 60 * 60 + fin[1] * 60 + (tamanho === 3 ? fin[2] * 1 : 0);
    if (t1 > t2) {
        $("#riego_inicio").val($("#riego_fin").val());
    }
    calcularTiempo();
});

$("#maceta_tipo").change(function() {
    if ($(this).val() == "1") { //Paralepípedo
        $("#formula_volumen").val("Volumen = l.a.h");
        representarLados("l", "a", "h");
        calcularVolumen(1);
    }
    if ($(this).val() == "2") { //Piramide Truncada
        $("#formula_volumen").val("Volumen = 1/3.h.[R+r+√(R.r)]");
        representarLados("R", "r", "h");
        calcularVolumen(2);
    }
    if ($(this).val() == "3") { //Cono Truncado
        $("#formula_volumen").val("Volumen = 1/3.π.hx[R²+r²+(R.r)]");
        representarLados("R", "r", "h");
        calcularVolumen(3);
    }
    if ($(this).val() == "4") {
        $("#formula_volumen").val("Volumen = π.r².h");
        representarLados("--", "r", "h");
        calcularVolumen(4);
    }
});

function representarLados(l, a, h) {
    $("#lblLargo").text("Lado A (" + l + ")");
    $("#lblAncho").text("Lado B (" + a + ")");
    $("#lblAlto").text("Lado C (" + h + ")");
}

function calcularVolumen(tipo) {
    var l = $("#maceta_largo").val() * 1;
    var a = $("#maceta_ancho").val() * 1;
    var h = $("#maceta_alto").val() * 1;
    var volumen = 0;
    if (tipo === 1) { //Paralepípedo
        volumen = l * a * h;
        $("#maceta_volumen").val(volumen);
    }
    if (tipo === 2) { //Piramide Truncada
        //"Volumen = 1/3.h.[R+r+√(R.r)]"
        volumen = Math.round(1 / 3 * h * (l + a + Math.sqrt(l * a)));
        $("#maceta_volumen").val(volumen);
    }
    if (tipo === 3) { //Cono Truncado
        //"Volumen = 1/3.π.hx[R²+r²+(R.r)]"
        volumen = Math.round(1 / 3 * Math.PI * h * (l * l + a * a + (l * a)));
        $("#maceta_volumen").val(volumen);
    }
    if (tipo === 4) { //Cono Truncado
        //"Volumen = π.r².h"
        volumen = Math.round(Math.PI * a * a * h);
        $("#maceta_volumen").val(volumen);
    }
}

$("#id_especie").change(function() {
    var datos = $(this).find("option:selected").text().split("|");
    $("#riego_mililitros").val(datos[1]);
    $("#riego_frecuencia").val(datos[2]);
    calcularTiempo();
    alert("Datos Recuperados!");
});

$("#divFormulario").on("pagecreate", function(event) {
    $("#gotero_caudal").change(function() {
        calcularTiempo();
    });

    $("#gotero_caudal").hover(function() {
        $("#gotero_caudal").prop("title", $("#gotero_caudal").val());
    });

    $("#riego_minutos_espera").hover(function() {
        $("#riego_minutos_espera").prop("title", $("#riego_minutos_espera").val());
    });

    $("#maceta_largo").change(function() {
        calcularVolumen($("#maceta_tipo").val() * 1);
    });
    $("#maceta_ancho").change(function() {
        calcularVolumen($("#maceta_tipo").val() * 1);
    });
    $("#maceta_alto").change(function() {
        calcularVolumen($("#maceta_tipo").val() * 1);
    });
});

function calcularTiempo() {
    var cantidad = $("#riego_mililitros").val() * 1;
    var frecuencia = $("#riego_frecuencia").val() * 1;
    var macetas = $("#maceta_cantidad").val() * 1;
    var caudal = $("#gotero_caudal").val() * 1;
    var tiempo_activo = (cantidad * macetas / frecuencia / caudal).toFixed(2);
    $("#riego_minutos_activo").val(tiempo_activo);
    //alert(tiempo_activo);
    var inicio = ($("#riego_inicio").val()).split(":");
    var fin = ($("#riego_fin").val()).split(":");
    var t1 = inicio[0] * 60 + inicio[1] * 1;
    var t2 = fin[0] * 60 + fin[1] * 1;
    var dh = t2 - t1;
    //console.log(t2);
    //console.log(t1);
    //console.log(dh);
    var tiempo_espera_max = Math.trunc((dh - tiempo_activo * frecuencia) / frecuencia);
    //console.log(tiempo_espera_max);
    if (tiempo_espera_max < 0) {
        tiempo_espera_max = 0;
    };
    $('#riego_minutos_espera').attr("max", tiempo_espera_max).val();
    $('#riego_minutos_espera').slider("refresh");
}