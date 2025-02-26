<?php
require_once '../controllers/db.php'; 
require_once '../models/consultas.php';

header('Content-Type: application/json; charset=utf-8');

// Evitar salida inesperada
ob_start();

if (isset($_POST['carrera_id'])) {
    try {
        $carrera_id = intval($_POST['carrera_id']); 
        $consultas = new Consultas($conn);

        // Llamar a la función con la nueva tabla usuario_has_carrera
        $usuarios = $consultas->obtenerUsuariosPorCarrera($carrera_id);

        if (!empty($usuarios)) {
            echo json_encode($usuarios);
        } else {
            echo json_encode(['error' => 'No se encontraron usuarios para la carrera seleccionada.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Parámetro carrera_id no recibido.']);
}

// Limpiar y evitar salida inesperada
ob_end_flush();
?>
