<?php
include('../../controllers/db.php');
include('../../models/session.php');
include('../../models/consultas.php');

// Definir el tiempo de sesión
$sessionLifetime = 18000;
$sessionManager = new SessionManager($sessionLifetime);
$idusuario = (int)$sessionManager->getUserId();

// Verificar si el usuario tiene sesión activa
if ($idusuario === null) {
    header("Location: ../templates/auth-login.php");
    exit();
}

// Verificar si se ha subido un archivo sin errores
if (isset($_FILES['documento']) && $_FILES['documento']['error'] == 0) {
    $fileName = $_FILES['documento']['name'];
    $fileTmpPath = $_FILES['documento']['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx'];

    if (in_array($fileExtension, $allowedExtensions)) {
        // Renombrar archivo para evitar conflictos
        $newFileName = 'doc_' . $idusuario . '_' . time() . '.' . $fileExtension;
        $uploadFileDir = '../../views/templates/assets/documents/';

        // Verificar si la carpeta de documentos existe
        if (!is_dir($uploadFileDir)) {
            echo "<script>console.log('La carpeta de subida no existe.');</script>";
            exit();
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Guardar la ruta del documento en la base de datos
            $documentUrl = 'views/templates/assets/documents/' . $newFileName;

            $consultas = new Consultas($conn);
            $resultado = $consultas->guardarDocumento($documentUrl, $idusuario);

            if (empty($documentUrl)) {
                echo "<script>console.log('La URL del documento está vacía.');</script>";
                return false;
            }

            if ($resultado) {
                header("Location: perfil.php?success=Documento subido correctamente");
                exit();
            } else {
                echo "<script>console.log('Error al guardar el documento en la base de datos.');</script>";
            }
        } else {
            echo "<script>console.log('Error al mover el archivo subido.');</script>";
        }
    } else {
        echo "<script>console.log('Formato de archivo no permitido.');</script>";
    }
} else {
    echo "<script>console.log('No se ha subido ningún archivo o hubo un error en la subida.');</script>";
}
?>
