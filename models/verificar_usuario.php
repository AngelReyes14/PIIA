<?php
// Incluir la clase Database
require_once '../controllers/db.php';

// Crear una instancia de la clase Database
$database = new Database('localhost', 'piia', 'root', '1234');
$conn = $database->getConnection();

// Obtener el idusuario de la solicitud
$idusuario = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

// Consulta para verificar si el usuario existe
$sql = "SELECT COUNT(*) AS total FROM usuario WHERE usuario_id = :usuario_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $idusuario, type: PDO::PARAM_INT);
$stmt->execute();

// Obtener el resultado
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode(['existe' => $result['total'] > 0]);
