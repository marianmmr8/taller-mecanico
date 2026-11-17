<?php
// Controlador frontal: todas las peticiones de la aplicación entran por aquí
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/vistas/ayudas.php';
require __DIR__ . '/modelos/ReparacionesModelo.php';
require __DIR__ . '/modelos/CatalogoModelo.php';
require __DIR__ . '/controladores/ReparacionesControlador.php';


$pdo = conectar();
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
    'anadirManoObra' => $controlador->anadirManoObra(),
    'cerrar' => $controlador->cerrar(),
    'factura' => $controlador->factura(),
    'nueva'     => $controlador->nueva(),
    'guardar'     => $controlador->guardar(),
    default   => $controlador->noENcontrada(),
};


