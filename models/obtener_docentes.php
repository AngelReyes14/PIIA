<?php
require_once '../controllers/db.php'; // Incluye la conexión a la base de datos
require_once '../models/consultas.php'; // Incluye el archivo de consultas

header('Content-Type: application/json; charset=utf-8'); // Asegura que la respuesta sea JSON

if (isset($_POST['carrera_id'])) {
    try {
        $carrera_id = intval($_POST['carrera_id']); // Sanitiza el valor recibido
        $consultas = new Consultas($conn); // Crea una instancia de la clase Consultas

        // Llama a la función para obtener usuarios por carrera
        $usuarios = $consultas->obtenerDocentesPorCarrera($carrera_id);

        if (!empty($usuarios)) {
            // Retorna los usuarios en formato JSON si hay resultados
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
    // Mensaje en caso de que no se reciba el parámetro carrera_id
    echo json_encode(['error' => 'Parámetro carrera_id no recibido.']);
}
?>
