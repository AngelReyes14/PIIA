<?php
class User
{
    private $conn;

    // Constructor para recibir la conexión de la base de datos
    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // Método para iniciar sesión
    public function login($email, $password)
    {
        $hashedPassword = hash('sha256', $password);
        
        // Consulta SQL para verificar si el usuario existe
        $query = "SELECT * FROM usuario WHERE correo = :email AND password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Verificar si las credenciales son correctas
        if ($stmt->rowCount() > 0) {
            // Usuario encontrado, iniciar sesión
            $_SESSION['email'] = $email;
            $_SESSION['last_activity'] = time(); // Guardar el tiempo actual
            return true; // Inicio de sesión exitoso
        }
        return false; // Credenciales incorrectas
    }
}

// Manejo de la sesión
session_start(); // Iniciar la sesión

// Incluir archivo de conexión
include('../controllers/db.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Crear instancia de la clase User
    $user = new User($conn);

    // Código de login aquí...
    if ($user->login($email, $password)) {
        // Verificar si se ha seleccionado "Recordar mis datos"
        if (isset($_POST['remember_me'])) {
            // Establecer una cookie que expire en 30 días
            setcookie('email', $email, time() + (30 * 24 * 60 * 60), "/"); // Expira en 30 días
        } else {
            // Si no se seleccionó, eliminar la cookie
            if (isset($_COOKIE['email'])) {
                setcookie('email', '', time() - 3600, "/"); // Eliminar la cookie
            }
        }

        // Redirigir al index.php si el login es exitoso
        header('Location: ../views/templates/index.php');
        exit;
    } else {
        // Credenciales incorrectas, redirigir al login con un mensaje de error
        $_SESSION['error'] = 'Usuario o contraseña incorrectos.';
        header('Location: ../views/templates/auth-login.php'); // Redirigir a la página de login
        exit;
    }
}
