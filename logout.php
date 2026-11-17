<?php
require __DIR__ . '/config/autenticacion.php';

iniciarSesionAplicacion();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!validarCsrfAplicacion($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('Solicitud no válida.');
}

destruirSesionAplicacion();
header('Location: login.php?salir=1');
exit;
