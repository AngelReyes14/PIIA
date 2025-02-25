<?php
require_once '../controllers/db.php';
require_once '../models/consultas.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($_POST['usuario_usuario_id'])) {
    $usuario_id = intval($_POST['usuario_usuario_id']);
    
    error_log("Usuario recibido: " . $usuario_id); // 🛠 Verifica que el ID llega bien

    try {
        $consultas = new Consultas($conn);
        $carreras = $consultas->obtenerCarrerasPorUsuario($usuario_id);

        error_log("Carreras obtenidas: " . json_encode($carreras)); // 🛠 Verifica si la consulta devuelve datos

        if (!empty($carreras)) {
            echo json_encode($carreras);
        } else {
            echo json_encode(['error' => 'No se encontraron carreras para este usuario.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Parámetro usuario_usuario_id no recibido.']);
}
?>
