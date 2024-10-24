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
    if ($form_type === 'materia') {
        // Crear una instancia de la clase Materia
        $materia = new Materia($conn);
        $materia->handleFormSubmission();  // Método para procesar el formulario de materias
    } elseif ($form_type === 'carrera') {
        // Crear una instancia de la clase Carrera
        $carrera = new Carrera($conn);
        $carrera->handleFormSubmission();  // Método para procesar el formulario de carrera

    } elseif ($form_type === 'usuario') {
        // Crear una instancia de la clase Usuario
        $usuario = new Usuario($conn);
        $usuario->usuarios();  // Método para procesar el formulario de usuario
        
    } elseif ($form_type === 'grupo') {
        // Crear una instancia de la clase Grupo
        $grupo = new Grupo($conn);
        $grupo->handleFormSubmission();  // Método para procesar el formulario de grupo
    } elseif ($form_type === 'materia-grupo') {
        // Crear una instancia de la clase Usuario
        $materiagrupo = new MateriaGrupo($conn);
        $materiagrupo->GrupoMateria();  // Método para procesar el formulario de usuario

    }elseif ($form_type ===  'usuario-carrera'){
        $usuarioCarrera = new UsuarioHasCarrera($conn);
        $usuarioCarrera-> UsuarioCarrera();  // Método para procesar el formulario de usuario-carrera
    } else {
        // Manejar otros formularios o enviar un mensaje de error
        echo "Formulario no reconocido.";
    }
} else {
    echo "Tipo de formulario no especificado.";
}

$conn = $database->getConnection(); // Establecer conexión a la base de datos

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario
    $area = $_POST['area'];
    $fecha = $_POST['fecha'];
    $justificaciones = isset($_POST['justificacion']) ? $_POST['justificacion'] : [];
    $motivo = $_POST['motivo'];
    $horario_inicio = $_POST['start-time'];
    $horario_termino = $_POST['end-time'];
    $hora_incidencia = $_POST['time'];
    $dia_incidencia = $_POST['dia-incidencia'];
    $usuario_id = $sessionManager->getUserId(); // Obtener ID del usuario en sesión

    // Inserta en la base de datos
    $query = "INSERT INTO incidencia_has_usuario (usuario_usuario_id, fecha_solicitada, motivo, horario_inicio, horario_termino, horario_incidencia, dia_incidencia, carrera_carrera_id)
              VALUES (:usuario_id, :fecha, :motivo, :horario_inicio, :horario_termino, :hora_incidencia, :dia_incidencia, :carrera_id)";
    
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':motivo', $motivo);
    $stmt->bindParam(':horario_inicio', $horario_inicio);
    $stmt->bindParam(':horario_termino', $horario_termino);
    $stmt->bindParam(':hora_incidencia', $hora_incidencia);
    $stmt->bindParam(':dia_incidencia', $dia_incidencia);
    $stmt->bindParam(':carrera_id', $area); // Asegúrate de que el campo 'area' tenga el ID correcto
    
    // Ejecuta la consulta
    if ($stmt->execute()) {
        // Si la inserción fue exitosa, puedes redirigir o enviar una respuesta
        echo json_encode(['status' => 'success', 'message' => 'Datos enviados correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar los datos.']);
    }
} else {
    // Si no se accede al archivo mediante POST
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}

?>
