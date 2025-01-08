<?php
require_once '../controllers/db.php';
require_once '../models/consultas.php';

header('Content-Type: application/json; charset=utf-8'); // Asegura el tipo de contenido

if (isset($_POST['periodo']) && isset($_POST['usuarioId']) && isset($_POST['carrera'])) {
    try {
        // Recibir los parámetros de los filtros
        $periodo = intval($_POST['periodo']);
        $usuarioId = intval($_POST['usuarioId']);
        $carrera = intval($_POST['carrera']);

        // Crear instancia de la clase Consultas
        $consultas = new Consultas($conn);

        // Obtener el horario filtrado por los parámetros recibidos
        $horario = $consultas->obtenerHorario($periodo, $usuarioId, $carrera);

        if (!empty($horario)) {
            echo json_encode($horario);  // Retorna los datos del horario en formato JSON
        } else {
            echo json_encode(['error' => 'No se encontraron resultados para los filtros dados.']);
        }

    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros para realizar la consulta.']);
}
?>
