<?php
require_once '../controllers/db.php'; // Incluye la conexión a la base de datos
require_once '../models/consultas.php'; // Incluye el archivo de consultas

header('Content-Type: application/json; charset=utf-8'); // Asegura que la respuesta sea JSON

if (isset($_POST['carrera_id']) && isset($_POST['periodo_id'])) {
    try {
        $carrera_id = intval($_POST['carrera_id']); // Sanitiza el valor recibido
        $periodo_id = intval($_POST['periodo_id']); // Sanitiza el valor del periodo
        $consultas = new Consultas($conn); // Crea una instancia de la clase Consultas

        // Llama a la función para obtener usuarios por carrera
        $usuarios = $consultas->obtenerDocentesPorCarrera($carrera_id);

        if (!empty($usuarios)) {
            // Para cada docente, obtenemos sus evaluaciones en el periodo seleccionado
            foreach ($usuarios as &$usuario) {
                $evaluaciones = $consultas->obtenerEvaluacionesPorDocenteYPeriodo($usuario['usuario_id'], $periodo_id);
                $usuario['evaluacion_tecnm'] = isset($evaluaciones['evaluacion_tecnm']) ? $evaluaciones['evaluacion_tecnm'] : null;
                $usuario['evaluacion_estudiantil'] = isset($evaluaciones['evaluacion_estudiantil']) ? $evaluaciones['evaluacion_estudiantil'] : null;
            }

            // Retorna los usuarios con las evaluaciones en formato JSON
            echo json_encode($usuarios);
        } else {
            // Mensaje en caso de no encontrar usuarios
            echo json_encode(['error' => 'No se encontraron docentes para la carrera seleccionada.']);
        }
    } catch (Exception $e) {
        // Manejo de errores
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    // Mensaje en caso de que no se reciba el parámetro carrera_id o periodo_id
    echo json_encode(['error' => 'Parámetro carrera_id o periodo_id no recibido.']);
}
?>
