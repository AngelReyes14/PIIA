<?php
class SessionManager
{
    private $sessionLifetime;

    // Constructor para establecer el tiempo de vida de la sesión
    public function __construct($sessionLifetime)
    {
        // Verificar si ya hay una sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->sessionLifetime = $sessionLifetime;
    }

    // Método para verificar si la sesión está activa
    public function isSessionActive()
    {
        return isset($_SESSION['email']);
    }

    // Método para verificar si la sesión ha caducado
    public function isSessionExpired()
    {
        if (isset($_SESSION['last_activity'])) {
            $inactiveTime = time() - $_SESSION['last_activity'];
            return $inactiveTime > $this->sessionLifetime;
        }
        return false;
    }

    // Método para actualizar el tiempo de la última actividad
    public function updateLastActivity()
    {
        $_SESSION['last_activity'] = time();
    }

    // Método para destruir la sesión
    public function destroySession()
    {
        session_unset();  // Eliminar todas las variables de sesión
        session_destroy(); // Destruir la sesión
    }

    // Redirigir al login si no hay sesión o ha caducado
    public function checkSessionAndRedirect($redirectPath)
    {
        if (!$this->isSessionActive() || $this->isSessionExpired()) {
            if ($this->isSessionExpired()) {
                $_SESSION['error'] = 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.';
                $_SESSION['session_expired'] = true; // Indica que la sesión ha expirado
                header("Location: $redirectPath");
                exit;
            } else {
                $this->destroySession(); // Destruir solo si no hay sesión activa
                header("Location: $redirectPath");
                exit;
            }
        } else {
            // Si la sesión está activa, actualizamos el tiempo de la última actividad
            $this->updateLastActivity();
        }
    }

    // Método para cerrar sesión y redirigir
    public function logoutAndRedirect($redirectPath)
    {
        $this->destroySession(); // Cerrar sesión
        header("Location: $redirectPath"); // Redirigir a la página deseada
        exit;
    }
}

// Uso de la clase
$sessionLifetime = 18000; // Ajusta el tiempo de vida de la sesión
$sessionManager = new SessionManager($sessionLifetime);

// Verificar si la sesión ha caducado o no está activa y redirigir al login
$sessionManager->checkSessionAndRedirect('../templates/auth-login.php');

// Cargar el archivo JavaScript
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
echo "<script src='sessionAlert.js'></script>";

// Ejemplo de uso del método logoutAndRedirect (puedes llamarlo cuando se haga clic en "Cerrar sesión")
if (isset($_POST['logout'])) {
    $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}
?>
