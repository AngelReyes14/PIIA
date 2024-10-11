<?php
class Consultas {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function obtenerUsuarioPorId($usuario_id) {
        $sql = "select * from vista_usuarios where usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCarreras() {
        $query = "SELECT carrera_id, nombre_carrera FROM carrera"; // Asegúrate de que la tabla y las columnas sean correctas
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    // Método para obtener los semestres
    public function obtenerSemestres() {
        $query = "SELECT semestre_id, nombre_semestre FROM semestre"; // Asegúrate de que este es el nombre de tu tabla y columnas
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTurnos(){
        $query = "SELECT idturno, descripcion FROM turno";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPeriodo(){
        $query = "SELECT periodo_id, descripcion FROM periodo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDatosGrupo(){
        $query = "SELECT * FROM datosgrupo";        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Agregar un método en Consultas para obtener la carrera de un usuario
public function obtenerCarreraPorUsuarioId($usuario_id) {
    $sql = "SELECT c.carrera_id, c.nombre_carrera 
            FROM carrera c 
            JOIN usuario u ON c.carrera_id = u.carrera_carrera_id 
            WHERE u.usuario_id = :usuario_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    // Método para obtener semestres basados en la carrera seleccionada
    public function obtenerSemestresPorCarrera($carrera_id) {
        if (empty($carrera_id)) {
            return ['error' => 'ID de carrera no proporcionado'];
        }

        $query = "SELECT semestre_id, nombre_semestre FROM semestre WHERE carrera_carrera_id = :carrera_id"; // Asegúrate que esta relación es correcta
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':carrera_id', $carrera_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene todos los resultados como un array asociativo

        if (empty($result)) {
            return ['error' => 'No hay semestres disponibles'];
        } else {
            return $result; // Devuelve los semestres obtenidos
        }
    }

    public function verificarGruposPorCarrera($carreraId) {
        $sql = "SELECT COUNT(*) AS total FROM grupo WHERE carrera_id = :carrera_id";
        $stmt = $this->conn->prepare($sql);
        
        // Vinculamos el parámetro
        $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
        
        $stmt->execute();
        
        // Recuperamos el resultado
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    

}

class Grupo {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function handleFormSubmission() {
        session_start(); // Iniciar la sesión

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = trim($_POST['grupo']);
            $semestre_id = intval($_POST['semestre']);
            $turno_id = intval($_POST['turno']);
            $periodo_id = intval($_POST['periodo']);

            // Registrar los datos para depuración
            error_log("Descripción: $descripcion, Semestre ID: $semestre_id, Turno ID: $turno_id, Periodo ID: $periodo_id");

            // Validar que los campos no estén vacíos
            if (empty($descripcion) || empty($semestre_id) || empty($turno_id) || empty($periodo_id)) {
                header("Location: ../views/templates/formulario_grupo.php?error=campos_vacios");
                exit();
            }

            // Verificar si el grupo ya existe en la base de datos
            if ($this->isDuplicateGrupo($descripcion)) {
                header("Location: ../views/templates/formulario_grupo.php?error=duplicate");
                exit();
            }

            // Insertar el grupo en la base de datos
            if ($this->insertarGrupo($descripcion, $semestre_id, $turno_id, $periodo_id)) {
                header("Location: ../views/templates/formulario_grupo.php?success=true");
                exit();
            } else {
                header("Location: ../views/templates/formulario_grupo.php?error=insert");
                exit();
            }
        } else {
            // Si no es una solicitud POST, redirigir o manejar el error
            header("Location: ../views/templates/formulario_grupo.php?error=invalid_request");
            exit();
        }
    }

    private function insertarGrupo($descripcion, $semestre_id, $turno_id, $periodo_id) {
        $sql = "INSERT INTO grupo (descripcion, semestre_semestre_id, turno_idturno, periodo_periodo_id) 
                VALUES (:descripcion, :semestre_id, :idturno, :periodo_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':semestre_id', $semestre_id, PDO::PARAM_INT);
        $stmt->bindParam(':idturno', $turno_id, PDO::PARAM_INT);
        $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            error_log("Grupo '$descripcion' insertado exitosamente.");
            return true;
        } catch (PDOException $e) {
            // Registrar el error para depuración
            error_log("Error al insertar grupo: " . $e->getMessage());
            $_SESSION['error_message'] = "Error al insertar el grupo: " . $e->getMessage();
            return false;
        }
    }

    private function isDuplicateGrupo($descripcion) {
        $queryCheck = "SELECT COUNT(*) FROM grupo WHERE descripcion = :descripcion";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
    }
}

class Materia {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_materia = $_POST['nombre_materia'];
            $credito_materia = $_POST['credito_materia'];
            $hora_teorica = $_POST['hora_teorica'];
            $hora_practica = $_POST['hora_practica'];

            if ($this->isDuplicateMateria($nombre_materia)) {
                header("Location: ../views/templates/form_materia.php?error=duplicate");
                exit();
            }

            $this->insertarMateria($nombre_materia, $credito_materia, $hora_teorica, $hora_practica);
        }
    }

    private function isDuplicateMateria($nombre_materia) {
        $queryCheck = "SELECT COUNT(*) FROM materia WHERE descripcion = :nombre_materia";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':nombre_materia', $nombre_materia);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
    }
    
