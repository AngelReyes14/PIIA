<?php
include('../../controllers/db.php'); // Asegúrate de que este archivo incluya la conexión a la base de datos.
include('../../models/consultas.php'); // Incluir la clase de consultas

// Crear instancia de consultas y obtener tipo de usuario
$consultas = new Consultas($conn);
$idusuario = (int) $sessionManager->getUserId();
$tipoUsuarioId = $consultas->obtenerTipoUsuarioPorId($idusuario);

// Definir los menús de acuerdo al tipo de usuario
$menuItems = [
    1 => [
        'Inicio' => ['icon' => 'fe fe-calendar fe-16', 'link' => 'index.php'],
        'Perfil' => ['icon' => 'fe fe-user fe-16', 'link' => 'Perfil.php'],
        'Incidencias' => ['icon' => 'fe fe-file-text fe-16', 'link' => 'form_incidencias.php']
    ],
    2 => [
        'Inicio' => ['icon' => 'fe fe-calendar fe-16', 'link' => 'index.php'],
        'Incidencias' => ['icon' => 'fe fe-file-text fe-16', 'link' => 'form_incidencias.php'],
        'Recursos humanos' => [
            'Empleados' => ['icon' => 'fe fe-calendar fe-16', 'link' => 'recursos_humanos_empleados.php']
        ],
        'Dashboard' => [
            'Docentes' => ['icon' => 'fe fe-calendar fe-16', 'link' => 'dashboard_docentes.php'],
            'Carrera' => ['icon' => 'fe fe-calendar fe-16', 'link' => 'dashboard_carreras.php']
        ]
    ],
    3 => [
        'Perfil' => ['icon' => 'fe fe-user fe-16', 'link' => 'Perfil.php'],
        'Formulario Extra' => ['icon' => 'fe fe-plus fe-16', 'link' => 'formulario_extra.php'],
        'Carreras' => ['icon' => 'fe fe-book fe-16', 'link' => 'dashboard_carreras.php']
    ]
];

// Verificar si $tipoUsuarioId es un valor válido (un número entero)
if (!is_int($tipoUsuarioId) || !isset($menuItems[$tipoUsuarioId])) {
    // Si no es válido, asignamos un valor predeterminado o mostramos un error
    $tipoUsuarioId = 1; // O un tipo de usuario predeterminado
    echo "<script>console.error('Error: Tipo de usuario no válido para el usuario con ID $idusuario');</script>";
} else {
    echo "<script>console.log('Tipo de usuario ID para el usuario $idusuario: $tipoUsuarioId');</script>";
}

// Comienza a capturar el contenido del aside
$asideContent = '
<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <nav class="vertnav navbar navbar-light">
        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.php">
                <img src="../templates/assets/icon/icon_piia.png" class="imgIcon">
            </a>
        </div>
        <ul class="navbar-nav flex-fill w-100 mb-2">';

// Itera sobre los elementos del menú según el tipo de usuario
if (isset($menuItems[$tipoUsuarioId])) {
    foreach ($menuItems[$tipoUsuarioId] as $menuItem => $details) {
        if (isset($details['link'], $details['icon'])) {
            $asideContent .= '
        <li class="nav-item w-100">
            <a class="nav-link" href="' . htmlspecialchars($details['link']) . '">
                <i class="' . htmlspecialchars($details['icon']) . '"></i>
                <span class="ml-3 item-text">' . htmlspecialchars($menuItem) . '</span>
            </a>
        </li>';
        } elseif (is_array($details)) {
            // Si el elemento tiene submenús, los iteramos
            $asideContent .= '
        <li class="nav-item dropdown w-100" style="border:none;">
            <a class="nav-link dropdown-toggle" href="#" id="submenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fe fe-folder"></i>
                <span class="ml-3 item-text">' . htmlspecialchars($menuItem) . '</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="submenu">';
            foreach ($details as $subMenuItem => $subDetails) {
                if (isset($subDetails['link'], $subDetails['icon'])) {
                    $asideContent .= '
                <a class="dropdown-item" href="' . htmlspecialchars($subDetails['link']) . '">
                    <i class="' . htmlspecialchars($subDetails['icon']) . '"></i> ' . htmlspecialchars($subMenuItem) . '
                </a>';
                }
            }
            $asideContent .= '</div></li>';
        }
    }
}

$asideContent .= '</ul></nav></aside>'; // Cierra las etiquetas del aside y nav

// Output the aside content
echo $asideContent;
?>
