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
        // Asegurarse de que haya un ID de usuario en la sesión
        return isset($_SESSION['user_id']);
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
        session_destroy(); // Destruir la sesión completamente
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

    // Método para obtener el ID del usuario
    public function getUserId()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
}

// Crear una instancia de la clase SessionManager
$sessionLifetime = 18000; // Ajusta el tiempo de vida de la sesión (mejor si se usa un archivo de configuración)
$sessionManager = new SessionManager($sessionLifetime);

// Obtenemos el ID del usuario a través del SessionManager
$idusuario = $sessionManager->getUserId();

if ($idusuario === null) {
    // Si no hay un usuario logueado, redirigir al login
    header("Location: ../templates/auth-login.php");
    exit;
}
?>