    private function insertarMateria($nombre_materia, $credito_materia, $hora_teorica, $hora_practica) {
        $sql = "INSERT INTO materia (descripcion, credito, hora_teorica, hora_practica) 
                VALUES (:nombre, :creditos, :horas_teoricas, :horas_practicas)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre_materia);
        $stmt->bindParam(':creditos', $credito_materia);
        $stmt->bindParam(':horas_teoricas', $hora_teorica);
        $stmt->bindParam(':horas_practicas', $hora_practica);

        try {
            $stmt->execute();
            header("Location: ../views/templates/form_materia.php?success=true");
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            header("Location: ../views/templates/form_materia.php");
        }
    }
}


class Carrera {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_carrera = $_POST['nombre_carrera'];
            $fecha_acreditacion = $_POST['fecha_acreditacion'];
            $organismo_auxiliar = $_POST['organismo_auxiliar'];
            $fecha_inicio_validacion = $_POST['fecha_inicio_validacion'];
            $fecha_fin_validacion = $_POST['fecha_fin_validacion'];

            $imagen_url = $this->handleImageUpload($_FILES['imagen_url']);
            if ($imagen_url === false) {
                header("Location: ../views/templates/form_carrera.php?error=upload");
                exit();
            }

            if ($this->isDuplicateCarrera($nombre_carrera)) {
                header("Location: ../views/templates/form_carrera.php?error=duplicate");
                exit();
            }

