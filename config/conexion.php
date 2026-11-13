<?php

require_once "config.php";
// Devuelve la conexión con la base de datos del taller
function conectar(): PDO
{
    try {
        global $servidor, $nombreBase, $usuario, $password;
        return new PDO(
            "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4",
            $usuario,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        exit('No se ha podido conectar con la base de datos.');
    }
}