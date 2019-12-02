<?php
session_start();
if (!empty($_SESSION['usuario'])) {
    $lifetime = 1800;
    setcookie(session_name(), session_id(), time() + $lifetime, "/");
    ?>
<!DOCTYPE html>
<html class="ui-mobile">

<head>
    <?php
        include("head_html.php");
        ?>
    <link href="css/estilo_tabla.css" rel="stylesheet" />
    <script type="text/javascript">
    var id_acceso = "<?php echo $_SESSION['id'] ?>";
    </script>
</head>

<body>
    <div data-role="page" data-theme="a" id="divFormulario">
        <div data-role="header" class="sr-cuentausuario" data-theme="a">
            <center>
                <span style="font-size:15pt;color:orange;color: orange; text-shadow: black 0.1em 0.1em 0.2em;">Perfil
                    del Usuario</span><br>
                <span style="margin-top:0px;color: white; text-shadow: black 0.1em 0.1em 0.2em;">Actualizar Datos</span>
            </center>
        </div>
        <div data-role="main" class="ui-conten" style="margin:10px 10px 10px 10px;">
            <fieldset data-role="controlgroup" style="padding:5px 5px 5px 5px;" data-theme="a">
                <legend style="text-align:center;"><b>Datos de Mi Usuario</b></legend>
                <div data-role="fieldcontain">
                    <label for="nombres">Nombres:</label>
                    <input type="text" name="nombres" id="nombres" value="" placeholder="Ingrese datos"
                        data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" value="" placeholder="Ingrese datos"
                        data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="email">E-m@il:</label>
                    <input type="email" name="email" id="email" value="" placeholder="usuario@dominio"
                        data-clear-btn="true" required />
                </div>

                <!--div data-role="fieldcontain">
                    <label for="administrador">Administrador:</label>
                    <select name="administrador" id="administrador" data-role="slider">
                        <option value="1">Sí</option>
                        <option value="0" selected>No</option>
                    </select>
                </div-->

                <div data-role="fieldcontain">
                    <label for="usuario">Usuario:</label>
                    <input type="text" name="usuario" id="usuario" value="" placeholder="Ingrese datos"
                        data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="clave">Clave Nueva:</label>
                    <input type="password" name="clave" id="clave" value="" placeholder="Ingrese datos"
                        data-clear-btn="true" required />
                </div>

                <div data-role="fieldcontain">
                    <label for="claveVerificar">Confirmar Clave:</label>
                    <input type="password" name="claveVerificar" id="claveVerificar" value=""
                        placeholder="Ingrese datos" data-clear-btn="true" required />
                </div>

                <!--div data-role="fieldcontain">
                    <label for="activo">Activar:</label>
                    <select name="activo" id="activo" data-role="slider">
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div-->
            </fieldset>

            <div data-role="navbar" class="ui-responsive ui-shadow" id="divNavFormM"
                style="margin: 2px 50px 50px 50px;">
                <ul>
                    <li><a href="#divFormulario" data-icon="edit" data-transition="flip" id="lnActualizar"
                            data-theme="a" title="ACTUALIZAR"></a></li>
                    <li><a href="#divFormulario" data-icon="forward" data-transition="flip" id="lnCancelar"
                            data-theme="a" title="REFRESCAR DATOS"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./cuentaUsuarioControl.js"></script>
</body>

</html>
<?php
} else {
    include('accesoRestringido.php');
}
?>