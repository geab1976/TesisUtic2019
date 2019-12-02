        $("#btnIngresar").click(function() {
            //alert("SI ENTRA");
            var usuario = $.trim($("#txtUsuario").val());
            var clave = $.trim($("#txtClave").val());
            $.ajax({
                type: "POST",
                url: "loginDatos.php",
                data: ({
                    usuario: usuario,
                    clave: md5(clave)
                }),
                cache: false,
                dataType: "text",
                success: function(data) {
                    if ($.trim(data) == '1') {
                        $("#btnAcceso").click();
                    } else {
                        $("#btn-submit").click();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                }
            });
        });
        $("#txtClave").keypress(function(e) {
            if (e.which == 13) {
                $("#btnIngresar").click();
            }
        });