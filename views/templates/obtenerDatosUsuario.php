<?php
include('../../models/session.php'); // Manejador de sesión
include('../../controllers/db.php');
include('../../models/consultas.php');

// En obtener_usuarios.php
if (isset($_GET['carrera_id'])) {
    // Tu código existente...
    header('Content-Type: application/json');
    echo json_encode($usuarios);
} else {
    // Para depuración, imprime esto en caso de que no se reciba el ID
    echo json_encode(["error" => "ID de carrera no proporcionado."]);
}

?>
