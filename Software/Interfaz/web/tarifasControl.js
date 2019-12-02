function cargarDatos(opcion) {
    $.ajax({
        url: "tarifasDatos.php",
        data: {
            opcion: opcion,
            id: opcion === 1 ? "0" : $("[name=optDato]:checked").val(),
            fecha_inicio: opcion === 3 || opcion === 4 ? $("#fecha_inicio").val() : "",
            fecha_fin: opcion === 3 || opcion === 4 ? $("#fecha_fin").val() : "",
            tarifa: opcion === 3 || opcion === 4 ? $("#tarifa").val() : ""
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            //alert("1");
            if (opcion === 1) {
                var tbl = $("#tblDatos tbody").html("");
                $.each(data, function(id, dato) {
                    tbl.append("" +
                            "<tr>" +
                            "<td><input type='radio' value='" + dato.id_tarifa_agua + "' name='optDato'></td>" +
                            "<td>" + (dato.fecha_inicio).split("-")[2] + "/" + (dato.fecha_inicio).split("-")[1] + "/" + (dato.fecha_inicio).split("-")[2] + "</td>" +
                            "<td>" + (dato.fecha_fin).split("-")[2] + "/" + (dato.fecha_fin).split("-")[1] + "/" + (dato.fecha_fin).split("-")[2] + "</td>" +
                            "<td>" + dato.tarifa + "</td>" +
                            "</tr>"
                        )
                        .parents("table").table("refresh");
                });
            }
            if (opcion === 2) {
                $.each(data, function(id, dato) {
                    $("#fecha_inicio").val(dato.fecha_inicio);
                    $("#fecha_fin").val(dato.fecha_fin);
                    $("#tarifa").val(dato.tarifa);
                });
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
        cargarDatos(2);
        return true;
    } else {
        alert("Seleccione un registro!");
        return false;
    }
});

$('#lnBorrar').click(function(e) {
    if ($("[name=optDato]").is(":checked")) {
        if (confirm("Desea Borrar el Registro Seleccionado?")) {
            cargarDatos(5);
            //alert("Registro Borrado!!!");
        }
        return true;
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

$("#fecha_inicio").on("change", function() { //.bind( "change",
    if ($("#fecha_fin").val().length === 0) {
        $("#fecha_fin").val($("#fecha_inicio").val());
    }
    var inicio = ($("#fecha_inicio").val()).split("-");
    var fin = ($("#fecha_fin").val()).split("-");
    var f1 = new Date(parseInt(inicio[2]), parseInt(inicio[1] - 1), parseInt(inicio[0]));
    var f2 = new Date(parseInt(fin[2]), parseInt(fin[1] - 1), parseInt(fin[0]));
    if (f1 > f2) {
        $("#fecha_fin").val($("#fecha_inicio").val());
    }
});

$("#fecha_fin").on("change", function() { //.bind( "change",
    if ($("#fecha_inicio").val().length === 0) {
        $("#fecha_inicio").val($("#fecha_fin").val());
    }
    var inicio = ($("#fecha_inicio").val()).split("-");
    var fin = ($("#fecha_fin").val()).split("-");
    var f1 = new Date(parseInt(inicio[2]), parseInt(inicio[1] - 1), parseInt(inicio[0]));
    var f2 = new Date(parseInt(fin[2]), parseInt(fin[1] - 1), parseInt(fin[0]));
    if (f2 < f1) {
        $("#fecha_inicio").val($("#fecha_fin").val());
    }
});


function validarCampos(opcion) {
    var ban = 0;
    if ($("#fecha_inicio").val().length === 0 ||
        $("#fecha_fin").val().length === 0 || $("#tarifa").val().length === 0) {
        alert("Complete los campos requeridos!!!");
        ban = 1
    }
    if ($("#tarifa").val() <= 0) {
        alert("La tarifa debe tener valor mayor a 0!!!");
        ban = 1
    }
    /*if (!validarEmail($("#email").val())) {
        if (ban === 0) {
            alert("Correo Electr�nico Inv�lido!!!");
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
    }*/
    if (ban === 1) {
        return false;
    } else {
        cargarDatos(opcion);
        // alert(opcion === 3 ? "Registro Actualizado!!!" : "Registro Agregado!!!");
        cargarDatos(1);
        return true;
    }
}

function limpiarCampos() {
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
    $("#tarifa").val("0");
}

/*$("#divFormulario").on("pagecreate", function(event) {
    $("#riego_mililitros").hover(function() {
        $("#riego_mililitros").prop("title", $("#riego_mililitros").val());
    });

    $("#riego_frecuencia").hover(function() {
        $("#riego_frecuencia").prop("title", $("#riego_frecuencia").val());
    });

    $("#ta_min").hover(function() {
        $("#ta_min").prop("title", $("#ta_min").val());
    });
    $("#ta_max").hover(function() {
        $("#ta_max").prop("title", $("#ta_max").val());
    });

    $("#hs_min").hover(function() {
        $("#hs_min").prop("title", $("#hs_min").val());
    });
    $("#hs_max").hover(function() {
        $("#hs_max").prop("title", $("#hs_max").val());
    });

    $("#ls_min").hover(function() {
        $("#ls_min").prop("title", $("#ls_min").val());
    });
    $("#ls_max").hover(function() {
        $("#ls_max").prop("title", $("#ls_max").val());
    });
});
*/
/*function validarEmail(email) {
    var re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}*/