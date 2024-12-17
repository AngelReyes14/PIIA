<?php
require_once '../controllers/db.php';
require_once '../models/consultas.php';

header('Content-Type: application/json; charset=utf-8'); // Asegura el tipo de contenido

if (isset($_POST['carrera_id'])) {
    try {
        $carrera_id = intval($_POST['carrera_id']);
        $consultas = new Consultas($conn);
        $semestres = $consultas->obtenerSemestresPorCarrera($carrera_id);

        if (!empty($semestres)) {
            echo json_encode($semestres);
        } else {
            echo json_encode(['error' => 'No se encontraron semestres.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Parámetro carrera_id no recibido.']);
}
