<?php
class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    // Constructor para inicializar los parámetros de conexión
    public function __construct($host, $dbname, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->connect(); // Llamar al método de conexión en la inicialización
    }

    // Método para establecer la conexión
    private function connect()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            // Establecer el modo de error de PDO para que lance excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Si la conexión es exitosa, opcionalmente puedes mostrar un mensaje
            //echo "Conexión exitosa";
        } catch (PDOException $e) {
            // Si hay un error en la conexión, lo capturamos y mostramos el mensaje
            echo "Error de conexión: " . $e->getMessage();
            exit; // Finalizar el script si hay un error de conexión
        }
    }

    // Método para obtener la conexión
    public function getConnection()
    {
        return $this->conn;
    }
}

// Uso de la clase Database
$database = new Database('localhost', 'piia', 'root', '1234');
$conn = $database->getConnection(); // Obtener la conexión para usarla en otros lugares
