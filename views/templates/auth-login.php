<?php
session_start();

// Verificar si hay un mensaje de error en la sesión
if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error']; // Obtener el mensaje de error
    unset($_SESSION['error']); // Eliminar el mensaje de error de la sesión
} else {
    $errorMessage = null; // No hay error
}

// Recuperar el correo electrónico si existe en la cookie
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/icon/icon_piia.png">
    <title>PIIA - Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/simplebar.css">
    <link rel="stylesheet" href="css/feather.css">
    <link rel="stylesheet" href="css/daterangepicker.css">
    <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="light" data-error="<?php echo htmlspecialchars($errorMessage); ?>">
    <div class="wrapper vh-100">
        <div class="row align-items-center h-100 justify-content-center">
            <div class="col-lg-8 col-md-9 mx-auto">
                <div class="card card_shadow p-4">
                    <div class="row">
                        <!-- Imagen de PIIA -->
                        <div class="col-lg-6 col-md-6 text-center d-flex align-items-center justify-content-center">
                            <img src="assets/images/PIIA_oscuro 1.png" alt="Imagen del PIIA" class="img_login_first">
                        </div>
                        <!-- Formulario y logo del Tec -->
                        <div class="col-lg-6 col-md-6">
                            <div class="text-center">
                                <img src="assets/images/logo-teschi.png" alt="logo del tecnológico" class="img_login_second mb-4">
                            </div>

                            <!-- FORMULARIO DE LOGIN -->
                            <form class="text-center" action="../../models/auth.php" method="POST">
                                <div class="form-group">
                                    <div class="input-container">
                                        <img src="assets/icon/user.png" alt="User Icon">
                                        <input type="email" id="inputUser" name="email" class="form-control form-control-lg input_text_login input_login"
                                            placeholder="Correo Electrónico" required value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-container">
                                        <img src="assets/icon/password.png" alt="Password Icon">
                                        <input type="password" id="inputPassword" name="password"
                                            class="form-control form-control-lg input_text_login input_login" placeholder="Contraseña"
                                            required>
                                    </div>
                                </div>
                                <div class="checkbox mb-3">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="remember_me" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                                        <span class="checkbox-box"></span>
                                    </label>
                                    <label class="input_login_checkbox">Recordar mis datos</label>
                                    <label> | </label>
                                    <a href="recuperarPassword.php" class="align-items-center justify-content-center input_login_checkbox">Olvidé mi contraseña</a>
                                </div>
                                <button type="submit" class="btn btn-lg btn-light btn-block" id="registro">Ingresar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/alerts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
