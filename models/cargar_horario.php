<?php
require_once '../controllers/db.php';
require_once '../models/consultas.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar que los parámetros necesarios estén presentes y válidos
if (isset($_POST['periodo'], $_POST['usuarioId'], $_POST['carrera']) &&
    is_numeric($_POST['periodo']) && is_numeric($_POST['usuarioId']) && is_numeric($_POST['carrera'])) {
    
    try {
        $periodo = intval($_POST['periodo']);
        $usuarioId = intval($_POST['usuarioId']);
        $carrera = intval($_POST['carrera']);

        // Crear instancia de la clase Consultas
        $consultas = new Consultas($conn);

        // Obtener el horario
        $horario = $consultas->obtenerHorario($periodo, $usuarioId, $carrera);

        // Validar si hay resultados
        if (!empty($horario)) {
            echo json_encode($horario);
        } else {
            echo json_encode(['error' => 'No se encontraron resultados para los filtros dados.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros o los valores no son válidos para realizar la consulta.']);
}
