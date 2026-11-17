<?php
// Controlador frontal: todas las peticiones de la aplicación entran por aquí
require_once __DIR__ . '/config/autenticacion.php';
iniciarSesionAplicacion();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && !validarCsrfAplicacion($_POST['csrf_token'] ?? null)
) {
    http_response_code(403);
    exit('La solicitud no es válida o ha caducado. Recarga la página e inténtalo de nuevo.');
}

require __DIR__ . '/config/conexion.php';
require __DIR__ . '/vistas/ayudas.php';
require __DIR__ . '/modelos/ReparacionesModelo.php';
require __DIR__ . '/modelos/CatalogoModelo.php';
require __DIR__ . '/controladores/ReparacionesControlador.php';


$pdo = conectar();
$seccion = $_GET['seccion'] ?? 'reparaciones';

if ($seccion === 'administracion') {
    require __DIR__ . '/modelos/AdministracionModelo.php';
    require __DIR__ . '/controladores/AdministracionControlador.php';

    $administracion = new AdministracionControlador(new AdministracionModelo($pdo));
    $accionAdministracion = $_GET['accion'] ?? 'inicio';
    match ($accionAdministracion) {
        'inicio' => $administracion->inicio(),
        'ventas' => $administracion->ventas(),
        'inventario' => $administracion->inventario(),
        'reponer' => $administracion->reponer(),
        default => $administracion->noEncontrada(),
    };
    exit;
}

if ($seccion !== 'reparaciones') {
    http_response_code(404);
    $titulo = 'Página no encontrada';
    require __DIR__ . '/vistas/no-encontrada.php';
    exit;
}

$controlador = new ReparacionesControlador(new ReparacionModelo($pdo), new CatalogoModelo($pdo));

// TODO 1: guarda en $accion el parámetro «accion» de la dirección;
//         si no llega, vale 'listado'

$accion = $_GET['accion'] ?? 'listado';

// TODO 2: con match, escribe «Aquí irá el listado de reparaciones» si la acción
//         es 'listado' y «No existe la acción …» (con la acción escapada)
//         en cualquier otro caso
match ($accion) {
    // Prueba provisional del modelo: se quita en el paso 4
    'listado' => $controlador->listado(),
    'ver'     => $controlador->ver(),
    'anadirPieza' => $controlador->anadirPieza(),
    'quitarPieza' => $controlador->quitarPieza(),
    'anadirManoObra' => $controlador->anadirManoObra(),
    'cerrar' => $controlador->cerrar(),
    'factura' => $controlador->factura(),
    'nueva'     => $controlador->nueva(),
    'guardar'     => $controlador->guardar(),
    default   => $controlador->noENcontrada(),
};


