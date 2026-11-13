<?php
// Controlador frontal: todas las peticiones de la aplicación entran por aquí
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/vistas/ayudas.php';

$pdo = conectar();


// TODO 1: guarda en $accion el parámetro «accion» de la dirección;
//         si no llega, vale 'listado'

$accion = $_GET['accion'] ?? '';

// TODO 2: con match, escribe «Aquí irá el listado de reparaciones» si la acción
//         es 'listado' y «No existe la acción …» (con la acción escapada)
//         en cualquier otro caso
match ($accion) {
    'listado' => print('Aquí irá el listado de reparaciones'),
    default   => print('No existe la acción ' . htmlspecialchars($accion)),
};