            $this->insertCarrera($nombre_carrera, $fecha_acreditacion, $organismo_auxiliar, $imagen_url, $fecha_inicio_validacion, $fecha_fin_validacion);
        }
    }

    private function handleImageUpload($imagen) {
        $uploadDir = '../views/templates/assets/uploads/';
        $uploadFile = $uploadDir . basename($imagen['name']);
        if (move_uploaded_file($imagen['tmp_name'], $uploadFile)) {
            return $uploadFile;
        }
        return false;
    }

    private function isDuplicateCarrera($nombre_carrera) {
        $queryCheck = "SELECT COUNT(*) FROM carrera WHERE nombre_carrera = :nombre_carrera";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':nombre_carrera', $nombre_carrera);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
    }

    private function insertCarrera($nombre_carrera, $fecha_acreditacion, $organismo_auxiliar, $imagen_url, $fecha_inicio_validacion, $fecha_fin_validacion) {
        $query = "INSERT INTO carrera (nombre_carrera, fecha_validacion, organismo_auxiliar, imagen_url, fecha_inicio_validacion, fecha_fin_validacion) 
                  VALUES (:nombre_carrera, :fecha_validacion, :organismo_auxiliar, :imagen_url, :fecha_inicio_validacion, :fecha_fin_validacion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_carrera', $nombre_carrera);
        $stmt->bindParam(':fecha_validacion', $fecha_acreditacion);
        $stmt->bindParam(':organismo_auxiliar', $organismo_auxiliar);
        $stmt->bindParam(':imagen_url', $imagen_url);
        $stmt->bindParam(':fecha_inicio_validacion', $fecha_inicio_validacion);
        $stmt->bindParam(':fecha_fin_validacion', $fecha_fin_validacion);

        try {
            $stmt->execute();
            header("Location: ../views/templates/form_carrera.php?success=true");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}

class Usuario {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function usuarios() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_usuario = $_POST['usuario_nombre'];
            $apellido_p = $_POST['usuario_apellido_p'];
            $apellido_m = $_POST['usuario_apellido_m'];
            $edad = $_POST['edad'];
            $correo = $_POST['correo'];
            $password = hash('sha256', $_POST['password']); // Encriptar contraseña
            $fecha_contratacion = $_POST['fecha_contratacion'];
            $numero_empleado = $_POST['numero_empleado'];
            $grado_academico = $_POST['grado_academico'];
            $cedula = $_POST['cedula'];

            $sexo_sexo_id = $_POST['sexo_sexo_id'];
            $status_status_id = 1; // Asignar el valor 1 directamente
            $tipo_usuario_tipo_usuario_id = $_POST['tipo_usuario_tipo_usuario_id'];
            $carrera_carrera_id = $_POST['carrera_carrera_id'];
            $cuerpo_colegiado_cuerpo_colegiado_id = $_POST['cuerpo_colegiado_cuerpo_colegiado_id'];

            // Manejar la carga de imagen
            $imagen_url = $this->handleImageUpload($_FILES['imagen_url']);
            if ($imagen_url === false) {
                header("Location: ../views/templates/form_usuario.php?error=upload");
                exit();
            }

            // Validar que no se envíe null para los campos obligatorios
            if (empty($cuerpo_colegiado_cuerpo_colegiado_id)) {
                header("Location: ../views/templates/form_usuario.php?error=cuerpo_colegiado_empty");
                exit();
            }

            // Insertar en la base de datos
            $this->insertUsuario($nombre_usuario, $apellido_p, $apellido_m, $edad, $correo, $password, $fecha_contratacion, $numero_empleado, 
                $grado_academico, $cedula, $imagen_url, $sexo_sexo_id, $status_status_id, $tipo_usuario_tipo_usuario_id, 
                $carrera_carrera_id, $cuerpo_colegiado_cuerpo_colegiado_id);
        }
    }
    
    private function handleImageUpload($imagen) {
        $uploadDir = '../views/templates/assets/uploads/';
        $uploadFile = $uploadDir . basename($imagen['name']);
        if (move_uploaded_file($imagen['tmp_name'], $uploadFile)) {
            return $uploadFile;
        }
        return false; // Retorna false si la carga falló
    }
    
    private function insertUsuario($nombre_usuario, $apellido_p, $apellido_m, $edad, $correo, $password, $fecha_contratacion, $numero_empleado, 
    $grado_academico, $cedula, $imagen_url, $sexo_sexo_id, $status_status_id, $tipo_usuario_tipo_usuario_id, 
    $carrera_carrera_id, $cuerpo_colegiado_cuerpo_colegiado_id) {

    $query = "CALL piia.insertarUsuario(:nombre_usuario, :apellido_p, :apellido_m, :edad, :correo, :password, 
        :fecha_contratacion, :numero_empleado, :grado_academico, :cedula, :imagen_url, :sexo_sexo_id, 
        :status_status_id, :tipo_usuario_tipo_usuario_id, :cuerpo_colegiado_cuerpo_colegiado_id, :carrera_carrera_id)";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
    $stmt->bindParam(':apellido_p', $apellido_p);
    $stmt->bindParam(':apellido_m', $apellido_m);
    $stmt->bindParam(':edad', $edad);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':fecha_contratacion', $fecha_contratacion);
    $stmt->bindParam(':numero_empleado', $numero_empleado);
    $stmt->bindParam(':grado_academico', $grado_academico);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':imagen_url', $imagen_url);
    $stmt->bindParam(':sexo_sexo_id', $sexo_sexo_id);
    $stmt->bindParam(':status_status_id', $status_status_id);
    $stmt->bindParam(':tipo_usuario_tipo_usuario_id', $tipo_usuario_tipo_usuario_id);
    $stmt->bindParam(':cuerpo_colegiado_cuerpo_colegiado_id', $cuerpo_colegiado_cuerpo_colegiado_id);
    $stmt->bindParam(':carrera_carrera_id', $carrera_carrera_id);

    try {
        $stmt->execute();
        header("Location: ../views/templates/formulario_usuario.php?success=true");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
}