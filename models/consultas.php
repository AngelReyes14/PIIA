<?php
class Consultas {
    private $conn;
    
    

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    public function obtenerIncidencias() {
        $query = "SELECT * FROM incidencia"; // Asegúrate de cambiar esto según la estructura de tu tabla
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método en la clase Consultas para obtener períodos
    public function obtenerPeriodos() {
        $query = "SELECT periodo_id, descripcion, fecha_inicio, fecha_termino FROM periodo ORDER BY fecha_inicio DESC"; // Ajusta la consulta según tu tabla
        $result = $this->conn->query($query);
    
        $periodos = [];
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Cambia a fetch(PDO::FETCH_ASSOC) si estás usando PDO
                $periodos[] = $row;
            }
        }
        return $periodos;
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

    public function verUsuariosCarreras(){
        $query = "SELECT * FROM vista_usuario_carrera";  // Cambia el nombre de la vista
        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve todas las filas como un array asociativo
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;  // Devuelve false si ocurre algún error
        }
    }
    

    public function obtenerImagen($iduser) {
        $sql = "SELECT imagen_url FROM usuario WHERE usuario_id = :iduser";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':iduser', $iduser); // Asegúrate de enlazar el parámetro
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Agregar "../" al inicio de la URL de la imagen si existe
        if ($result && isset($result['imagen_url'])) {
            $result['imagen_url'] = "../" . $result['imagen_url'];
        }
        
        return $result; // Devuelve el resultado modificado
    }

    public function obtenerProfesores() {
        $sql = "SELECT * FROM usuario WHERE tipo_usuario_tipo_usuario_id = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function obtenerTipoUsuarioPorId($usuario_id) {
        $sql = "select tipo_usuario_tipo_usuario_id from vista_usuarios where usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['tipo_usuario_tipo_usuario_id'] : null; // Retorna solo el ID
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
    

    public function CarreraMaestros($carrera_id) {
        $sql = "SELECT * FROM piia.vista_usuarios where carrera_carrera_id = :carrera_id and tipo_usuario_tipo_usuario_id = 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function Incidenciausuario($carrera_id) {
        $sql = "SELECT * FROM vista_incidencias_usuarios WHERE carrera_carrera_id = :carrera_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function obtenerCarreras() {
        $query = "SELECT carrera_id, nombre_carrera FROM carrera"; // Asegúrate de que la tabla y las columnas sean correctas
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    //*********************** PRUEBA ************************************************************* */    
 
    
    public function obtenerUsuariosPorCarrera($carrera_id) {
        // Consulta para obtener usuarios asociados a una carrera específica
        $query = "SELECT 
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
                    u.sexo_sexo_id,
                    u.status_status_id,
                    u.tipo_usuario_tipo_usuario_id,
                    u.cuerpo_colegiado_cuerpo_colegiado_id,
                    u.carrera_carrera_id,
                    c.carrera_id,
                    c.nombre_carrera 
                  FROM 
                    vista_usuarios u
                  JOIN 
                    carrera c ON u.carrera_carrera_id = c.carrera_id
                  WHERE 
                    c.carrera_id = :carrera_id"; // Usando un parámetro para evitar inyecciones SQL
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT); // Enlazamos el parámetro
        try {
            $stmt->execute(); // Ejecutamos la consulta
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devolvemos los resultados como un array asociativo
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Manejo de errores
            return false; // Devuelve false si ocurre algún error
        }
    }
    
    
    
    public function obtenerTodosLosUsuarios() {
        $sql = "SELECT * FROM vista_usuarios";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    
    
     //************************************************************************************* */    

    
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
public function obtenerDatosUsuario(){
    $query = "SELECT * FROM datos_usuarios";        
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Método para obtener todos los sexos disponibles
public function obtenerSexos() {
$query = "SELECT sexo_id, descripcion FROM sexo"; // Ajusta la tabla y columnas según tu base de datos
$stmt = $this->conn->prepare($query);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerDatosincidencias(){
    $query = "SELECT * FROM datos_incidencia";        
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

    public function datosCarreraPorId($usuarioId) {
        try {
            // Primera consulta para obtener el ID de la carrera usando el usuario ID
            $query = "SELECT carrera_carrera_id FROM usuario WHERE usuario_id = :usuario_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener el resultado de carrera_id
            $carrera = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($carrera) {
                $carreraId = $carrera['carrera_carrera_id'];
    
                // Segunda consulta para obtener todos los datos de la carrera usando el carrera_id
                $query = "SELECT * FROM vista_datos_carrera WHERE carrera_id = :carrera_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
                $stmt->execute();
                
                // Retorna todos los datos de la carrera
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return null; // Retorna null si no encuentra una carrera asociada al usuario
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    public function mujeresCarrera($carreraId) {
        // Prepara la consulta SQL
        $sql = "SELECT COUNT(*) as total_mujeres 
        FROM usuario
        WHERE carrera_carrera_id = :carrera_id AND sexo_sexo_id = 2";// Suponiendo que '1' representa mujeres
        
        // Prepara la sentencia
        $stmt = $this->conn->prepare($sql);
        
        // Vincula el parámetro correctamente
        $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT); // Asegúrate de que el nombre sea exactamente ':carrera_id'
        
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtiene el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Retorna el total de mujeres
        return $result['total_mujeres'];
    }

    public function hombresCarrera($carreraId) {
        // Prepara la consulta SQL
        $sql = "SELECT COUNT(*) as total_hombres 
        FROM usuario
        WHERE carrera_carrera_id = :carrera_id AND sexo_sexo_id = 1";// Suponiendo que '1' representa hombres
        
        // Prepara la sentencia
        $stmt = $this->conn->prepare($sql);
        
        // Vincula el parámetro correctamente
        $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT); // Asegúrate de que el nombre sea exactamente ':carrera_id'
        
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtiene el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Retorna el total de mujeres
        return $result['total_hombres'];
    }

    public function docentesCarrera($carreraId) {
        // Prepara la consulta SQL
        $sql = "SELECT COUNT(*) as total_docentes 
                FROM usuario 
                WHERE carrera_carrera_id = :carrera_id 
                AND tipo_usuario_tipo_usuario_id = :tipo_usuario_id"; // Asegúrate de que este es el ID para docentes
    
        // Prepara la sentencia
        $stmt = $this->conn->prepare($sql);
        
        // Vincula los parámetros
        $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
        
        // Aquí puedes poner el ID correspondiente para los docentes. Asegúrate de reemplazarlo por el correcto.
        $tipoUsuarioDocente = 1; // Supongamos que '1' es el ID para docentes, cámbialo según tu base de datos.
        $stmt->bindParam(':tipo_usuario_id', $tipoUsuarioDocente, PDO::PARAM_INT);
        
        // Ejecuta la consulta
        $stmt->execute();
        
        // Obtiene el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Retorna el total de docentes
        return $result['total_docentes'];
    }
    
    public function gruposCarrera($carreraId) {
        try {
            // Primera consulta: obtener los ID de los semestres para la carrera
            $sql = "SELECT semestre_id 
                    FROM semestre 
                    WHERE carrera_carrera_id = :carrera_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener todos los IDs de semestres
            $semestres = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Obtiene una columna como array
            
            if (empty($semestres)) {
                return 0; // Si no hay semestres, retorna 0
            }
            
            // Segunda consulta: contar grupos para los semestres obtenidos
            $placeholders = implode(',', array_fill(0, count($semestres), '?')); // Genera los placeholders para la consulta
            $sql = "SELECT COUNT(*) as total_grupos 
                    FROM grupo 
                    WHERE semestre_semestre_id IN ($placeholders)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($semestres); // Pasa el array de semestres como parámetros
            
            // Obtener el resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Retorna el total de grupos
            return $result['total_grupos'];
            
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
    
    public function gruposTurnoMatutino($carreraId) {
        try {
            // Obtener los ID de los semestres para la carrera
            $sql = "SELECT semestre_id 
                    FROM semestre 
                    WHERE carrera_carrera_id = :carrera_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener todos los IDs de semestres
            $semestres = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            
            if (empty($semestres)) {
                return 0; // Si no hay semestres, retornar 0
            }
            
            // Contar grupos del turno matutino
            $placeholders = implode(',', array_fill(0, count($semestres), '?'));
            $sql = "SELECT COUNT(*) as total_grupos_matutino 
                    FROM grupo 
                    WHERE semestre_semestre_id IN ($placeholders) AND turno_idturno = '1'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($semestres);
            
            // Obtener el resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total_grupos_matutino'];
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
    
    public function gruposTurnoVespertino($carreraId) {
        try {
            // Obtener los ID de los semestres para la carrera
            $sql = "SELECT semestre_id 
                    FROM semestre 
                    WHERE carrera_carrera_id = :carrera_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener todos los IDs de semestres
            $semestres = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            
            if (empty($semestres)) {
                return 0; // Si no hay semestres, retornar 0
            }
            
            // Contar grupos del turno vespertino
            $placeholders = implode(',', array_fill(0, count($semestres), '?'));
            $sql = "SELECT COUNT(*) as total_grupos_vespertino
                    FROM grupo 
                    WHERE semestre_semestre_id IN ($placeholders) AND turno_idturno = '2'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($semestres);
            
            // Obtener el resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total_grupos_vespertino'];
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
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
            $periodo_periodo_id = $_POST['periodo_periodo_id']; // Agregar periodo_id
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
                $carrera_carrera_id, $cuerpo_colegiado_cuerpo_colegiado_id, $periodo_periodo_id);
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
    $carrera_carrera_id, $cuerpo_colegiado_cuerpo_colegiado_id, $periodo_periodo_id) {

            // Verificar si el correo ya existe
    if ($this->isEmailDuplicate($correo)) {
        header("Location: ../views/templates/formulario_usuario.php?error=duplicate_email");
        exit();
    }

    // Verificar si el número de empleado ya existe
    if ($this->isEmployeeNumberDuplicate($numero_empleado)) {
        header("Location: ../views/templates/formulario_usuario.php?error=duplicate_employee");
        exit();
    }

        $query = "CALL piia.insertarUsuario(:nombre_usuario, :apellido_p, :apellido_m, :edad, :correo, :password, 
            :fecha_contratacion, :numero_empleado, :grado_academico, :cedula, :imagen_url, :sexo_sexo_id, 
            :status_status_id, :tipo_usuario_tipo_usuario_id, :cuerpo_colegiado_cuerpo_colegiado_id, :carrera_carrera_id, :periodo_periodo_id)";

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
        $stmt->bindParam(':periodo_periodo_id', $periodo_periodo_id); // Añadir bind para periodo_id

        try {
            $stmt->execute();
            header("Location: ../views/templates/formulario_usuario.php?success=true");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
    private function isEmailDuplicate($correo) {
        $query = "SELECT COUNT(*) FROM piia.usuario WHERE correo = :correo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    private function isEmployeeNumberDuplicate($numero_empleado) {
        $query = "SELECT COUNT(*) FROM piia.usuario WHERE numero_empleado = :numero_empleado";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numero_empleado', $numero_empleado);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

class UsuarioHasCarrera {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function UsuarioCarrera() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_POST['usuario'];
            $carrera_id = $_POST['carrera'];
            $periodo_id = $_POST['periodo'];

            $this->insertarUsuarioCarrera($usuario_id, $carrera_id, $periodo_id);
        }
    }

    private function insertarUsuarioCarrera($usuario_id, $carrera_id, $periodo_id) {
        // Verificar si ya existe la relación
        $sql_verificar = "SELECT COUNT(*) FROM usuario_has_carrera 
                          WHERE usuario_usuario_id = :usuario_id 
                          AND carrera_carrera_id = :carrera_id 
                          AND periodo_periodo_id = :periodo_id";

        $stmt_verificar = $this->conn->prepare($sql_verificar);
        $stmt_verificar->bindParam(':usuario_id', $usuario_id);
        $stmt_verificar->bindParam(':carrera_id', $carrera_id);
        $stmt_verificar->bindParam(':periodo_id', $periodo_id);

        try {
            $stmt_verificar->execute();
            $count = $stmt_verificar->fetchColumn();

            if ($count > 0) {
                // Usuario ya registrado, redirigir con error
                header("Location: ../views/templates/form_usuarios-carreras.php?error=duplicate");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: ../views/templates/form_usuarios-carreras.php?error=database");
            exit();
        }

        // Insertar si no está registrado
        $sql_insertar = "INSERT INTO usuario_has_carrera (usuario_usuario_id, carrera_carrera_id, periodo_periodo_id) 
                         VALUES (:usuario_id, :carrera_id, :periodo_id)";

        $stmt_insertar = $this->conn->prepare($sql_insertar);
        $stmt_insertar->bindParam(':usuario_id', $usuario_id);
        $stmt_insertar->bindParam(':carrera_id', $carrera_id);
        $stmt_insertar->bindParam(':periodo_id', $periodo_id);

        try {
            $stmt_insertar->execute();
            header("Location: ../views/templates/form_usuarios-carreras.php?success=true");
        } catch (PDOException $e) {
            header("Location: ../views/templates/form_usuarios-carreras.php?error=insert");
        }
    }
}

class IncidenciaUsuario {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $incidenciaId = $_POST['incidencias'];
            $usuarioId = $_POST['usuario-servidor-publico']; 
            $fechaSolicitada = $_POST['fecha'];
            $motivo = $_POST['motivo'];
            $horarioInicio = $_POST['start-time'];
            $horarioTermino = $_POST['end-time'];
            $horario_incidencia = $_POST['time'];
            $diaIncidencia = $_POST['dia-incidencia']; 
            $carreraId = $_POST['area'];
            $status_incidencia_id = $_POST['status_incidencia_id'] ?? 3;

            // Validar los datos (ejemplo básico, se puede expandir)
            if (empty($incidenciaId) || empty($usuarioId) || empty($fechaSolicitada) || empty($motivo)) {
                echo "Por favor, completa todos los campos requeridos.";
                return;
            }

            // Insertar los datos en la base de datos
            $this->insertIncidenciaUsuario($incidenciaId, $usuarioId, $fechaSolicitada, $motivo, $horarioInicio, $horarioTermino, $horario_incidencia, $diaIncidencia, $carreraId, $status_incidencia_id);
        }
    }

    private function insertIncidenciaUsuario($incidenciaId, $usuarioId, $fechaSolicitada, $motivo, $horarioInicio, $horarioTermino, $horario_incidencia, $diaIncidencia, $carreraId, $status_incidencia_id) {
        $validacionDivicionAcademica = 3;
        $validacionSubdireccion = 3;
        $validacionRH = 3;

        $query = "INSERT INTO incidencia_has_usuario (
                    incidencia_incidenciaid,
                    usuario_usuario_id,
                    fecha_solicitada,
                    motivo,
                    horario_inicio,
                    horario_termino,
                    horario_incidencia,
                    dia_incidencia,
                    carrera_carrera_id,
                    Validacion_Divicion_Academica,
                    Validacion_Subdireccion,
                    Validacion_RH,
                    status_incidencia_id
                  ) VALUES (
                    :incidencia_id,
                    :usuario_id,
                    :fecha_solicitada,
                    :motivo,
                    :horario_inicio,
                    :horario_termino,
                    :horario_incidencia,
                    :dia_incidencia,
                    :carrera_id,
                    :validacion_divicion_academica,
                    :validacion_subdireccion,
                    :validacion_rh,
                    :status_incidencia_id
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':incidencia_id', $incidenciaId);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':fecha_solicitada', $fechaSolicitada);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':horario_inicio', $horarioInicio);
        $stmt->bindParam(':horario_termino', $horarioTermino);
        $stmt->bindParam(':horario_incidencia', $horario_incidencia);
        $stmt->bindParam(':dia_incidencia', $diaIncidencia);
        $stmt->bindParam(':carrera_id', $carreraId);
        $stmt->bindParam(':validacion_divicion_academica', $validacionDivicionAcademica);
        $stmt->bindParam(':validacion_subdireccion', $validacionSubdireccion);
        $stmt->bindParam(':validacion_rh', $validacionRH);
        $stmt->bindParam(':status_incidencia_id', $status_incidencia_id);

        try {
            $stmt->execute();
            header("Location: ../views/templates/form_incidencias.php?success=true");
            exit(); // Detiene el script después de la redirección
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage()); // Registra el error en el log
            echo "Ocurrió un error al procesar la solicitud.";
            exit();
        }
    }
}

class CarreraManager
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    /**
     * Obtener la carrera asociada a un usuario específico.
     * 
     * @param int $userId El ID del usuario autenticado.
     * @return array|null Los datos de la carrera o null si no se encuentra.
     */
    public function obtenerCarreraPorUsuario($userId)
    {
        $query = "SELECT carrera.carrera_id, carrera.nombre_carrera 
                  FROM carrera
                  JOIN usuario ON usuario.carrera_carrera_id = carrera.carrera_id
                  WHERE usuario.usuario_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null; // Devuelve null si no hay resultados
    }
}

class UsuarioManager
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    /**
     * Obtener el servidor público asociado a un usuario específico.
     * 
     * @param int $userId El ID del usuario autenticado.
     * @return array|null Los datos del servidor público o null si no se encuentra.
     */
    public function obtenerServidorPublicoPorUsuario($userId)
    {
        $query = "SELECT usuario_id, CONCAT(nombre_usuario, ' ', apellido_p, ' ', apellido_m) AS nombre_completo 
                  FROM usuario 
                  WHERE usuario_id = :user_id"; // Filtramos solo por el usuario en sesión

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null; // Devuelve null si no hay resultados
    }
}

class FormHandler {

    private $conn; // Conexion a la base de datos

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Método para manejar la validación de la incidencia
    public function handleForm() {
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
                    $stmt = $this->conn->prepare($query);
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
    }
}
