<?php
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Finally, destroy the session.
session_destroy();
?>
<!DOCTYPE html>
<html class="ui-mobile">

<head>
    <?php
    include("head_html.php"); 
    ?>
</head>

<body>
    <div data-role="page" data-theme="a" style="background-color: white;">
        <!-- /header -->
        <section class="container">
            <center>
                <div
                    style="background-image: url('images/gotero_maceta_inicio.png'); background-repeat: no-repeat;background-position: center;background-size: 220px">
                    <br><br><br><br><br><br><br><br>
                    <img src="images/riego_sol.png" height="50"
                        style="-webkit-filter: drop-shadow(3px 3px 3px white );filter: drop-shadow(3px 3px 3px white);"><br>
                    <span
                        style="font-weight: 700;text-align: center;margin: 0 auto;font-size: 20pt; color: red; text-shadow: white 1px 1px 0, blue 2px 2px 0,white 0.1em 0.1em 0.2em;">
                        <b>SistRiego</b>
                    </span>
                    <br><br>
                </div>
                <!--label for="txtUsuario"
                            style="font-weight: 700;font-size:15pt;color: black; text-shadow: white 0.1em 0.1em 0.1em;">USUARIO</label-->
                <div style="width: 220px">
                    <input type="text" name="txtUsuario" id="txtUsuario" value="" data-clear-btn="true" data-theme="a"
                        placeholder="Ingrese Usuario" />
                    <!--label for="password"
                            style="font-weight: 700;font-size:15pt;color: black; text-shadow: white 0.1em 0.1em 0.1em;">CLAVE</label-->
                    <input type="password" name="txtClave" id="txtClave" value="" data-clear-btn="true" data-theme="a"
                        placeholder="Ingrese Clave" />
                    <a href="#dlg-invalid-credentials" data-rel="popup" data-transition="pop" data-position-to="window"
                        id="btn-submit" class="ui-btn ui-btn-b ui-corner-all mc-top-margin-1-5"
                        style="display: none;font-size: 7pt;"></a>

                    <a href="#" id="btnIngresar" class="ui-btn ui-btn-a ui-corner-all" data-transition="flip"
                        style="font-size:12pt;width:90px;height:15px">ACCEDER</a>

                    <a href="paginaPrincipal.php" style="display: none" id="btnAcceso"></a>

                    <div data-role="popup" id="dlg-invalid-credentials" data-dismissible="false"
                        style="max-width: 400px;" data-theme="a">
                        <div role="main" class="ui-content">
                            <h3 class="mc-text-danger">Acceso Denegado!</h3>
                            <p>- El usuario o la clave ingresada no es correcta, o</p>
                            <p>- La cuenta de usuario está deshabilitada.</p>
                            <div class="mc-text-center">
                                <a href="#" data-rel="back"
                                    class="ui-btn ui-corner-all ui-shadow ui-btn-b mc-top-margin-1-5">OK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </center>
            <!-- /content -->
        </section>
        <script type="text/javascript" src="./loginControl.js"></script>
    </div>
    <!-- /page -->
</body>

</html>