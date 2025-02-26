<?php
include('../../controllers/db.php');
include('../../models/consultas.php');



header('Content-Type: application/json');

if (isset($_GET['idusuario'])) {
    $idusuario = intval($_GET['idusuario']);
    error_log("ID usuario recibido: " . $idusuario);

    $sql = "SELECT 
                u.usuario_id, 
                u.nombre_usuario, 
                u.apellido_p, 
                u.apellido_m, 
                u.edad, 
                u.correo, 
                u.fecha_contratacion, 
                u.numero_empleado, 
                u.grado_academico, 
                u.cedula, 
                u.imagen_url, 
                c.nombre_carrera 
            FROM usuario u
            JOIN carrera c ON u.carrera_carrera_id = c.carrera_id
            WHERE u.usuario_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        } else {
            echo json_encode(["error" => "No se encontró el usuario."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Error en la consulta"]);
    }
} else {
    echo json_encode(["error" => "ID de usuario no proporcionado"]);
}

$conn->close();
?>