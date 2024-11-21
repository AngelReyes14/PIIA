<?php
// Incluir la conexión a la base de datos y las clases necesarias
require_once '../controllers/db.php';
require_once '../models/consultas.php';

// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Verificar el tipo de formulario
if (isset($_POST['form_type'])) {
    $form_type = $_POST['form_type'];

    // Manejar la recepción de los datos según el tipo de formulario
    if ($form_type === 'validacion-incidencia') {
        // Validación de la incidencia
        if (isset($_POST['incidencia_id'], $_POST['validacion'], $_POST['estado'])) {
            $incidenciaId = (int)$_POST['incidencia_id']; // ID de la incidencia
            $validacion = $_POST['validacion']; // Tipo de validación (division, subdireccion, rh)
            $estado = (int)$_POST['estado'];  // Estado (1: Aceptado, 2: Rechazado, 3: En espera)

            // Determinar el campo a actualizar basado en la validación
            $campoValidacion = '';
            switch ($validacion) {
                case 'division':
                    $campoValidacion = 'validacion_divicion_academica';
                    break;
                case 'subdireccion':
                    $campoValidacion = 'validacion_subdireccion';
                    break;
                case 'rh':
                    $campoValidacion = 'validacion_rh';
                    break;
                default:
                    echo "Campo de validación no válido.";
                    exit;
            }

            // Actualizar solo la incidencia seleccionada
            $query = "UPDATE incidencia_has_usuario 
                      SET $campoValidacion = :estado 
                      WHERE incidencia_incidenciaid = :incidenciaId";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':incidenciaId', $incidenciaId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error al actualizar la incidencia.";
            }
        } else {
            echo "Datos incompletos para la actualización.";
        }
    } else {
        // Manejar otros formularios o enviar un mensaje de error
        echo "Formulario no reconocido.";
    }
} else {
    echo "Tipo de formulario no especificado.";
}
?>
