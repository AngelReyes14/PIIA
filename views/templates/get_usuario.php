<?php
// get_usuario.php
header('Content-Type: application/json');

include('../../models/session.php');
include('../../controllers/db.php'); // Asegúrate de que este archivo incluye la conexión a la base de datos
include('../../models/consultas.php'); // Incluir la clase de consultas

// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Obtener el usuario_id desde la solicitud GET
$idusuario = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 1;

// Obtener el usuario
$usuario = $consultas->obtenerUsuarioPorId($idusuario);

// Obtener la carrera del usuario
if ($usuario) {
    $carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);
    if ($carrera) {
        $usuario['carrera_id'] = $carrera['carrera_id'];
        $usuario['nombre_carrera'] = $carrera['nombre_carrera'];
    } else {
        $usuario['carrera_id'] = null;
        $usuario['nombre_carrera'] = 'Carrera no asignada';
    }
    echo json_encode(['success' => true, 'usuario' => $usuario]);
} else {
    // Intentar obtener el primer usuario si no se encuentra el actual
    $usuario = $consultas->obtenerUsuarioPorId(1);
    if ($usuario) {
        $carrera = $consultas->obtenerCarreraPorUsuarioId(1);
        if ($carrera) {
            $usuario['carrera_id'] = $carrera['carrera_id'];
            $usuario['nombre_carrera'] = $carrera['nombre_carrera'];
        } else {
            $usuario['carrera_id'] = null;
            $usuario['nombre_carrera'] = 'Carrera no asignada';
        }
        echo json_encode(['success' => true, 'usuario' => $usuario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró ningún usuario.']);
    }
}
?>
