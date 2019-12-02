function cargarDatos(opcion) {
    $.ajax({
        url: "fechaHoraDatos.php",
        data: {
            opcion: opcion,
            fecha: $("#fecha").val(),
            hora: $("#hora").val()
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 2) {
                $.each(data, function(id, dato) {
                    $("#fecha").val(dato.fecha);
                    $("#hora").val(dato.hora);
                });
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

cargarDatos(2);

$('#lnActualizar').click(function(e) {
    if (confirm("Desea actualizar la fecha y hora del dispositivo?")) {
        return validarCampos(3);
    } else {
        return false;
    }
});

$('#lnCancelar').click(function(e) {
    if (confirm("Desea volver a cargar los datos?")) {
        cargarDatos(2);
        return true;
    } else {
        return false;
    }
});

function validarCampos(opcion) {
    var ban = 0;
    if ($("#fecha").val().length === 0 ||
        $("#hora").val().length === 0) {
        alert("Complete los campos requeridos!!!");
        ban = 1
    }
    if (ban === 1) {
        return false;
    } else {
        var fecha = ($("#fecha").val()).split("-");
        var fecha_hora = fecha[0] + ";" + fecha[1] + ";" + fecha[2] + ";"; //str.replace(/\//g, "red");
        var hora = ($("#hora").val()).split(":");
        fecha_hora += hora[0] + ";" + hora[1] + ";" + hora[2];
        //alert(fecha_hora);
        $.ajax({
            async: true,
            cache: false,
            url: "/arduino/" + fecha_hora,
            success: function(result) {
                cargarDatos(3);
                console.log($.trim(result));
            },
            error: function(error) {
                console.log(error);
            }
        });
        alert("Fecha y Hora del Dispositivo Actualizado");
        return true;
        document.location = 'fechaHoraHome.php';
    }
}

function limpiarCampos() {
    $("#fecha").val("");
    $("#hora").val("");
}