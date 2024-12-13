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

    } elseif ($form_type === 'incidencia-usuario') {
        // Crear una instancia de la clase IncidenciaUsuario
        $incidenciaUsuario = new IncidenciaUsuario($conn);
        $incidenciaUsuario->handleRequest();  // Método para procesar el formulario de incidencia-usuario

    } elseif ($form_type === 'validacion-incidencia') {
        // Crear una instancia de la clase IncidenciaUsuario
        $incidenciaUsuario = new ActualizarEstado($conn);
        $incidenciaUsuario->handleForm();  // Método para procesar el formulario de incidencia-usuario
    } elseif ($form_type === 'agregar-edificio') {
            $nombreEdificio = $_POST['nombre_edificio'] ?? null;
        
            if ($nombreEdificio) {
                try {
                    $resultado = $consultas->agregarEdificio($nombreEdificio);
        
                    if ($resultado) {
                        header('Location: success.php?message=Edificio registrado exitosamente');
                        exit();
                    } else {
                        header('Location: error.php?message=No se pudo registrar el edificio');
                        exit();
                    }
                } catch (Exception $e) {
                    echo "Error al registrar el edificio: " . $e->getMessage();
                }
            } else {
                echo "Los datos del formulario están incompletos.";
            }   
        }
        else {
            // Manejar otros formularios o enviar un mensaje de error
            echo "Formulario no reconocido.";
        }
    } else {
        echo "Tipo de formulario no especificado.";
    } 
?>