<?php
// filtrarPorCarrera.php
include('../../models/consultas.php');
include('../../controllers/db.php'); // Asegúrate de incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['carrera_id'])) {
        $carrera_id = intval($_POST['carrera_id']);
        
        // Crear instancia de la clase Consultas
        $consultas = new Consultas($conn);

        // Obtener usuarios por carrera
        $usuarios = $consultas->obtenerUsuariosPorCarrera($carrera_id);

        // Devolver los usuarios en formato JSON
        echo json_encode($usuarios);
    } else {
        echo json_encode(['error' => 'No se recibió el ID de la carrera.']);
    }
}

?>
