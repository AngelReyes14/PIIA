<?php
class Consultas {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function verCarreras() {
        $query = "SELECT carrera_id, nombre_carrera, organismo_auxiliar, fecha_validacion, fecha_fin_validacion FROM carrera";
        $stmt = $this->conn->prepare($query);
    
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve todas las filas como un array asociativo
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;  // Devuelve false si ocurre algún error
        }
    }

    public function verMaterias(){
        $query = "SELECT * FROM vista_materias";
        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve todas las filas como un array asociativo
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;  // Devuelve false si ocurre algún error
        }
    }

    public function verMateriasGrupo(){
        $query = "SELECT * FROM vista_materias_grupo_periodo";
        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve todas las filas como un array asociativo
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;  // Devuelve false si ocurre algún error
        }
    }

    public function obtenerUsuarioPorId($usuario_id) {
        $sql = "select * from vista_usuarios where usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   public function actualizarImagenPerfil($imagenUrl, $idusuario) {
    if (empty($imagenUrl)) {
        echo "<script>console.log('La URL de la imagen está vacía.');</script>";
        return false;
    }

    $sql = "UPDATE usuario SET imagen_url = :imagen_url WHERE usuario_id = :usuario_id";
    $stmt = $this->conn->prepare($sql);
    
    if (!$stmt) {
        echo "<script>console.log('Error en la preparación de la consulta: " . $this->conn->error . "');</script>";
        return false;
    }

    // Enlazar los parámetros
    $stmt->bindValue(':imagen_url', $imagenUrl, PDO::PARAM_STR);
    $stmt->bindValue(':usuario_id', $idusuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true; // Devuelve verdadero si la consulta se ejecutó correctamente
    } else {
        echo "<script>console.log('Error al ejecutar la consulta: " . $stmt->errorInfo()[2] . "');</script>";
        return false; // Devuelve falso si hay un error
    }
}

    
    

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT usuario_id FROM vista_usuarios WHERE correo = :correo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
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

    public function obtenerGrupos(){
        $query = "SELECT grupo_id, descripcion FROM grupo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMaterias(){
        $query = "SELECT materia_id, descripcion FROM materia";
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
    
// Método para obtener todos los sexos disponibles
public function obtenerSexos() {
    $query = "SELECT sexo_id, descripcion FROM sexo"; // Ajusta la tabla y columnas según tu base de datos
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Método para obtener todos los tipos de usuario
public function obtenerTiposDeUsuario() {
    $query = "SELECT tipo_usuario_id, descripcion FROM tipo_usuario"; // Asegúrate de que la tabla y las columnas sean correctas
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Método para obtener los cuerpos colegiados
public function obtenerCuerposColegiados() {
    $query = "SELECT cuerpo_colegiado_id, descripcion FROM cuerpo_colegiado"; // Asegúrate de que esta es la tabla correcta
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

            // Insertar el grupo en la base de datos
            if ($this->insertarGrupo($descripcion, $semestre_id, $turno_id, $periodo_id )) {
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

class MateriaGrupo {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function GrupoMateria() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_materia = $_POST['materia'];
            $id_grupo = $_POST['grupo'];
            $id_periodo = $_POST['periodo'];

            $this->insertarMateriaGrupo($id_materia, $id_grupo, $id_periodo);
        }
    }
    
    private function insertarMateriaGrupo($id_materia, $id_grupo, $id_periodo) {
        $sql = "INSERT INTO materia_has_grupo (materia_materia_id, grupo_grupo_id, periodo_periodo_id) 
                VALUES (:materia, :grupo, :periodo)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':materia', $id_materia);
        $stmt->bindParam(':grupo', $id_grupo);
        $stmt->bindParam(':periodo', $id_periodo);

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

            // Validar campos relacionados
            if (!$this->isValidSexo($sexo_sexo_id)) {
                header("Location: ../views/templates/form_usuario.php?error=sexo_invalid");
                exit();
            }
            if (!$this->isValidCarrera($carrera_carrera_id)) {
                header("Location: ../views/templates/form_usuario.php?error=carrera_invalid");
                exit();
            }
            if (!$this->isValidCuerpoColegiado($cuerpo_colegiado_cuerpo_colegiado_id)) {
                header("Location: ../views/templates/form_usuario.php?error=cuerpo_colegiado_invalid");
                exit();
            }
            if (!$this->isValidTipoUsuario($tipo_usuario_tipo_usuario_id)) {
                header("Location: ../views/templates/form_usuario.php?error=tipo_usuario_invalid");
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

    private function isValidSexo($sexo_sexo_id) {
        $queryCheck = "SELECT COUNT(*) FROM piia.sexo WHERE sexo_id = :sexo_sexo_id";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':sexo_sexo_id', $sexo_sexo_id);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
    }

    private function isValidCarrera($carrera_carrera_id) {
        $queryCheck = "SELECT COUNT(*) FROM piia.carrera WHERE carrera_id = :carrera_carrera_id";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':carrera_carrera_id', $carrera_carrera_id);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
    }

    private function isValidCuerpoColegiado($cuerpo_colegiado_cuerpo_colegiado_id) {
        $queryCheck = "SELECT COUNT(*) FROM piia.cuerpo_colegiado WHERE cuerpo_colegiado_id = :cuerpo_colegiado_cuerpo_colegiado_id";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':cuerpo_colegiado_cuerpo_colegiado_id', $cuerpo_colegiado_cuerpo_colegiado_id);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
    }

    private function isValidTipoUsuario($tipo_usuario_tipo_usuario_id) {
        $queryCheck = "SELECT COUNT(*) FROM piia.tipo_usuario WHERE tipo_usuario_id = :tipo_usuario_tipo_usuario_id";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':tipo_usuario_tipo_usuario_id', $tipo_usuario_tipo_usuario_id);
        $stmtCheck->execute();
        return $stmtCheck->fetchColumn() > 0;
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
