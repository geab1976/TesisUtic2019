function cargarDatos(opcion) {
    $.ajax({
        url: "especiesDatos.php",
        data: {
            opcion: opcion,
            id: opcion === 1 ? "0" : $("[name=optDato]:checked").val(),
            nombre: opcion === 3 || opcion === 4 ? $("#nombre").val() : "",
            descripcion: opcion === 3 || opcion === 4 ? $("#descripcion").val() : "",
            riego_mililitros: opcion === 3 || opcion === 4 ? $("#riego_mililitros").val() : "",
            riego_frecuencia: opcion === 3 || opcion === 4 ? $("#riego_frecuencia").val() : "",
            ta_min: opcion === 3 || opcion === 4 ? $("#ta_min").val() : "",
            ta_max: opcion === 3 || opcion === 4 ? $("#ta_max").val() : "",
            hs_min: opcion === 3 || opcion === 4 ? $("#hs_min").val() : "",
            hs_max: opcion === 3 || opcion === 4 ? $("#hs_max").val() : "",
            ls_min: opcion === 3 || opcion === 4 ? $("#ls_min").val() : "",
            ls_max: opcion === 3 || opcion === 4 ? $("#ls_max").val() : ""
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
                            "<td><input type='radio' value='" + dato.id_especie + "' name='optDato'></td>" +
                            "<td>" + dato.nombre + " - " + dato.descripcion + "</td>" +
                            "<td>" + dato.riego_mililitros + " ml | " + dato.riego_frecuencia + "</td>" +
                            "<td>(" + dato.ta_min + " a " + dato.ta_max + ") &deg;C | " +
                            "(" + dato.hs_min + " a " + dato.hs_max + ") % | " +
                            "(" + dato.ls_min + " a " + dato.ls_max + ") %</td>" +
                            "</tr>"
                        )
                        .parents("table").table("refresh");
                });
            }
            if (opcion === 2) {
                $.each(data, function(id, dato) {
                    $("#nombre").val(dato.nombre);
                    $("#descripcion").val(dato.descripcion);
                    $("#riego_mililitros").val(dato.riego_mililitros).slider("refresh");
                    $("#riego_frecuencia").val(dato.riego_frecuencia).slider("refresh");
                    $("#ta_min").val($.trim(dato.ta_min)).slider("refresh");
                    $("#ta_max").val($.trim(dato.ta_max)).slider("refresh");
                    $("#hs_max").val($.trim(dato.hs_min)).slider("refresh");
                    $("#hs_max").val($.trim(dato.hs_max)).slider("refresh");
                    $("#ls_max").val($.trim(dato.ls_min)).slider("refresh");
                    $("#ls_max").val($.trim(dato.ls_max)).slider("refresh");
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

function validarCampos(opcion) {
    var ban = 0;
    if ($("#nombre").val().length === 0 ||
        $("#descripcion").val().length === 0) {
        alert("Complete los campos requeridos!!!");
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
        //cargarDatos(1);
        return true;
    }
}

function limpiarCampos() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#riego_mililitros").val("500");
    $("#riego_frecuencia").val("3");
    $("#ta_min").val("4");
    $("#ta_max").val("35");
    $("#hs_min").val("20");
    $("#hs_max").val("70");
    $("#ta_max").val("35");
    $("#ls_min").val("1");
    $("#ls_max").val("90");
}

$("#divFormulario").on("pagecreate", function(event) {
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

/*function validarEmail(email) {
    var re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}*/