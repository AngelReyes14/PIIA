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
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="css/simplebar.css">
    <!-- Fonts CSS -->
    <link rel="stylesheet" href="css/recuperarPassword.css">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="css/feather.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="css/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
</head>

<body class="light">
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
                <form class="text-center">
                <div>
                    <h1 class ="title"> Recuperar mi contraseña.</h1>                    
                </div>
                <div>
                    <h1 class ="text"> Proporcione la dirección de correo electrónico asociada con su cuenta para recuperar su contraseña.</h1>
                </div>
                <div class="form-group">
                        <div class="input-container">
                        <img src="assets/icon/email.png" alt="User Icon">
                        <input type="email" id="inputUser" class="form-control form-control-lg input_text_login input_login"
                            placeholder="Correo Electrónico">
                        </div>
                    </div>
                    <a href="auth-login.php" class="btn btn-lg btn-success" id="regresar">Regresar</a>
                    <button class="btn btn-lg btn-light" id="recuperar">Recuperar</button>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</body>



</html>