var motivo = "<option value='0'>TODO</option>";

function cargarDatos(opcion) {
    $.ajax({
        url: "historicosDatos.php",
        data: {
            opcion: opcion,
            id_historico_motivo: $("#id_historico_motivo").val(),
            motivo: $("#id_historico_motivo>option:selected").text(),
            fecha_desde: $("#fecha_desde").val(),
            fecha_hasta: $("#fecha_hasta").val(),
            filtro: $("#filtro").val()
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 1) {
                var lista = $("#id_historico_motivo");
                $.each(data, function(id, dato) {
                    //alert(activar[(dato.resumen_activar)*1-1]);
                    motivo += "<option value='" + dato.id + "'>" + dato.motivo + "</option>";
                });
                lista.html(motivo).selectmenu('refresh', true);
            }
            if (opcion === 2) {
                alert($.trim(data));
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

function ejecutarAccion() {
    var id = $("#id_historico_motivo").val();
    var accion = $("#accion").val();
    var desde = $("#fecha_desde").val();
    var hasta = $("#fecha_hasta").val();
    var filtro = $("#filtro").val();

    switch (accion) {
        case "1":
        case "2":
            $("#id_historico_motivo_enviar").val(id);
            $("#fecha_desde_enviar").val(desde);
            $("#fecha_hasta_enviar").val(hasta);
            $("#filtro_enviar").val(filtro);
            $("#accion_enviar").val(accion);
            $("#frmImprimir").submit();
            break;
        case "3":
            if (confirm("¿Desea Borrar Registros con los Datos Ingresados?")) {
                cargarDatos(2);
            }
            break;
        case "4":
            if (confirm("¿Desea Borrar Todos Registros?")) {
                cargarDatos(3);
            }
            break;
    }
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