<?php
include('../../controllers/db.php');
include('../../models/session.php');
include('../../models/consultas.php');

$sessionLifetime = 18000;
$sessionManager = new SessionManager($sessionLifetime);
$idusuario = (int)$sessionManager->getUserId();

if ($idusuario === null) {
    header("Location: ../templates/auth-login.php");
    exit();
}

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $fileName = $_FILES['profile_picture']['name'];
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = 'user_' . $idusuario . '.' . $fileExtension;
        $uploadFileDir = 'assets/uploads/';

        if (!is_dir($uploadFileDir)) {
            echo "<script>console.log('La carpeta de subida no existe.');</script>";
            exit();
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $imagenUrl = '../views/templates/assets/uploads/' . $newFileName;

            $consultas = new Consultas($conn);
            $resultado = $consultas->actualizarImagenPerfil($imagenUrl, $idusuario);

            if (empty($imagenUrl)) {
                echo "<script>console.log('La URL de la imagen está vacía.');</script>";
                return false;
            }
            

            if ($resultado) {
                header("Location: perfil.php?success=Imagen actualizada correctamente");
                exit();
            } else {
                echo "<script>console.log('Error al actualizar la imagen en la base de datos.');</script>";
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
