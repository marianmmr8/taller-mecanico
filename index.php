<?php
// Controlador frontal: todas las peticiones de la aplicación entran por aquí
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/vistas/ayudas.php';
require __DIR__ . '/modelos/ReparacionesModelo.php';


$pdo = conectar();
$modelo = new ReparacionModelo($pdo);

// TODO 1: guarda en $accion el parámetro «accion» de la dirección;
//         si no llega, vale 'listado'

$accion = $_GET['accion'] ?? '';

// TODO 2: con match, escribe «Aquí irá el listado de reparaciones» si la acción
//         es 'listado' y «No existe la acción …» (con la acción escapada)
//         en cualquier otro caso
match ($accion) {
    // Prueba provisional del modelo: se quita en el paso 4
    'listado' => print('<pre>' . htmlspecialchars(print_r($modelo->todas(), true)) . '</pre>'),
    default   => print('No existe la acción ' . htmlspecialchars($accion)),
};


