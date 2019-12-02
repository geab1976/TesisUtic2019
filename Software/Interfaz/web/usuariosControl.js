function cargarDatos(opcion) {
    $.ajax({
        url: "usuariosDatos.php",
        data: {
            opcion: opcion,
            id: opcion === 1 ? "0" : $("[name=optDato]:checked").val(),
            nombres: opcion === 3 || opcion === 4 ? $("#nombres").val() : "",
            apellidos: opcion === 3 || opcion === 4 ? $("#apellidos").val() : "",
            email: opcion === 3 || opcion === 4 ? $("#email").val() : "",
            administrador: opcion === 3 || opcion === 4 ? $("#administrador").val() : "",
            activo: opcion === 3 || opcion === 4 ? $("#activo").val() : "",
            usuario: opcion === 3 || opcion === 4 ? $("#usuario").val() : "",
            clave: opcion === 3 || opcion === 4 ? $("#clave").val() : ""
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            //alert("1");
            if (opcion === 1) {
                var tbl = $("#tblDatos tbody").html("");
                $.each(data, function(id, dato) {
                    tbl.append("<tr><td>" + (id_acceso != dato.id_usuario ?
                                "<input type='radio' value='" + dato.id_usuario +
                                "' name='optDato'>" : "") + "</td><td>" +
                            dato.nombres + " " + dato.apellidos + " | " + dato.usuario + " | " +
                            (dato.activo == "1" ? "Activo" : "Inactivo") +
                            "</td><td>" + dato.email + " | " + dato.administrador + "</td></tr>"
                        )
                        .parents("table").table("refresh");
                });
            }
            if (opcion === 2) {
                $.each(data, function(id, dato) {
                    $("#nombres").val(dato.nombres);
                    $("#apellidos").val(dato.apellidos);
                    $("#email").val(dato.email);
                    $("#usuario").val(dato.usuario);
                    //$("#administrador").val($.trim(dato.administrador)).slider("destroy").slider("create").slider(refresh);
                    //.closest("#tblUsuarios").table("refresh").trigger("create")
                    $("#administrador").val($.trim(dato.administrador)).slider("refresh");
                    $("#activo").val($.trim(dato.activo));
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
        return true;
    }
}

function limpiarCampos() {
    $("#nombres").val("");
    $("#apellidos").val("");
    $("#email").val("");
    $("#usuario").val("");
    $("#administrador").val("0");
    $("#activo").val("1");
    $("#clave").val("");
    $("#claveVerificar").val("");
}

function validarEmail(email) {
    var re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}