function cargarDatos(opcion) {
    $.ajax({
        url: "riegosDatos.php",
        data: {
            opcion: opcion
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 1) {
                var lista = $("#id_especie").html("");
                $.each(data, function(id, dato) {
                    //alert(activar[(dato.resumen_activar)*1-1]);
                    lista.append("" +
                        "<option value='" + dato.id_especie + "'>" + dato.especie + "</option>"
                    );
                });
                lista.selectmenu('refresh', true);
            }
        },
        complete: function(data) {
            //alert("2");
        },
        error: function(msg) {
            //alert("3");
            console.log(msg);
        }
    });
}

cargarDatos(1);

function validarCampos(opcion) {
    var ban = 0;
    if ($("#nombres").val().length === 0 ||
        $("#apellidos").val().length === 0 ||
        $("#email").val().length === 0 ||
        $("#usuario").val().length === 0) {
        alert("Complete los campos requeridos!!!");
        ban = 1
    }
    if (!validarEmail($("#email").val())) {
        if (ban === 0) {
            alert("Correo Electrónico Inválido!!!");
            ban = 1;
        }
    }
    if ($("#clave").val() !== ($("#claveVerificar").val()) ||
        $('#clave').val().length === 0 ||
        $('#claveVerificar').val().length === 0) {
        if (ban === 0) {
            alert("La clave ingresada no coinciden!");
        }
        ban = 1;
    }
    if (ban === 1) {
        return false;
    } else {
        cargarDatos(opcion);
        alert("Registro Actualizado!!! Por favor, vuelva a ingresar a SistRiego");
        document.location = 'cerrarSesionActual.php';
    }
}

function obtenerFechaHoy() {
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var hoy = fecha.getFullYear() + '-' +
        (mes < 10 ? '0' : '') + mes + '-' +
        (dia < 10 ? '0' : '') + dia;
    return hoy;
}

function enviarReporte(tipo) {
    $("#id_especie_enviar").val($("#id_especie").val());
    $("#fecha_desde_enviar").val($("#fecha_desde").val());
    $("#fecha_hasta_enviar").val($("#fecha_hasta").val());
    $("#agrupar_enviar").val($("#agrupar").val());
    $("#consulta_enviar").val(tipo);
    $("#suministro_enviar").val($("#suministro").val());
    $("#frmImprimir").submit();
}

$("#fecha_desde").on("change", function() { //.bind( "change",
    if ($("#fecha_hasta").val().length === 0) {
        $("#fecha_hasta").val($("#fecha_desde").val());
    }
    var inicio = ($("#fecha_desde").val()).split("-");
    var fin = ($("#fecha_hasta").val()).split("-");
    var f1 = new Date(parseInt(inicio[0]), parseInt(inicio[1] - 1), parseInt(inicio[2]));
    var f2 = new Date(parseInt(fin[0]), parseInt(fin[1] - 1), parseInt(fin[2]));
    if (f1 > f2) {
        $("#fecha_hasta").val($("#fecha_desde").val());
    }
});

$("#fecha_hasta").on("change", function() { //.bind( "change",
    if ($("#fecha_desde").val().length === 0) {
        $("#fecha_desde").val($("#fecha_hasta").val());
    }
    var inicio = ($("#fecha_desde").val()).split("-");
    var fin = ($("#fecha_hasta").val()).split("-");
    var f1 = new Date(parseInt(inicio[0]), parseInt(inicio[1] - 1), parseInt(inicio[2]));
    var f2 = new Date(parseInt(fin[0]), parseInt(fin[1] - 1), parseInt(fin[2]));
    if (f2 < f1) {
        $("#fecha_desde").val($("#fecha_hasta").val());
    }
});

$("#fecha_desde").val(obtenerFechaHoy());
$("#fecha_hasta").val(obtenerFechaHoy());