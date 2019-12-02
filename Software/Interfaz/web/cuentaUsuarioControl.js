function cargarDatos(opcion) {
    $.ajax({
        url: "cuentaUsuarioDatos.php",
        data: {
            opcion: opcion,
            id: opcion === 1 ? "0" : id_acceso,
            nombres: opcion === 3 || opcion === 4 ? $("#nombres").val() : "",
            apellidos: opcion === 3 || opcion === 4 ? $("#apellidos").val() : "",
            email: opcion === 3 || opcion === 4 ? $("#email").val() : "",
            //administrador: opcion === 3 || opcion === 4 ? $("#administrador").val() : "",
            //activo: opcion === 3 || opcion === 4 ? $("#activo").val() : "",
            usuario: opcion === 3 || opcion === 4 ? $("#usuario").val() : "",
            clave: opcion === 3 || opcion === 4 ? $("#clave").val() : ""
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 2) {
                $.each(data, function(id, dato) {
                    $("#nombres").val(dato.nombres);
                    $("#apellidos").val(dato.apellidos);
                    $("#email").val(dato.email);
                    $("#usuario").val(dato.usuario);
                    //$("#administrador").val($.trim(dato.administrador)).slider("refresh");
                    //$("#activo").val($.trim(dato.activo));
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
    if (confirm("Desea actualizar sus datos?")) {
        return validarCampos(3);
    } else {
        return false;
    }
});

$('#lnCancelar').click(function(e) {
    if (confirm("Desea volver a cargar sus datos previos?")) {
        cargarDatos(2);
        return true;
    } else {
        return false;
    }
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
        alert("Registro Actualizado!!! Por favor, vuelva a ingresar a SistRiego");
        document.location = 'cerrarSesionActual.php';
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